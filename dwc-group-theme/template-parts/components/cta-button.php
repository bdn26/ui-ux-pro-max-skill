<?php
/**
 * Reusable CTA button/link.
 *
 * Pass via get_template_part( 'template-parts/components/cta-button', null,
 * array( 'dwc_cta' => array( 'label' => '', 'url' => '', 'style' => 'primary|secondary|text|ghost', 'icon' => true ) ) );
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dwc_cta = wp_parse_args(
	isset( $dwc_cta ) ? $dwc_cta : array(),
	array(
		'label' => '',
		'url'   => '#',
		'style' => 'primary',
		'icon'  => true,
	)
);

if ( ! $dwc_cta['label'] ) {
	return;
}
?>
<a class="btn btn--<?php echo esc_attr( $dwc_cta['style'] ); ?>" href="<?php echo esc_url( $dwc_cta['url'] ); ?>">
	<?php echo esc_html( $dwc_cta['label'] ); ?>
	<?php if ( $dwc_cta['icon'] ) : ?>
		<span aria-hidden="true" class="btn__icon">&rarr;</span>
	<?php endif; ?>
</a>
