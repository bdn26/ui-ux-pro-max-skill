<?php
/**
 * Cart page — override of woocommerce/cart/cart.php.
 *
 * Same form fields/names WooCommerce's own JS and server-side handler
 * expect (cart[key][qty], update_cart, apply_coupon) so quantity
 * updates, coupons and totals keep working — only the row markup is
 * restyled from a <table> into editorial cards.
 *
 * @package BeautyDNA
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="bdna-cart-page">
	<div class="bdna-container">
		<h1 class="bdna-heading-lg"><?php esc_html_e( 'Your Cart', 'beautydna' ); ?></h1>

		<?php beautydna_render_free_shipping_bar(); ?>

		<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
			<?php do_action( 'woocommerce_before_cart_table' ); ?>

			<div class="bdna-cart-layout">
				<div class="bdna-cart-items">
					<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							continue;
						}

						$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
						?>
						<div class="bdna-cart-item woocommerce-cart-form__cart-item" role="row">
							<div class="bdna-cart-item__media">
								<?php
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail' ), $cart_item, $cart_item_key );
								if ( empty( $product_permalink ) ) {
									echo wp_kses_post( $thumbnail );
								} else {
									printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), wp_kses_post( $thumbnail ) );
								}
								?>
							</div>

							<div class="bdna-cart-item__info">
								<?php
								if ( empty( $product_permalink ) ) {
									echo '<p class="bdna-cart-item__name">' . wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '</p>';
								} else {
									echo '<a class="bdna-cart-item__name" href="' . esc_url( $product_permalink ) . '">' . wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '</a>';
								}
								echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

								if ( ! $_product->is_sold_individually() && $_product->is_purchasable() ) {
									echo '<div class="bdna-cart-item__qty">';
									woocommerce_quantity_input(
										array(
											'input_name'  => "cart[{$cart_item_key}][qty]",
											'input_value' => $cart_item['quantity'],
											'max_value'   => $_product->get_max_purchase_quantity(),
											'min_value'   => '0',
											'product_name'=> $_product->get_name(),
										),
										$_product
									);
									echo '</div>';
								} else {
									printf( '<span class="bdna-cart-item__qty-fixed">%s %d</span>', esc_html__( 'Qty', 'beautydna' ), esc_html( $cart_item['quantity'] ) );
								}
								?>
							</div>

							<div class="bdna-cart-item__price">
								<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>

							<?php
							echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								'woocommerce_cart_item_remove_link',
								sprintf(
									'<a href="%s" class="remove remove_from_cart_button bdna-cart-item__remove" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;<span class="bdna-visually-hidden">%s</span></a>',
									esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
									esc_attr__( 'Remove this item', 'beautydna' ),
									esc_attr( $product_id ),
									esc_attr( $cart_item_key ),
									esc_attr( $_product->get_sku() ),
									esc_html__( 'Remove', 'beautydna' )
								),
								$cart_item_key
							);
							?>
						</div>
					<?php endforeach; ?>

					<?php do_action( 'woocommerce_cart_contents' ); ?>

					<div class="bdna-cart-actions">
						<?php if ( wc_coupons_enabled() ) : ?>
							<div class="bdna-cart-coupon">
								<label for="coupon_code" class="bdna-visually-hidden"><?php esc_html_e( 'Coupon code', 'beautydna' ); ?></label>
								<input type="text" name="coupon_code" class="bdna-field" id="coupon_code" placeholder="<?php esc_attr_e( 'Discount code', 'beautydna' ); ?>" />
								<button type="submit" class="bdna-btn bdna-btn--secondary" name="apply_coupon" value="<?php esc_attr_e( 'Apply', 'beautydna' ); ?>"><?php esc_html_e( 'Apply', 'beautydna' ); ?></button>
							</div>
						<?php endif; ?>
						<button type="submit" class="bdna-text-link" name="update_cart" value="<?php esc_attr_e( 'Update Cart', 'beautydna' ); ?>"><?php esc_html_e( 'Update Cart', 'beautydna' ); ?></button>
					</div>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
					<?php do_action( 'woocommerce_cart_actions' ); ?>
				</div>

				<aside class="bdna-cart-summary">
					<?php do_action( 'woocommerce_cart_collaterals' ); ?>
				</aside>
			</div>

			<?php do_action( 'woocommerce_after_cart_table' ); ?>
		</form>
	</div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
