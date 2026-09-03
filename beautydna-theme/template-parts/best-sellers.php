<?php
/**
 * Best Sellers — real WooCommerce products ordered by total sales.
 * Falls back to newest published products until sales data exists.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WooCommerce' ) ) {
	return;
}

$query = new WP_Query( array(
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => 4,
	'meta_key'       => 'total_sales', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
	'orderby'        => array( 'meta_value_num' => 'DESC', 'date' => 'DESC' ),
	'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
		'relation' => 'OR',
		array( 'key' => 'total_sales', 'compare' => 'EXISTS' ),
		array( 'key' => 'total_sales', 'compare' => 'NOT EXISTS' ),
	),
) );

if ( ! $query->have_posts() ) {
	return;
}
?>
<section class="bdna-section bdna-best-sellers" aria-labelledby="bdna-best-sellers-heading">
	<div class="bdna-container">
		<div class="bdna-section-heading bdna-section-heading--split bdna-fade-in">
			<div>
				<p class="bdna-eyebrow"><?php esc_html_e( 'Most Loved', 'beautydna' ); ?></p>
				<h2 id="bdna-best-sellers-heading" class="bdna-heading-lg"><?php esc_html_e( 'Best Sellers', 'beautydna' ); ?></h2>
			</div>
			<a class="bdna-text-link" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'View All', 'beautydna' ); ?></a>
		</div>

		<ul class="bdna-product-grid bdna-fade-in">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				wc_get_template_part( 'content', 'product' );
			endwhile;
			?>
		</ul>
	</div>
</section>
<?php wp_reset_postdata(); ?>
