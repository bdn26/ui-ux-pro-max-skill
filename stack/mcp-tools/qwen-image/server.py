#!/usr/bin/env python3
"""MCP server exposing Qwen-Image generation and editing as tools.

Backed by MuAPI's hosted, asynchronous image generation API
(https://muapi.ai), which hosts the Qwen-Image model family:

- ``qwen-image``            text-to-image
- ``qwen-image-edit``       single-image instruction editing
- ``qwen-image-edit-plus``  multi-image instruction editing

MuAPI exposes every image model behind the same request shape
(``POST /api/v1/{model}`` with an ``x-api-key`` header and a JSON body),
followed by polling a per-request result URL until the prediction
completes. The polling/download plumbing here mirrors the MuAPI provider
already used by ``cli/assets/skills/design/scripts/logo/generate.py`` in
the parent ui-ux-pro-max-skill repo.

Requires the ``MUAPI_API_KEY`` environment variable (get one at
https://muapi.ai). The server starts without it, but tool calls fail
until it is set.

Run directly for local testing:
    python3 server.py

Wired into Claude Code via .mcp.json:
    "qwen-image": {
      "command": "python3",
      "args": ["mcp-tools/qwen-image/server.py"],
      "env": { "MUAPI_API_KEY": "${MUAPI_API_KEY}" }
    }
"""

import ipaddress
import json
import mimetypes
import os
import re
import time
from datetime import datetime, timezone
from pathlib import Path
from typing import Optional
from urllib.error import HTTPError, URLError
from urllib.parse import urlparse
from urllib.request import HTTPRedirectHandler, Request, build_opener

from mcp.server.fastmcp import FastMCP

MUAPI_API_KEY = os.environ.get("MUAPI_API_KEY")
MUAPI_API_BASE = "https://api.muapi.ai/api/v1"
HTTP_USER_AGENT = "qwen-image-mcp/1.0"

TEXT_TO_IMAGE_MODEL = "qwen-image"
EDIT_MODELS = ("qwen-image-edit", "qwen-image-edit-plus")
ALL_MODELS = (TEXT_TO_IMAGE_MODEL, *EDIT_MODELS)

ASPECT_RATIOS = ("1:1", "16:9", "9:16", "4:3", "3:4")
DEFAULT_ASPECT_RATIO = "1:1"

POLL_INTERVAL_SECONDS = 2
MAX_POLLS = 90

OUTPUT_DIR = Path(__file__).parent / "output"

mcp = FastMCP("qwen-image")


# ============ shared HTTP helpers (SSRF-guarded, ported from the ============
# ============ MuAPI provider in cli/assets/skills/design/scripts/logo) =====


class _SafeRedirectHandler(HTTPRedirectHandler):
    """Reject redirects to non-public or non-HTTPS destinations."""

    def redirect_request(self, req, fp, code, msg, headers, newurl):
        _validate_public_https_url(newurl)
        return super().redirect_request(req, fp, code, msg, headers, newurl)


def _validate_public_https_url(url):
    parsed = urlparse(url)
    if (
        parsed.scheme != "https"
        or not parsed.hostname
        or parsed.username
        or parsed.password
    ):
        raise ValueError("MuAPI returned an invalid media URL")

    hostname = parsed.hostname.lower().rstrip(".")
    if hostname == "localhost" or hostname.endswith(
        (".localhost", ".local", ".internal")
    ):
        raise ValueError("MuAPI media URL used a local hostname")

    try:
        ip = ipaddress.ip_address(hostname)
    except ValueError:
        return
    else:
        if not ip.is_global:
            raise ValueError("MuAPI media URL used a non-public address")


def _json_request(url, api_key, method="GET", payload=None):
    body = json.dumps(payload).encode("utf-8") if payload is not None else None
    request = Request(
        url,
        data=body,
        method=method,
        headers={
            "x-api-key": api_key,
            "Accept": "application/json",
            "User-Agent": HTTP_USER_AGENT,
            **({"Content-Type": "application/json"} if body is not None else {}),
        },
    )
    try:
        with build_opener(_SafeRedirectHandler()).open(request, timeout=60) as response:
            return json.loads(response.read().decode("utf-8"))
    except HTTPError as exc:
        detail = exc.read().decode("utf-8", errors="replace")
        raise RuntimeError(f"MuAPI request failed ({exc.code}): {detail[:300]}") from exc
    except (URLError, TimeoutError, json.JSONDecodeError) as exc:
        raise RuntimeError(f"MuAPI request failed: {exc}") from exc


