<?php
/**
 * DWC Group theme bootstrap.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'DWC_GROUP_VERSION', '1.0.0' );
define( 'DWC_GROUP_DIR', get_template_directory() );
define( 'DWC_GROUP_URI', get_template_directory_uri() );

require DWC_GROUP_DIR . '/inc/setup.php';
require DWC_GROUP_DIR . '/inc/enqueue.php';
require DWC_GROUP_DIR . '/inc/customizer.php';
require DWC_GROUP_DIR . '/inc/nav-walker.php';
require DWC_GROUP_DIR . '/inc/template-tags.php';
require DWC_GROUP_DIR . '/inc/cpt-services.php';
require DWC_GROUP_DIR . '/inc/contact-form.php';
require DWC_GROUP_DIR . '/inc/onboarding.php';

if ( class_exists( 'WooCommerce' ) ) {
	require DWC_GROUP_DIR . '/inc/woocommerce.php';
}
