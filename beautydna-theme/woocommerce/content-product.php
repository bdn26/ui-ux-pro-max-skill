<?php
/**
 * Product card — override of WooCommerce's woocommerce/content-product.php.
 *
 * Keeps all real WooCommerce data and hooks (sale flash, rating, price,
 * add-to-cart button/AJAX) but restyles the markup into an editorial
 * product card with a hover-swap image and a short descriptor line.
 *
 * @package BeautyDNA
 * @see https://woocommerce.com/document/template-structure/
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( 'bdna-card', $product ); ?>>
	<?php
	/**
	 * woocommerce_before_shop_loop_item hook — kept so plugins relying on
	 * it (badges, subscriptions, bundles) keep working.
	 */
	do_action( 'woocommerce_before_shop_loop_item' );
	?>

	<a href="<?php the_permalink(); ?>" class="woocommerce-LoopProduct-link woocommerce-loop-product__link">
		<div class="bdna-card-media">
			<?php
			echo wp_kses_post( $product->get_image( 'beautydna-product-card' ) );

			$gallery_ids = $product->get_gallery_image_ids();
			if ( ! empty( $gallery_ids ) ) {
				echo wp_get_attachment_image( $gallery_ids[0], 'beautydna-product-card', false, array( 'class' => 'bdna-card-media__hover', 'alt' => '' ) );
			}

			do_action( 'woocommerce_before_shop_loop_item_title' );
			?>
		</div>

		<div class="bdna-card-body">
			<?php do_action( 'woocommerce_shop_loop_item_title' ); ?>

			<?php
			$short_desc = $product->get_short_description();
			if ( $short_desc ) :
				?>
				<p class="bdna-card-descriptor"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $short_desc ), 8 ) ); ?></p>
			<?php endif; ?>

			<?php
			$rating_count = $product->get_rating_count();
			if ( $rating_count > 0 ) {
				echo wc_get_rating_html( $product->get_average_rating(), $rating_count );
				echo '<span class="bdna-card-reviews">(' . absint( $rating_count ) . ')</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
			?>

			<?php woocommerce_template_loop_price(); ?>
		</div>
	</a>

	<?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>

	<div class="bdna-card-actions">
		<?php woocommerce_template_loop_add_to_cart(); ?>
	</div>

	<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
</li>