def _download_image(url, output_path):
    _validate_public_https_url(url)
    request = Request(url, headers={"Accept": "image/*", "User-Agent": HTTP_USER_AGENT})
    try:
        with build_opener(_SafeRedirectHandler()).open(request, timeout=120) as response:
            content_type = response.headers.get_content_type()
            if not content_type.startswith("image/"):
                raise RuntimeError(f"MuAPI output is not an image ({content_type})")
            image_data = response.read()
    except (HTTPError, URLError, TimeoutError) as exc:
        raise RuntimeError(f"Unable to download MuAPI image: {exc}") from exc

    if not image_data:
        raise RuntimeError("MuAPI returned an empty image")

    extension = mimetypes.guess_extension(content_type) or ".png"
    if output_path.suffix == "":
        output_path = output_path.with_suffix(extension)
    output_path.parent.mkdir(parents=True, exist_ok=True)
    output_path.write_bytes(image_data)
    return output_path


def _muapi_response_objects(response):
    """Return the response and common MuAPI envelopes without guessing fields."""
    if not isinstance(response, dict):
        raise TypeError("MuAPI returned an invalid response")

    objects = [response]
    for key in ("data", "output", "result"):
        value = response.get(key)
        if isinstance(value, dict) and value not in objects:
            objects.append(value)
    return objects


def _muapi_response_value(response, keys):
    for item in _muapi_response_objects(response):
        for key in keys:
            value = item.get(key)
            if value not in (None, ""):
                return value
    return None


def _muapi_error(response):
    value = _muapi_response_value(response, ("error", "message", "detail"))
    if isinstance(value, str):
        return value[:300]
    return "MuAPI request failed"


def _muapi_result_url(response):
    """Return the documented result URL from the creation response."""
    for item in _muapi_response_objects(response):
        urls = item.get("urls")
        if not isinstance(urls, dict) or "get" not in urls:
            continue

        result_url = urls.get("get")
        if not isinstance(result_url, str) or not result_url:
            raise RuntimeError("MuAPI creation response did not include a valid result URL")
        try:
            _validate_public_https_url(result_url)
        except ValueError as exc:
            raise RuntimeError("MuAPI creation response did not include a valid result URL") from exc
        return result_url

    raise RuntimeError("MuAPI creation response did not include a valid result URL")


def _muapi_output_url(response):
    for item in _muapi_response_objects(response):
        outputs = item.get("outputs")
        if isinstance(outputs, list):
            for output in outputs:
                if isinstance(output, str) and output.startswith("https://"):
                    return output
                if isinstance(output, dict):
                    for key in ("url", "image_url"):
                        value = output.get(key)
                        if isinstance(value, str) and value.startswith("https://"):
                            return value
    raise RuntimeError("MuAPI completed without an HTTPS image URL")


def _slugify(text, max_length=40):
    slug = re.sub(r"[^a-z0-9]+", "-", text.lower()).strip("-")
    return (slug[:max_length].rstrip("-")) or "image"


def _default_output_path(prompt, model):
    timestamp = datetime.now(timezone.utc).strftime("%Y%m%dT%H%M%SZ")
    return OUTPUT_DIR / f"{model}-{timestamp}-{_slugify(prompt)}"


def _generate_with_muapi(model, payload):
    if not MUAPI_API_KEY:
        raise RuntimeError(
            "MUAPI_API_KEY not set. Get a key at https://muapi.ai and set it in the "
            "qwen-image MCP server's environment."
        )

    response = _json_request(f"{MUAPI_API_BASE}/{model}", MUAPI_API_KEY, method="POST", payload=payload)
    request_id = _muapi_response_value(response, ("request_id", "id"))
    if not isinstance(request_id, str) or not request_id:
        raise RuntimeError("MuAPI did not return a request ID")
    result_url = _muapi_result_url(response)

    data = response
    for poll_number in range(MAX_POLLS + 1):
        status = str(_muapi_response_value(data, ("status",)) or "").lower()
        if status in {"completed", "succeeded", "success"}:
            return _muapi_output_url(data)
        if status in {"failed", "error", "timeout", "canceled", "cancelled"}:
            raise RuntimeError(f"MuAPI generation {status}: {_muapi_error(data)}")
        if poll_number == MAX_POLLS:
            break
        time.sleep(POLL_INTERVAL_SECONDS)
        data = _json_request(result_url, MUAPI_API_KEY)

    raise RuntimeError("MuAPI prediction timed out while polling")


