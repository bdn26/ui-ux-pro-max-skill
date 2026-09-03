<?php
/**
 * 404 template — points shoppers back toward the shop rather than a dead
 * end.
 */

get_header();
?>
<div class="bdna-container bdna-section bdna-404">
	<p class="bdna-eyebrow"><?php esc_html_e( '404', 'beautydna' ); ?></p>
	<h1 class="bdna-heading-xl"><?php esc_html_e( "We couldn't find that page.", 'beautydna' ); ?></h1>
	<p class="bdna-lede"><?php esc_html_e( "It may have moved, or the link may be outdated. Let's get you back to the good stuff.", 'beautydna' ); ?></p>
	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<a class="bdna-btn bdna-btn--primary" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Shop All', 'beautydna' ); ?></a>
	<?php else : ?>
		<a class="bdna-btn bdna-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to Home', 'beautydna' ); ?></a>
	<?php endif; ?>
</div>
<?php
get_footer();
