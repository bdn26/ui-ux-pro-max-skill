<?php
/**
 * Closes #bdna-main opened in header.php, prints the newsletter band,
 * the multi-column footer, and includes the cart drawer once per page.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
</main>

<section class="bdna-newsletter">
	<div class="bdna-container bdna-newsletter__inner">
		<h2 class="bdna-heading-lg"><?php esc_html_e( 'GET YOUR BEAUTYDNA.', 'beautydna' ); ?></h2>
		<p class="bdna-lede"><?php esc_html_e( 'Beauty tips, new launches and exclusive offers — delivered to your inbox.', 'beautydna' ); ?></p>
		<form class="bdna-newsletter__form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
			<label for="bdna-newsletter-email" class="bdna-visually-hidden"><?php esc_html_e( 'Email address', 'beautydna' ); ?></label>
			<input type="email" id="bdna-newsletter-email" name="email" class="bdna-field" placeholder="<?php esc_attr_e( 'Email address', 'beautydna' ); ?>" required />
			<input type="hidden" name="action" value="beautydna_newsletter_signup" />
			<?php wp_nonce_field( 'beautydna_newsletter', 'beautydna_newsletter_nonce' ); ?>
			<button type="submit" class="bdna-btn bdna-btn--primary"><?php esc_html_e( 'Sign Up', 'beautydna' ); ?></button>
		</form>
		<?php if ( isset( $_GET['beautydna_newsletter'] ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
			<p id="bdna-newsletter-status" class="bdna-newsletter__status" role="status">
				<?php echo 'success' === $_GET['beautydna_newsletter'] // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					? esc_html__( "You're on the list. Welcome to BeautyDNA.", 'beautydna' )
					: esc_html__( 'Please enter a valid email address.', 'beautydna' ); ?>
			</p>
		<?php endif; ?>
	</div>
</section>

<footer class="bdna-footer bdna-inverse">
	<div class="bdna-container bdna-footer__grid">

		<div class="bdna-footer__brand">
			<span class="bdna-logo-text"><?php bloginfo( 'name' ); ?></span>
			<p><?php esc_html_e( 'Elevated beauty and wellness essentials for your look, your feel, your glow.', 'beautydna' ); ?></p>
			<div class="bdna-footer__social">
				<?php
				$networks = array( 'instagram', 'tiktok', 'pinterest', 'facebook' );
				foreach ( $networks as $network ) :
					$url = get_theme_mod( 'beautydna_social_' . $network );
					if ( ! $url ) {
						continue;
					}
					?>
					<a href="<?php echo esc_url( $url ); ?>" aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>" target="_blank" rel="noopener noreferrer">
						<?php echo beautydna_social_icon( $network ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>

		<nav class="bdna-footer__col" aria-labelledby="bdna-footer-shop">
			<h3 id="bdna-footer-shop"><?php esc_html_e( 'Shop', 'beautydna' ); ?></h3>
			<?php
			if ( has_nav_menu( 'footer-shop' ) ) {
				wp_nav_menu( array( 'theme_location' => 'footer-shop', 'container' => false, 'items_wrap' => '<ul>%3$s</ul>' ) );
			} else {
				?>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/skincare/' ) ); ?>"><?php esc_html_e( 'Skincare', 'beautydna' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/haircare/' ) ); ?>"><?php esc_html_e( 'Haircare', 'beautydna' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/supplements/' ) ); ?>"><?php esc_html_e( 'Supplements', 'beautydna' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/body/' ) ); ?>"><?php esc_html_e( 'Body', 'beautydna' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/best-sellers/' ) ); ?>"><?php esc_html_e( 'Best Sellers', 'beautydna' ); ?></a></li>
				</ul>
				<?php
			}
			?>
		</nav>

		<nav class="bdna-footer__col" aria-labelledby="bdna-footer-about">
			<h3 id="bdna-footer-about"><?php esc_html_e( 'About', 'beautydna' ); ?></h3>
			<?php
			if ( has_nav_menu( 'footer-about' ) ) {
				wp_nav_menu( array( 'theme_location' => 'footer-about', 'container' => false, 'items_wrap' => '<ul>%3$s</ul>' ) );
			} else {
				?>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/our-story/' ) ); ?>"><?php esc_html_e( 'Our Story', 'beautydna' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/ingredients/' ) ); ?>"><?php esc_html_e( 'Ingredients', 'beautydna' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/sustainability/' ) ); ?>"><?php esc_html_e( 'Sustainability', 'beautydna' ); ?></a></li>
				</ul>
				<?php
			}
			?>
		</nav>

		<nav class="bdna-footer__col" aria-labelledby="bdna-footer-care">
			<h3 id="bdna-footer-care"><?php esc_html_e( 'Customer Care', 'beautydna' ); ?></h3>
			<?php
			if ( has_nav_menu( 'footer-care' ) ) {
				wp_nav_menu( array( 'theme_location' => 'footer-care', 'container' => false, 'items_wrap' => '<ul>%3$s</ul>' ) );
			} else {
				?>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/shipping/' ) ); ?>"><?php esc_html_e( 'Shipping', 'beautydna' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/returns/' ) ); ?>"><?php esc_html_e( 'Returns', 'beautydna' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact', 'beautydna' ); ?></a></li>
				</ul>
				<?php
			}
			?>
		</nav>

		<nav class="bdna-footer__col" aria-labelledby="bdna-footer-help">
			<h3 id="bdna-footer-help"><?php esc_html_e( 'Help', 'beautydna' ); ?></h3>
			<?php
			if ( has_nav_menu( 'footer-help' ) ) {
				wp_nav_menu( array( 'theme_location' => 'footer-help', 'container' => false, 'items_wrap' => '<ul>%3$s</ul>' ) );
			} else {
				?>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/faq/' ) ); ?>"><?php esc_html_e( 'FAQ', 'beautydna' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy', 'beautydna' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php esc_html_e( 'Terms', 'beautydna' ); ?></a></li>
				</ul>
				<?php
			}
			?>
		</nav>
	</div>

	<div class="bdna-container bdna-footer__bottom">
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'All rights reserved.', 'beautydna' ); ?></p>
	</div>
</footer>

<?php get_template_part( 'template-parts/cart-drawer' ); ?>

<?php wp_footer(); ?>
</body>
</html>
