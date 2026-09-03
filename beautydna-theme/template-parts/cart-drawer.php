<?php
/**
 * Slide-out cart drawer. Contents come entirely from WooCommerce's own
 * mini-cart template (woocommerce/cart/mini-cart.php, overridden below
 * for styling) and stay in sync via the woocommerce_add_to_cart_fragments
 * filter in inc/ajax.php — no cart state is duplicated here.
 */

if ( ! defined( 'ABSPATH' ) || ! class_exists( 'WooCommerce' ) ) {
	return;
}
?>
<div class="bdna-cart-drawer" id="bdna-cart-drawer" data-bdna-cart-drawer hidden role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Your cart', 'beautydna' ); ?>">
	<div class="bdna-cart-drawer__header">
		<h2><?php esc_html_e( 'Your Cart', 'beautydna' ); ?></h2>
		<button type="button" class="bdna-icon-btn" data-bdna-cart-close>
			<span class="bdna-visually-hidden"><?php esc_html_e( 'Close cart', 'beautydna' ); ?></span>&times;
		</button>
	</div>

	<?php beautydna_render_free_shipping_bar(); ?>

	<div class="bdna-cart-drawer__body widget_shopping_cart_content">
		<?php woocommerce_mini_cart(); ?>
	</div>
</div>
