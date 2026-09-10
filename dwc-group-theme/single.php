<?php
/**
 * Single post / single Service template.
 *
 * @package DWC_Group
 */

get_header();
?>

<main id="main" class="site-main">

	<?php while ( have_posts() ) : the_post(); ?>

		<?php if ( 'dwc_service' === get_post_type() ) : ?>

			<?php
			get_template_part(
				'template-parts/components/page-hero',
				null,
				array(
					'dwc_hero' => array(
						'label'    => __( 'OUR SERVICES', 'dwc-group' ),
						'title'    => get_the_title(),
						'subtitle' => get_the_excerpt(),
					),
				)
			);
			dwc_group_breadcrumbs();
			get_template_part( 'template-parts/services/service-full' );
			?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/components/page-hero' ); ?>
			<?php dwc_group_breadcrumbs(); ?>

			<div class="container">
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="single-post__thumbnail"><?php the_post_thumbnail( 'dwc-wide', array( 'loading' => 'eager' ) ); ?></div>
					<?php endif; ?>
					<?php the_content(); ?>
					<?php
					wp_link_pages(
						array(
							'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'dwc-group' ),
							'after'  => '</nav>',
						)
					);
					?>
				</article>
				<?php
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
				?>
			</div>

		<?php endif; ?>

	<?php endwhile; ?>

</main>

<?php
get_footer();
