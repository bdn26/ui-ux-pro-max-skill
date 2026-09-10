<?php
/**
 * Numbered principle block used in the "Why DWC" section.
 *
 * Pass via get_template_part( ..., null, array( 'dwc_principle' => array( 'number' => '01', 'title' => '', 'text' => '' ) ) );
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dwc_principle = wp_parse_args(
	isset( $dwc_principle ) ? $dwc_principle : array(),
	array(
		'number' => '',
		'title'  => '',
		'text'   => '',
	)
);

if ( ! $dwc_principle['title'] ) {
	return;
}
?>
<div class="principle">
	<span class="principle__number" aria-hidden="true"><?php echo esc_html( $dwc_principle['number'] ); ?></span>
	<h3 class="principle__title"><?php echo esc_html( $dwc_principle['title'] ); ?></h3>
	<p class="principle__text"><?php echo esc_html( $dwc_principle['text'] ); ?></p>
</div>
