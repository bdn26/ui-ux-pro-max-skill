<?php
/**
 * Fallback template for any request WordPress can't match to a more
 * specific template file.
 */

get_header();
?>
<div class="bdna-container bdna-section">
	<?php if ( have_posts() ) : ?>
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
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Nothing found.', 'beautydna' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
