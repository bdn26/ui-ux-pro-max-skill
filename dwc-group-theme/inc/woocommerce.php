<?php
/**
 * WooCommerce integration: Publications is a plain WooCommerce product
 * catalog (downloadable/virtual products) restyled to feel like an
 * editorial research library rather than a generic store. Only loaded when
 * WooCommerce is active (see functions.php).
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Swap WooCommerce's default wrapper markup for the theme's own container so
 * shop/product pages sit inside the same grid as every other page.
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

function dwc_group_wc_wrapper_start() {
	echo '<main id="main" class="site-main woocommerce-main"><div class="container">';
}
add_action( 'woocommerce_before_main_content', 'dwc_group_wc_wrapper_start', 10 );

function dwc_group_wc_wrapper_end() {
	echo '</div></main>';
}
add_action( 'woocommerce_after_main_content', 'dwc_group_wc_wrapper_end', 10 );

/**
 * No theme sidebar on shop/product pages -- publications get the full,
 * editorial-width treatment.
 */
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

/** Three publications per row on wider screens; CSS handles the responsive stack. */
add_filter( 'loop_shop_columns', function () { return 3; } );
add_filter( 'woocommerce_products_per_page', function () { return 9; } );

/** Use the theme's product-card image size in the shop loop, and the taller
 * portrait "cover" size on the single publication page. */
add_filter( 'single_product_archive_thumbnail_size', function () { return 'dwc-card'; } );
add_filter( 'woocommerce_get_image_size_single', function ( $size ) {
	return array(
		'width'  => 800,
		'height' => 1040,
		'crop'   => 1,
	);
} );
add_theme_support( 'wc-product-gallery-lightbox' );

/**
 * Rename shop-loop microcopy to match the "publication" framing.
 */
add_filter( 'woocommerce_product_add_to_cart_text', 'dwc_group_purchase_labels', 10, 2 );
add_filter( 'woocommerce_product_single_add_to_cart_text', 'dwc_group_purchase_labels', 10, 2 );
function dwc_group_purchase_labels( $text, $product = null ) {
	return __( 'View Report', 'dwc-group' );
}

/**
 * The single-product page uses its own explicit "Purchase Report" button
 * label (set directly in the template), so the filter above only needs to
 * cover shop-loop / homepage cards. Nothing further required here.
 */

/**
 * Product-level meta box: What's Included / Format / Delivery -- the three
 * supporting facts every publication product page needs.
 */
