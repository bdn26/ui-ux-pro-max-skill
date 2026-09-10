<?php
/**
 * Publications archive (WooCommerce shop page).
 *
 * Overrides woocommerce/templates/archive-product.php. Keeps the standard
 * WooCommerce hook order intact (for plugin compatibility) and only
 * replaces the page-title markup with the theme's editorial hero + category
 * filter, matching the "Publications" storefront spec.
 *
 * @package DWC_Group
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

do_action( 'woocommerce_before_main_content' );
?>

<header class="publications-hero">
	<p class="eyebrow"><?php esc_html_e( 'PUBLICATIONS', 'dwc-group' ); ?></p>
	<h1 class="publications-hero__title"><?php esc_html_e( 'Publications', 'dwc-group' ); ?></h1>
	<p class="publications-hero__subtitle"><?php esc_html_e( 'Investigative reports, research, analysis, and digital publications from DWC Group.', 'dwc-group' ); ?></p>
</header>

<?php
$product_categories = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'exclude'    => array( get_option( 'default_product_cat', 0 ) ),
	)
);

if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) :
	?>
	<nav class="publications-filter" aria-label="<?php esc_attr_e( 'Filter publications by category', 'dwc-group' ); ?>">
		<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>" class="publications-filter__link <?php echo ! is_product_category() ? 'is-active' : ''; ?>"><?php esc_html_e( 'All', 'dwc-group' ); ?></a>
		<?php foreach ( $product_categories as $term ) : ?>
			<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="publications-filter__link <?php echo ( is_product_category( $term->slug ) ) ? 'is-active' : ''; ?>"><?php echo esc_html( $term->name ); ?></a>
		<?php endforeach; ?>
	</nav>
	<?php
endif;

do_action( 'woocommerce_archive_description' );

if ( woocommerce_product_loop() ) {

	do_action( 'woocommerce_before_shop_loop' );

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();
			do_action( 'woocommerce_shop_loop' );
			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	do_action( 'woocommerce_after_shop_loop' );
} else {
	do_action( 'woocommerce_no_products_found' );
}

do_action( 'woocommerce_after_main_content' );

do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