# ============================== MCP tools ===================================


@mcp.tool()
def generate_image(
    prompt: str,
    aspect_ratio: str = DEFAULT_ASPECT_RATIO,
    output_path: Optional[str] = None,
) -> dict:
    """Generate an image from a text prompt using Qwen-Image.

    Args:
        prompt: What to generate. Qwen-Image is strong at rendering
            legible text inside the image (multi-line layouts, labels,
            posters) in addition to general scenes/illustrations.
        aspect_ratio: One of 1:1, 16:9, 9:16, 4:3, 3:4. Defaults to 1:1.
        output_path: Where to save the downloaded image. Defaults to a
            timestamped file under mcp-tools/qwen-image/output/.

    Returns:
        A dict with output_path (local file the image was saved to) and
        image_url (the hosted MuAPI result URL).
    """
    if aspect_ratio not in ASPECT_RATIOS:
        raise ValueError(f"Unsupported aspect_ratio: {aspect_ratio}. Choose one of: {', '.join(ASPECT_RATIOS)}")

    payload = {"prompt": prompt, "aspect_ratio": aspect_ratio}
    image_url = _generate_with_muapi(TEXT_TO_IMAGE_MODEL, payload)

    destination = Path(output_path) if output_path else _default_output_path(prompt, TEXT_TO_IMAGE_MODEL)
    saved_path = _download_image(image_url, destination)

    return {"output_path": str(saved_path), "image_url": image_url, "model": TEXT_TO_IMAGE_MODEL}


@mcp.tool()
def edit_image(
    prompt: str,
    image_urls: list[str],
    model: str = "qwen-image-edit",
    aspect_ratio: Optional[str] = None,
    output_path: Optional[str] = None,
) -> dict:
    """Edit one or more images with a natural-language instruction using Qwen-Image-Edit.

    Args:
        prompt: The edit instruction (e.g. "change the background to a
            studio gradient", "replace the headline text with 'Launch
            Day'", "combine these into one poster").
        image_urls: HTTPS URLs of the source image(s) to edit. Use
            "qwen-image-edit" for a single source image, or
            "qwen-image-edit-plus" for multi-image edits/composition.
        model: "qwen-image-edit" or "qwen-image-edit-plus".
        aspect_ratio: Optional output aspect ratio (1:1, 16:9, 9:16, 4:3, 3:4).
        output_path: Where to save the downloaded image. Defaults to a
            timestamped file under mcp-tools/qwen-image/output/.

    Returns:
        A dict with output_path (local file the image was saved to) and
        image_url (the hosted MuAPI result URL).

    Note:
        MuAPI documents each model's exact input schema at
        https://muapi.ai/docs/api-reference. This sends the source
        image(s) as `image_urls`; if MuAPI's live schema for a given
        model expects a different field name, adjust the payload below.
    """
    if model not in EDIT_MODELS:
        raise ValueError(f"Unsupported model: {model}. Choose one of: {', '.join(EDIT_MODELS)}")
    if not image_urls:
        raise ValueError("image_urls must contain at least one HTTPS image URL")
    for url in image_urls:
        _validate_public_https_url(url)

    payload = {"prompt": prompt, "image_urls": image_urls}
    if aspect_ratio:
        if aspect_ratio not in ASPECT_RATIOS:
            raise ValueError(f"Unsupported aspect_ratio: {aspect_ratio}. Choose one of: {', '.join(ASPECT_RATIOS)}")
        payload["aspect_ratio"] = aspect_ratio

    image_url = _generate_with_muapi(model, payload)

    destination = Path(output_path) if output_path else _default_output_path(prompt, model)
    saved_path = _download_image(image_url, destination)

    return {"output_path": str(saved_path), "image_url": image_url, "model": model}


if __name__ == "__main__":
    mcp.run()
