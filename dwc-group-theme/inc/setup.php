<?php
/**
 * Core theme setup: supports, menus, image sizes.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme setup.
 */
function dwc_group_setup() {
	load_theme_textdomain( 'dwc-group', DWC_GROUP_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'custom-line-height' );
	add_theme_support( 'custom-spacing' );
	add_theme_support( 'appearance-tools' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );

	add_theme_support(
		'custom-logo',
		array(
			'height'      => 72,
			'width'       => 240,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// WooCommerce declared here even if the plugin is not yet active,
	// so activating it later does not require re-saving permalinks/support.
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'dwc-group' ),
			'footer-company'  => __( 'Footer &mdash; Company', 'dwc-group' ),
			'footer-services' => __( 'Footer &mdash; Services', 'dwc-group' ),
			'footer-publications' => __( 'Footer &mdash; Publications', 'dwc-group' ),
			'footer-legal' => __( 'Footer &mdash; Legal', 'dwc-group' ),
		)
	);

	add_image_size( 'dwc-card', 640, 480, true );
	add_image_size( 'dwc-cover', 800, 1040, true );
	add_image_size( 'dwc-wide', 1600, 900, true );

	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 1200;
	}
}
add_action( 'after_setup_theme', 'dwc_group_setup' );

/**
 * Register widget areas (footer + WooCommerce sidebar-compatible, used sparingly).
 */
function dwc_group_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Footer Widget Area', 'dwc-group' ),
			'id'            => 'footer-widgets',
			'description'   => __( 'Optional widgets shown above the footer bottom bar.', 'dwc-group' ),
			'before_widget' => '<div class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="footer-widget__title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'dwc_group_widgets_init' );

/**
 * Fallback primary menu when none has been assigned yet.
 */
function dwc_group_fallback_menu() {
	$links = array(
		'/'           => __( 'Home', 'dwc-group' ),
		'/about-us/'  => __( 'About Us', 'dwc-group' ),
		'/services/'  => __( 'Our Services', 'dwc-group' ),
		'/publications/' => __( 'Publications', 'dwc-group' ),
		'/contact/'   => __( 'Contact', 'dwc-group' ),
	);
	echo '<ul id="primary-menu" class="site-nav__list">';
	foreach ( $links as $url => $label ) {
		printf( '<li class="menu-item"><a href="%s">%s</a></li>', esc_url( home_url( $url ) ), esc_html( $label ) );
	}
	echo '</ul>';
}

/**
 * Excerpt tuning.
 */
function dwc_group_excerpt_length( $length ) {
	return 24;
}
add_filter( 'excerpt_length', 'dwc_group_excerpt_length' );

function dwc_group_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'dwc_group_excerpt_more' );

/**
 * Document title / meta description fallback (kept minimal -- Yoast/Rank Math take over when active).
 */
function dwc_group_meta_description() {
	if ( function_exists( 'YoastSEO' ) || defined( 'RANK_MATH_VERSION' ) ) {
		return;
	}
	$description = '';
	if ( is_front_page() ) {
		$description = get_bloginfo( 'description' );
	} elseif ( is_singular() ) {
		$description = wp_strip_all_tags( get_the_excerpt() );
	}
	if ( $description ) {
		printf( '<meta name="description" content="%s" />' . "\n", esc_attr( wp_trim_words( $description, 30 ) ) );
	}
}
add_action( 'wp_head', 'dwc_group_meta_description', 1 );

/**
 * Security / cleanup.
 */
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );
