<?php
/**
 * Reusable section heading: eyebrow label + headline + optional supporting
 * copy, centered or left-aligned. Pass an $args array via get_template_part
 * is not available pre-WP 5.5 pattern, so we read from a global set by the
 * caller instead ($dwc_heading).
 *
 * Expected $dwc_heading keys: label, heading, text, align (left|center).
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dwc_heading = wp_parse_args(
	isset( $dwc_heading ) ? $dwc_heading : array(),
	array(
		'label'   => '',
		'heading' => '',
		'text'    => '',
		'align'   => 'left',
		'tag'     => 'h2',
	)
);

if ( ! $dwc_heading['heading'] && ! $dwc_heading['label'] ) {
	return;
}

$tag = tag_escape( $dwc_heading['tag'] );
?>
<div class="section-heading section-heading--<?php echo esc_attr( $dwc_heading['align'] ); ?>">
	<?php if ( $dwc_heading['label'] ) : ?>
		<p class="eyebrow"><?php echo esc_html( $dwc_heading['label'] ); ?></p>
	<?php endif; ?>
	<?php if ( $dwc_heading['heading'] ) : ?>
		<<?php echo esc_html( $tag ); ?> class="section-heading__title"><?php echo esc_html( $dwc_heading['heading'] ); ?></<?php echo esc_html( $tag ); ?>>
	<?php endif; ?>
	<?php if ( $dwc_heading['text'] ) : ?>
		<p class="section-heading__text"><?php echo esc_html( $dwc_heading['text'] ); ?></p>
	<?php endif; ?>
</div>
