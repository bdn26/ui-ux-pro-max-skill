<?php
/**
 * Search form — used both in the header search overlay and on the
 * search results page. Searches WooCommerce products when the shop is
 * active, otherwise standard WordPress content.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$is_shop_search = class_exists( 'WooCommerce' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="bdna-search-field" class="bdna-visually-hidden"><?php esc_html_e( 'Search', 'beautydna' ); ?></label>
	<input
		type="search"
		id="bdna-search-field"
		class="search-field"
		placeholder="<?php echo esc_attr_x( 'Search products, skincare, haircare…', 'placeholder', 'beautydna' ); ?>"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		name="s"
	/>
	<?php if ( $is_shop_search ) : ?>
		<input type="hidden" name="post_type" value="product" />
	<?php endif; ?>
	<button type="submit" class="search-submit"><?php esc_html_e( 'Search', 'beautydna' ); ?></button>
</form>
