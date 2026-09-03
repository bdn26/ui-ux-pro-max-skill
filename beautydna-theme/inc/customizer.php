<?php
/**
 * Customizer settings for the editorial copy blocks a merchandiser needs
 * to change often (hero, announcement bar, brand story, social links)
 * without touching template code.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function beautydna_customize_register( $wp_customize ) {

	$wp_customize->add_panel( 'beautydna_homepage', array(
		'title'    => __( 'BeautyDNA Homepage', 'beautydna' ),
		'priority' => 30,
	) );

	/* ---- Announcement bar ------------------------------------------- */
	$wp_customize->add_section( 'beautydna_announcement', array(
		'title' => __( 'Announcement Bar', 'beautydna' ),
		'panel' => 'beautydna_homepage',
	) );
	$wp_customize->add_setting( 'beautydna_announcement_text', array(
		'default'           => 'FREE SHIPPING ON ORDERS OVER $75',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'beautydna_announcement_text', array(
		'label'   => __( 'Announcement text', 'beautydna' ),
		'section' => 'beautydna_announcement',
		'type'    => 'text',
	) );

	/* ---- Hero ---------------------------------------------------------- */
	$wp_customize->add_section( 'beautydna_hero', array(
		'title' => __( 'Hero', 'beautydna' ),
		'panel' => 'beautydna_homepage',
	) );

	$hero_fields = array(
		'beautydna_hero_heading'    => array( 'label' => __( 'Headline', 'beautydna' ), 'default' => "YOUR BEAUTY.\nYOUR DNA.", 'type' => 'textarea' ),
		'beautydna_hero_subheading' => array( 'label' => __( 'Supporting copy', 'beautydna' ), 'default' => 'Elevated beauty and wellness essentials designed to help you look, feel and glow your best.', 'type' => 'textarea' ),
		'beautydna_hero_cta_primary_label' => array( 'label' => __( 'Primary CTA label', 'beautydna' ), 'default' => 'Shop Best Sellers', 'type' => 'text' ),
		'beautydna_hero_cta_primary_url'   => array( 'label' => __( 'Primary CTA URL', 'beautydna' ), 'default' => '', 'type' => 'url' ),
		'beautydna_hero_cta_secondary_label' => array( 'label' => __( 'Secondary CTA label', 'beautydna' ), 'default' => 'Explore BeautyDNA', 'type' => 'text' ),
		'beautydna_hero_cta_secondary_url'   => array( 'label' => __( 'Secondary CTA URL', 'beautydna' ), 'default' => '', 'type' => 'url' ),
	);

	foreach ( $hero_fields as $id => $field ) {
		$wp_customize->add_setting( $id, array(
			'default'           => $field['default'],
			'sanitize_callback' => 'url' === $field['type'] ? 'esc_url_raw' : 'sanitize_textarea_field',
		) );
		$wp_customize->add_control( $id, array(
			'label'   => $field['label'],
			'section' => 'beautydna_hero',
			'type'    => $field['type'],
		) );
	}

	$wp_customize->add_setting( 'beautydna_hero_image', array( 'sanitize_callback' => 'absint' ) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'beautydna_hero_image', array(
		'label'    => __( 'Hero image', 'beautydna' ),
		'section'  => 'beautydna_hero',
		'mime_type' => 'image',
	) ) );

	/* ---- Brand story ----------------------------------------------- */
	$wp_customize->add_section( 'beautydna_story', array(
		'title' => __( 'Brand Story', 'beautydna' ),
		'panel' => 'beautydna_homepage',
	) );
	$story_fields = array(
		'beautydna_story_heading' => array( 'label' => __( 'Headline', 'beautydna' ), 'default' => 'BEAUTY, FROM THE INSIDE OUT.' ),
		'beautydna_story_body'    => array( 'label' => __( 'Body copy', 'beautydna' ), 'default' => "BeautyDNA is a modern beauty and wellness brand built around one idea: how you care for yourself, inside and out, shapes how you look and feel. We formulate skincare, haircare, body care and beauty supplements that work together — so your routine feels less like a checklist and more like a ritual." ),
		'beautydna_story_cta'     => array( 'label' => __( 'CTA label', 'beautydna' ), 'default' => 'Discover BeautyDNA' ),
	);
	foreach ( $story_fields as $id => $field ) {
		$wp_customize->add_setting( $id, array( 'default' => $field['default'], 'sanitize_callback' => 'sanitize_textarea_field' ) );
		$wp_customize->add_control( $id, array( 'label' => $field['label'], 'section' => 'beautydna_story', 'type' => 'textarea' ) );
	}

	/* ---- Social links -------------------------------------------------- */
	$wp_customize->add_section( 'beautydna_social', array(
		'title' => __( 'Social Links', 'beautydna' ),
		'panel' => 'beautydna_homepage',
	) );
	foreach ( array( 'instagram', 'tiktok', 'pinterest', 'facebook' ) as $network ) {
		$wp_customize->add_setting( 'beautydna_social_' . $network, array( 'sanitize_callback' => 'esc_url_raw' ) );
		$wp_customize->add_control( 'beautydna_social_' . $network, array(
			'label'   => ucfirst( $network ) . ' ' . __( 'URL', 'beautydna' ),
			'section' => 'beautydna_social',
			'type'    => 'url',
		) );
	}
}
add_action( 'customize_register', 'beautydna_customize_register' );
