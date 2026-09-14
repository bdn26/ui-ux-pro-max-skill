<?php
/**
 * Native contact form handler (no page-builder or form-plugin dependency).
 * Posts to admin-post.php, verifies a nonce, sanitizes everything, and emails
 * the site's configured contact address.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dwc_group_contact_url() {
	$page = get_page_by_path( 'contact' );
	return $page ? get_permalink( $page ) : home_url( '/contact/' );
}

function dwc_group_service_interest_options() {
	return array(
		'hr'         => __( 'Human Resources & Talent Acquisition', 'dwc-group' ),
		'government' => __( 'Government Consulting', 'dwc-group' ),
		'accounting' => __( 'Accounting & Payroll Support', 'dwc-group' ),
		'fraudit'    => __( 'FraudAudit AI', 'dwc-group' ),
		'other'      => __( 'Other', 'dwc-group' ),
	);
}

function dwc_group_handle_contact_submission() {
	if ( ! isset( $_POST['dwc_contact_nonce'] ) || ! wp_verify_nonce( $_POST['dwc_contact_nonce'], 'dwc_contact_submit' ) ) {
		wp_safe_redirect( add_query_arg( 'dwc_contact', 'error', dwc_group_contact_url() ) );
		exit;
	}

	// Honeypot field: real users never fill this in.
	if ( ! empty( $_POST['dwc_company_website'] ) ) {
		wp_safe_redirect( add_query_arg( 'dwc_contact', 'success', dwc_group_contact_url() ) );
		exit;
	}

	$first_name   = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
	$last_name    = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
	$organization = isset( $_POST['organization'] ) ? sanitize_text_field( wp_unslash( $_POST['organization'] ) ) : '';
	$email        = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone        = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$service_key  = isset( $_POST['service_of_interest'] ) ? sanitize_key( wp_unslash( $_POST['service_of_interest'] ) ) : '';
	$message      = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

	$services = dwc_group_service_interest_options();
	$service  = isset( $services[ $service_key ] ) ? $services[ $service_key ] : __( 'Not specified', 'dwc-group' );

	if ( ! $first_name || ! $last_name || ! is_email( $email ) || ! $message ) {
		wp_safe_redirect( add_query_arg( 'dwc_contact', 'error', dwc_group_contact_url() ) );
		exit;
	}

	$to = get_theme_mod( 'dwc_contact_email', 'info@digitalwrk.co' );
	if ( ! $to || ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}

	$subject = sprintf( __( 'New Inquiry from %1$s %2$s — %3$s', 'dwc-group' ), $first_name, $last_name, get_bloginfo( 'name' ) );

	$body_lines = array(
		sprintf( '%s: %s %s', __( 'Name', 'dwc-group' ), $first_name, $last_name ),
		sprintf( '%s: %s', __( 'Organization', 'dwc-group' ), $organization ? $organization : '—' ),
		sprintf( '%s: %s', __( 'Email', 'dwc-group' ), $email ),
		sprintf( '%s: %s', __( 'Phone', 'dwc-group' ), $phone ? $phone : '—' ),
		sprintf( '%s: %s', __( 'Service of Interest', 'dwc-group' ), $service ),
		'',
		__( 'Message:', 'dwc-group' ),
		$message,
	);
	$body = implode( "\n", $body_lines );

	$headers = array( 'Content-Type: text/plain; charset=UTF-8' );
	if ( is_email( $email ) ) {
		$headers[] = 'Reply-To: ' . $first_name . ' ' . $last_name . ' <' . $email . '>';
	}

	$sent = wp_mail( $to, $subject, $body, $headers );

	/**
	 * Fires after a contact inquiry is processed, so a site owner can hook in
	 * a CRM sync or additional notification without touching this file.
	 */
	do_action( 'dwc_group_contact_submitted', compact( 'first_name', 'last_name', 'organization', 'email', 'phone', 'service', 'message', 'sent' ) );

	wp_safe_redirect( add_query_arg( 'dwc_contact', $sent ? 'success' : 'error', dwc_group_contact_url() ) );
	exit;
}
add_action( 'admin_post_dwc_contact_submit', 'dwc_group_handle_contact_submission' );
add_action( 'admin_post_nopriv_dwc_contact_submit', 'dwc_group_handle_contact_submission' );
