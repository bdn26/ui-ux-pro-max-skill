<?php
/**
 * Homepage "About DWC Group" introduction: text on one side, visual on the
 * other.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$about_page  = get_page_by_path( 'about-us' );
$image_id    = get_theme_mod( 'dwc_intro_image' );
$stats       = array(
	array( 'value' => 'Gov', 'label' => __( 'Public-Sector Focused', 'dwc-group' ) ),
	array( 'value' => 'HR', 'label' => __( 'Workforce & Talent', 'dwc-group' ) ),
	array( 'value' => '$', 'label' => __( 'Accounting & Payroll', 'dwc-group' ) ),
	array( 'value' => 'AI', 'label' => __( 'Program Integrity', 'dwc-group' ) ),
);
?>
<section class="section intro" data-dwc-reveal>
	<div class="container intro__grid">
		<div class="intro__visual">
			<?php if ( $image_id ) : ?>
				<?php echo wp_get_attachment_image( $image_id, 'dwc-wide', false, array( 'loading' => 'lazy', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<div class="intro__pattern" aria-hidden="true"></div>
			<?php endif; ?>
		</div>

		<div class="intro__content">
			<?php
			get_template_part(
				'template-parts/components/section-heading',
				null,
				array(
					'dwc_heading' => array(
						'label'   => get_theme_mod( 'dwc_intro_label', __( 'ABOUT DWC GROUP', 'dwc-group' ) ),
						'heading' => get_theme_mod( 'dwc_intro_heading', __( 'Experience Across Government, Workforce & Financial Operations', 'dwc-group' ) ),
					),
				)
			);
			?>
			<div class="intro__text">
				<?php echo wp_kses_post( wpautop( get_theme_mod( 'dwc_intro_text', __( 'Digital Wrk Consulting Group is a multidisciplinary consulting firm providing professional support across government operations, human resources, talent acquisition, and accounting services. We work with public sector organizations and businesses that support government programs, helping them strengthen administrative processes, maintain compliance, and operate efficiently.', 'dwc-group' ) ) ) ); ?>
			</div>
			<?php
			get_template_part(
				'template-parts/components/cta-button',
				null,
				array(
					'dwc_cta' => array(
						'label' => get_theme_mod( 'dwc_intro_cta_label', __( 'Learn More About DWC', 'dwc-group' ) ),
						'url'   => $about_page ? get_permalink( $about_page ) : home_url( '/about-us/' ),
						'style' => 'text',
					),
				)
			);
			?>

			<ul class="intro__stats">
				<?php foreach ( $stats as $stat ) : ?>
					<li>
						<span class="intro__stat-value" aria-hidden="true"><?php echo esc_html( $stat['value'] ); ?></span>
						<span class="intro__stat-label"><?php echo esc_html( $stat['label'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
