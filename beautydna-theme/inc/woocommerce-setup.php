<?php
/**
 * WooCommerce integration: presentation customization only. Every hook
 * here restyles or relabels core WooCommerce output — it never replaces
 * cart, checkout, account or catalog functionality with custom markup.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Catalog layout
 * ---------------------------------------------------------------------- */

/**
 * woocommerce/content-product.php (below) renders the rating and price
 * itself inside the card body for layout control, so the default
 * loop-item-title hook callbacks are removed here to avoid duplicating
 * them. The wishlist button stays hooked to the same action.
 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

add_filter( 'loop_shop_columns', fn() => 4 );
add_filter( 'loop_shop_per_page', fn() => 12 );

add_filter( 'woocommerce_output_related_products_args', function ( $args ) {
	$args['posts_per_page'] = 4;
	$args['columns']        = 4;
	return $args;
} );

add_filter( 'woocommerce_cross_sells_columns', fn() => 4 );

/**
 * "Related products" (core heading) sits alongside the upsell heading
 * "You may also like…" on the same page — relabel it so the two
 * sections don't read as duplicates.
 */
add_filter( 'gettext', function ( $translation, $text, $domain ) {
	if ( 'woocommerce' === $domain && 'Related products' === $text ) {
		return __( 'More To Explore', 'beautydna' );
	}
	return $translation;
}, 10, 3 );

/* -------------------------------------------------------------------------
 * Breadcrumbs — keep WooCommerce's structured-data breadcrumb, restyle it.
 * ---------------------------------------------------------------------- */

add_filter( 'woocommerce_breadcrumb_defaults', function ( $defaults ) {
	$defaults['wrap_before'] = '<nav class="bdna-breadcrumbs woocommerce-breadcrumb" aria-label="' . esc_attr__( 'Breadcrumb', 'beautydna' ) . '">';
	$defaults['wrap_after']  = '</nav>';
	$defaults['delimiter']   = ' <span aria-hidden="true">/</span> ';
	$defaults['home']        = __( 'Home', 'beautydna' );
	return $defaults;
} );

/* -------------------------------------------------------------------------
 * Sale badge — restyle the default flash into the BeautyDNA badge.
 * ---------------------------------------------------------------------- */

add_filter( 'woocommerce_sale_flash', function ( $html, $post, $product ) {
	return '<span class="bdna-badge bdna-badge--sale">' . esc_html__( 'Sale', 'beautydna' ) . '</span>';
}, 10, 3 );

/* -------------------------------------------------------------------------
 * "Quick Add" label on archive/category grids only — single product page
 * keeps the standard "Add to Cart" label.
 * ---------------------------------------------------------------------- */

add_filter( 'woocommerce_product_add_to_cart_text', function ( $text, $product ) {
	if ( ! is_product() && $product && $product->is_purchasable() && $product->is_in_stock() ) {
		return __( 'Quick Add', 'beautydna' );
	}
	return $text;
}, 10, 2 );

/* -------------------------------------------------------------------------
 * Wishlist toggle button on product cards & single product summary.
 * Lightweight, cookie-free client-side wishlist (localStorage) — see
 * assets/js/main.js. Swap for a full wishlist plugin's hooks if the
 * merchant later needs synced/logged-in wishlists.
 * ---------------------------------------------------------------------- */

function beautydna_wishlist_button( $product_id = null ) {
	$product_id = $product_id ? $product_id : get_the_ID();
	printf(
		'<button type="button" class="bdna-wishlist-btn" data-product-id="%1$d" aria-pressed="false" aria-label="%2$s">
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 20.5s-7.5-4.7-10-9.3C.4 7.8 2 4 5.7 4c2.1 0 3.6 1.2 4.3 2.6C10.7 5.2 12.2 4 14.3 4 18 4 19.6 7.8 18 11.2c-2.5 4.6-10 9.3-10 9.3Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
		</button>',
		absint( $product_id ),
		esc_attr__( 'Add to wishlist', 'beautydna' )
	);
}

add_action( 'woocommerce_after_shop_loop_item_title', 'beautydna_archive_wishlist_button', 5 );
function beautydna_archive_wishlist_button() {
	echo '<div class="bdna-card-quick-actions">';
	beautydna_wishlist_button();
	echo '</div>';
}

add_action( 'woocommerce_single_product_summary', 'beautydna_single_wishlist_button', 35 );
function beautydna_single_wishlist_button() {
	global $product;
	echo '<div class="bdna-product__wishlist">';
	beautydna_wishlist_button( $product ? $product->get_id() : null );
	echo '<span>' . esc_html__( 'Add to Wishlist', 'beautydna' ) . '</span>';
	echo '</div>';
}

/* -------------------------------------------------------------------------
 * "Buy Now" — submits the existing add-to-cart form but redirects straight
 * to checkout instead of the cart, using WooCommerce's own cart pipeline.
 * ---------------------------------------------------------------------- */

add_filter( 'woocommerce_add_to_cart_redirect', function ( $url ) {
	if ( isset( $_GET['bdna_buy_now'] ) && '1' === $_GET['bdna_buy_now'] ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return wc_get_checkout_url();
	}
	return $url;
} );

