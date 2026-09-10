<?php
/**
 * Shared helper functions used across template-parts.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Renders the site logo: custom logo if set, footer logo variant on dark
 * backgrounds, otherwise a clean text wordmark that always works.
 */
function dwc_group_the_logo( $context = 'header' ) {
	$footer_logo_id = get_theme_mod( 'dwc_footer_logo' );

	if ( 'footer' === $context && $footer_logo_id ) {
		$image = wp_get_attachment_image( $footer_logo_id, 'medium', false, array( 'class' => 'site-logo__image', 'loading' => 'lazy' ) );
		printf( '<a href="%s" class="site-logo" rel="home">%s</a>', esc_url( home_url( '/' ) ), $image );
		return;
	}

	if ( has_custom_logo() ) {
		$class = 'footer' === $context ? 'site-logo site-logo--footer' : 'site-logo';
		echo '<div class="' . esc_attr( $class ) . '">';
		the_custom_logo();
		echo '</div>';
		return;
	}

	printf(
		'<a href="%s" class="site-logo site-logo--text" rel="home"><span class="site-logo__mark">DWC</span><span class="site-logo__word">Group</span></a>',
		esc_url( home_url( '/' ) )
	);
}

/**
 * Social links configured in the Customizer, as [label => url].
 */
function dwc_group_social_links() {
	$links = array(
		'linkedin' => get_theme_mod( 'dwc_social_linkedin' ),
		'twitter'  => get_theme_mod( 'dwc_social_twitter' ),
		'facebook' => get_theme_mod( 'dwc_social_facebook' ),
	);
	return array_filter( $links );
}

/**
 * Header CTA target (defaults to the Contact page).
 */
function dwc_group_header_cta_url() {
	$url = get_theme_mod( 'dwc_header_cta_url' );
	return $url ? $url : dwc_group_contact_url();
}

/**
 * Generic page-hero subtitle meta box, reused by Services/Contact/About and
 * any standard page. Optional -- pages render fine without it.
 */
function dwc_group_page_meta_boxes() {
	add_meta_box( 'dwc_page_hero', __( 'Page Hero Subtitle', 'dwc-group' ), 'dwc_group_page_hero_box_html', 'page', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'dwc_group_page_meta_boxes' );

function dwc_group_page_hero_box_html( $post ) {
	wp_nonce_field( 'dwc_page_hero_save', 'dwc_page_hero_nonce' );
	$subtitle = get_post_meta( $post->ID, '_dwc_hero_subtitle', true );
	?>
	<p>
		<label for="dwc_hero_subtitle"><?php esc_html_e( 'Shown beneath the page title in the hero banner.', 'dwc-group' ); ?></label><br />
		<textarea id="dwc_hero_subtitle" name="dwc_hero_subtitle" class="widefat" rows="3"><?php echo esc_textarea( $subtitle ); ?></textarea>
	</p>
	<?php
}

function dwc_group_save_page_hero_meta( $post_id ) {
	if ( ! isset( $_POST['dwc_page_hero_nonce'] ) || ! wp_verify_nonce( $_POST['dwc_page_hero_nonce'], 'dwc_page_hero_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['dwc_hero_subtitle'] ) ) {
		update_post_meta( $post_id, '_dwc_hero_subtitle', sanitize_textarea_field( wp_unslash( $_POST['dwc_hero_subtitle'] ) ) );
	}
}
add_action( 'save_post_page', 'dwc_group_save_page_hero_meta' );

function dwc_group_page_subtitle( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return get_post_meta( $post_id, '_dwc_hero_subtitle', true );
}

/**
 * Breadcrumbs -- lightweight, schema-friendly, not a replacement for an SEO
 * plugin's breadcrumbs (Yoast/Rank Math breadcrumbs are used instead when
 * those plugins provide them).
 */
function dwc_group_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}
	get_template_part( 'template-parts/components/breadcrumbs' );
}

/**
 * Truncate helper for card descriptions.
 */
function dwc_group_trim( $text, $words = 20 ) {
	return wp_trim_words( wp_strip_all_tags( $text ), $words, '&hellip;' );
}
