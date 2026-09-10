<?php
/**
 * Single publication (WooCommerce product) layout.
 *
 * Overrides woocommerce/templates/content-single-product.php. Restructures
 * the default gallery + summary into a two-column editorial "cover +
 * purchase panel" layout while preserving every standard WooCommerce hook
 * (title, price, excerpt, add-to-cart, meta, tabs, related products) so
 * plugins that hook into those actions keep working normally.
 *
 * @package DWC_Group
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'publication-single', $product ); ?>>

	<?php do_action( 'woocommerce_before_single_product' ); ?>

	<div class="publication-single__grid">

		<div class="publication-single__media">
			<?php do_action( 'woocommerce_before_single_product_summary' ); ?>
		</div>

		<div class="publication-single__summary summary entry-summary">
			<?php do_action( 'woocommerce_single_product_summary' ); ?>
		</div>

	</div>

	<div class="publication-single__details">
		<?php do_action( 'woocommerce_after_single_product_summary' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_single_product' ); ?>

</div>
