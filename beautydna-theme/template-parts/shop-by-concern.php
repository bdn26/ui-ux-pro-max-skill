<?php
/**
 * Shop by Concern — editorial, image-led. Maps to WooCommerce product
 * tags so shoppers land on a real, filtered product listing. Tag each
 * relevant product with the matching slug in Products → Tags.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$concerns = array(
	'glow-brighten'       => __( 'Glow & Brighten', 'beautydna' ),
	'hydration'           => __( 'Hydration', 'beautydna' ),
	'hair-scalp'          => __( 'Hair & Scalp', 'beautydna' ),
	'body-wellness'       => __( 'Body Wellness', 'beautydna' ),
	'beauty-from-within'  => __( 'Beauty From Within', 'beautydna' ),
);
?>
<section class="bdna-section bdna-concern" aria-labelledby="bdna-concern-heading">
	<div class="bdna-container">
		<div class="bdna-section-heading bdna-fade-in">
			<p class="bdna-eyebrow"><?php esc_html_e( 'Guided By Goal', 'beautydna' ); ?></p>
			<h2 id="bdna-concern-heading" class="bdna-heading-lg"><?php esc_html_e( 'Shop by Concern', 'beautydna' ); ?></h2>
		</div>

		<div class="bdna-concern__row">
			<?php foreach ( $concerns as $slug => $label ) :
				$term = get_term_by( 'slug', $slug, 'product_tag' );
				$link = $term && ! is_wp_error( $term ) ? get_term_link( $term ) : ( wc_get_page_permalink( 'shop' ) . '?product_tag=' . $slug );
				?>
				<a class="bdna-concern-card bdna-fade-in" href="<?php echo esc_url( $link ); ?>">
					<?php beautydna_placeholder( sprintf( /* translators: %s: concern name */ __( '%s imagery', 'beautydna' ), $label ), '3 / 4' ); ?>
					<span class="bdna-concern-card__label"><?php echo esc_html( $label ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