function dwc_group_product_meta_box() {
	add_meta_box( 'dwc_publication_details', __( 'Publication Details', 'dwc-group' ), 'dwc_group_product_meta_box_html', 'product', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'dwc_group_product_meta_box' );

function dwc_group_product_meta_box_html( $post ) {
	wp_nonce_field( 'dwc_publication_save', 'dwc_publication_nonce' );
	$included = get_post_meta( $post->ID, '_dwc_whats_included', true );
	$format   = get_post_meta( $post->ID, '_dwc_format', true );
	$delivery = get_post_meta( $post->ID, '_dwc_delivery', true );
	?>
	<p>
		<label for="dwc_whats_included"><strong><?php esc_html_e( "What's Included (one item per line)", 'dwc-group' ); ?></strong></label><br />
		<textarea id="dwc_whats_included" name="dwc_whats_included" class="widefat" rows="5"><?php echo esc_textarea( $included ); ?></textarea>
	</p>
	<p>
		<label for="dwc_format"><strong><?php esc_html_e( 'Format', 'dwc-group' ); ?></strong></label><br />
		<input type="text" id="dwc_format" name="dwc_format" class="widefat" value="<?php echo esc_attr( $format ); ?>" placeholder="<?php esc_attr_e( 'e.g. PDF report, 32 pages', 'dwc-group' ); ?>" />
	</p>
	<p>
		<label for="dwc_delivery"><strong><?php esc_html_e( 'Delivery', 'dwc-group' ); ?></strong></label><br />
		<input type="text" id="dwc_delivery" name="dwc_delivery" class="widefat" value="<?php echo esc_attr( $delivery ); ?>" placeholder="<?php esc_attr_e( 'e.g. Instant digital download after purchase', 'dwc-group' ); ?>" />
	</p>
	<?php
}

function dwc_group_save_product_meta( $post_id ) {
	if ( ! isset( $_POST['dwc_publication_nonce'] ) || ! wp_verify_nonce( $_POST['dwc_publication_nonce'], 'dwc_publication_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['dwc_whats_included'] ) ) {
		update_post_meta( $post_id, '_dwc_whats_included', sanitize_textarea_field( wp_unslash( $_POST['dwc_whats_included'] ) ) );
	}
	if ( isset( $_POST['dwc_format'] ) ) {
		update_post_meta( $post_id, '_dwc_format', sanitize_text_field( wp_unslash( $_POST['dwc_format'] ) ) );
	}
	if ( isset( $_POST['dwc_delivery'] ) ) {
		update_post_meta( $post_id, '_dwc_delivery', sanitize_text_field( wp_unslash( $_POST['dwc_delivery'] ) ) );
	}
}
add_action( 'save_post_product', 'dwc_group_save_product_meta' );

/**
 * Rename the built-in "Description" tab to "Overview" and append What's
 * Included / Format / Delivery as their own tabs, falling back to sensible
 * defaults for downloadable/virtual products so the page never looks empty.
 */
function dwc_group_product_tabs( $tabs ) {
	global $product;

	if ( isset( $tabs['description'] ) ) {
		$tabs['description']['title'] = __( 'Overview', 'dwc-group' );
	}

	if ( ! $product ) {
		return $tabs;
	}

	$included = get_post_meta( $product->get_id(), '_dwc_whats_included', true );
	$format   = get_post_meta( $product->get_id(), '_dwc_format', true );
	$delivery = get_post_meta( $product->get_id(), '_dwc_delivery', true );

	if ( ! $included ) {
		$included = __( "Full digital report\nExecutive summary\nSupporting data and citations", 'dwc-group' );
	}
	if ( ! $format ) {
		$format = $product->is_downloadable() ? __( 'PDF digital publication', 'dwc-group' ) : __( 'Digital publication', 'dwc-group' );
	}
	if ( ! $delivery ) {
		$delivery = __( 'Instant digital download after successful purchase.', 'dwc-group' );
	}

	$tabs['dwc_included'] = array(
		'title'    => __( "What's Included", 'dwc-group' ),
		'priority' => 25,
		'callback' => function () use ( $included ) {
			$items = array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $included ) ) ) );
			echo '<ul class="dwc-list-check">';
			foreach ( $items as $item ) {
				echo '<li>' . esc_html( $item ) . '</li>';
			}
			echo '</ul>';
		},
	);

	$tabs['dwc_format'] = array(
		'title'    => __( 'Format', 'dwc-group' ),
		'priority' => 30,
		'callback' => function () use ( $format ) {
			echo '<p>' . esc_html( $format ) . '</p>';
		},
	);

	$tabs['dwc_delivery'] = array(
		'title'    => __( 'Delivery', 'dwc-group' ),
		'priority' => 35,
		'callback' => function () use ( $delivery ) {
			echo '<p>' . esc_html( $delivery ) . '</p>';
			echo '<p class="dwc-digital-notice">' . esc_html__( 'Digital publication. No physical product will be shipped.', 'dwc-group' ) . '</p>';
		},
	);

	unset( $tabs['additional_information'] );

	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'dwc_group_product_tabs' );

/**
 * Digital-product notice + category label shown directly in the purchase
 * panel, ahead of the price.
 */
function dwc_group_before_add_to_cart_form() {
	global $product;
	if ( ! $product ) {
		return;
	}
	$terms = wc_get_product_category_list( $product->get_id() );
	if ( $terms ) {
		echo '<p class="publication-eyebrow">' . wp_kses_post( $terms ) . '</p>';
	}
}
add_action( 'woocommerce_single_product_summary', 'dwc_group_before_add_to_cart_form', 4 );

function dwc_group_digital_notice() {
	global $product;
	if ( ! $product ) {
		return;
	}
	echo '<p class="dwc-digital-notice dwc-digital-notice--summary">' . esc_html__( 'Digital publication. No physical product will be shipped.', 'dwc-group' ) . '</p>';
}
add_action( 'woocommerce_single_product_summary', 'dwc_group_digital_notice', 31 );

/**
 * Homepage / archive card helper: consistent price + category markup used by
 * both the shop loop and the homepage publications section.
 */
function dwc_group_publication_category_label( $product_id ) {
	$terms = get_the_terms( $product_id, 'product_cat' );
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return '';
	}
	return esc_html( $terms[0]->name );
}

/**
 * Related products: relabel as "Related Publications" and limit to 3.
 */
add_filter(
	'woocommerce_output_related_products_args',
	function ( $args ) {
		$args['posts_per_page'] = 3;
		$args['columns']        = 3;
		return $args;
	}
);
add_filter(
	'woocommerce_product_related_products_heading',
	function () {
		return __( 'Related Publications', 'dwc-group' );
	}
);
