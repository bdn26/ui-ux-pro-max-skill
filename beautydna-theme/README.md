# BeautyDNA — WordPress / WooCommerce Theme

A custom, editorial WooCommerce theme built for the BeautyDNA beauty &
wellness brand: skincare, haircare, supplements, body care and beauty
tools. Premium, minimal, product-first — built on real WordPress/
WooCommerce functionality throughout (no faked cart/checkout/account UI).

## Requirements

- WordPress 6.3+
- WooCommerce 8.0+
- PHP 8.0+

## Install

1. Zip the `beautydna-theme` folder (or copy it directly) into
   `wp-content/themes/beautydna`.
2. Optional: add a `screenshot.png` (1200×900) to the theme root — the
   thumbnail WordPress shows in Appearance → Themes. None is bundled.
3. In wp-admin: **Appearance → Themes → Activate BeautyDNA**.
4. Install and activate **WooCommerce** if it isn't already.
5. Run through **Setup** below.

## Setup

### 1. Pages & menus
- WooCommerce's setup wizard creates Shop / Cart / Checkout / My Account
  pages automatically — confirm under **WooCommerce → Settings →
  Advanced → Page setup**.
- Create a page for the homepage (Settings → Reading → "A static page" →
  set Homepage to any page; the theme's homepage layout renders via
  `front-page.php` regardless of which page is assigned, as long as a
  static front page is selected).
- Create a **Wishlist** page and assign the **BeautyDNA — Wishlist** page
  template (Page Attributes → Template) to it. Its URL should be
  `/wishlist/` to match the header/footer links, or update those links
  in `header.php` / `footer.php` if you use a different slug.
- Build out **Appearance → Menus**: assign a menu to "Primary Navigation"
  (desktop nav) and optionally "Mobile Navigation" (falls back to
  Primary if not set), plus the four footer menu locations.

### 2. Product categories (Shop by Category)
Create these five Product Categories (**Products → Categories**) with a
category image each — the homepage category grid picks them up by slug
automatically:
`skin`, `hair`, `body`, `supplements`, `tools`.

### 3. Product tags (Shop by Concern)
Tag relevant products with these Product Tags so the homepage "Shop by
Concern" section links to a real, filtered listing:
`glow-brighten`, `hydration`, `hair-scalp`, `body-wellness`,
`beauty-from-within`.

### 4. Featured product & best sellers
- Mark one product **Featured** (Product data → Advanced tab isn't it —
  it's the star icon in the products list, or "Catalog visibility →
  Featured" on Edit Product) to control the homepage split-screen
  feature.
- "Best Sellers" ranks by WooCommerce's own sales count automatically —
  no setup needed, it just needs completed orders to have data.

### 5. Product story content (Benefits / How To Use / FAQ tabs)
On each Product edit screen, scroll to the **BeautyDNA Product Story**
box to fill in Benefits (one per line), How To Use, and FAQ. Empty
fields simply hide that tab.

### 6. Frequently Bought Together
Set a product's **Upsells** (Product data → Linked Products) to the 1–2
companion products you want offered together on its product page.

### 7. Ingredients & Testimonials
- **Ingredients** (its own admin menu): add entries with a title,
  excerpt and featured image — powers the homepage ingredient
  storytelling section. Falls back to starter copy if empty.
- **Testimonials** (its own admin menu): title = customer name, content
  = the quote, plus a star rating / verified-buyer checkbox in the side
  panel.

### 8. Homepage copy & hero image
**Appearance → Customize → BeautyDNA Homepage** controls the
announcement bar text, hero heading/subheading/CTAs/image, brand story
copy, and social links — no code changes needed.

### 9. Shop filters
Add WooCommerce's **Filter Products by Price**, **Filter by Attribute**
and/or **Product Categories** widgets to the **Shop Sidebar** widget
area (Appearance → Widgets) to power the shop/category filter panel
(desktop sidebar, mobile drawer).

## Notes on custom features

- **Wishlist** is intentionally a lightweight, client-side
  (localStorage) feature with no account sync — it needs no plugin or
  database table. Swap it for a full wishlist plugin's hooks if you
  later need cross-device sync.
- **Buy Now** submits WooCommerce's real add-to-cart form and redirects
  straight to checkout (`woocommerce_add_to_cart_redirect`) — it does
  not bypass stock/validation. It renders on simple/external products
  (hooked to `woocommerce_after_add_to_cart_button`); WooCommerce core
  doesn't fire that hook for variable products, so variable products
  show Add to Cart only unless you add an equivalent button to
  `woocommerce_after_variations_form` or a variations-specific hook.
- **Frequently Bought Together** uses each product's own Upsells, added
  via WooCommerce's standard `wc-ajax=add_to_cart` endpoint.
- **Recently Viewed** reads the cookie WooCommerce core already sets via
  `wc_track_product_view()` — nothing custom is tracked.
- Cart drawer, mini-cart, free-shipping bar and header cart count all
  refresh together via the `woocommerce_add_to_cart_fragments` filter —
  standard WooCommerce AJAX cart behavior, just extended.
- Checkout and My Account pages are **not** template-overridden — only
  restyled via CSS — so every payment gateway, shipping method and
  account flow WooCommerce/plugins provide keeps working unmodified.

## Placeholder content

Anywhere real photography isn't available yet, you'll see a soft
gradient block labeled with what belongs there (e.g. "Hero campaign
image — 1800×1200"). Replace via:
- Customizer (hero image),
- Category thumbnail (Products → Categories → edit → Thumbnail),
- Featured Image (Ingredients, Testimonials, product images),

Search the codebase for `beautydna_placeholder(` if you want to find
every remaining placeholder call site.

## File structure

```
beautydna-theme/
├── style.css                    WordPress theme header (styles live in assets/css)
├── functions.php                Bootstraps inc/
├── inc/
│   ├── theme-setup.php          Theme supports, menus, CPTs (Ingredient, Testimonial), breadcrumbs
│   ├── enqueue.php               CSS/JS registration
│   ├── customizer.php            Hero / announcement / story / social settings
│   ├── woocommerce-setup.php     Catalog layout, sale badge, tabs, Buy Now, free-shipping helper
│   ├── woocommerce-hooks.php     Frequently Bought Together, Recently Viewed, sticky mobile ATC
│   ├── ajax.php                  Wishlist lookup, cart fragments, newsletter signup
│   └── template-tags.php         Placeholder helper, fallback menu, social icons
├── template-parts/                Homepage sections + cart drawer
├── woocommerce/                   Template overrides (archive, product card, single product, cart, mini-cart)
├── page-templates/
│   └── template-wishlist.php
└── assets/
    ├── css/                       00–09, loaded in cascade order
    └── js/                        main.js, cart-drawer.js, product-gallery.js, filters.js
```
