<?php
/**
 * Immersive editorial hero. Copy + image come from the customizer
 * (Appearance → Customize → BeautyDNA Homepage → Hero) so merchandising
 * doesn't require a code change.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading    = get_theme_mod( 'beautydna_hero_heading', "YOUR BEAUTY.\nYOUR DNA." );
$subheading = get_theme_mod( 'beautydna_hero_subheading', 'Elevated beauty and wellness essentials designed to help you look, feel and glow your best.' );
$cta_primary_label   = get_theme_mod( 'beautydna_hero_cta_primary_label', 'Shop Best Sellers' );
$cta_primary_url     = get_theme_mod( 'beautydna_hero_cta_primary_url' ) ?: ( class_exists( 'WooCommerce' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ) );
$cta_secondary_label = get_theme_mod( 'beautydna_hero_cta_secondary_label', 'Explore BeautyDNA' );
$cta_secondary_url   = get_theme_mod( 'beautydna_hero_cta_secondary_url' ) ?: home_url( '/our-story/' );
$hero_image_id       = get_theme_mod( 'beautydna_hero_image' );
?>
<section class="bdna-hero" data-bdna-parallax>
	<div class="bdna-hero__media">
		<?php if ( $hero_image_id ) : ?>
			<?php echo wp_get_attachment_image( $hero_image_id, 'beautydna-hero', false, array( 'class' => 'bdna-hero__image', 'data-bdna-parallax-layer' => '', 'alt' => get_bloginfo( 'name' ) . ' — ' . __( 'editorial beauty campaign', 'beautydna' ) ) ); ?>
		<?php else : ?>
			<?php beautydna_placeholder( __( 'Hero campaign image — 1800×1200', 'beautydna' ), '3 / 2', 'bdna-hero__image' ); ?>
		<?php endif; ?>
	</div>
	<div class="bdna-hero__scrim"></div>
	<div class="bdna-hero__content bdna-container">
		<p class="bdna-eyebrow bdna-eyebrow--inverse"><?php esc_html_e( 'New Season Edit', 'beautydna' ); ?></p>
		<h1 class="bdna-hero__heading"><?php echo nl2br( esc_html( $heading ) ); ?></h1>
		<p class="bdna-hero__lede"><?php echo esc_html( $subheading ); ?></p>
		<div class="bdna-hero__ctas">
			<a class="bdna-btn bdna-btn--inverse" href="<?php echo esc_url( $cta_primary_url ); ?>"><?php echo esc_html( $cta_primary_label ); ?></a>
			<a class="bdna-btn bdna-btn--ghost-inverse" href="<?php echo esc_url( $cta_secondary_url ); ?>"><?php echo esc_html( $cta_secondary_label ); ?></a>
		</div>
	</div>
	<div class="bdna-hero__scroll-cue" aria-hidden="true"></div>
</section>
