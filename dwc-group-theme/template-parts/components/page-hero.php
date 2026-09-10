<?php
/**
 * Standard interior-page hero banner: eyebrow, title, subtitle.
 *
 * Pass via get_template_part( ..., null, array( 'dwc_hero' => array( 'label' => '', 'title' => '', 'subtitle' => '' ) ) ).
 * Falls back to the current post's title / hero-subtitle meta when omitted.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dwc_hero = wp_parse_args(
	isset( $dwc_hero ) ? $dwc_hero : array(),
	array(
		'label'    => '',
		'title'    => get_the_title(),
		'subtitle' => dwc_group_page_subtitle(),
	)
);
?>
<header class="page-hero">
	<div class="container">
		<?php if ( $dwc_hero['label'] ) : ?>
			<p class="eyebrow"><?php echo esc_html( $dwc_hero['label'] ); ?></p>
		<?php endif; ?>
		<h1 class="page-hero__title"><?php echo esc_html( $dwc_hero['title'] ); ?></h1>
		<?php if ( $dwc_hero['subtitle'] ) : ?>
			<p class="page-hero__subtitle"><?php echo esc_html( $dwc_hero['subtitle'] ); ?></p>
		<?php endif; ?>
	</div>
</header>
