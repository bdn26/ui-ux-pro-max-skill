<?php
/**
 * Default page template.
 *
 * @package DWC_Group
 */

get_header();
?>

<main id="main" class="site-main">

	<?php
	while ( have_posts() ) :
		the_post();
		?>

		<?php
		get_template_part( 'template-parts/components/page-hero' );
		dwc_group_breadcrumbs();
		?>

		<div class="container">
			<div class="entry-content">
				<?php
				the_content();
				wp_link_pages(
					array(
						'before' => '<nav class="page-links">' . esc_html__( 'Pages:', 'dwc-group' ),
						'after'  => '</nav>',
					)
				);
				?>
			</div>
			<?php
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>
		</div>
	<?php endwhile; ?>
</main>

<?php
get_footer();
