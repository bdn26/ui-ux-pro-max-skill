<?php
/**
 * Additional single-product sections built entirely on top of real
 * WooCommerce data: Frequently Bought Together (upsells), Recently
 * Viewed (WooCommerce's own tracking cookie) and a sticky mobile
 * add-to-cart bar.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Frequently Bought Together — uses each product's configured Upsells
 * (Product Data → Linked Products → Upsells) so merchandising stays in
 * wp-admin, not in template code.
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_after_single_product_summary', 'beautydna_frequently_bought_together', 8 );
function beautydna_frequently_bought_together() {
	global $product;
	if ( ! $product ) {
		return;
	}

	$upsell_ids = $product->get_upsell_ids();
	if ( empty( $upsell_ids ) ) {
		return;
	}

	$companions = array_slice( $upsell_ids, 0, 2 );
	$products   = array_filter( array_map( 'wc_get_product', $companions ) );
	if ( empty( $products ) ) {
		return;
	}

	$all_ids = array_merge( array( $product->get_id() ), wp_list_pluck( $products, 'id' ) );
	$total   = $product->get_price();
	foreach ( $products as $p ) {
		$total += (float) $p->get_price();
	}
	?>
	<section class="bdna-fbt bdna-section bdna-fade-in" aria-labelledby="bdna-fbt-heading">
		<div class="bdna-container">
			<h2 id="bdna-fbt-heading" class="bdna-heading-md"><?php esc_html_e( 'Frequently Bought Together', 'beautydna' ); ?></h2>
			<form class="bdna-fbt__row" data-bdna-fbt>
				<div class="bdna-fbt__item bdna-fbt__item--main">
					<?php echo wp_kses_post( $product->get_image( 'beautydna-square' ) ); ?>
					<p class="bdna-fbt__name"><?php echo esc_html( $product->get_name() ); ?></p>
					<p class="bdna-fbt__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
				</div>
				<?php foreach ( $products as $p ) : ?>
					<span class="bdna-fbt__plus" aria-hidden="true">+</span>
					<div class="bdna-fbt__item">
						<label>
							<input type="checkbox" name="bdna_fbt_ids[]" value="<?php echo esc_attr( $p->get_id() ); ?>" checked />
							<?php echo wp_kses_post( $p->get_image( 'beautydna-square' ) ); ?>
							<span class="bdna-fbt__name"><?php echo esc_html( $p->get_name() ); ?></span>
							<span class="bdna-fbt__price"><?php echo wp_kses_post( $p->get_price_html() ); ?></span>
						</label>
					</div>
				<?php endforeach; ?>
				<div class="bdna-fbt__summary">
					<p class="bdna-fbt__total"><?php esc_html_e( 'Total:', 'beautydna' ); ?> <strong><?php echo wp_kses_post( wc_price( $total ) ); ?></strong></p>
					<button type="submit" class="bdna-btn bdna-btn--primary" data-bdna-fbt-submit data-main-id="<?php echo esc_attr( $product->get_id() ); ?>">
						<?php esc_html_e( 'Add Selected to Cart', 'beautydna' ); ?>
					</button>
				</div>
			</form>
		</div>
	</section>
	<?php
}

/* -------------------------------------------------------------------------
 * Recently Viewed — reads the cookie WooCommerce core already populates
 * via wc_track_product_view() on every single-product page load.
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_after_single_product_summary', 'beautydna_recently_viewed', 25 );
function beautydna_recently_viewed() {
	if ( empty( $_COOKIE['woocommerce_recently_viewed'] ) ) {
		return;
	}

	$viewed_ids = wp_parse_id_list( (array) explode( '|', wp_unslash( $_COOKIE['woocommerce_recently_viewed'] ) ) );
	$viewed_ids = array_diff( $viewed_ids, array( get_the_ID() ) );
	$viewed_ids = array_reverse( array_filter( $viewed_ids ) );

	if ( empty( $viewed_ids ) ) {
		return;
	}

	$products = array_slice( $viewed_ids, 0, 4 );

	$query = new WP_Query( array(
		'post_type'      => 'product',
		'post__in'       => $products,
		'orderby'        => 'post__in',
		'posts_per_page' => 4,
		'post_status'    => 'publish',
	) );

	if ( ! $query->have_posts() ) {
		return;
	}
	?>
	<section class="bdna-recently-viewed bdna-section bdna-fade-in" aria-labelledby="bdna-recently-viewed-heading">
		<div class="bdna-container">
			<h2 id="bdna-recently-viewed-heading" class="bdna-heading-md"><?php esc_html_e( 'Recently Viewed', 'beautydna' ); ?></h2>
			<ul class="bdna-product-grid">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					wc_get_template_part( 'content', 'product' );
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		</div>
	</section>
	<?php
}

/* -------------------------------------------------------------------------
 * Sticky mobile add-to-cart bar. Shown/hidden via IntersectionObserver in
 * assets/js/main.js once the real add-to-cart form scrolls out of view.
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_after_single_product', 'beautydna_sticky_add_to_cart' );
function beautydna_sticky_add_to_cart() {
	global $product;
	if ( ! $product ) {
		return;
	}
	?>
	<div class="bdna-sticky-atc" data-bdna-sticky-atc hidden>
		<div class="bdna-sticky-atc__info">
			<?php echo wp_kses_post( $product->get_image( 'thumbnail' ) ); ?>
			<div>
				<p class="bdna-sticky-atc__name"><?php echo esc_html( $product->get_name() ); ?></p>
				<p class="bdna-sticky-atc__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></p>
			</div>
		</div>
		<a href="#bdna-add-to-cart" class="bdna-btn bdna-btn--primary bdna-btn--sm" data-bdna-scroll-to-atc>
			<?php esc_html_e( 'Add to Cart', 'beautydna' ); ?>
		</a>
	</div>
	<?php
}

/* -------------------------------------------------------------------------
 * Mark the real add-to-cart form so the sticky bar / IntersectionObserver
 * has a stable anchor, without touching WooCommerce's own form markup.
 * ---------------------------------------------------------------------- */

add_action( 'woocommerce_before_add_to_cart_form', function () {
	echo '<div id="bdna-add-to-cart" data-bdna-atc-anchor></div>';
} );
