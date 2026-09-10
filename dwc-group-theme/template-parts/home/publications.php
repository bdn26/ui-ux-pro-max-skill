<?php
/**
 * Homepage Publications preview -- dynamically pulls featured (falling back
 * to most recent) WooCommerce products. Renders nothing if WooCommerce
 * isn't active or no publications have been added yet, so the homepage
 * never shows a broken/empty section.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$featured_ids = wc_get_featured_product_ids();

$args = array(
	'post_type'      => 'product',
	'posts_per_page' => 4,
	'post_status'    => 'publish',
	'no_found_rows'  => true,
);

if ( ! empty( $featured_ids ) ) {
	$args['post__in'] = $featured_ids;
	$args['orderby']  = 'post__in';
} else {
	$args['orderby'] = 'date';
	$args['order']   = 'DESC';
}

$publications_query = new WP_Query( $args );

if ( ! $publications_query->have_posts() ) {
	return;
}

$publications_page = get_page_by_path( 'publications' );
?>
<section class="section publications-preview" data-dwc-reveal>
	<div class="container">
		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'dwc_heading' => array(
					'label'   => get_theme_mod( 'dwc_pubs_label', __( 'PUBLICATIONS', 'dwc-group' ) ),
					'heading' => get_theme_mod( 'dwc_pubs_heading', __( 'Research, Reports & Digital Publications', 'dwc-group' ) ),
					'text'    => get_theme_mod( 'dwc_pubs_text', __( "Explore DWC Group's collection of investigative reports, research, analysis, and digital publications.", 'dwc-group' ) ),
					'align'   => 'center',
				),
			)
		);
		?>

		<div class="publications-grid">
			<?php
			while ( $publications_query->have_posts() ) :
				$publications_query->the_post();
				global $product;
				$product = wc_get_product( get_the_ID() ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				get_template_part( 'template-parts/publications/publication-card' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>

		<div class="publications-preview__footer">
			<?php
			get_template_part(
				'template-parts/components/cta-button',
				null,
				array(
					'dwc_cta' => array(
						'label' => get_theme_mod( 'dwc_pubs_cta_label', __( 'View All Publications', 'dwc-group' ) ),
						'url'   => $publications_page ? get_permalink( $publications_page ) : home_url( '/publications/' ),
						'style' => 'secondary',
					),
				)
			);
			?>
		</div>
	</div>
</section>
