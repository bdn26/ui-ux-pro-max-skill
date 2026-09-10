<?php
/**
 * Sticky primary header: logo, nav, header CTA, mobile menu.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<header id="masthead" class="site-header" data-dwc-header>
	<div class="container site-header__inner">

		<div class="site-header__brand">
			<?php dwc_group_the_logo( 'header' ); ?>
		</div>

		<nav id="site-navigation" class="site-nav" aria-label="<?php esc_attr_e( 'Primary', 'dwc-group' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'container'      => false,
						'items_wrap'     => '<ul id="%1$s" class="site-nav__list">%3$s</ul>',
						'walker'         => new DWC_Group_Nav_Walker(),
					)
				);
			} else {
				dwc_group_fallback_menu();
			}
			?>
		</nav>

		<div class="site-header__cta">
			<a class="btn btn--primary btn--compact" href="<?php echo esc_url( dwc_group_header_cta_url() ); ?>">
				<?php echo esc_html( get_theme_mod( 'dwc_header_cta_label', __( 'Discuss Your Needs', 'dwc-group' ) ) ); ?>
			</a>
		</div>

		<button
			type="button"
			class="menu-toggle"
			id="menu-toggle"
			aria-controls="mobile-navigation"
			aria-expanded="false"
			aria-label="<?php esc_attr_e( 'Open menu', 'dwc-group' ); ?>"
		>
			<span class="menu-toggle__bar"></span>
			<span class="menu-toggle__bar"></span>
			<span class="menu-toggle__bar"></span>
		</button>
	</div>

	<div class="mobile-nav" id="mobile-navigation" hidden>
		<div class="mobile-nav__inner">
			<nav aria-label="<?php esc_attr_e( 'Primary (mobile)', 'dwc-group' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'mobile-menu',
							'container'      => false,
							'items_wrap'     => '<ul id="%1$s" class="mobile-nav__list">%3$s</ul>',
							'walker'         => new DWC_Group_Nav_Walker(),
						)
					);
				} else {
					dwc_group_fallback_menu();
				}
				?>
			</nav>
			<a class="btn btn--primary btn--block" href="<?php echo esc_url( dwc_group_header_cta_url() ); ?>">
				<?php echo esc_html( get_theme_mod( 'dwc_header_cta_label', __( 'Discuss Your Needs', 'dwc-group' ) ) ); ?>
			</a>
		</div>
	</div>
</header>
