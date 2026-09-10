<?php
/**
 * Styles & scripts.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dwc_group_assets() {
	wp_enqueue_style( 'dwc-group-fonts', dwc_group_fonts_url(), array(), null );
	wp_enqueue_style( 'dwc-group-main', DWC_GROUP_URI . '/assets/css/main.css', array(), DWC_GROUP_VERSION );

	if ( class_exists( 'WooCommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		wp_enqueue_style( 'dwc-group-woocommerce', DWC_GROUP_URI . '/assets/css/woocommerce.css', array( 'dwc-group-main' ), DWC_GROUP_VERSION );
	}

	wp_enqueue_script( 'dwc-group-main', DWC_GROUP_URI . '/assets/js/main.js', array(), DWC_GROUP_VERSION, true );
	wp_script_add_data( 'dwc-group-main', 'strategy', 'defer' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'dwc_group_assets' );

/**
 * Google Fonts: Manrope (display/headings) + Inter (body/UI). Self-hosted swap
 * is trivial later -- only this URL needs to change.
 */
function dwc_group_fonts_url() {
	$fonts_url = '';

	$font_families = array(
		'Manrope:wght@500;600;700;800',
		'Inter:wght@400;500;600;700',
	);

	$query_args = array(
		'family'  => implode( '&family=', $font_families ),
		'display' => 'swap',
	);

	$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css2' );

	return esc_url_raw( $fonts_url );
}

/**
 * Resource hints for the font host.
 */
function dwc_group_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'dwc_group_resource_hints', 10, 2 );

/**
 * Defer non-critical scripts registered by this theme only (leaves plugin/admin scripts untouched).
 */
function dwc_group_defer_scripts( $tag, $handle, $src ) {
	if ( is_admin() ) {
		return $tag;
	}
	$deferred = array( 'dwc-group-main' );
	if ( in_array( $handle, $deferred, true ) && false === strpos( $tag, 'defer' ) ) {
		$tag = str_replace( ' src=', ' defer src=', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'dwc_group_defer_scripts', 10, 3 );

/**
 * Output brand colors + a couple of layout dials from the Customizer as CSS custom properties.
 */
function dwc_group_dynamic_styles() {
	$primary = get_theme_mod( 'dwc_color_primary', '#17140f' );
	$accent  = get_theme_mod( 'dwc_color_accent', '#bf5b2e' );
	$charcoal = get_theme_mod( 'dwc_color_charcoal', '#362e27' );
	?>
	<style id="dwc-group-dynamic-styles">
		:root {
			--dwc-navy: <?php echo esc_html( $primary ); ?>;
			--dwc-accent: <?php echo esc_html( $accent ); ?>;
			--dwc-charcoal: <?php echo esc_html( $charcoal ); ?>;
		}
	</style>
	<?php
}
add_action( 'wp_head', 'dwc_group_dynamic_styles', 20 );
