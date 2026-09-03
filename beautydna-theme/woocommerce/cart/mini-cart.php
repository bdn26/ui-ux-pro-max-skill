<?php
/**
 * Mini-cart — override of woocommerce/cart/mini-cart.php. Same
 * structure/classes WooCommerce's own JS (remove-from-cart, fragment
 * refresh) expects, restyled for the drawer.
 *
 * @package BeautyDNA
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' );

if ( ! WC()->cart->is_empty() ) :
	?>
	<ul class="woocommerce-mini-cart cart_list product_list_widget bdna-mini-cart">
		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				continue;
			}

			$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
			$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'thumbnail' ), $cart_item, $cart_item_key );
			$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
			$quantity          = apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%1$s &times; %2$s', esc_html( $cart_item['quantity'] ), wc_price( $_product->get_price() ) ) . '</span>', $cart_item, $cart_item_key );
			?>
			<li class="woocommerce-mini-cart-item mini_cart_item bdna-mini-cart__item">
				<?php if ( empty( $product_permalink ) ) : ?>
					<?php echo wp_kses_post( $thumbnail ); ?>
				<?php else : ?>
					<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo wp_kses_post( $thumbnail ); ?></a>
				<?php endif; ?>

				<div class="bdna-mini-cart__item-info">
					<?php if ( empty( $product_permalink ) ) : ?>
						<p class="bdna-mini-cart__item-name"><?php echo wp_kses_post( $product_name ); ?></p>
					<?php else : ?>
						<a class="bdna-mini-cart__item-name" href="<?php echo esc_url( $product_permalink ); ?>"><?php echo wp_kses_post( $product_name ); ?></a>
					<?php endif; ?>

					<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo wp_kses_post( $quantity ); ?>
				</div>

				<?php
				echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'woocommerce_cart_item_remove_link',
					sprintf(
						'<a href="%s" class="remove remove_from_cart_button bdna-mini-cart__remove" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">&times;</a>',
						esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
						esc_attr__( 'Remove this item', 'beautydna' ),
						esc_attr( $product_id ),
						esc_attr( $cart_item_key ),
						esc_attr( $_product->get_sku() )
					),
					$cart_item_key
				);
				?>
			</li>
			<?php
		}
		?>
	</ul>

	<p class="woocommerce-mini-cart__total total bdna-mini-cart__subtotal">
		<strong><?php esc_html_e( 'Subtotal', 'beautydna' ); ?></strong>
		<?php echo WC()->cart->get_cart_subtotal(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</p>

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

	<p class="woocommerce-mini-cart__buttons buttons bdna-mini-cart__buttons">
		<?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?>
	</p>

	<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>

<?php else : ?>

	<p class="woocommerce-mini-cart__empty-message bdna-mini-cart__empty">
		<?php esc_html_e( 'Your cart is empty — your next favorite is waiting.', 'beautydna' ); ?>
	</p>
	<a class="bdna-btn bdna-btn--secondary bdna-btn--block" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
		<?php esc_html_e( 'Continue Shopping', 'beautydna' ); ?>
	</a>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
