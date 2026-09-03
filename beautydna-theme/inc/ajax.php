<?php
/**
 * Small AJAX endpoints. Cart quantity/coupon/checkout AJAX is handled
 * entirely by WooCommerce core — this file only adds the wishlist lookup
 * and extends WooCommerce's own cart-fragments response.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* -------------------------------------------------------------------------
 * Wishlist page: the client holds product IDs in localStorage and asks
 * the server to render them as real WooCommerce product cards.
 * ---------------------------------------------------------------------- */

add_action( 'wp_ajax_beautydna_get_wishlist_products', 'beautydna_get_wishlist_products' );
add_action( 'wp_ajax_nopriv_beautydna_get_wishlist_products', 'beautydna_get_wishlist_products' );
function beautydna_get_wishlist_products() {
	check_ajax_referer( 'beautydna-cart', 'nonce' );

	$ids = isset( $_POST['ids'] ) ? wp_parse_id_list( wp_unslash( $_POST['ids'] ) ) : array();
	if ( empty( $ids ) ) {
		wp_send_json_success( array( 'html' => '' ) );
	}

	$query = new WP_Query( array(
		'post_type'      => 'product',
		'post__in'       => $ids,
		'orderby'        => 'post__in',
		'posts_per_page' => count( $ids ),
		'post_status'    => 'publish',
	) );

	ob_start();
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			wc_get_template_part( 'content', 'product' );
		}
		wp_reset_postdata();
	}
	$html = ob_get_clean();

	wp_send_json_success( array( 'html' => $html ) );
}

/* -------------------------------------------------------------------------
 * Extend WooCommerce's AJAX cart fragments so the header cart count, the
 * slide-out drawer contents and the free-shipping progress bar all refresh
 * together after any add-to-cart / quantity update, with no page reload.
 * ---------------------------------------------------------------------- */

add_filter( 'woocommerce_add_to_cart_fragments', 'beautydna_cart_fragments' );
function beautydna_cart_fragments( $fragments ) {
	ob_start();
	?>
	<span class="bdna-cart-count"><?php echo absint( WC()->cart->get_cart_contents_count() ); ?></span>
	<?php
	$fragments['.bdna-cart-count'] = ob_get_clean();

	ob_start();
	beautydna_render_free_shipping_bar();
	$fragments['[data-bdna-free-shipping-bar]'] = ob_get_clean();

	ob_start();
	woocommerce_mini_cart();
	$mini_cart = ob_get_clean();
	$fragments['div.widget_shopping_cart_content'] = '<div class="bdna-cart-drawer__body widget_shopping_cart_content">' . $mini_cart . '</div>';

	return $fragments;
}

/* -------------------------------------------------------------------------
 * Newsletter signup (footer). Stores the address as a lightweight custom
 * post so it works with zero email-service dependency; hook
 * 'beautydna_newsletter_signup' to push into Klaviyo/Mailchimp/etc.
 * ---------------------------------------------------------------------- */

add_action( 'admin_post_beautydna_newsletter_signup', 'beautydna_newsletter_signup' );
add_action( 'admin_post_nopriv_beautydna_newsletter_signup', 'beautydna_newsletter_signup' );
function beautydna_newsletter_signup() {
	if ( ! isset( $_POST['beautydna_newsletter_nonce'] ) || ! wp_verify_nonce( $_POST['beautydna_newsletter_nonce'], 'beautydna_newsletter' ) ) {
		wp_die( esc_html__( 'Security check failed.', 'beautydna' ) );
	}

	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$referer = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	if ( $email && is_email( $email ) ) {
		$existing = get_page_by_title( $email, OBJECT, 'bdna_subscriber' );
		if ( ! $existing ) {
			wp_insert_post( array(
				'post_type'   => 'bdna_subscriber',
				'post_title'  => $email,
				'post_status' => 'private',
			) );
		}
		do_action( 'beautydna_newsletter_signup', $email );
		wp_safe_redirect( add_query_arg( 'beautydna_newsletter', 'success', $referer ) . '#bdna-newsletter-status' );
	} else {
		wp_safe_redirect( add_query_arg( 'beautydna_newsletter', 'invalid', $referer ) . '#bdna-newsletter-status' );
	}
	exit;
}

add_action( 'init', function () {
	register_post_type( 'bdna_subscriber', array(
		'label'       => __( 'Newsletter Subscribers', 'beautydna' ),
		'public'      => false,
		'show_ui'     => true,
		'show_in_menu'=> 'options-general.php',
		'supports'    => array( 'title' ),
		'capability_type' => 'page',
	) );
} );

/**
 * Free-shipping progress bar — "You're $XX away from free shipping."
 * Shared by the cart drawer and the full cart page.
 */
function beautydna_render_free_shipping_bar() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}

	$threshold = beautydna_get_free_shipping_threshold();
	$subtotal  = (float) WC()->cart->get_subtotal();
	$remaining = max( 0, $threshold - $subtotal );
	$percent   = $threshold > 0 ? min( 100, round( ( $subtotal / $threshold ) * 100 ) ) : 100;
	?>
	<div class="bdna-free-shipping-bar" data-bdna-free-shipping-bar>
		<p class="bdna-free-shipping-bar__label">
			<?php if ( $remaining > 0 ) : ?>
				<?php
				printf(
					/* translators: %s: amount remaining until free shipping */
					esc_html__( "You're %s away from FREE shipping.", 'beautydna' ),
					wp_kses_post( wc_price( $remaining ) )
				);
				?>
			<?php else : ?>
				<?php esc_html_e( "You've unlocked FREE shipping!", 'beautydna' ); ?>
			<?php endif; ?>
		</p>
		<div class="bdna-free-shipping-bar__track">
			<div class="bdna-free-shipping-bar__fill" style="width:<?php echo esc_attr( $percent ); ?>%"></div>
		</div>
	</div>
	<?php
}
