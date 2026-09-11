<?php
/**
 * Homepage hero: large confident headline, supporting copy, primary +
 * secondary CTAs, subtle data-inspired background pattern.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$hero_image_id = get_theme_mod( 'dwc_hero_image' );
$services_page = get_page_by_path( 'services' );
$publications_page = get_page_by_path( 'publications' );
?>
<section class="hero" data-dwc-reveal>
	<div class="hero__pattern" aria-hidden="true"></div>
	<div class="container hero__inner">
		<div class="hero__content">
			<p class="eyebrow"><?php echo esc_html( get_theme_mod( 'dwc_hero_eyebrow', __( 'Digital Wrk Consulting Group', 'dwc-group' ) ) ); ?></p>
			<h1 class="hero__title"><?php echo esc_html( get_theme_mod( 'dwc_hero_heading', __( 'Strategic Consulting for Government, People, Finance & Program Integrity', 'dwc-group' ) ) ); ?></h1>
			<p class="hero__subtitle"><?php echo esc_html( get_theme_mod( 'dwc_hero_subheading', __( 'Digital Wrk Consulting Group provides practical, reliable consulting services across government operations, human resources, talent acquisition, accounting, payroll, and AI-powered fraud auditing.', 'dwc-group' ) ) ); ?></p>
			<div class="hero__actions">
				<a class="btn btn--primary" href="<?php echo esc_url( $services_page ? get_permalink( $services_page ) : home_url( '/services/' ) ); ?>">
					<?php echo esc_html( get_theme_mod( 'dwc_hero_cta_primary_label', __( 'Explore Our Services', 'dwc-group' ) ) ); ?>
				</a>
				<a class="btn btn--secondary" href="<?php echo esc_url( $publications_page ? get_permalink( $publications_page ) : home_url( '/publications/' ) ); ?>">
					<?php echo esc_html( get_theme_mod( 'dwc_hero_cta_secondary_label', __( 'View Publications', 'dwc-group' ) ) ); ?>
				</a>
			</div>
		</div>

		<div class="hero__visual">
			<?php if ( $hero_image_id ) : ?>
				<?php echo wp_get_attachment_image( $hero_image_id, 'dwc-wide', false, array( 'loading' => 'eager', 'alt' => '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<img src="<?php echo esc_url( DWC_GROUP_URI . '/assets/images/hero.webp' ); ?>" alt="" loading="eager" />
			<?php endif; ?>
		</div>
	</div>
</section>
