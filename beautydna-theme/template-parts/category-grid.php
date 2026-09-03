<?php
/**
 * Shop by Category — pulls real WooCommerce product_cat terms (name,
 * description, category thumbnail) by slug. Create these five product
 * categories in wp-admin (Products → Categories) with matching slugs and
 * a category image to replace the placeholders automatically.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$categories = array(
	'skin'        => array( 'label' => __( 'Skin', 'beautydna' ), 'desc' => __( 'Cleansers, serums and moisturizers for a considered routine.', 'beautydna' ) ),
	'hair'        => array( 'label' => __( 'Hair', 'beautydna' ), 'desc' => __( 'Scalp-first care for strength, shine and growth.', 'beautydna' ) ),
	'body'        => array( 'label' => __( 'Body', 'beautydna' ), 'desc' => __( 'Rituals for softer skin, head to toe.', 'beautydna' ) ),
	'supplements' => array( 'label' => __( 'Supplements', 'beautydna' ), 'desc' => __( 'Beauty and wellness, from the inside out.', 'beautydna' ) ),
	'tools'       => array( 'label' => __( 'Tools', 'beautydna' ), 'desc' => __( 'The edit that elevates every step.', 'beautydna' ) ),
);
?>
<section class="bdna-section bdna-category-grid" aria-labelledby="bdna-category-heading">
	<div class="bdna-container">
		<div class="bdna-section-heading bdna-fade-in">
			<p class="bdna-eyebrow"><?php esc_html_e( 'Shop The Edit', 'beautydna' ); ?></p>
			<h2 id="bdna-category-heading" class="bdna-heading-lg"><?php esc_html_e( 'Shop by Category', 'beautydna' ); ?></h2>
		</div>
		<div class="bdna-category-grid__row">
			<?php foreach ( $categories as $slug => $fallback ) :
				$term = get_term_by( 'slug', $slug, 'product_cat' );
				$link = $term && ! is_wp_error( $term ) ? get_term_link( $term ) : ( wc_get_page_permalink( 'shop' ) . '?product_cat=' . $slug );
				$label = $term && ! is_wp_error( $term ) ? $term->name : $fallback['label'];
				$desc  = $term && ! is_wp_error( $term ) && $term->description ? $term->description : $fallback['desc'];
				$thumb_id = $term ? get_term_meta( $term->term_id, 'thumbnail_id', true ) : '';
				?>
				<a class="bdna-category-card bdna-fade-in" href="<?php echo esc_url( $link ); ?>">
					<div class="bdna-category-card__media">
						<?php if ( $thumb_id ) : ?>
							<?php echo wp_get_attachment_image( $thumb_id, 'beautydna-category', false, array( 'alt' => esc_attr( $label ) ) ); ?>
						<?php else : ?>
							<?php beautydna_placeholder( sprintf( /* translators: %s: category name */ __( '%s category image', 'beautydna' ), $label ) ); ?>
						<?php endif; ?>
					</div>
					<div class="bdna-category-card__body">
						<h3><?php echo esc_html( strtoupper( $label ) ); ?></h3>
						<p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $desc ), 12 ) ); ?></p>
						<span class="bdna-text-link"><?php esc_html_e( 'Shop Now', 'beautydna' ); ?>
							<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</span>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