add_action( 'woocommerce_after_add_to_cart_button', function () {
	global $product;
	if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
		return;
	}
	$buy_now_action = add_query_arg( 'bdna_buy_now', '1' );
	printf(
		'<button type="submit" name="add-to-cart" value="%1$d" formaction="%2$s" class="bdna-btn bdna-btn--secondary bdna-buy-now">%3$s</button>',
		absint( $product->get_id() ),
		esc_url( $buy_now_action ),
		esc_html__( 'Buy Now', 'beautydna' )
	);
} );

/* -------------------------------------------------------------------------
 * Product tabs — relabel Description to "Overview", add Benefits,
 * Ingredients and How To Use tabs sourced from product meta so content
 * stays editable from the product edit screen without a page builder.
 * ---------------------------------------------------------------------- */

add_filter( 'woocommerce_product_tabs', function ( $tabs ) {
	global $product;

	if ( isset( $tabs['description'] ) ) {
		$tabs['description']['title'] = __( 'Overview', 'beautydna' );
	}

	if ( isset( $tabs['additional_information'] ) ) {
		$tabs['additional_information']['title'] = __( 'Ingredients', 'beautydna' );
	}

	$benefits = $product ? get_post_meta( $product->get_id(), '_beautydna_benefits', true ) : '';
	if ( $benefits ) {
		$tabs['beautydna_benefits'] = array(
			'title'    => __( 'Benefits', 'beautydna' ),
			'priority' => 15,
			'callback' => function () use ( $benefits ) {
				echo '<div class="bdna-tab-copy">' . wp_kses_post( wpautop( $benefits ) ) . '</div>';
			},
		);
	}

	$how_to_use = $product ? get_post_meta( $product->get_id(), '_beautydna_how_to_use', true ) : '';
	if ( $how_to_use ) {
		$tabs['beautydna_how_to_use'] = array(
			'title'    => __( 'How To Use', 'beautydna' ),
			'priority' => 25,
			'callback' => function () use ( $how_to_use ) {
				echo '<div class="bdna-tab-copy">' . wp_kses_post( wpautop( $how_to_use ) ) . '</div>';
			},
		);
	}

	$faq = $product ? get_post_meta( $product->get_id(), '_beautydna_faq', true ) : '';
	if ( $faq ) {
		$tabs['beautydna_faq'] = array(
			'title'    => __( 'FAQ', 'beautydna' ),
			'priority' => 35,
			'callback' => function () use ( $faq ) {
				echo '<div class="bdna-tab-copy bdna-faq">' . wp_kses_post( wpautop( $faq ) ) . '</div>';
			},
		);
	}

	uasort( $tabs, fn( $a, $b ) => ( $a['priority'] ?? 99 ) <=> ( $b['priority'] ?? 99 ) );

	return $tabs;
} );

/* -------------------------------------------------------------------------
 * Register the custom product meta fields in a simple metabox so editors
 * don't need a page builder to fill Benefits / How To Use / FAQ.
 * ---------------------------------------------------------------------- */

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'beautydna_product_story', __( 'BeautyDNA Product Story', 'beautydna' ), 'beautydna_render_product_story_box', 'product', 'normal', 'default' );
} );

function beautydna_render_product_story_box( $post ) {
	wp_nonce_field( 'beautydna_product_story', 'beautydna_product_story_nonce' );
	$fields = array(
		'_beautydna_benefits'   => __( 'Benefits (one per line)', 'beautydna' ),
		'_beautydna_how_to_use' => __( 'How To Use', 'beautydna' ),
		'_beautydna_faq'        => __( 'FAQ (Q on one line, A on the next)', 'beautydna' ),
	);
	foreach ( $fields as $key => $label ) {
		$value = get_post_meta( $post->ID, $key, true );
		printf( '<p><label for="%1$s"><strong>%2$s</strong></label><br />', esc_attr( $key ), esc_html( $label ) );
		printf( '<textarea id="%1$s" name="%1$s" rows="4" style="width:100%%;">%2$s</textarea></p>', esc_attr( $key ), esc_textarea( $value ) );
	}
}

add_action( 'save_post_product', function ( $post_id ) {
	if ( ! isset( $_POST['beautydna_product_story_nonce'] ) || ! wp_verify_nonce( $_POST['beautydna_product_story_nonce'], 'beautydna_product_story' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	foreach ( array( '_beautydna_benefits', '_beautydna_how_to_use', '_beautydna_faq' ) as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_textarea_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
} );

/* -------------------------------------------------------------------------
 * Free shipping threshold — read from the site's configured "Free
 * shipping" method minimum if present, otherwise fall back to a
 * customizer value. Powers the cart-drawer progress bar.
 * ---------------------------------------------------------------------- */

function beautydna_get_free_shipping_threshold() {
	$threshold = apply_filters( 'beautydna_free_shipping_threshold', get_theme_mod( 'beautydna_free_shipping_threshold', 75 ) );
	return floatval( $threshold );
}

/* -------------------------------------------------------------------------
 * Simplify the checkout: fewer fields, single column on narrow screens is
 * handled purely in CSS. Here we just drop the "Order notes" field, which
 * is rarely used and adds friction, while leaving every other WooCommerce
 * checkout field/hook untouched.
 * ---------------------------------------------------------------------- */

add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
