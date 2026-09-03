<?php
/**
 * Shop / product category / tag archive — override of
 * woocommerce/archive-product.php.
 *
 * Structure: editorial header (title + description + count) → toolbar
 * (sort + mobile filter toggle) → sidebar filters (real WooCommerce
 * widgets: Filter by Price, Attribute, Product Categories — add them to
 * the "Shop Sidebar" widget area) → product grid → pagination.
 *
 * @package BeautyDNA
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * woocommerce_before_main_content — kept for plugin compatibility, but we
 * don't render its default wrapper markup; we build our own below.
 */
do_action( 'woocommerce_before_main_content' );
?>

<div class="bdna-shop-header">
	<div class="bdna-container">
		<?php woocommerce_breadcrumb(); ?>
		<h1 class="bdna-heading-lg woocommerce-products-header__title"><?php woocommerce_page_title(); ?></h1>
		<?php
		if ( is_product_category() || is_product_tag() ) {
			$term_description = term_description();
			if ( $term_description ) {
				echo '<div class="bdna-lede bdna-shop-header__desc">' . wp_kses_post( $term_description ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		} elseif ( is_shop() && wc_get_page_id( 'shop' ) > 0 ) {
			$shop_page = get_post( wc_get_page_id( 'shop' ) );
			if ( $shop_page && $shop_page->post_content ) {
				echo '<div class="bdna-lede bdna-shop-header__desc">' . wp_kses_post( wpautop( $shop_page->post_content ) ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}
		?>
	</div>
</div>

<div class="bdna-container">
	<div class="bdna-shop-layout">

		<aside class="bdna-shop-filters" id="bdna-shop-filters" data-bdna-filters aria-label="<?php esc_attr_e( 'Product filters', 'beautydna' ); ?>">
			<div class="bdna-shop-filters__header">
				<h2><?php esc_html_e( 'Filter', 'beautydna' ); ?></h2>
				<button type="button" class="bdna-icon-btn" data-bdna-filters-close>
					<span class="bdna-visually-hidden"><?php esc_html_e( 'Close filters', 'beautydna' ); ?></span>&times;
				</button>
			</div>
			<?php if ( is_active_sidebar( 'shop-sidebar' ) ) : ?>
				<?php dynamic_sidebar( 'shop-sidebar' ); ?>
			<?php else : ?>
				<p class="bdna-filters-empty"><?php esc_html_e( 'Add "Filter Products by Price", "Filter by Attribute" or "Product Categories" widgets to the Shop Sidebar area (Appearance → Widgets) to enable filtering here.', 'beautydna' ); ?></p>
			<?php endif; ?>
			<button type="button" class="bdna-btn bdna-btn--primary bdna-btn--block bdna-shop-filters__apply" data-bdna-filters-apply><?php esc_html_e( 'Show Results', 'beautydna' ); ?></button>
		</aside>

		<div class="bdna-shop-main">
			<div class="bdna-shop-toolbar">
				<button type="button" class="bdna-filter-toggle" data-bdna-filters-open>
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M4 6h16M7 12h10M10 18h4" stroke-linecap="round"/></svg>
					<?php esc_html_e( 'Filter', 'beautydna' ); ?>
				</button>
				<p class="bdna-shop-toolbar__count"><?php woocommerce_result_count(); ?></p>
				<div class="bdna-shop-toolbar__right">
					<?php woocommerce_catalog_ordering(); ?>
				</div>
			</div>

			<?php if ( woocommerce_product_loop() ) : ?>

				<?php woocommerce_product_loop_start(); ?>

				<?php if ( wc_get_loop_prop( 'total' ) ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php wc_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; ?>
				<?php endif; ?>

				<?php woocommerce_product_loop_end(); ?>

				<?php do_action( 'woocommerce_after_shop_loop' ); // pagination ?>

			<?php else : ?>

				<?php do_action( 'woocommerce_no_products_found' ); ?>

			<?php endif; ?>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_main_content' ); ?>

<?php get_footer( 'shop' ); ?>
