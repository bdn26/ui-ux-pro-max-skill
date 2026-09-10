<?php
/**
 * Template Name: About Page
 * Template Post Type: page
 *
 * Editable via Appearance > Customize > DWC Group Settings > About Page.
 *
 * @package DWC_Group
 */

get_header();

$values = array();
for ( $i = 1; $i <= 5; $i++ ) {
	$defaults = array( __( 'Accuracy', 'dwc-group' ), __( 'Transparency', 'dwc-group' ), __( 'Compliance', 'dwc-group' ), __( 'Accountability', 'dwc-group' ), __( 'Practical Solutions', 'dwc-group' ) );
	$value    = get_theme_mod( 'dwc_about_value_' . $i, $defaults[ $i - 1 ] );
	if ( $value ) {
		$values[] = $value;
	}
}
?>

<main id="main" class="site-main">

	<?php
	get_template_part(
		'template-parts/components/page-hero',
		null,
		array(
			'dwc_hero' => array(
				'label'    => __( 'ABOUT US', 'dwc-group' ),
				'title'    => get_theme_mod( 'dwc_about_hero_heading', __( 'Experience Built for Complex Organizations.', 'dwc-group' ) ),
				'subtitle' => get_theme_mod( 'dwc_about_hero_text', __( 'Digital Wrk Consulting Group is a multidisciplinary consulting firm providing professional support in government consulting, human resources and talent acquisition, and accounting and payroll services. We work with public sector and government-facing organizations to strengthen compliance, improve administrative and workforce processes, and support accurate financial operations through practical, reliable solutions.', 'dwc-group' ) ),
			),
		)
	);
	dwc_group_breadcrumbs();
	?>

	<div class="container about-page">

		<?php
		while ( have_posts() ) :
			the_post();
			if ( trim( get_the_content() ) ) :
				?>
				<div class="entry-content about-page__extra"><?php the_content(); ?></div>
				<?php
			endif;
		endwhile;
		?>

		<section class="about-block" data-dwc-reveal>
			<h2 class="about-block__title"><?php echo esc_html( get_theme_mod( 'dwc_about_who_heading', __( 'Who We Are', 'dwc-group' ) ) ); ?></h2>
			<div class="about-block__text"><?php echo wp_kses_post( wpautop( get_theme_mod( 'dwc_about_who_text', __( 'Digital Wrk Consulting Group is a multidisciplinary consulting firm providing professional support across government operations, human resources, talent acquisition, and accounting services. We work with public sector organizations and businesses that support government programs, helping them strengthen administrative processes, maintain compliance, and operate efficiently.', 'dwc-group' ) ) ) ); ?></div>
		</section>

		<section class="about-block" data-dwc-reveal>
			<h2 class="about-block__title"><?php echo esc_html( get_theme_mod( 'dwc_about_how_heading', __( 'How We Work', 'dwc-group' ) ) ); ?></h2>
			<div class="about-block__text"><?php echo wp_kses_post( wpautop( get_theme_mod( 'dwc_about_how_text', __( 'At DWC, we bring extensive experience in government environments, HR and payroll administration, financial documentation, and advisory services. This background allows us to understand the regulatory, procedural, and accountability requirements unique to public-sector and highly regulated organizations.

We approach each engagement with a focus on accuracy, transparency, and practical solutions that align with established policies and applicable regulations.', 'dwc-group' ) ) ) ); ?></div>
		</section>

		<section class="about-block" data-dwc-reveal>
			<h2 class="about-block__title"><?php echo esc_html( get_theme_mod( 'dwc_about_why_heading', __( 'Why DWC', 'dwc-group' ) ) ); ?></h2>
			<div class="about-block__text"><?php echo wp_kses_post( wpautop( get_theme_mod( 'dwc_about_why_text', __( 'Digital Wrk Consulting Group is committed to delivering reliable, well-documented, and client-focused services. We partner closely with organizations to support sound governance, effective workforce management, and consistent financial and administrative practices.', 'dwc-group' ) ) ) ); ?></div>
		</section>

		<?php if ( $values ) : ?>
			<section class="values" data-dwc-reveal>
				<h2 class="screen-reader-text"><?php esc_html_e( 'Our Values', 'dwc-group' ); ?></h2>
				<ul class="values__list">
					<?php foreach ( $values as $value ) : ?>
						<li class="values__item"><?php echo esc_html( $value ); ?></li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endif; ?>

		<section class="about-cta" data-dwc-reveal>
			<p><?php esc_html_e( "Ready to discuss your organization's needs?", 'dwc-group' ); ?></p>
			<a class="btn btn--primary" href="<?php echo esc_url( dwc_group_contact_url() ); ?>"><?php echo esc_html( get_theme_mod( 'dwc_header_cta_label', __( 'Discuss Your Needs', 'dwc-group' ) ) ); ?></a>
		</section>

	</div>
</main>

<?php
get_footer();
