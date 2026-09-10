<?php
/**
 * Homepage services grid -- pulls the four (or however many are published)
 * `dwc_service` posts rather than hard-coding cards.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services_query = new WP_Query(
	array(
		'post_type'      => 'dwc_service',
		'posts_per_page' => 4,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);

if ( ! $services_query->have_posts() ) {
	return;
}
?>
<section class="section services" data-dwc-reveal>
	<div class="container">
		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'dwc_heading' => array(
					'label'   => get_theme_mod( 'dwc_services_label', __( 'OUR SERVICES', 'dwc-group' ) ),
					'heading' => get_theme_mod( 'dwc_services_heading', __( 'Practical Expertise. Structured Solutions.', 'dwc-group' ) ),
					'text'    => get_theme_mod( 'dwc_services_text', __( 'Strategic consulting services in government, human capital management, talent acquisition, finance, accounting, and program integrity.', 'dwc-group' ) ),
					'align'   => 'center',
				),
			)
		);
		?>

		<div class="services__grid">
			<?php
			while ( $services_query->have_posts() ) :
				$services_query->the_post();
				get_template_part( 'template-parts/services/service-card' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
