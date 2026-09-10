<?php
/**
 * FraudAudit AI feature section -- dark, dashboard-inspired, enterprise/
 * government-appropriate. Explicitly framed as an investigative/program-
 * integrity support platform, never as an automatic fraud-determination
 * system.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$default_body = __( 'FraudAudit AI is an intelligent program integrity platform built to help agencies detect, investigate, and document fraudulent and improper claims with speed and precision. By combining advanced anomaly detection, configurable rules, and provider behavior analysis, the system surfaces high-risk activity and explains exactly why it matters, giving investigators clear, actionable insight.

Designed for real-world workflows, FraudAudit AI enables teams to flag claims for payment review, escalate cases for audit, schedule in-person investigations, and maintain fully documented case histories within a single, intuitive interface.

From initial detection to evidence reporting, every action is tracked, organized, and audit-ready.', 'dwc-group' );

$body  = get_theme_mod( 'dwc_fraud_text', $default_body );
$fraud_service = get_posts( array( 'post_type' => 'dwc_service', 'posts_per_page' => 1, 'meta_key' => '_dwc_featured', 'meta_value' => '1' ) );
$default_url = $fraud_service ? get_permalink( $fraud_service[0] ) : dwc_group_contact_url();
$cta_url = get_theme_mod( 'dwc_fraud_cta_url' );
$cta_url = $cta_url ? $cta_url : $default_url;

$dashboard_metrics = array(
	array( 'label' => __( 'Flagged for Review', 'dwc-group' ), 'value' => '128' ),
	array( 'label' => __( 'Under Investigation', 'dwc-group' ), 'value' => '42' ),
	array( 'label' => __( 'Escalated for Audit', 'dwc-group' ), 'value' => '17' ),
);
?>
<section class="section fraud-ai" data-dwc-reveal>
	<div class="fraud-ai__pattern" aria-hidden="true"></div>
	<div class="container fraud-ai__grid">
		<div class="fraud-ai__content">
			<p class="eyebrow eyebrow--light"><?php echo esc_html( get_theme_mod( 'dwc_fraud_label', __( 'FRAUDAUDIT AI', 'dwc-group' ) ) ); ?></p>
			<h2 class="fraud-ai__title"><?php echo esc_html( get_theme_mod( 'dwc_fraud_heading', __( 'Modern Program Integrity Starts With Better Intelligence.', 'dwc-group' ) ) ); ?></h2>
			<div class="fraud-ai__copy">
				<?php echo wp_kses_post( wpautop( $body ) ); ?>
			</div>
			<?php
			get_template_part(
				'template-parts/components/cta-button',
				null,
				array(
					'dwc_cta' => array(
						'label' => get_theme_mod( 'dwc_fraud_cta_label', __( 'Explore FraudAudit AI', 'dwc-group' ) ),
						'url'   => $cta_url,
						'style' => 'primary',
					),
				)
			);
			?>
		</div>

		<div class="fraud-ai__visual" aria-hidden="true">
			<div class="fraud-dashboard">
				<div class="fraud-dashboard__header">
					<span class="fraud-dashboard__dot"></span>
					<span class="fraud-dashboard__dot"></span>
					<span class="fraud-dashboard__dot"></span>
					<span class="fraud-dashboard__label"><?php esc_html_e( 'Program Integrity Overview', 'dwc-group' ); ?></span>
				</div>
				<div class="fraud-dashboard__metrics">
					<?php foreach ( $dashboard_metrics as $metric ) : ?>
						<div class="fraud-dashboard__metric">
							<span class="fraud-dashboard__value"><?php echo esc_html( $metric['value'] ); ?></span>
							<span class="fraud-dashboard__metric-label"><?php echo esc_html( $metric['label'] ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
				<div class="fraud-dashboard__chart">
					<svg viewBox="0 0 320 110" preserveAspectRatio="none" focusable="false">
						<polyline class="fraud-dashboard__line" points="0,90 40,70 80,78 120,45 160,58 200,30 240,42 280,18 320,26" />
					</svg>
				</div>
				<ul class="fraud-dashboard__rows">
					<li><span>Provider Activity #4471</span><span class="tag tag--high"><?php esc_html_e( 'High Risk', 'dwc-group' ); ?></span></li>
					<li><span>Claim Batch #2208</span><span class="tag tag--review"><?php esc_html_e( 'Payment Review', 'dwc-group' ); ?></span></li>
					<li><span>Case #0093</span><span class="tag tag--audit"><?php esc_html_e( 'Audit Escalation', 'dwc-group' ); ?></span></li>
				</ul>
			</div>
		</div>
	</div>
</section>
