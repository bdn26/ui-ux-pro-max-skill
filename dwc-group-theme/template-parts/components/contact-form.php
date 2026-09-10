<?php
/**
 * Native WordPress contact form (posts to admin-post.php, handled in
 * inc/contact-form.php). No form-plugin dependency.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$status = isset( $_GET['dwc_contact'] ) ? sanitize_key( wp_unslash( $_GET['dwc_contact'] ) ) : '';
?>

<?php if ( 'success' === $status ) : ?>
	<div class="form-notice form-notice--success" role="status">
		<?php esc_html_e( 'Thank you. Your inquiry has been received and our team will be in touch shortly.', 'dwc-group' ); ?>
	</div>
<?php elseif ( 'error' === $status ) : ?>
	<div class="form-notice form-notice--error" role="alert">
		<?php esc_html_e( 'Something went wrong submitting your inquiry. Please check the required fields and try again.', 'dwc-group' ); ?>
	</div>
<?php endif; ?>

<form class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
	<input type="hidden" name="action" value="dwc_contact_submit" />
	<?php wp_nonce_field( 'dwc_contact_submit', 'dwc_contact_nonce' ); ?>
	<div class="contact-form__honeypot" aria-hidden="true">
		<label for="dwc_company_website"><?php esc_html_e( 'Leave this field empty', 'dwc-group' ); ?></label>
		<input type="text" id="dwc_company_website" name="dwc_company_website" tabindex="-1" autocomplete="off" />
	</div>

	<div class="form-grid">
		<div class="form-field">
			<label for="first_name"><?php esc_html_e( 'First Name', 'dwc-group' ); ?> <span aria-hidden="true">*</span></label>
			<input type="text" id="first_name" name="first_name" required autocomplete="given-name" />
		</div>
		<div class="form-field">
			<label for="last_name"><?php esc_html_e( 'Last Name', 'dwc-group' ); ?> <span aria-hidden="true">*</span></label>
			<input type="text" id="last_name" name="last_name" required autocomplete="family-name" />
		</div>
		<div class="form-field">
			<label for="organization"><?php esc_html_e( 'Organization', 'dwc-group' ); ?></label>
			<input type="text" id="organization" name="organization" autocomplete="organization" />
		</div>
		<div class="form-field">
			<label for="email"><?php esc_html_e( 'Email', 'dwc-group' ); ?> <span aria-hidden="true">*</span></label>
			<input type="email" id="email" name="email" required autocomplete="email" />
		</div>
		<div class="form-field">
			<label for="phone"><?php esc_html_e( 'Phone', 'dwc-group' ); ?></label>
			<input type="tel" id="phone" name="phone" autocomplete="tel" />
		</div>
		<div class="form-field">
			<label for="service_of_interest"><?php esc_html_e( 'Service of Interest', 'dwc-group' ); ?></label>
			<select id="service_of_interest" name="service_of_interest">
				<?php foreach ( dwc_group_service_interest_options() as $key => $label ) : ?>
					<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-field form-field--full">
			<label for="message"><?php esc_html_e( 'Message', 'dwc-group' ); ?> <span aria-hidden="true">*</span></label>
			<textarea id="message" name="message" rows="6" required></textarea>
		</div>
	</div>

	<button type="submit" class="btn btn--primary btn--block-mobile"><?php esc_html_e( 'Submit Inquiry', 'dwc-group' ); ?></button>
</form>
