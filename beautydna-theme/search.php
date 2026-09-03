<?php
/**
 * Search results — renders WooCommerce product cards when the query is
 * scoped to products (our header/search-overlay form sets
 * post_type=product), otherwise a simple content grid.
 */

get_header();

$is_product_search = isset( $_GET['post_type'] ) && 'product' === $_GET['post_type']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
?>
<div class="bdna-container bdna-section">
	<div class="bdna-section-heading">
		<p class="bdna-eyebrow"><?php esc_html_e( 'Search Results', 'beautydna' ); ?></p>
		<h1 class="bdna-heading-lg">
			<?php
			printf(
				/* translators: %s: search query */
				esc_html__( 'Results for "%s"', 'beautydna' ),
				esc_html( get_search_query() )
			);
			?>
		</h1>
	</div>

	<?php if ( have_posts() ) : ?>
		<?php if ( $is_product_search && class_exists( 'WooCommerce' ) ) : ?>
			<ul class="bdna-product-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					wc_get_template_part( 'content', 'product' );
				endwhile;
				?>
			</ul>
		<?php else : ?>
			<div class="bdna-generic-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'bdna-post-card' ); ?>>
						<a href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'beautydna-square' ); else : beautydna_placeholder( get_the_title(), '1 / 1' ); endif; ?>
							<h2><?php the_title(); ?></h2>
						</a>
						<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
					</article>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p class="bdna-lede"><?php esc_html_e( 'No results found. Try a different search term.', 'beautydna' ); ?></p>
		<?php get_search_form(); ?>
	<?php endif; ?>
</div>
<?php
get_footer();
