# DWC Group WordPress Theme

A custom, production-ready WordPress theme built for **Digital Wrk Consulting Group (DWC Group)** — a premium, government/public-sector-grade corporate consulting site. No page builder, no Elementor dependency, no framework bloat. Lightweight, accessible, SEO-friendly, and fully editable through WordPress.

## What's included

- A custom theme (not a child theme) built entirely from WordPress core APIs.
- Full **WooCommerce** support for the Publications section — digital/downloadable reports sold as ordinary WooCommerce products, styled to look like an editorial research library rather than a generic store.
- A `Service` custom post type powering the homepage service cards and the full "Our Services" page — no content is hard-coded in PHP; everything is editable from the WordPress admin.
- A WordPress Customizer panel ("DWC Group Settings") for every homepage/about/contact text block, brand colors, logo, and contact details.
- A native contact form (posts to `admin-post.php`, no plugin dependency).
- Reusable, documented template-part components (hero, section heading, CTA button, service card, publication card, numbered principle, breadcrumbs, etc.) so nothing is duplicated across templates.
- Minimal, dependency-free vanilla JavaScript (sticky header, mobile nav, scroll-reveal) that fully respects `prefers-reduced-motion`.

## Requirements

- WordPress 6.0+
- PHP 7.4+
- WooCommerce (only required for the Publications e-commerce section — the rest of the site works without it)

## Installation

1. Copy the `dwc-group-theme` folder into `wp-content/themes/`.
2. In **Appearance → Themes**, activate **DWC Group**.
3. On first activation the theme automatically:
   - Creates the core pages (Home, About Us, Our Services, Publications, Contact) with the correct templates assigned.
   - Sets the static front page to Home.
   - Creates a Primary Navigation menu and assigns it to the header.
   - Seeds four starter `Service` entries (HR & Talent Acquisition, Government Consulting, Accounting & Payroll, FraudAudit AI) with the brand-brief copy, ready to edit.
   - If WooCommerce is active, seeds the Publications product categories (Investigative Reports, Research, Government & Public Policy, Program Integrity, Fraud & Compliance, Industry Analysis) and points the Shop page at the Publications page.
4. Go to **Settings → Permalinks** and click **Save Changes** once, to flush rewrite rules for the new `Service` post type.
5. Install and activate **WooCommerce** (if not already active) to enable the Publications storefront, then add your first digital publication (see below).
6. Add a `screenshot.png` (1200×900px) to the theme's root folder for the admin theme picker — none is bundled by default.

## Editing content (no code required)

| What | Where |
|---|---|
| Logo, footer logo | Appearance → Customize → Site Identity / DWC Group Settings → Brand |
| Primary/accent/charcoal colors | Appearance → Customize → DWC Group Settings → Brand |
| Contact email, phone, office/service area, social links | Appearance → Customize → DWC Group Settings → Contact Details |
| Header CTA label/URL | Appearance → Customize → DWC Group Settings → Header & Global CTA |
| Homepage hero, intro, services intro, Why DWC principles, FraudAudit AI copy, publications intro, final CTA | Appearance → Customize → DWC Group Settings → Homepage — ... |
| About page copy (hero, Who We Are, How We Work, Why DWC, values) | Appearance → Customize → DWC Group Settings → About Page |
| The four services shown on the homepage and the "Our Services" page | Admin sidebar → **Services** (add/edit/reorder; each service has a body copy field, a capability checklist, and a CTA label/URL) |
| Publications (digital reports) | Admin sidebar → **Products** (WooCommerce) |
| Navigation menus | Appearance → Menus (Primary, and four optional footer menus) |
| Contact form recipient | Same email as "Contact Email" above; falls back to the site admin email |

## Setting up a digital publication (WooCommerce)

