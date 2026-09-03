<?php
/**
 * BeautyDNA theme bootstrap.
 *
 * Loads theme setup, asset enqueueing, WooCommerce integration, the
 * customizer and small AJAX endpoints from inc/. Kept out of this file
 * so each concern stays independently readable and testable.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BEAUTYDNA_VERSION', '1.0.0' );
define( 'BEAUTYDNA_DIR', get_template_directory() );
define( 'BEAUTYDNA_URI', get_template_directory_uri() );

require BEAUTYDNA_DIR . '/inc/theme-setup.php';
require BEAUTYDNA_DIR . '/inc/enqueue.php';
require BEAUTYDNA_DIR . '/inc/customizer.php';
require BEAUTYDNA_DIR . '/inc/woocommerce-setup.php';
require BEAUTYDNA_DIR . '/inc/woocommerce-hooks.php';
require BEAUTYDNA_DIR . '/inc/ajax.php';
require BEAUTYDNA_DIR . '/inc/template-tags.php';
