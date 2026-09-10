<?php
/**
 * Service card used on the homepage services grid. Expects the loop to be
 * on a `dwc_service` post (the_post() already called).
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$service_id  = get_the_ID();
$is_featured = dwc_group_service_is_featured( $service_id );
?>
<article class="service-card <?php echo $is_featured ? 'service-card--featured' : ''; ?>">
	<?php if ( $is_featured ) : ?>
		<p class="service-card__tag"><?php esc_html_e( 'DWC Technology', 'dwc-group' ); ?></p>
	<?php endif; ?>
	<h3 class="service-card__title"><?php the_title(); ?></h3>
	<p class="service-card__text"><?php echo esc_html( dwc_group_trim( get_the_excerpt(), 26 ) ); ?></p>
	<?php
	get_template_part(
		'template-parts/components/cta-button',
		null,
		array(
			'dwc_cta' => array(
				'label' => dwc_group_service_cta_label( $service_id ),
				'url'   => get_permalink( $service_id ),
				'style' => $is_featured ? 'primary' : 'text',
			),
		)
	);
	?>
</article>