1. **Products → Add New.**
2. Set **Product data → General** to a **Simple product**, check **Virtual** and **Downloadable**, set the price, and upload the PDF (or other file) under **Downloadable Files**.
3. Assign a **Product category** (Investigative Reports, Research, Government & Public Policy, Program Integrity, Fraud & Compliance, Industry Analysis — or add your own).
4. Set the **Product image** (cover) and a **Short description** (used on cards and the purchase panel).
5. In the **Publication Details** meta box (below the editor), optionally set **What's Included**, **Format**, and **Delivery** — sensible defaults are shown automatically if left blank.
6. Publish. The product now appears in the homepage Publications section (if marked "Featured", or simply by recency), on the Publications archive, and is purchasable with an instant digital download after checkout — no physical shipping is ever requested, since the product is virtual.

## Theme architecture

```
dwc-group-theme/
├── style.css                    Theme header (required by WordPress)
├── functions.php                Bootstraps inc/
├── front-page.php               Homepage
├── page.php / single.php / archive.php / 404.php / index.php
├── header.php / footer.php / searchform.php / comments.php
├── inc/
│   ├── setup.php                 Theme supports, nav menus, image sizes
│   ├── enqueue.php                Styles/scripts, Google Fonts, dynamic color CSS
│   ├── customizer.php             All editable Customizer settings
│   ├── nav-walker.php             Accessible nav menu walker
│   ├── template-tags.php          Shared helper functions
│   ├── cpt-services.php           `Service` custom post type
│   ├── contact-form.php           Native contact form handler
│   ├── onboarding.php             One-time first-activation setup
│   └── woocommerce.php            WooCommerce integration (loaded only if active)
├── page-templates/                About / Services / Contact page templates
├── template-parts/
│   ├── header/, footer/           Site chrome
│   ├── home/                      Homepage sections (hero, intro, services, why-dwc, fraud-audit-ai, publications, final-cta)
│   ├── services/                  Service card + full service section components
│   ├── publications/              Publication card component
│   └── components/                Section heading, CTA button, breadcrumbs, numbered principle, page hero, contact form, post card
├── woocommerce/                   Template overrides (shop archive, product card, single product)
└── assets/
    ├── css/                       main.css (design system), woocommerce.css, editor-style.css
    └── js/                        main.js (vanilla JS, no dependencies)
```

## Design system

- **Colors:** deep navy (`--dwc-navy`), charcoal (`--dwc-charcoal`), white, soft gray, with a restrained electric-blue accent (`--dwc-accent`). All three are editable via the Customizer and exposed as CSS custom properties.
- **Typography:** Manrope for headings, Inter for body text (Google Fonts, loaded with `font-display: swap` and a `preconnect` hint).
- **Motion:** subtle fade-up reveals on scroll and hover micro-interactions only; everything is skipped automatically for visitors with `prefers-reduced-motion: reduce`.

## SEO & performance

- Semantic HTML5, a logical heading hierarchy, schema-friendly breadcrumbs (`BreadcrumbList`), and descriptive alt text throughout.
- No competing meta-description/Open Graph output when Yoast SEO or Rank Math is active — the theme only fills the gap when neither plugin is installed.
- Minimal CSS/JS footprint, deferred non-critical script loading, lazy-loaded images via WordPress core, and responsive image sizes (`dwc-card`, `dwc-cover`, `dwc-wide`).

## Accessibility

- Skip-to-content link, visible focus states, keyboard-operable navigation (including the mobile menu and submenu toggles), ARIA labels/roles on navigation and forms, and a reduced-motion-safe scroll-reveal.

## Customizing further

- **Colors, fonts, spacing:** design tokens live at the top of `assets/css/main.css` as CSS custom properties.
- **Swap Google Fonts for self-hosted fonts:** edit `dwc_group_fonts_url()` in `inc/enqueue.php`.
- **Add a new footer link column or homepage section:** follow the existing pattern in `template-parts/footer/site-footer.php` or `front-page.php` — each section is an independent, reusable template part.

## License

GPL v2 or later, consistent with WordPress's own licensing.
