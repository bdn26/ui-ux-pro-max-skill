<?php
/**
 * Emotional brand-story section. Copy editable via Customizer →
 * BeautyDNA Homepage → Brand Story.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading = get_theme_mod( 'beautydna_story_heading', 'BEAUTY, FROM THE INSIDE OUT.' );
$body    = get_theme_mod( 'beautydna_story_body', '' );
$cta     = get_theme_mod( 'beautydna_story_cta', 'Discover BeautyDNA' );
?>
<section class="bdna-section bdna-brand-story" aria-labelledby="bdna-story-heading">
	<div class="bdna-brand-story__grid">
		<div class="bdna-brand-story__media bdna-fade-in">
			<?php beautydna_placeholder( __( 'Editorial lifestyle photography', 'beautydna' ), '4 / 5' ); ?>
		</div>
		<div class="bdna-brand-story__content bdna-fade-in">
			<p class="bdna-eyebrow"><?php esc_html_e( 'Our Philosophy', 'beautydna' ); ?></p>
			<h2 id="bdna-story-heading" class="bdna-heading-xl"><?php echo esc_html( $heading ); ?></h2>
			<p class="bdna-lede bdna-brand-story__body"><?php echo esc_html( $body ); ?></p>
			<a class="bdna-btn bdna-btn--secondary" href="<?php echo esc_url( home_url( '/our-story/' ) ); ?>"><?php echo esc_html( $cta ); ?></a>
		</div>
	</div>
</section>
