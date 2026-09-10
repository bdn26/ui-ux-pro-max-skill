<?php
/**
 * Fallback template.
 *
 * @package DWC_Group
 */

get_header();
?>

<main id="main" class="site-main">
	<div class="container">

		<?php dwc_group_breadcrumbs(); ?>

		<?php if ( have_posts() ) : ?>
			<div class="post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/components/post-card' );
				endwhile;
				?>
			</div>
			<?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'Nothing found.', 'dwc-group' ); ?></p>
		<?php endif; ?>

	</div>
</main>

<?php
get_footer();
