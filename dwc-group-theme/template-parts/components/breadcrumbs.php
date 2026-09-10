<?php
/**
 * Lightweight, schema-friendly breadcrumbs. Skipped automatically when
 * Yoast SEO or Rank Math already provide their own (see dwc_group_breadcrumbs()).
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dwc_crumbs = array(
	array( 'label' => __( 'Home', 'dwc-group' ), 'url' => home_url( '/' ) ),
);

$dwc_wc_active = class_exists( 'WooCommerce' );

if ( $dwc_wc_active && is_singular( 'product' ) ) {
	$dwc_crumbs[] = array( 'label' => __( 'Publications', 'dwc-group' ), 'url' => get_permalink( wc_get_page_id( 'shop' ) ) );
	$dwc_crumbs[] = array( 'label' => get_the_title(), 'url' => '' );
} elseif ( $dwc_wc_active && is_shop() ) {
	$dwc_crumbs[] = array( 'label' => __( 'Publications', 'dwc-group' ), 'url' => '' );
} elseif ( $dwc_wc_active && is_product_category() ) {
	$dwc_crumbs[] = array( 'label' => __( 'Publications', 'dwc-group' ), 'url' => get_permalink( wc_get_page_id( 'shop' ) ) );
	$dwc_crumbs[] = array( 'label' => single_term_title( '', false ), 'url' => '' );
} elseif ( is_singular( 'dwc_service' ) ) {
	$dwc_crumbs[] = array( 'label' => __( 'Our Services', 'dwc-group' ), 'url' => home_url( '/services/' ) );
	$dwc_crumbs[] = array( 'label' => get_the_title(), 'url' => '' );
} elseif ( is_page() ) {
	$dwc_crumbs[] = array( 'label' => get_the_title(), 'url' => '' );
} elseif ( is_singular() ) {
	$dwc_crumbs[] = array( 'label' => get_the_title(), 'url' => '' );
} elseif ( is_search() ) {
	$dwc_crumbs[] = array( 'label' => __( 'Search Results', 'dwc-group' ), 'url' => '' );
} elseif ( is_404() ) {
	$dwc_crumbs[] = array( 'label' => __( 'Page Not Found', 'dwc-group' ), 'url' => '' );
} else {
	$dwc_crumbs[] = array( 'label' => wp_get_document_title(), 'url' => '' );
}
?>
<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'dwc-group' ); ?>">
	<ol class="breadcrumbs__list" itemscope itemtype="https://schema.org/BreadcrumbList">
		<?php foreach ( $dwc_crumbs as $index => $crumb ) : ?>
			<li class="breadcrumbs__item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
				<?php if ( $crumb['url'] ) : ?>
					<a href="<?php echo esc_url( $crumb['url'] ); ?>" itemprop="item"><span itemprop="name"><?php echo esc_html( $crumb['label'] ); ?></span></a>
				<?php else : ?>
					<span itemprop="name" aria-current="page"><?php echo esc_html( $crumb['label'] ); ?></span>
				<?php endif; ?>
				<meta itemprop="position" content="<?php echo esc_attr( $index + 1 ); ?>" />
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
