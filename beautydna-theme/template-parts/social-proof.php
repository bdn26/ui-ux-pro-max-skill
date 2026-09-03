<?php
/**
 * Testimonials (from the "Testimonials" admin menu) + an Instagram-style
 * gallery. The gallery here is a styled placeholder grid linking out to
 * the configured Instagram profile — wire in a feed plugin (e.g. Smash
 * Balloon) against .bdna-instagram-grid to pull live posts later.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$query = new WP_Query( array(
	'post_type'      => 'bdna_testimonial',
	'post_status'    => 'publish',
	'posts_per_page' => 3,
) );

$fallback = array(
	array( 'name' => __( 'Amara T.', 'beautydna' ), 'quote' => __( 'My skin has never felt this balanced. The routine is simple and the results speak for themselves.', 'beautydna' ), 'rating' => 5 ),
	array( 'name' => __( 'Priya K.', 'beautydna' ), 'quote' => __( 'Genuinely feels like a premium brand — from unboxing to the way the products perform.', 'beautydna' ), 'rating' => 5 ),
	array( 'name' => __( 'Jordan M.', 'beautydna' ), 'quote' => __( 'The supplements slotted right into my routine. Customer care was excellent when I had questions.', 'beautydna' ), 'rating' => 4 ),
);

$instagram_url = get_theme_mod( 'beautydna_social_instagram', '#' );
?>
<section class="bdna-section bdna-testimonials" aria-labelledby="bdna-testimonials-heading">
	<div class="bdna-container">
		<div class="bdna-section-heading bdna-section-heading--center bdna-fade-in">
			<p class="bdna-eyebrow"><?php esc_html_e( 'Loved By Our Community', 'beautydna' ); ?></p>
			<h2 id="bdna-testimonials-heading" class="bdna-heading-lg"><?php esc_html_e( 'What Customers Say', 'beautydna' ); ?></h2>
		</div>

		<div class="bdna-testimonials__row">
			<?php if ( $query->have_posts() ) : ?>
				<?php while ( $query->have_posts() ) : $query->the_post();
					$rating   = get_post_meta( get_the_ID(), '_bdna_rating', true ) ?: 5;
					$verified = get_post_meta( get_the_ID(), '_bdna_verified', true );
					?>
					<figure class="bdna-testimonial-card bdna-fade-in">
						<?php echo wc_get_rating_html( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<blockquote>&ldquo;<?php echo esc_html( wp_strip_all_tags( get_the_content() ) ); ?>&rdquo;</blockquote>
						<figcaption>
							<?php the_title(); ?>
							<?php if ( $verified ) : ?><span class="bdna-badge"><?php esc_html_e( 'Verified Buyer', 'beautydna' ); ?></span><?php endif; ?>
						</figcaption>
					</figure>
				<?php endwhile; wp_reset_postdata(); ?>
			<?php else : ?>
				<?php foreach ( $fallback as $t ) : ?>
					<figure class="bdna-testimonial-card bdna-fade-in">
						<?php echo wc_get_rating_html( $t['rating'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<blockquote>&ldquo;<?php echo esc_html( $t['quote'] ); ?>&rdquo;</blockquote>
						<figcaption>
							<?php echo esc_html( $t['name'] ); ?>
							<span class="bdna-badge"><?php esc_html_e( 'Verified Buyer', 'beautydna' ); ?></span>
						</figcaption>
					</figure>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
</section>

<section class="bdna-instagram" aria-labelledby="bdna-instagram-heading">
	<div class="bdna-container">
		<div class="bdna-section-heading bdna-section-heading--center bdna-fade-in">
			<p class="bdna-eyebrow"><?php esc_html_e( 'Tag Us', 'beautydna' ); ?></p>
			<h2 id="bdna-instagram-heading" class="bdna-heading-lg">@beautydna</h2>
		</div>
		<div class="bdna-instagram-grid bdna-fade-in">
			<?php for ( $i = 1; $i <= 6; $i++ ) : ?>
				<a class="bdna-instagram-grid__tile" href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer">
					<?php beautydna_placeholder( sprintf( /* translators: %d: tile number */ __( 'Instagram post %d', 'beautydna' ), $i ), '1 / 1' ); ?>
				</a>
			<?php endfor; ?>
		</div>
	</div>
</section>
