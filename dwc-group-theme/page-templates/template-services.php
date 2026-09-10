<?php
/**
 * Template Name: Our Services
 * Template Post Type: page
 *
 * Loops every published `dwc_service` post -- add, edit, or reorder services
 * from the WordPress admin (Services menu) and this page updates
 * automatically.
 *
 * @package DWC_Group
 */

get_header();

$services_query = new WP_Query(
	array(
		'post_type'      => 'dwc_service',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);
?>

<main id="main" class="site-main">

	<?php
	get_template_part(
		'template-parts/components/page-hero',
		null,
		array(
			'dwc_hero' => array(
				'label'    => '',
				'title'    => __( 'Our Services', 'dwc-group' ),
				'subtitle' => __( 'Strategic consulting services in government, human capital management, talent acquisition, finance, accounting, and program integrity.', 'dwc-group' ),
			),
		)
	);
	dwc_group_breadcrumbs();
	?>

	<?php if ( $services_query->have_posts() ) : ?>
		<div class="services-list">
			<?php
			$i = 0;
			while ( $services_query->have_posts() ) :
				$services_query->the_post();
				get_template_part( 'template-parts/services/service-full', null, array( 'dwc_index' => $i ) );
				$i++;
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	<?php else : ?>
		<div class="container">
			<p><?php esc_html_e( 'Add services from the WordPress admin (Services menu) to populate this page.', 'dwc-group' ); ?></p>
		</div>
	<?php endif; ?>

</main>

<?php
get_footer();
