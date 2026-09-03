<?php
/**
 * Ingredient & benefit storytelling. Reads the "Ingredients" custom post
 * type (Ingredients → Add New in wp-admin: title, excerpt, featured
 * image). Falls back to five common beauty/wellness ingredients as
 * starter content — replace via wp-admin, not by editing this file.
 * No medical claims are made in the fallback copy.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$query = new WP_Query( array(
	'post_type'      => 'bdna_ingredient',
	'post_status'    => 'publish',
	'posts_per_page' => 5,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
) );

$fallback = array(
	array( 'title' => __( 'Collagen', 'beautydna' ), 'excerpt' => __( 'A structural protein our bodies produce naturally — often explored to support skin elasticity and joint comfort as part of a beauty-from-within routine.', 'beautydna' ) ),
	array( 'title' => __( 'Sea Moss', 'beautydna' ), 'excerpt' => __( 'A mineral-rich sea vegetable long used in wellness traditions, valued for its nutrient density.', 'beautydna' ) ),
	array( 'title' => __( 'Apple Cider Vinegar', 'beautydna' ), 'excerpt' => __( 'A fermented staple in wellness routines, often paired with other whole-food ingredients.', 'beautydna' ) ),
	array( 'title' => __( 'Ashwagandha', 'beautydna' ), 'excerpt' => __( 'An adaptogenic root with a long history in traditional wellness practices.', 'beautydna' ) ),
	array( 'title' => __( 'Hyaluronic Acid', 'beautydna' ), 'excerpt' => __( 'A moisture-binding molecule found naturally in skin, prized in hydration-focused formulas.', 'beautydna' ) ),
);
?>
<section class="bdna-section bdna-ingredients bdna-inverse" aria-labelledby="bdna-ingredients-heading">
	<div class="bdna-container">
		<div class="bdna-section-heading bdna-fade-in">
			<p class="bdna-eyebrow bdna-eyebrow--inverse"><?php esc_html_e( 'What Goes In It', 'beautydna' ); ?></p>
			<h2 id="bdna-ingredients-heading" class="bdna-heading-lg"><?php esc_html_e( 'Ingredients, Explained', 'beautydna' ); ?></h2>
			<p class="bdna-lede"><?php esc_html_e( 'Educational notes on what we formulate with and why — not medical advice.', 'beautydna' ); ?></p>
		</div>

		<div class="bdna-ingredients__row">
			<?php if ( $query->have_posts() ) : ?>
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<article class="bdna-ingredient-card bdna-fade-in">
						<div class="bdna-ingredient-card__media">
							<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'beautydna-square' ); else : beautydna_placeholder( get_the_title(), '1 / 1', '', true ); endif; ?>
						</div>
						<h3><?php the_title(); ?></h3>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
					</article>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php else : ?>
				<?php foreach ( $fallback as $ingredient ) : ?>
					<article class="bdna-ingredient-card bdna-fade-in">
						<div class="bdna-ingredient-card__media">
							<?php beautydna_placeholder( $ingredient['title'], '1 / 1', '', true ); ?>
						</div>
						<h3><?php echo esc_html( $ingredient['title'] ); ?></h3>
						<p><?php echo esc_html( $ingredient['excerpt'] ); ?></p>
					</article>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>
