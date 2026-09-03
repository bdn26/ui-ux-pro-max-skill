<?php
/**
 * Styles & scripts. Split into small files per concern so a change to,
 * say, the cart drawer never invalidates the cache for the homepage CSS.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function beautydna_assets() {
	// Editorial serif + clean sans, self-hosted preconnect for performance.
	// Swap the family list here if the brand later commissions custom type.
	wp_enqueue_style(
		'beautydna-fonts',
		'https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,500;0,9..144,600;1,9..144,500&family=Inter:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	$css_files = array(
		'00-tokens',
		'01-base',
		'02-layout',
		'03-components',
		'04-home',
		'05-shop',
		'06-product',
		'07-cart-checkout',
		'08-account',
		'09-misc',
	);

	$deps = array( 'beautydna-fonts' );
	foreach ( $css_files as $file ) {
		wp_enqueue_style( 'beautydna-' . $file, BEAUTYDNA_URI . '/assets/css/' . $file . '.css', $deps, BEAUTYDNA_VERSION );
		$deps = array( 'beautydna-' . $file );
	}

	wp_enqueue_script( 'beautydna-main', BEAUTYDNA_URI . '/assets/js/main.js', array(), BEAUTYDNA_VERSION, true );

	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_script( 'beautydna-cart-drawer', BEAUTYDNA_URI . '/assets/js/cart-drawer.js', array( 'jquery' ), BEAUTYDNA_VERSION, true );
		wp_localize_script(
			'beautydna-cart-drawer',
			'beautydnaCart',
			array(
				'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
				'cartUrl'          => wc_get_cart_url(),
				'checkoutUrl'      => wc_get_checkout_url(),
				'freeShipping'     => beautydna_get_free_shipping_threshold(),
				'currencySymbol'   => get_woocommerce_currency_symbol(),
				'nonce'            => wp_create_nonce( 'beautydna-cart' ),
			)
		);

		if ( is_product() ) {
			wp_enqueue_script( 'beautydna-product-gallery', BEAUTYDNA_URI . '/assets/js/product-gallery.js', array(), BEAUTYDNA_VERSION, true );
		}

		if ( is_shop() || is_product_category() || is_product_tag() ) {
			wp_enqueue_script( 'beautydna-filters', BEAUTYDNA_URI . '/assets/js/filters.js', array(), BEAUTYDNA_VERSION, true );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'beautydna_assets' );

/**
 * Defer/async non-critical scripts without a build step.
 */
function beautydna_script_attributes( $tag, $handle ) {
	$defer_handles = array( 'beautydna-main', 'beautydna-cart-drawer', 'beautydna-product-gallery', 'beautydna-filters' );
	if ( in_array( $handle, $defer_handles, true ) && false === strpos( $tag, 'defer' ) ) {
		$tag = str_replace( ' src', ' defer src', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'beautydna_script_attributes', 10, 2 );
