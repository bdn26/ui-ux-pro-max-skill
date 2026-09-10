<?php
/**
 * Full service section: title, body copy, capability list, CTA. Used on the
 * "Our Services" page (looped) and on a single Service's own page.
 *
 * Pass via get_template_part( ..., null, array( 'dwc_index' => 1 ) ) to
 * alternate the media-left/media-right layout; omit when only one is shown.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$service_id   = get_the_ID();
$capabilities = dwc_group_service_capabilities( $service_id );
$is_featured  = dwc_group_service_is_featured( $service_id );
$reverse      = isset( $dwc_index ) && 0 !== $dwc_index % 2;
?>
<article id="service-<?php echo esc_attr( $service_id ); ?>" class="service-full <?php echo $is_featured ? 'service-full--featured' : ''; ?> <?php echo $reverse ? 'service-full--reverse' : ''; ?>">
	<div class="container service-full__grid">
		<div class="service-full__content">
			<?php if ( $is_featured ) : ?>
				<p class="eyebrow"><?php esc_html_e( 'DWC TECHNOLOGY', 'dwc-group' ); ?></p>
			<?php endif; ?>
			<h2 class="service-full__title"><?php the_title(); ?></h2>
			<div class="service-full__copy"><?php the_content(); ?></div>

			<?php
			get_template_part(
				'template-parts/components/cta-button',
				null,
				array(
					'dwc_cta' => array(
						'label' => dwc_group_service_cta_label( $service_id ),
						'url'   => dwc_group_service_cta_url( $service_id ),
						'style' => 'primary',
					),
				)
			);
			?>
		</div>

		<?php if ( $capabilities ) : ?>
			<div class="service-full__capabilities">
				<h3 class="service-full__capabilities-title"><?php esc_html_e( 'Capabilities', 'dwc-group' ); ?></h3>
				<ul class="dwc-list-check">
					<?php foreach ( $capabilities as $capability ) : ?>
						<li><?php echo esc_html( $capability ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
</article>
