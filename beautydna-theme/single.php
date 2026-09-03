<?php
/**
 * Single blog post template (journal / beauty-editorial content, if used).
 */

get_header();
?>
<div class="bdna-page bdna-section">
	<div class="bdna-container bdna-page__inner">
		<?php beautydna_breadcrumbs(); ?>
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'bdna-single-post' ); ?>>
				<header class="bdna-page__header">
					<p class="bdna-eyebrow"><?php echo esc_html( get_the_date() ); ?></p>
					<h1 class="bdna-heading-lg"><?php the_title(); ?></h1>
				</header>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="bdna-single-post__media"><?php the_post_thumbnail( 'beautydna-hero' ); ?></div>
				<?php endif; ?>
				<div class="bdna-page__content">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
		?>
	</div>
</div>
<?php
get_footer();
