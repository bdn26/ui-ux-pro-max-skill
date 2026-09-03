<?php
/**
 * Core theme supports, menus, widget areas and image sizes.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function beautydna_setup() {
	load_theme_textdomain( 'beautydna', BEAUTYDNA_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support(
		'align-wide'
	);

	// WooCommerce declarations (real support, no reinvented cart/checkout UI).
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Navigation', 'beautydna' ),
			'mobile'  => __( 'Mobile Navigation', 'beautydna' ),
			'footer-shop'    => __( 'Footer — Shop', 'beautydna' ),
			'footer-about'   => __( 'Footer — About', 'beautydna' ),
			'footer-care'    => __( 'Footer — Customer Care', 'beautydna' ),
			'footer-help'    => __( 'Footer — Help', 'beautydna' ),
		)
	);

	set_post_thumbnail_size( 800, 1000, true );
	add_image_size( 'beautydna-hero', 1800, 1200, true );
	add_image_size( 'beautydna-category', 900, 1100, true );
	add_image_size( 'beautydna-product-card', 800, 1000, true );
	add_image_size( 'beautydna-square', 700, 700, true );
}
add_action( 'after_setup_theme', 'beautydna_setup' );

/**
 * "Ingredient" is a small custom post type so the ingredient/benefit
 * storytelling section (template-parts/ingredient-story.php) is editable
 * from wp-admin — title, excerpt and featured image — instead of being
 * hard-coded in a template.
 */
function beautydna_register_ingredient_cpt() {
	register_post_type(
		'bdna_ingredient',
		array(
			'label'        => __( 'Ingredients', 'beautydna' ),
			'labels'       => array(
				'name'          => __( 'Ingredients', 'beautydna' ),
				'singular_name' => __( 'Ingredient', 'beautydna' ),
				'add_new_item'  => __( 'Add New Ingredient', 'beautydna' ),
			),
			'public'       => true,
			'show_in_menu' => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-carrot',
			'supports'     => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
			'has_archive'  => false,
			'rewrite'      => array( 'slug' => 'ingredients' ),
		)
	);
}
add_action( 'init', 'beautydna_register_ingredient_cpt' );

/**
 * "Testimonial" custom post type for the social-proof section — title is
 * the customer's first name + initial, content is the quote, plus a
 * star-rating and verified-purchase meta field.
 */
function beautydna_register_testimonial_cpt() {
	register_post_type(
		'bdna_testimonial',
		array(
			'label'        => __( 'Testimonials', 'beautydna' ),
			'labels'       => array(
				'name'          => __( 'Testimonials', 'beautydna' ),
				'singular_name' => __( 'Testimonial', 'beautydna' ),
				'add_new_item'  => __( 'Add New Testimonial', 'beautydna' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'show_in_menu' => true,
			'menu_icon'    => 'dashicons-star-filled',
			'supports'     => array( 'title', 'editor' ),
		)
	);
}
add_action( 'init', 'beautydna_register_testimonial_cpt' );

add_action( 'add_meta_boxes', function () {
	add_meta_box( 'beautydna_testimonial_meta', __( 'Rating & Verification', 'beautydna' ), function ( $post ) {
		wp_nonce_field( 'beautydna_testimonial_meta', 'beautydna_testimonial_meta_nonce' );
		$rating   = get_post_meta( $post->ID, '_bdna_rating', true ) ?: 5;
		$verified = get_post_meta( $post->ID, '_bdna_verified', true );
		?>
		<p>
			<label for="bdna_rating"><?php esc_html_e( 'Star rating (1-5)', 'beautydna' ); ?></label><br />
			<input type="number" min="1" max="5" id="bdna_rating" name="bdna_rating" value="<?php echo esc_attr( $rating ); ?>" />
		</p>
		<p>
			<label>
				<input type="checkbox" name="bdna_verified" value="1" <?php checked( $verified, '1' ); ?> />
				<?php esc_html_e( 'Verified customer', 'beautydna' ); ?>
			</label>
		</p>
		<?php
	}, 'bdna_testimonial', 'side' );
} );

add_action( 'save_post_bdna_testimonial', function ( $post_id ) {
	if ( ! isset( $_POST['beautydna_testimonial_meta_nonce'] ) || ! wp_verify_nonce( $_POST['beautydna_testimonial_meta_nonce'], 'beautydna_testimonial_meta' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	update_post_meta( $post_id, '_bdna_rating', isset( $_POST['bdna_rating'] ) ? min( 5, max( 1, absint( $_POST['bdna_rating'] ) ) ) : 5 );
	update_post_meta( $post_id, '_bdna_verified', isset( $_POST['bdna_verified'] ) ? '1' : '' );
} );

/**
 * Sidebars are intentionally minimal — this is a product-first storefront,
 * not a blog. One widget area is kept for the shop sidebar filter fallback.
 */
function beautydna_widgets_init() {
	register_sidebar(
		array(
			'name'          => __( 'Shop Sidebar', 'beautydna' ),
			'id'            => 'shop-sidebar',
			'description'   => __( 'Optional widgets shown above the mobile filter drawer.', 'beautydna' ),
			'before_widget' => '<div class="bdna-widget">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="bdna-widget__title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'beautydna_widgets_init' );

/**
 * Content width for embeds/oEmbed sizing.
 */
$GLOBALS['content_width'] = 1200;

/**
 * Register the announcement bar rotator + hero content as theme mods via
 * the customizer (see inc/customizer.php) rather than hard-coding copy.
 */
function beautydna_default_announcement() {
	return get_theme_mod( 'beautydna_announcement_text', __( 'FREE SHIPPING ON ORDERS OVER $75', 'beautydna' ) );
}

/**
 * Breadcrumbs for plain WordPress pages/posts. WooCommerce pages use
 * WooCommerce's own woocommerce_breadcrumb() (restyled in
 * inc/woocommerce-setup.php) so structured data stays intact.
 */
function beautydna_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	echo '<nav class="bdna-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'beautydna' ) . '"><ol>';
	echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'beautydna' ) . '</a></li>';
	echo '<li aria-current="page">' . esc_html( get_the_title() ) . '</li>';
	echo '</ol></nav>';
}
