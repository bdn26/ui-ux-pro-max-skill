<?php
/**
 * Site footer: brand column, company/services/publications link columns,
 * and a bottom legal bar. Every column falls back gracefully when a menu
 * hasn't been assigned yet.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$services = get_posts(
	array(
		'post_type'      => 'dwc_service',
		'posts_per_page' => 4,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	)
);

$publications_page = get_page_by_path( 'publications' );
$privacy_page       = get_page_by_path( 'privacy-policy' );
$terms_page         = get_page_by_path( 'terms-conditions' );
?>
<footer id="colophon" class="site-footer">

	<?php if ( is_active_sidebar( 'footer-widgets' ) ) : ?>
		<div class="container footer-widgets">
			<?php dynamic_sidebar( 'footer-widgets' ); ?>
		</div>
	<?php endif; ?>

	<div class="container footer-columns">

		<div class="footer-column footer-column--brand">
			<?php dwc_group_the_logo( 'footer' ); ?>
			<p class="footer-description"><?php echo esc_html( get_theme_mod( 'dwc_footer_description', __( 'Digital Wrk Consulting Group is a multidisciplinary consulting firm supporting government, workforce, and financial operations with practical, reliable expertise.', 'dwc-group' ) ) ); ?></p>
			<?php $social = dwc_group_social_links(); ?>
			<?php if ( $social ) : ?>
				<ul class="footer-social" aria-label="<?php esc_attr_e( 'Social media', 'dwc-group' ); ?>">
					<?php foreach ( $social as $network => $url ) : ?>
						<li>
							<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer">
								<span class="screen-reader-text"><?php echo esc_html( ucfirst( $network ) ); ?></span>
								<?php echo esc_html( ucfirst( $network ) ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>

		<nav class="footer-column" aria-label="<?php esc_attr_e( 'Company', 'dwc-group' ); ?>">
			<h3 class="footer-column__title"><?php esc_html_e( 'Company', 'dwc-group' ); ?></h3>
			<?php if ( has_nav_menu( 'footer-company' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-company', 'container' => false, 'items_wrap' => '<ul class="footer-column__list">%3$s</ul>' ) ); ?>
			<?php else : ?>
				<ul class="footer-column__list">
					<li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>"><?php esc_html_e( 'About Us', 'dwc-group' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Our Services', 'dwc-group' ); ?></a></li>
					<li><a href="<?php echo esc_url( $publications_page ? get_permalink( $publications_page ) : home_url( '/publications/' ) ); ?>"><?php esc_html_e( 'Publications', 'dwc-group' ); ?></a></li>
					<li><a href="<?php echo esc_url( dwc_group_contact_url() ); ?>"><?php esc_html_e( 'Contact', 'dwc-group' ); ?></a></li>
				</ul>
			<?php endif; ?>
		</nav>

		<nav class="footer-column" aria-label="<?php esc_attr_e( 'Services', 'dwc-group' ); ?>">
			<h3 class="footer-column__title"><?php esc_html_e( 'Services', 'dwc-group' ); ?></h3>
			<?php if ( has_nav_menu( 'footer-services' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-services', 'container' => false, 'items_wrap' => '<ul class="footer-column__list">%3$s</ul>' ) ); ?>
			<?php elseif ( $services ) : ?>
				<ul class="footer-column__list">
					<?php foreach ( $services as $service ) : ?>
						<li><a href="<?php echo esc_url( get_permalink( $service ) ); ?>"><?php echo esc_html( get_the_title( $service ) ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php else : ?>
				<ul class="footer-column__list">
					<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'HR & Talent Acquisition', 'dwc-group' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Government Consulting', 'dwc-group' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'Accounting & Payroll', 'dwc-group' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/services/' ) ); ?>"><?php esc_html_e( 'FraudAudit AI', 'dwc-group' ); ?></a></li>
				</ul>
			<?php endif; ?>
		</nav>

		<nav class="footer-column" aria-label="<?php esc_attr_e( 'Publications', 'dwc-group' ); ?>">
			<h3 class="footer-column__title"><?php esc_html_e( 'Publications', 'dwc-group' ); ?></h3>
			<?php if ( has_nav_menu( 'footer-publications' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-publications', 'container' => false, 'items_wrap' => '<ul class="footer-column__list">%3$s</ul>' ) ); ?>
			<?php else : ?>
				<ul class="footer-column__list">
					<?php
					$footer_categories = array( 'Investigative Reports', 'Research', 'Program Integrity', 'Fraud & Compliance' );
					foreach ( $footer_categories as $cat_name ) :
						$term = class_exists( 'WooCommerce' ) ? get_term_by( 'name', $cat_name, 'product_cat' ) : false;
						$url  = $term ? get_term_link( $term ) : ( $publications_page ? get_permalink( $publications_page ) : home_url( '/publications/' ) );
						?>
						<li><a href="<?php echo esc_url( $url ); ?>"><?php echo esc_html( $cat_name ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</nav>

	</div>

	<div class="footer-bottom">
		<div class="container footer-bottom__inner">
			<p class="footer-bottom__copy">
				&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All Rights Reserved.', 'dwc-group' ); ?>
			</p>
			<?php if ( has_nav_menu( 'footer-legal' ) ) : ?>
				<?php wp_nav_menu( array( 'theme_location' => 'footer-legal', 'container' => false, 'items_wrap' => '<ul class="footer-legal">%3$s</ul>' ) ); ?>
			<?php else : ?>
				<ul class="footer-legal">
					<?php if ( $privacy_page ) : ?>
						<li><a href="<?php echo esc_url( get_permalink( $privacy_page ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'dwc-group' ); ?></a></li>
					<?php endif; ?>
					<?php if ( $terms_page ) : ?>
						<li><a href="<?php echo esc_url( get_permalink( $terms_page ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'dwc-group' ); ?></a></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</footer>
