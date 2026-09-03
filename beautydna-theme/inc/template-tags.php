<?php
/**
 * Small reusable template helpers shared across template-parts/ and the
 * woocommerce/ overrides.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print a placeholder visual block. Replace with a real <img> (post
 * thumbnail, ACF image field, etc.) once photography is available — the
 * data-placeholder label makes every stand-in easy to find in the DOM.
 */
function beautydna_placeholder( $label, $ratio = '4 / 5', $extra_class = '', $dark = false ) {
	printf(
		'<span class="bdna-placeholder%1$s%2$s" style="--bdna-ratio:%3$s;" data-placeholder="%4$s" role="img" aria-label="%4$s"></span>',
		$dark ? ' bdna-placeholder--dark' : '',
		$extra_class ? ' ' . esc_attr( $extra_class ) : '',
		esc_attr( $ratio ),
		esc_attr( $label )
	);
}

/**
 * Fallback menu when no menu is assigned yet in Appearance → Menus, so the
 * header/footer never render empty during initial setup.
 */
function beautydna_fallback_primary_menu() {
	$items = array(
		'shop'        => __( 'Shop', 'beautydna' ),
		'skincare'    => __( 'Skincare', 'beautydna' ),
		'haircare'    => __( 'Haircare', 'beautydna' ),
		'supplements' => __( 'Supplements', 'beautydna' ),
		'body'        => __( 'Body', 'beautydna' ),
		'best-sellers'=> __( 'Best Sellers', 'beautydna' ),
		'new'         => __( 'New', 'beautydna' ),
	);
	echo '<ul id="bdna-primary-menu" class="bdna-nav__list">';
	foreach ( $items as $slug => $label ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( home_url( '/' . $slug . '/' ) ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * A handful of inline social icons so the footer/header don't depend on
 * an icon-font plugin.
 */
function beautydna_social_icon( $network ) {
	$icons = array(
		'instagram' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1"/></svg>',
		'tiktok'    => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M14 3c.4 2.3 1.9 3.9 4.2 4.1v2.6c-1.5 0-2.9-.5-4.2-1.4v6.3a5.4 5.4 0 1 1-5.4-5.4c.3 0 .6 0 .9.1v2.7a2.7 2.7 0 1 0 1.9 2.6V3H14Z"/></svg>',
		'pinterest' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-3.6 19.3c0-.8 0-1.8.2-2.6l1.4-6s-.3-.7-.3-1.7c0-1.6.9-2.8 2-2.8 1 0 1.4.7 1.4 1.6 0 1-.6 2.4-.9 3.7-.3 1.1.5 2 1.6 2 1.9 0 3.2-2.4 3.2-5.3 0-2.2-1.5-3.9-4.2-3.9-3.1 0-5 2.3-5 4.8 0 .9.3 1.5.7 2 .2.2.2.3.1.5l-.3 1c-.1.3-.3.4-.6.3-1.4-.6-2.1-2.3-2.1-4.1 0-3 2.5-6.7 7.5-6.7 4 0 6.6 2.9 6.6 6 0 4.1-2.3 7.2-5.6 7.2-1.1 0-2.2-.6-2.5-1.3l-.8 3c-.2.9-.7 1.9-1.1 2.6A10 10 0 1 0 12 2Z"/></svg>',
		'facebook'  => '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-7.5H16l.4-3H13.5V8.4c0-.9.2-1.5 1.5-1.5H16.5V4.2A20 20 0 0 0 14 4c-2.4 0-4 1.5-4 4.1v2.4H7.5v3H10V21h3.5Z"/></svg>',
	);
	return $icons[ $network ] ?? '';
}
