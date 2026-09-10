<?php
/**
 * Generic archive (categories, tags, author, date) and search results.
 *
 * @package DWC_Group
 */

get_header();
?>

<main id="main" class="site-main">
	<?php
	get_template_part(
		'template-parts/components/page-hero',
		null,
		array(
			'dwc_hero' => array(
				'label'    => is_search() ? __( 'SEARCH', 'dwc-group' ) : '',
				'title'    => is_search()
					? sprintf( __( 'Search Results for &ldquo;%s&rdquo;', 'dwc-group' ), get_search_query() )
					: wp_strip_all_tags( get_the_archive_title() ),
				'subtitle' => is_search() ? '' : wp_strip_all_tags( get_the_archive_description() ),
			),
		)
	);
	dwc_group_breadcrumbs();
	?>

	<div class="container">
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
			<p><?php esc_html_e( 'No results found.', 'dwc-group' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
