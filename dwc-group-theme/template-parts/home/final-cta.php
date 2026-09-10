<?php
/**
 * Homepage closing CTA.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services_page = get_page_by_path( 'services' );
?>
<section class="section final-cta" data-dwc-reveal>
	<div class="container final-cta__inner">
		<h2 class="final-cta__title"><?php echo esc_html( get_theme_mod( 'dwc_final_heading', __( "Let's Strengthen Your Organization.", 'dwc-group' ) ) ); ?></h2>
		<p class="final-cta__text"><?php echo esc_html( get_theme_mod( 'dwc_final_text', __( "Whether you need government consulting, workforce support, accounting and payroll assistance, or program integrity technology, DWC Group provides practical solutions designed around your organization's needs.", 'dwc-group' ) ) ); ?></p>
		<div class="final-cta__actions">
			<a class="btn btn--primary" href="<?php echo esc_url( dwc_group_contact_url() ); ?>">
				<?php echo esc_html( get_theme_mod( 'dwc_final_cta_primary', __( 'Discuss Your Needs', 'dwc-group' ) ) ); ?>
			</a>
			<a class="btn btn--ghost" href="<?php echo esc_url( $services_page ? get_permalink( $services_page ) : home_url( '/services/' ) ); ?>">
				<?php echo esc_html( get_theme_mod( 'dwc_final_cta_secondary', __( 'Explore Our Services', 'dwc-group' ) ) ); ?>
			</a>
		</div>
	</div>
</section>
