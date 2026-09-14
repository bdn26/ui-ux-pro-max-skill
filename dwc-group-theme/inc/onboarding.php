<?php
/**
 * One-time setup that runs the first time the theme is activated on a clean
 * WordPress install: creates the core pages with the right templates, a
 * primary menu, a static front page, four starter Service entries (with the
 * exact copy from the brand brief), and -- if WooCommerce is active -- the
 * Publications product categories. Everything created here is ordinary,
 * fully editable WordPress content; nothing here runs again once done.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * True when a page has no meaningful custom template assigned. WordPress
 * stores an unset template as an empty string, but the editor's "Default
 * template" option explicitly saves the literal string "default" -- both
 * mean "not one of ours" and should be treated the same way.
 */
function dwc_group_page_has_no_template( $post_id ) {
	$template = get_page_template_slug( $post_id );
	return in_array( $template, array( '', 'default' ), true );
}

function dwc_group_run_onboarding() {
	if ( get_option( 'dwc_group_onboarded' ) ) {
		return;
	}

	$pages = array(
		'home' => array(
			'title'   => __( 'Home', 'dwc-group' ),
			'content' => '',
			'template' => '',
		),
		'about-us' => array(
			'title'    => __( 'About Us', 'dwc-group' ),
			'content'  => '',
			'template' => 'page-templates/template-about.php',
		),
		'services' => array(
			'title'    => __( 'Our Services', 'dwc-group' ),
			'content'  => '',
			'template' => 'page-templates/template-services.php',
		),
		'publications' => array(
			'title'    => __( 'Publications', 'dwc-group' ),
			'content'  => '',
			'template' => '',
		),
		'contact' => array(
			'title'    => __( 'Contact', 'dwc-group' ),
			'content'  => '',
			'template' => 'page-templates/template-contact.php',
		),
	);

	$page_ids = array();

	foreach ( $pages as $slug => $data ) {
		$existing = get_page_by_path( $slug );
		if ( $existing ) {
			$page_ids[ $slug ] = $existing->ID;

			// A page can already exist at this slug (a default page shipped by
			// the host/installer, or a page created before this theme was
			// active) without ever having our template assigned -- that's
			// what makes it render blank. Assign the template whenever the
			// page isn't already using one, and backfill empty content, but
			// never touch a page the site owner has already customized.
			if ( $data['template'] && dwc_group_page_has_no_template( $existing->ID ) ) {
				update_post_meta( $existing->ID, '_wp_page_template', $data['template'] );
			}
			if ( $data['content'] && '' === trim( $existing->post_content ) ) {
				wp_update_post(
					array(
						'ID'           => $existing->ID,
						'post_content' => $data['content'],
					)
				);
			}
			continue;
		}

		$post_id = wp_insert_post(
			array(
				'post_title'   => $data['title'],
				'post_name'    => $slug,
				'post_content' => $data['content'],
				'post_status'  => 'publish',
				'post_type'    => 'page',
			)
		);

		if ( $post_id && ! is_wp_error( $post_id ) ) {
			$page_ids[ $slug ] = $post_id;
			if ( $data['template'] ) {
				update_post_meta( $post_id, '_wp_page_template', $data['template'] );
			}
		}
	}

	// If WooCommerce is active, point Publications at the Shop page instead
	// of a plain page so WooCommerce keeps managing it.
	if ( class_exists( 'WooCommerce' ) && isset( $page_ids['publications'] ) ) {
		update_option( 'woocommerce_shop_page_id', $page_ids['publications'] );
	}

	// Static front page.
	if ( isset( $page_ids['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $page_ids['home'] );
		if ( isset( $page_ids['publications'] ) ) {
			update_option( 'page_for_posts', 0 );
		}
	}

	dwc_group_create_primary_menu( $page_ids );
	dwc_group_seed_services();
	dwc_group_seed_product_categories();

	update_option( 'dwc_group_onboarded', 1 );

	// The pages/CPT posts above were inserted directly, bypassing the normal
	// editor flow that would otherwise trigger this -- without it, every URL
	// except the static front page 404s until something flushes the rewrite
	// rules (e.g. visiting Settings > Permalinks and clicking Save).
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'dwc_group_run_onboarding' );

/**
 * Same reasoning as dwc_group_repair_page_templates() below: a plain file
 * overwrite never fires after_switch_theme, so a site whose rewrite rules
 * were never flushed (every page 404s except the front page) would stay
 * broken even once this fix ships. Runs once on any admin page load.
 */
function dwc_group_flush_rewrite_rules_once() {
	if ( get_option( 'dwc_group_rewrites_flushed' ) ) {
		return;
	}
	flush_rewrite_rules();
	update_option( 'dwc_group_rewrites_flushed', 1 );
}
add_action( 'admin_init', 'dwc_group_flush_rewrite_rules_once' );

/**
 * Self-healing template repair, independent of after_switch_theme.
 *
 * after_switch_theme only fires when WordPress actually switches the active
 * theme -- re-uploading theme files over an already-active theme (the normal
 * "update via zip" or FTP-overwrite path) never fires it, so a site stuck
 * with un-templated pages (see dwc_group_run_onboarding()) would stay broken
 * even after the underlying bug is fixed. This runs once on any admin page
 * load instead, so deploying the fix is enough on its own.
 */
function dwc_group_repair_page_templates() {
	if ( get_option( 'dwc_group_templates_repaired_v2' ) ) {
		return;
	}

	$template_map = array(
		'about-us' => 'page-templates/template-about.php',
		'services' => 'page-templates/template-services.php',
		'contact'  => 'page-templates/template-contact.php',
	);

	foreach ( $template_map as $slug => $template ) {
		$page = get_page_by_path( $slug );
		if ( $page && dwc_group_page_has_no_template( $page->ID ) ) {
			update_post_meta( $page->ID, '_wp_page_template', $template );
		}
	}

	update_option( 'dwc_group_templates_repaired_v2', 1 );
}
add_action( 'admin_init', 'dwc_group_repair_page_templates' );

function dwc_group_create_primary_menu( $page_ids ) {
	$menu_name = __( 'Primary Navigation', 'dwc-group' );
	$menu_exists = wp_get_nav_menu_object( $menu_name );

	if ( $menu_exists ) {
		$menu_id = $menu_exists->term_id;
	} else {
		$menu_id = wp_create_nav_menu( $menu_name );
	}

	if ( is_wp_error( $menu_id ) ) {
		return;
	}

	$items = array(
		'home'         => __( 'Home', 'dwc-group' ),
		'about-us'     => __( 'About Us', 'dwc-group' ),
		'services'     => __( 'Our Services', 'dwc-group' ),
		'publications' => __( 'Publications', 'dwc-group' ),
		'contact'      => __( 'Contact', 'dwc-group' ),
	);

	if ( empty( wp_get_nav_menu_items( $menu_id ) ) ) {
		foreach ( $items as $slug => $label ) {
			if ( empty( $page_ids[ $slug ] ) ) {
				continue;
			}
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'     => $label,
					'menu-item-object'    => 'page',
					'menu-item-object-id' => $page_ids[ $slug ],
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
				)
			);
		}
	}

	$locations = get_theme_mod( 'nav_menu_locations' );
	$locations['primary'] = $menu_id;
	set_theme_mod( 'nav_menu_locations', $locations );
}

