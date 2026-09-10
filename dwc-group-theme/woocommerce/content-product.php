<?php
/**
 * Product card used inside the WooCommerce loop (shop archive, category
 * archives, [products] shortcode). Overrides
 * woocommerce/templates/content-product.php and simply delegates to the
 * theme's shared publication-card component.
 *
 * @package DWC_Group
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

?>
<li class="product-grid-item">
	<?php get_template_part( 'template-parts/publications/publication-card' ); ?>
</li>
<?php
