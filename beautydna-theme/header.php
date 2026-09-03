<?php
/**
 * The header: announcement bar, sticky primary nav, search overlay and
 * the mobile drawer menu. Cart drawer markup lives in
 * template-parts/cart-drawer.php and is included once, near the footer,
 * so it isn't duplicated if header.php is ever called more than once.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="bdna-skip-link" href="#bdna-main"><?php esc_html_e( 'Skip to content', 'beautydna' ); ?></a>

<?php $announcement = beautydna_default_announcement(); ?>
<?php if ( $announcement ) : ?>
<div class="bdna-announcement" role="note">
	<p><?php echo esc_html( $announcement ); ?></p>
</div>
<?php endif; ?>

<header class="bdna-header" data-bdna-header>
	<div class="bdna-header__inner bdna-container">

		<button type="button" class="bdna-header__hamburger" data-bdna-menu-open aria-expanded="false" aria-controls="bdna-mobile-menu">
			<span></span><span></span><span></span>
			<span class="bdna-visually-hidden"><?php esc_html_e( 'Open menu', 'beautydna' ); ?></span>
		</button>

		<div class="bdna-header__logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
				<?php if ( has_custom_logo() ) : the_custom_logo(); else : ?>
					<span class="bdna-logo-text"><?php bloginfo( 'name' ); ?></span>
				<?php endif; ?>
			</a>
		</div>

		<nav class="bdna-nav" aria-label="<?php esc_attr_e( 'Primary', 'beautydna' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'items_wrap'     => '<ul id="bdna-primary-menu" class="bdna-nav__list">%3$s</ul>',
						'depth'          => 2,
					)
				);
			} else {
				beautydna_fallback_primary_menu();
			}
			?>
		</nav>

		<div class="bdna-header__actions">
			<button type="button" class="bdna-icon-btn" data-bdna-search-open aria-haspopup="dialog">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3" stroke-linecap="round"/></svg>
				<span class="bdna-visually-hidden"><?php esc_html_e( 'Search', 'beautydna' ); ?></span>
			</button>

			<a class="bdna-icon-btn" href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : admin_url() ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c1.6-3.6 4.6-5.5 8-5.5s6.4 1.9 8 5.5" stroke-linecap="round"/></svg>
				<span class="bdna-visually-hidden"><?php esc_html_e( 'Account', 'beautydna' ); ?></span>
			</a>

			<a class="bdna-icon-btn bdna-icon-btn--wishlist" href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M12 20.5s-7.5-4.7-10-9.3C.4 7.8 2 4 5.7 4c2.1 0 3.6 1.2 4.3 2.6C10.7 5.2 12.2 4 14.3 4 18 4 19.6 7.8 18 11.2c-2.5 4.6-10 9.3-10 9.3Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
				<span class="bdna-icon-btn__count" data-bdna-wishlist-count hidden>0</span>
				<span class="bdna-visually-hidden"><?php esc_html_e( 'Wishlist', 'beautydna' ); ?></span>
			</a>

			<?php if ( class_exists( 'WooCommerce' ) ) : ?>
			<button type="button" class="bdna-icon-btn bdna-icon-btn--cart" data-bdna-cart-open>
				<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><path d="M6 8h12l-1 12H7L6 8Z" stroke-linejoin="round"/><path d="M9 8V6a3 3 0 0 1 6 0v2" stroke-linecap="round"/></svg>
				<span class="bdna-icon-btn__count bdna-cart-count"><?php echo absint( WC()->cart ? WC()->cart->get_cart_contents_count() : 0 ); ?></span>
				<span class="bdna-visually-hidden"><?php esc_html_e( 'Cart', 'beautydna' ); ?></span>
			</button>
			<?php endif; ?>
		</div>
	</div>
</header>

<!-- Search overlay -->
<div class="bdna-search-overlay" data-bdna-search-overlay hidden role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Search', 'beautydna' ); ?>">
	<div class="bdna-container bdna-search-overlay__inner">
		<?php echo get_search_form(); ?>
		<button type="button" class="bdna-search-overlay__close" data-bdna-search-close>
			<span class="bdna-visually-hidden"><?php esc_html_e( 'Close search', 'beautydna' ); ?></span>&times;
		</button>
	</div>
</div>

<!-- Mobile menu drawer -->
<div class="bdna-mobile-menu" id="bdna-mobile-menu" data-bdna-mobile-menu hidden>
	<div class="bdna-mobile-menu__header">
		<span class="bdna-logo-text"><?php bloginfo( 'name' ); ?></span>
		<button type="button" class="bdna-icon-btn" data-bdna-menu-close>
			<span class="bdna-visually-hidden"><?php esc_html_e( 'Close menu', 'beautydna' ); ?></span>&times;
		</button>
	</div>
	<nav aria-label="<?php esc_attr_e( 'Mobile', 'beautydna' ); ?>">
		<?php
		if ( has_nav_menu( 'mobile' ) ) {
			wp_nav_menu( array( 'theme_location' => 'mobile', 'container' => false, 'items_wrap' => '<ul class="bdna-mobile-menu__list">%3$s</ul>' ) );
		} elseif ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'items_wrap' => '<ul class="bdna-mobile-menu__list">%3$s</ul>' ) );
		} else {
			beautydna_fallback_primary_menu();
		}
		?>
	</nav>
	<div class="bdna-mobile-menu__footer">
		<a href="<?php echo esc_url( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'myaccount' ) : admin_url() ); ?>"><?php esc_html_e( 'Account', 'beautydna' ); ?></a>
		<a href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>"><?php esc_html_e( 'Wishlist', 'beautydna' ); ?></a>
	</div>
</div>
<div class="bdna-scrim" data-bdna-scrim hidden></div>

<main id="bdna-main">
