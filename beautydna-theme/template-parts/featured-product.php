<?php
/**
 * Large split-screen feature for a single hero product. Uses WooCommerce's
 * native "Featured" flag (Products → Edit → Catalog visibility) so a
 * merchandiser can swap the spotlighted product with no code change.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$featured_ids = wc_get_featured_product_ids();
$product_id   = ! empty( $featured_ids ) ? $featured_ids[0] : 0;

if ( ! $product_id ) {
	$fallback = wc_get_products( array( 'limit' => 1, 'orderby' => 'date', 'order' => 'DESC', 'status' => 'publish' ) );
	$product_id = ! empty( $fallback ) ? $fallback[0]->get_id() : 0;
}

if ( ! $product_id ) {
	return;
}

$product = wc_get_product( $product_id );
if ( ! $product ) {
	return;
}

$benefits = get_post_meta( $product_id, '_beautydna_benefits', true );
$benefits_list = $benefits ? array_filter( array_map( 'trim', explode( "\n", $benefits ) ) ) : array();
?>
<section class="bdna-section bdna-featured-product" aria-labelledby="bdna-featured-heading">
	<div class="bdna-featured-product__grid">
		<div class="bdna-featured-product__media bdna-fade-in">
			<?php echo wp_kses_post( $product->get_image( 'beautydna-hero' ) ); ?>
		</div>
		<div class="bdna-featured-product__content bdna-fade-in">
			<p class="bdna-eyebrow"><?php esc_html_e( 'The Spotlight', 'beautydna' ); ?></p>
			<h2 id="bdna-featured-heading" class="bdna-heading-lg"><?php echo esc_html( $product->get_name() ); ?></h2>

			<?php if ( $product->get_rating_count() > 0 ) : ?>
				<div class="bdna-featured-product__rating">
					<?php echo wc_get_rating_html( $product->get_average_rating(), $product->get_rating_count() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="bdna-card-reviews">(<?php echo absint( $product->get_rating_count() ); ?> <?php esc_html_e( 'reviews', 'beautydna' ); ?>)</span>
				</div>
			<?php endif; ?>

			<p class="bdna-lede bdna-featured-product__story"><?php echo wp_kses_post( wp_trim_words( $product->get_short_description() ?: $product->get_description(), 45 ) ); ?></p>

			<?php if ( ! empty( $benefits_list ) ) : ?>
				<ul class="bdna-featured-product__benefits">
					<?php foreach ( $benefits_list as $benefit ) : ?>
						<li><?php echo esc_html( $benefit ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>

			<p class="bdna-featured-product__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>

			<div class="bdna-featured-product__cta">
				<?php
				echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'woocommerce_loop_add_to_cart_link',
					sprintf(
						'<a href="%s" data-quantity="1" class="bdna-btn bdna-btn--primary ajax_add_to_cart add_to_cart_button" data-product_id="%d" data-product_sku="%s" aria-label="%s" rel="nofollow">%s</a>',
						esc_url( $product->add_to_cart_url() ),
						esc_attr( $product->get_id() ),
						esc_attr( $product->get_sku() ),
						esc_attr( $product->add_to_cart_description() ),
						esc_html( $product->add_to_cart_text() )
					),
					$product,
					array()
				);
				?>
				<a class="bdna-text-link" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php esc_html_e( 'Full Details', 'beautydna' ); ?></a>
			</div>
		</div>
	</div>
</section>
