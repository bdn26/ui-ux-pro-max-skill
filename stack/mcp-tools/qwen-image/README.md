# Qwen Image MCP

A local MCP server that exposes [Qwen-Image](https://github.com/QwenLM/Qwen-Image) generation
and editing as tools, so Claude can produce hero art, illustrations, and edited imagery
directly inside the design loop instead of leaving placeholders.

It calls the Qwen-Image model family hosted on [MuAPI](https://muapi.ai)'s asynchronous image
generation API:

- `qwen-image` — text-to-image (also strong at rendering legible in-image text)
- `qwen-image-edit` — single-image instruction editing
- `qwen-image-edit-plus` — multi-image instruction editing / composition

## Setup

1. Get an API key at <https://muapi.ai> and set it:
   ```bash
   export MUAPI_API_KEY=your-key-here
   ```
2. Install the server's one dependency:
   ```bash
   pip install -r mcp-tools/qwen-image/requirements.txt
   ```
3. It's already wired into the project's `.mcp.json`. Open Claude Code in the project root and
   approve the `qwen-image` server alongside playwright/chrome-devtools/shadcn, then verify with
   `/mcp`.

The server starts fine without `MUAPI_API_KEY` set — only calling a tool requires it.

## Tools

- **`generate_image(prompt, aspect_ratio="1:1", output_path=None)`** — text-to-image.
- **`edit_image(prompt, image_urls, model="qwen-image-edit", aspect_ratio=None, output_path=None)`**
  — instruction-based editing; pass `model="qwen-image-edit-plus"` for multi-image edits.

Both download the result to a local file (default: a timestamped file under
`mcp-tools/qwen-image/output/`, gitignored) and return `{output_path, image_url, model}`.

## Run standalone (for testing outside Claude Code)

```bash
python3 mcp-tools/qwen-image/server.py
```

This starts the stdio MCP server directly; use an MCP inspector/client to call its tools.

## Notes

- MuAPI documents each model's live request schema at <https://muapi.ai/docs/api-reference>.
  `edit_image` sends source images as `image_urls`; if a model's schema differs, adjust the
  payload in `server.py`.
- Generated images are downloaded over validated public HTTPS URLs only (no localhost/private
  network targets, no unsafe redirects) — the same guard used by the MuAPI provider in the
  parent `ui-ux-pro-max-skill` repo's logo generator.
