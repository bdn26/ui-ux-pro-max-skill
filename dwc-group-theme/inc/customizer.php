<?php
/**
 * Customizer: every editable "business" setting lives here so nobody has to
 * touch PHP to update copy, colors, CTAs, or contact details.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dwc_group_customize_register( $wp_customize ) {

	/* ---------------------------------------------------------------------
	 * Panel: DWC Group Settings
	 * ------------------------------------------------------------------ */
	$wp_customize->add_panel(
		'dwc_group_panel',
		array(
			'title'    => __( 'DWC Group Settings', 'dwc-group' ),
			'priority' => 30,
		)
	);

	/* ---- Section: Brand ---- */
	$wp_customize->add_section(
		'dwc_brand',
		array(
			'title' => __( 'Brand', 'dwc-group' ),
			'panel' => 'dwc_group_panel',
		)
	);

	$wp_customize->add_setting( 'dwc_footer_logo', array( 'sanitize_callback' => 'absint', 'transport' => 'refresh' ) );
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'dwc_footer_logo',
			array(
				'label'       => __( 'Footer Logo (light/reversed mark for dark background)', 'dwc-group' ),
				'description' => __( 'Falls back to the site identity logo, then to a text wordmark, if left empty.', 'dwc-group' ),
				'section'     => 'dwc_brand',
				'mime_type'   => 'image',
			)
		)
	);

	$wp_customize->add_setting(
		'dwc_color_primary',
		array(
			'default'           => '#17140f',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'dwc_color_primary',
			array(
				'label'   => __( 'Primary Color (near-black ink)', 'dwc-group' ),
				'section' => 'dwc_brand',
			)
		)
	);

	$wp_customize->add_setting(
		'dwc_color_accent',
		array(
			'default'           => '#bf5b2e',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'dwc_color_accent',
			array(
				'label'   => __( 'Accent Color (warm copper)', 'dwc-group' ),
				'section' => 'dwc_brand',
			)
		)
	);

	$wp_customize->add_setting(
		'dwc_color_charcoal',
		array(
			'default'           => '#362e27',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'dwc_color_charcoal',
			array(
				'label'   => __( 'Charcoal (secondary dark)', 'dwc-group' ),
				'section' => 'dwc_brand',
			)
		)
	);

	/* ---- Section: Contact ---- */
	$wp_customize->add_section(
		'dwc_contact',
		array(
			'title' => __( 'Contact Details', 'dwc-group' ),
			'panel' => 'dwc_group_panel',
		)
	);

	$contact_fields = array(
		'dwc_contact_email' => array( __( 'Contact Email', 'dwc-group' ), 'sanitize_email', 'info@digitalwrk.co' ),
		'dwc_contact_phone' => array( __( 'Contact Phone', 'dwc-group' ), 'sanitize_text_field', '' ),
		'dwc_office_area'   => array( __( 'Office / Service Area', 'dwc-group' ), 'sanitize_text_field', '' ),
	);
	foreach ( $contact_fields as $id => $field ) {
		$wp_customize->add_setting( $id, array( 'default' => $field[2], 'sanitize_callback' => $field[1] ) );
		$wp_customize->add_control( $id, array( 'label' => $field[0], 'section' => 'dwc_contact', 'type' => 'text' ) );
	}

	$social_fields = array(
		'dwc_social_linkedin' => __( 'LinkedIn URL', 'dwc-group' ),
		'dwc_social_twitter'  => __( 'X / Twitter URL', 'dwc-group' ),
		'dwc_social_facebook' => __( 'Facebook URL', 'dwc-group' ),
	);
	foreach ( $social_fields as $id => $label ) {
		$wp_customize->add_setting( $id, array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( $id, array( 'label' => $label, 'section' => 'dwc_contact', 'type' => 'url' ) );
	}

	/* ---- Section: Calls to Action ---- */
	$wp_customize->add_section(
		'dwc_ctas',
		array(
			'title' => __( 'Header & Global CTA', 'dwc-group' ),
			'panel' => 'dwc_group_panel',
		)
	);

	$wp_customize->add_setting( 'dwc_header_cta_label', array( 'default' => __( 'Discuss Your Needs', 'dwc-group' ), 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'dwc_header_cta_label', array( 'label' => __( 'Header CTA Label', 'dwc-group' ), 'section' => 'dwc_ctas', 'type' => 'text' ) );

	$wp_customize->add_setting( 'dwc_header_cta_url', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'dwc_header_cta_url', array( 'label' => __( 'Header CTA URL (defaults to Contact page)', 'dwc-group' ), 'section' => 'dwc_ctas', 'type' => 'url' ) );

	/* ---------------------------------------------------------------------
	 * Section: Homepage — Hero
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section(
		'dwc_home_hero',
		array(
			'title' => __( 'Homepage — Hero', 'dwc-group' ),
			'panel' => 'dwc_group_panel',
		)
	);

	dwc_group_text_setting( $wp_customize, 'dwc_hero_eyebrow', __( 'Digital Wrk Consulting Group', 'dwc-group' ), 'dwc_home_hero', __( 'Eyebrow Label', 'dwc-group' ) );
	dwc_group_textarea_setting( $wp_customize, 'dwc_hero_heading', __( 'Strategic Consulting for Government, People, Finance & Program Integrity', 'dwc-group' ), 'dwc_home_hero', __( 'Headline', 'dwc-group' ) );
	dwc_group_textarea_setting( $wp_customize, 'dwc_hero_subheading', __( 'Digital Wrk Consulting Group provides practical, reliable consulting services across government operations, human resources, talent acquisition, accounting, payroll, and AI-powered fraud auditing.', 'dwc-group' ), 'dwc_home_hero', __( 'Supporting Copy', 'dwc-group' ) );

	$wp_customize->add_setting( 'dwc_hero_image', array( 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control(
		new WP_Customize_Media_Control(
			$wp_customize,
			'dwc_hero_image',
			array(
				'label'     => __( 'Hero Image (abstract / architectural, optional)', 'dwc-group' ),
				'section'   => 'dwc_home_hero',
				'mime_type' => 'image',
			)
		)
	);

	dwc_group_text_setting( $wp_customize, 'dwc_hero_cta_primary_label', __( 'Explore Our Services', 'dwc-group' ), 'dwc_home_hero', __( 'Primary CTA Label', 'dwc-group' ) );
	dwc_group_text_setting( $wp_customize, 'dwc_hero_cta_secondary_label', __( 'View Publications', 'dwc-group' ), 'dwc_home_hero', __( 'Secondary CTA Label', 'dwc-group' ) );

	/* ---------------------------------------------------------------------
	 * Section: Homepage — Intro
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section( 'dwc_home_intro', array( 'title' => __( 'Homepage — Intro', 'dwc-group' ), 'panel' => 'dwc_group_panel' ) );

	dwc_group_text_setting( $wp_customize, 'dwc_intro_label', __( 'ABOUT DWC GROUP', 'dwc-group' ), 'dwc_home_intro', __( 'Section Label', 'dwc-group' ) );
	dwc_group_textarea_setting( $wp_customize, 'dwc_intro_heading', __( 'Experience Across Government, Workforce & Financial Operations', 'dwc-group' ), 'dwc_home_intro', __( 'Headline', 'dwc-group' ) );
	dwc_group_wysiwyg_setting( $wp_customize, 'dwc_intro_text', __( 'Digital Wrk Consulting Group is a multidisciplinary consulting firm providing professional support across government operations, human resources, talent acquisition, and accounting services. We work with public sector organizations and businesses that support government programs, helping them strengthen administrative processes, maintain compliance, and operate efficiently.', 'dwc-group' ), 'dwc_home_intro', __( 'Body Copy', 'dwc-group' ) );
	dwc_group_text_setting( $wp_customize, 'dwc_intro_cta_label', __( 'Learn More About DWC', 'dwc-group' ), 'dwc_home_intro', __( 'CTA Label', 'dwc-group' ) );

	$wp_customize->add_setting( 'dwc_intro_image', array( 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'dwc_intro_image', array( 'label' => __( 'Intro Image', 'dwc-group' ), 'section' => 'dwc_home_intro', 'mime_type' => 'image' ) ) );

	/* ---------------------------------------------------------------------
	 * Section: Homepage — Services
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section( 'dwc_home_services', array( 'title' => __( 'Homepage — Services', 'dwc-group' ), 'panel' => 'dwc_group_panel' ) );
	dwc_group_text_setting( $wp_customize, 'dwc_services_label', __( 'OUR SERVICES', 'dwc-group' ), 'dwc_home_services', __( 'Section Label', 'dwc-group' ) );
	dwc_group_textarea_setting( $wp_customize, 'dwc_services_heading', __( 'Practical Expertise. Structured Solutions.', 'dwc-group' ), 'dwc_home_services', __( 'Headline', 'dwc-group' ) );
	dwc_group_textarea_setting( $wp_customize, 'dwc_services_text', __( 'Strategic consulting services in government, human capital management, talent acquisition, finance, accounting, and program integrity.', 'dwc-group' ), 'dwc_home_services', __( 'Supporting Copy', 'dwc-group' ) );

	/* ---------------------------------------------------------------------
	 * Section: Homepage — Why DWC (4 numbered principles)
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section( 'dwc_home_why', array( 'title' => __( 'Homepage — Why DWC', 'dwc-group' ), 'panel' => 'dwc_group_panel' ) );
	dwc_group_textarea_setting( $wp_customize, 'dwc_why_heading', __( 'Built Around Accuracy, Transparency & Accountability', 'dwc-group' ), 'dwc_home_why', __( 'Headline', 'dwc-group' ) );

	$why_defaults = array(
		1 => array( __( 'Accuracy', 'dwc-group' ), __( 'Reliable processes, documentation, and financial and administrative support.', 'dwc-group' ) ),
		2 => array( __( 'Transparency', 'dwc-group' ), __( 'Clear processes and well-documented work designed to support organizational accountability.', 'dwc-group' ) ),
		3 => array( __( 'Compliance', 'dwc-group' ), __( 'Solutions aligned with applicable policies, regulations, and reporting requirements.', 'dwc-group' ) ),
		4 => array( __( 'Practical Solutions', 'dwc-group' ), __( 'Consulting designed around real operational needs rather than theory alone.', 'dwc-group' ) ),
	);
	foreach ( $why_defaults as $i => $pair ) {
		dwc_group_text_setting( $wp_customize, "dwc_why_{$i}_title", $pair[0], 'dwc_home_why', sprintf( __( 'Principle %d — Title', 'dwc-group' ), $i ) );
		dwc_group_textarea_setting( $wp_customize, "dwc_why_{$i}_text", $pair[1], 'dwc_home_why', sprintf( __( 'Principle %d — Description', 'dwc-group' ), $i ) );
	}

	/* ---------------------------------------------------------------------
	 * Section: Homepage — FraudAudit AI feature
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section( 'dwc_home_fraud', array( 'title' => __( 'Homepage — FraudAudit AI', 'dwc-group' ), 'panel' => 'dwc_group_panel' ) );
	dwc_group_text_setting( $wp_customize, 'dwc_fraud_label', __( 'FRAUDAUDIT AI', 'dwc-group' ), 'dwc_home_fraud', __( 'Section Label', 'dwc-group' ) );
	dwc_group_textarea_setting( $wp_customize, 'dwc_fraud_heading', __( 'Modern Program Integrity Starts With Better Intelligence.', 'dwc-group' ), 'dwc_home_fraud', __( 'Headline', 'dwc-group' ) );
	dwc_group_wysiwyg_setting(
		$wp_customize,
		'dwc_fraud_text',
		__( 'FraudAudit AI is an intelligent program integrity platform built to help agencies detect, investigate, and document fraudulent and improper claims with speed and precision. By combining advanced anomaly detection, configurable rules, and provider behavior analysis, the system surfaces high-risk activity and explains exactly why it matters, giving investigators clear, actionable insight.

Designed for real-world workflows, FraudAudit AI enables teams to flag claims for payment review, escalate cases for audit, schedule in-person investigations, and maintain fully documented case histories within a single, intuitive interface.

From initial detection to evidence reporting, every action is tracked, organized, and audit-ready.', 'dwc-group' ),
		'dwc_home_fraud',
		__( 'Body Copy', 'dwc-group' )
	);
	dwc_group_text_setting( $wp_customize, 'dwc_fraud_cta_label', __( 'Explore FraudAudit AI', 'dwc-group' ), 'dwc_home_fraud', __( 'CTA Label', 'dwc-group' ) );
	$wp_customize->add_setting( 'dwc_fraud_cta_url', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
	$wp_customize->add_control( 'dwc_fraud_cta_url', array( 'label' => __( 'CTA URL', 'dwc-group' ), 'section' => 'dwc_home_fraud', 'type' => 'url' ) );

	/* ---------------------------------------------------------------------
	 * Section: Homepage — Publications intro
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section( 'dwc_home_pubs', array( 'title' => __( 'Homepage — Publications', 'dwc-group' ), 'panel' => 'dwc_group_panel' ) );
	dwc_group_text_setting( $wp_customize, 'dwc_pubs_label', __( 'PUBLICATIONS', 'dwc-group' ), 'dwc_home_pubs', __( 'Section Label', 'dwc-group' ) );
	dwc_group_textarea_setting( $wp_customize, 'dwc_pubs_heading', __( 'Research, Reports & Digital Publications', 'dwc-group' ), 'dwc_home_pubs', __( 'Headline', 'dwc-group' ) );
	dwc_group_textarea_setting( $wp_customize, 'dwc_pubs_text', __( "Explore DWC Group's collection of investigative reports, research, analysis, and digital publications.", 'dwc-group' ), 'dwc_home_pubs', __( 'Supporting Copy', 'dwc-group' ) );
	dwc_group_text_setting( $wp_customize, 'dwc_pubs_cta_label', __( 'View All Publications', 'dwc-group' ), 'dwc_home_pubs', __( 'CTA Label', 'dwc-group' ) );

	/* ---------------------------------------------------------------------
	 * Section: Homepage — Final CTA
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section( 'dwc_home_cta', array( 'title' => __( 'Homepage — Final CTA', 'dwc-group' ), 'panel' => 'dwc_group_panel' ) );
	dwc_group_textarea_setting( $wp_customize, 'dwc_final_heading', __( "Let's Strengthen Your Organization.", 'dwc-group' ), 'dwc_home_cta', __( 'Headline', 'dwc-group' ) );
	dwc_group_textarea_setting( $wp_customize, 'dwc_final_text', __( "Whether you need government consulting, workforce support, accounting and payroll assistance, or program integrity technology, DWC Group provides practical solutions designed around your organization's needs.", 'dwc-group' ), 'dwc_home_cta', __( 'Body Copy', 'dwc-group' ) );
	dwc_group_text_setting( $wp_customize, 'dwc_final_cta_primary', __( 'Discuss Your Needs', 'dwc-group' ), 'dwc_home_cta', __( 'Primary Button Label', 'dwc-group' ) );
	dwc_group_text_setting( $wp_customize, 'dwc_final_cta_secondary', __( 'Explore Our Services', 'dwc-group' ), 'dwc_home_cta', __( 'Secondary Button Label', 'dwc-group' ) );

	/* ---------------------------------------------------------------------
	 * Section: About Page
	 * ------------------------------------------------------------------ */
	$wp_customize->add_section( 'dwc_about_page', array( 'title' => __( 'About Page', 'dwc-group' ), 'panel' => 'dwc_group_panel' ) );

	dwc_group_textarea_setting( $wp_customize, 'dwc_about_hero_heading', __( 'Experience Built for Complex Organizations.', 'dwc-group' ), 'dwc_about_page', __( 'Hero Headline', 'dwc-group' ) );
	dwc_group_wysiwyg_setting( $wp_customize, 'dwc_about_hero_text', __( 'Digital Wrk Consulting Group is a multidisciplinary consulting firm providing professional support in government consulting, human resources and talent acquisition, and accounting and payroll services. We work with public sector and government-facing organizations to strengthen compliance, improve administrative and workforce processes, and support accurate financial operations through practical, reliable solutions.', 'dwc-group' ), 'dwc_about_page', __( 'Hero Body Copy', 'dwc-group' ) );

	dwc_group_text_setting( $wp_customize, 'dwc_about_who_heading', __( 'Who We Are', 'dwc-group' ), 'dwc_about_page', __( 'Section 1 Heading', 'dwc-group' ) );
	dwc_group_wysiwyg_setting( $wp_customize, 'dwc_about_who_text', __( 'Digital Wrk Consulting Group is a multidisciplinary consulting firm providing professional support across government operations, human resources, talent acquisition, and accounting services. We work with public sector organizations and businesses that support government programs, helping them strengthen administrative processes, maintain compliance, and operate efficiently.', 'dwc-group' ), 'dwc_about_page', __( 'Section 1 Body', 'dwc-group' ) );

	dwc_group_text_setting( $wp_customize, 'dwc_about_how_heading', __( 'How We Work', 'dwc-group' ), 'dwc_about_page', __( 'Section 2 Heading', 'dwc-group' ) );
	dwc_group_wysiwyg_setting(
		$wp_customize,
		'dwc_about_how_text',
		__( 'At DWC, we bring extensive experience in government environments, HR and payroll administration, financial documentation, and advisory services. This background allows us to understand the regulatory, procedural, and accountability requirements unique to public-sector and highly regulated organizations.

We approach each engagement with a focus on accuracy, transparency, and practical solutions that align with established policies and applicable regulations.', 'dwc-group' ),
		'dwc_about_page',
		__( 'Section 2 Body', 'dwc-group' )
	);

	dwc_group_text_setting( $wp_customize, 'dwc_about_why_heading', __( 'Why DWC', 'dwc-group' ), 'dwc_about_page', __( 'Section 3 Heading', 'dwc-group' ) );
	dwc_group_wysiwyg_setting( $wp_customize, 'dwc_about_why_text', __( 'Digital Wrk Consulting Group is committed to delivering reliable, well-documented, and client-focused services. We partner closely with organizations to support sound governance, effective workforce management, and consistent financial and administrative practices.', 'dwc-group' ), 'dwc_about_page', __( 'Section 3 Body', 'dwc-group' ) );

	$about_values = array( __( 'Accuracy', 'dwc-group' ), __( 'Transparency', 'dwc-group' ), __( 'Compliance', 'dwc-group' ), __( 'Accountability', 'dwc-group' ), __( 'Practical Solutions', 'dwc-group' ) );
	foreach ( $about_values as $i => $value ) {
		dwc_group_text_setting( $wp_customize, 'dwc_about_value_' . ( $i + 1 ), $value, 'dwc_about_page', sprintf( __( 'Value %d', 'dwc-group' ), $i + 1 ) );
	}

	/* ---- Section: Footer ---- */
	$wp_customize->add_section( 'dwc_footer', array( 'title' => __( 'Footer', 'dwc-group' ), 'panel' => 'dwc_group_panel' ) );
	dwc_group_textarea_setting( $wp_customize, 'dwc_footer_description', __( 'Digital Wrk Consulting Group is a multidisciplinary consulting firm supporting government, workforce, and financial operations with practical, reliable expertise.', 'dwc-group' ), 'dwc_footer', __( 'Company Description', 'dwc-group' ) );
}
add_action( 'customize_register', 'dwc_group_customize_register' );

/**
 * Small helpers to keep the registration block above readable.
 */
function dwc_group_text_setting( $wp_customize, $id, $default, $section, $label, $priority = null ) {
	$wp_customize->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'sanitize_text_field' ) );
	$args = array( 'label' => $label, 'section' => $section, 'type' => 'text' );
	if ( null !== $priority ) {
		$args['priority'] = $priority;
	}
	$wp_customize->add_control( $id, $args );
}

function dwc_group_textarea_setting( $wp_customize, $id, $default, $section, $label ) {
	$wp_customize->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'sanitize_textarea_field' ) );
	$wp_customize->add_control( $id, array( 'label' => $label, 'section' => $section, 'type' => 'textarea' ) );
}

function dwc_group_wysiwyg_setting( $wp_customize, $id, $default, $section, $label ) {
	$wp_customize->add_setting( $id, array( 'default' => $default, 'sanitize_callback' => 'wp_kses_post' ) );
	$wp_customize->add_control( $id, array( 'label' => $label, 'section' => $section, 'type' => 'textarea' ) );
}

/**
 * Live-preview partials for the most commonly edited text (selective refresh).
 */
function dwc_group_customize_partial_refresh( $wp_customize ) {
	if ( ! isset( $wp_customize->selective_refresh ) ) {
		return;
	}
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
}
add_action( 'customize_register', 'dwc_group_customize_partial_refresh' );
