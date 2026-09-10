<?php
/**
 * Publication card component.
 *
 * Reused by: woocommerce/content-product.php (shop archive + related
 * publications) and the homepage Publications section. Expects the global
 * WooCommerce $product to be set (via the loop, or wc_setup_product_data()).
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! $product instanceof WC_Product || ! $product->is_visible() ) {
	return;
}

$category = dwc_group_publication_category_label( $product->get_id() );
?>
<article <?php wc_product_class( 'publication-card', $product ); ?>>
	<a href="<?php the_permalink(); ?>" class="publication-card__media">
		<?php
		echo $product->get_image( 'dwc-card', array( 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</a>
	<div class="publication-card__body">
		<?php if ( $category ) : ?>
			<p class="publication-card__category"><?php echo esc_html( $category ); ?></p>
		<?php endif; ?>
		<h3 class="publication-card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<p class="publication-card__excerpt"><?php echo esc_html( dwc_group_trim( $product->get_short_description() ? $product->get_short_description() : $product->get_description(), 18 ) ); ?></p>
		<div class="publication-card__footer">
			<span class="publication-card__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
			<a href="<?php the_permalink(); ?>" class="btn btn--text">
				<?php esc_html_e( 'View Report', 'dwc-group' ); ?>
				<span aria-hidden="true">&rarr;</span>
			</a>
		</div>
	</div>
</article>
