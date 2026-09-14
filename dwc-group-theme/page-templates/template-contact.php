<?php
/**
 * Template Name: Contact
 * Template Post Type: page
 *
 * Contact details are set in Appearance > Customize > DWC Group Settings >
 * Contact Details -- nothing here needs to be hard-coded.
 *
 * @package DWC_Group
 */

get_header();

$email = get_theme_mod( 'dwc_contact_email', 'info@digitalwrk.co' );
$phone = get_theme_mod( 'dwc_contact_phone' );
$area  = get_theme_mod( 'dwc_office_area' );
?>

<main id="main" class="site-main">

	<?php
	get_template_part(
		'template-parts/components/page-hero',
		null,
		array(
			'dwc_hero' => array(
				'label'    => '',
				'title'    => __( "Let's Discuss Your Organization's Needs.", 'dwc-group' ),
				'subtitle' => __( 'Connect with Digital Wrk Consulting Group to discuss consulting, workforce, accounting and payroll, government operations, or program integrity needs.', 'dwc-group' ),
			),
		)
	);
	dwc_group_breadcrumbs();
	?>

	<div class="container contact-page">
		<div class="contact-page__grid">

			<div class="contact-page__form">
				<?php get_template_part( 'template-parts/components/contact-form' ); ?>
			</div>

			<aside class="contact-page__details">
				<h2><?php esc_html_e( 'Contact Details', 'dwc-group' ); ?></h2>
				<ul class="contact-details">
					<?php if ( $email ) : ?>
						<li>
							<span class="contact-details__label"><?php esc_html_e( 'Email', 'dwc-group' ); ?></span>
							<a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( $phone ) : ?>
						<li>
							<span class="contact-details__label"><?php esc_html_e( 'Phone', 'dwc-group' ); ?></span>
							<a href="tel:<?php echo esc_attr( preg_replace( '/[^+0-9]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( $area ) : ?>
						<li>
							<span class="contact-details__label"><?php esc_html_e( 'Office / Service Area', 'dwc-group' ); ?></span>
							<span><?php echo esc_html( $area ); ?></span>
						</li>
					<?php endif; ?>
				</ul>

				<?php if ( ! $email && ! $phone && ! $area ) : ?>
					<p class="contact-details__empty"><?php esc_html_e( 'Add your email, phone, and service area in Appearance > Customize > DWC Group Settings > Contact Details.', 'dwc-group' ); ?></p>
				<?php endif; ?>

				<?php $social = dwc_group_social_links(); ?>
				<?php if ( $social ) : ?>
					<ul class="footer-social contact-page__social" aria-label="<?php esc_attr_e( 'Social media', 'dwc-group' ); ?>">
						<?php foreach ( $social as $network => $url ) : ?>
							<li><a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( ucfirst( $network ) ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</aside>

		</div>
	</div>
</main>

<?php
get_footer();