/**
 * Seed the four core services with the exact brand-brief copy. Safe to run
 * once -- skipped entirely if any dwc_service posts already exist.
 */
function dwc_group_seed_services() {
	if ( ! empty( get_posts( array( 'post_type' => 'dwc_service', 'numberposts' => 1, 'post_status' => 'any' ) ) ) ) {
		return;
	}

	$services = array(
		array(
			'title'   => __( 'Human Resources & Talent Acquisition Services', 'dwc-group' ),
			'excerpt' => __( 'We provide comprehensive human resources and talent acquisition support designed to align workforce practices with organizational goals and regulatory standards.', 'dwc-group' ),
			'content' => __( 'We provide comprehensive human resources and talent acquisition support designed to align workforce practices with organizational goals and regulatory standards. Services include HR compliance support, job classification and job advertisement development, recruitment coordination, structured interview processes, onboarding support, and payroll-related HR administration. Our approach emphasizes consistency, documentation, and fair hiring practices to reduce risk and support sustainable workforce management.', 'dwc-group' ),
			'capabilities' => "HR Compliance Support\nJob Classification\nJob Advertisement Development\nRecruitment Coordination\nStructured Interview Processes\nOnboarding Support\nPayroll-Related HR Administration\nWorkforce Documentation",
			'cta'     => __( 'Discuss HR Support', 'dwc-group' ),
			'order'   => 1,
			'featured' => false,
		),
		array(
			'title'   => __( 'Government Consulting Services', 'dwc-group' ),
			'excerpt' => __( 'Digital Wrk Consulting Group supports government entities and organizations that work with the public sector by strengthening administrative processes, compliance, and operational effectiveness.', 'dwc-group' ),
			'content' => __( 'Digital Wrk Consulting Group supports government entities and organizations that work with the public sector by strengthening administrative processes, compliance, and operational effectiveness. Services include policy and procedure development, administrative process reviews, workforce support, and compliance alignment with applicable regulations and reporting requirements. We assist clients in improving documentation practices, internal coordination, and operational transparency to support accountability and effective service delivery.', 'dwc-group' ),
			'capabilities' => "Policy & Procedure Development\nAdministrative Process Reviews\nCompliance Alignment\nWorkforce Support\nDocumentation Practices\nOperational Reviews\nReporting Support\nInternal Coordination",
			'cta'     => __( 'Discuss Government Consulting', 'dwc-group' ),
			'order'   => 2,
			'featured' => false,
		),
		array(
			'title'   => __( 'Accounting & Payroll Support Services', 'dwc-group' ),
			'excerpt' => __( 'DWC Group offers accounting and payroll support services that promote accuracy, consistency, and compliance.', 'dwc-group' ),
			'content' => __( 'DWC Group offers accounting and payroll support services that promote accuracy, consistency, and compliance. Services include payroll processing support, payroll review and reconciliation, financial record organization, documentation support, and assistance with internal controls related to payroll and financial operations. We help organizations maintain reliable financial records and improve processes that support financial oversight and reporting.', 'dwc-group' ),
			'capabilities' => "Payroll Processing Support\nPayroll Review\nPayroll Reconciliation\nFinancial Record Organization\nDocumentation Support\nInternal Controls\nFinancial Operations Support\nReporting Support",
			'cta'     => __( 'Discuss Accounting & Payroll', 'dwc-group' ),
			'order'   => 3,
			'featured' => false,
		),
		array(
			'title'   => __( 'AI Powered Fraud Auditing', 'dwc-group' ),
			'excerpt' => __( 'FraudAudit AI is an intelligent program integrity platform built to help agencies detect, investigate, and document fraudulent and improper claims with speed and precision.', 'dwc-group' ),
			'content' => __( "FraudAudit AI is an intelligent program integrity platform built to help agencies detect, investigate, and document fraudulent and improper claims with speed and precision. By combining advanced anomaly detection, configurable rules, and provider behavior analysis, the system surfaces high-risk activity and explains exactly why it matters, giving investigators clear, actionable insight.\n\nDesigned for real-world workflows, FraudAudit AI enables teams to flag claims for payment review, escalate cases for audit, schedule in-person investigations, and maintain fully documented case histories within a single, intuitive interface.\n\nFrom initial detection to evidence reporting, every action is tracked, organized, and audit-ready.", 'dwc-group' ),
			'capabilities' => "Anomaly Detection\nConfigurable Rules\nProvider Behavior Analysis\nHigh-Risk Activity Identification\nPayment Review Flags\nAudit Escalation\nInvestigation Scheduling\nCase Management\nEvidence Documentation\nAudit-Ready Case Histories",
			'cta'     => __( 'Explore FraudAudit AI', 'dwc-group' ),
			'order'   => 4,
			'featured' => true,
		),
	);

	foreach ( $services as $service ) {
		$post_id = wp_insert_post(
			array(
				'post_title'   => $service['title'],
				'post_excerpt' => $service['excerpt'],
				'post_content' => wpautop( $service['content'] ),
				'post_status'  => 'publish',
				'post_type'    => 'dwc_service',
				'menu_order'   => $service['order'],
			)
		);
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			update_post_meta( $post_id, '_dwc_capabilities', $service['capabilities'] );
			update_post_meta( $post_id, '_dwc_cta_label', $service['cta'] );
			update_post_meta( $post_id, '_dwc_featured', $service['featured'] ? '1' : '' );
		}
	}
}

/**
 * Seed the Publications product categories referenced in the brand brief.
 * Only runs when WooCommerce is active; harmless no-op otherwise.
 */
function dwc_group_seed_product_categories() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return;
	}

	$categories = array(
		__( 'Investigative Reports', 'dwc-group' ),
		__( 'Research', 'dwc-group' ),
		__( 'Government & Public Policy', 'dwc-group' ),
		__( 'Program Integrity', 'dwc-group' ),
		__( 'Fraud & Compliance', 'dwc-group' ),
		__( 'Industry Analysis', 'dwc-group' ),
	);

	foreach ( $categories as $category ) {
		if ( ! term_exists( $category, 'product_cat' ) ) {
			wp_insert_term( $category, 'product_cat' );
		}
	}
}

/**
 * If WooCommerce is activated after the theme (a very common order of
 * operations), seed the product categories then too.
 */
function dwc_group_on_woocommerce_loaded() {
	dwc_group_seed_product_categories();
}
add_action( 'woocommerce_loaded', 'dwc_group_on_woocommerce_loaded' );
