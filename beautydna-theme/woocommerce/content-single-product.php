<?php
/**
 * Single product content — override of
 * woocommerce/content-single-product.php.
 *
 * Same WooCommerce hooks as core (gallery, title, rating, price, add to
 * cart, meta, tabs, upsells/related) — just restyled into a two-column
 * gallery + sticky buy-box layout. Nothing here fakes WooCommerce data.
 *
 * @package BeautyDNA
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! is_singular( 'product' ) ) {
	return;
}

if ( post_password_required() ) {
	echo get_the_password_form(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'bdna-product', $product ); ?>>

	<div class="bdna-container bdna-product__breadcrumbs">
		<?php woocommerce_breadcrumb(); ?>
	</div>

	<div class="bdna-container bdna-product__layout">

		<div class="bdna-product__gallery">
			<?php
			/**
			 * woocommerce_before_single_product_summary hook.
			 *
			 * @hooked woocommerce_show_product_images - 20
			 */
			do_action( 'woocommerce_before_single_product_summary' );
			?>
		</div>

		<div class="summary entry-summary bdna-product__summary">
			<?php
			/**
			 * woocommerce_single_product_summary hook.
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			do_action( 'woocommerce_single_product_summary' );
			?>

			<div class="bdna-product__trust">
				<div class="bdna-trust-item">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M4 12l5 5L20 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
					<?php esc_html_e( 'Free shipping over', 'beautydna' ); ?> <?php echo wp_kses_post( wc_price( beautydna_get_free_shipping_threshold() ) ); ?>
				</div>
				<div class="bdna-trust-item">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 3l7 3v6c0 4.5-3 7.5-7 9-4-1.5-7-4.5-7-9V6l7-3Z" stroke-linejoin="round"/></svg>
					<?php esc_html_e( '30-day returns', 'beautydna' ); ?>
				</div>
				<div class="bdna-trust-item">
					<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M3 12h18M3 12l5-5M3 12l5 5" stroke-linecap="round" stroke-linejoin="round"/></svg>
					<?php esc_html_e( 'Secure checkout', 'beautydna' ); ?>
				</div>
			</div>
		</div>

	</div>

	<div class="bdna-container bdna-product__tabs">
		<?php woocommerce_output_product_data_tabs(); ?>
	</div>

	<?php
	/**
	 * woocommerce_after_single_product_summary hook.
	 *
	 * @hooked beautydna_frequently_bought_together - 8
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 * @hooked beautydna_recently_viewed - 25
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>
</div>
