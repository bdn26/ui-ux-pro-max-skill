<?php
/**
 * 404 template.
 *
 * @package DWC_Group
 */

get_header();
?>

<main id="main" class="site-main">
	<div class="container error-404">
		<p class="eyebrow"><?php esc_html_e( '404', 'dwc-group' ); ?></p>
		<h1 class="page-hero__title"><?php esc_html_e( 'Page Not Found', 'dwc-group' ); ?></h1>
		<p class="page-hero__subtitle"><?php esc_html_e( "The page you're looking for doesn't exist or may have moved. Try a search, or head back to the homepage.", 'dwc-group' ); ?></p>

		<div class="error-404__search"><?php get_search_form(); ?></div>

		<div class="error-404__actions">
			<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'dwc-group' ); ?></a>
			<a class="btn btn--secondary" href="<?php echo esc_url( dwc_group_contact_url() ); ?>"><?php esc_html_e( 'Discuss Your Needs', 'dwc-group' ); ?></a>
		</div>
	</div>
</main>

<?php
get_footer();
