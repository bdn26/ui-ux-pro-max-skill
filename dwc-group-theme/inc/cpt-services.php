<?php
/**
 * "Services" custom post type -- powers both the homepage service cards and
 * the full Our Services page. Kept independent of WooCommerce; Publications
 * (the only sellable content) stay on WooCommerce products instead of a
 * competing commerce system.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function dwc_group_register_service_cpt() {
	$labels = array(
		'name'               => __( 'Services', 'dwc-group' ),
		'singular_name'      => __( 'Service', 'dwc-group' ),
		'add_new_item'       => __( 'Add New Service', 'dwc-group' ),
		'edit_item'          => __( 'Edit Service', 'dwc-group' ),
		'new_item'           => __( 'New Service', 'dwc-group' ),
		'view_item'          => __( 'View Service', 'dwc-group' ),
		'all_items'          => __( 'Services', 'dwc-group' ),
		'search_items'       => __( 'Search Services', 'dwc-group' ),
		'not_found'          => __( 'No services found.', 'dwc-group' ),
	);

	register_post_type(
		'dwc_service',
		array(
			'labels'        => $labels,
			'public'        => true,
			'show_in_rest'  => true,
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 20,
			'has_archive'   => false,
			'rewrite'       => array( 'slug' => 'service' ),
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
			'hierarchical'  => false,
		)
	);
}
add_action( 'init', 'dwc_group_register_service_cpt' );

/**
 * Meta box: CTA label/URL, capability list, featured flag.
 */
function dwc_group_service_meta_box() {
	add_meta_box(
		'dwc_service_details',
		__( 'Service Details', 'dwc-group' ),
		'dwc_group_service_meta_box_html',
		'dwc_service',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'dwc_group_service_meta_box' );

function dwc_group_service_meta_box_html( $post ) {
	wp_nonce_field( 'dwc_service_save', 'dwc_service_nonce' );

	$cta_label    = get_post_meta( $post->ID, '_dwc_cta_label', true );
	$cta_url      = get_post_meta( $post->ID, '_dwc_cta_url', true );
	$capabilities = get_post_meta( $post->ID, '_dwc_capabilities', true );
	$featured     = get_post_meta( $post->ID, '_dwc_featured', true );
	?>
	<p>
		<label for="dwc_cta_label"><strong><?php esc_html_e( 'CTA Button Label', 'dwc-group' ); ?></strong></label><br />
		<input type="text" id="dwc_cta_label" name="dwc_cta_label" class="widefat" value="<?php echo esc_attr( $cta_label ); ?>" placeholder="<?php esc_attr_e( 'e.g. Discuss HR Support', 'dwc-group' ); ?>" />
	</p>
	<p>
		<label for="dwc_cta_url"><strong><?php esc_html_e( 'CTA URL (defaults to Contact page)', 'dwc-group' ); ?></strong></label><br />
		<input type="url" id="dwc_cta_url" name="dwc_cta_url" class="widefat" value="<?php echo esc_attr( $cta_url ); ?>" />
	</p>
	<p>
		<label for="dwc_capabilities"><strong><?php esc_html_e( 'Capabilities (one per line)', 'dwc-group' ); ?></strong></label><br />
		<textarea id="dwc_capabilities" name="dwc_capabilities" class="widefat" rows="8"><?php echo esc_textarea( $capabilities ); ?></textarea>
	</p>
	<p>
		<label>
			<input type="checkbox" name="dwc_featured" value="1" <?php checked( $featured, '1' ); ?> />
			<?php esc_html_e( 'Highlight as technology capability (used for FraudAudit AI)', 'dwc-group' ); ?>
		</label>
	</p>
	<?php
}

function dwc_group_save_service_meta( $post_id ) {
	if ( ! isset( $_POST['dwc_service_nonce'] ) || ! wp_verify_nonce( $_POST['dwc_service_nonce'], 'dwc_service_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['dwc_cta_label'] ) ) {
		update_post_meta( $post_id, '_dwc_cta_label', sanitize_text_field( wp_unslash( $_POST['dwc_cta_label'] ) ) );
	}
	if ( isset( $_POST['dwc_cta_url'] ) ) {
		update_post_meta( $post_id, '_dwc_cta_url', esc_url_raw( wp_unslash( $_POST['dwc_cta_url'] ) ) );
	}
	if ( isset( $_POST['dwc_capabilities'] ) ) {
		update_post_meta( $post_id, '_dwc_capabilities', sanitize_textarea_field( wp_unslash( $_POST['dwc_capabilities'] ) ) );
	}
	update_post_meta( $post_id, '_dwc_featured', isset( $_POST['dwc_featured'] ) ? '1' : '' );
}
add_action( 'save_post_dwc_service', 'dwc_group_save_service_meta' );

/**
 * Convenience getters used across templates.
 */
function dwc_group_service_capabilities( $post_id ) {
	$raw = get_post_meta( $post_id, '_dwc_capabilities', true );
	if ( ! $raw ) {
		return array();
	}
	$lines = preg_split( '/\r\n|\r|\n/', $raw );
	return array_values( array_filter( array_map( 'trim', $lines ) ) );
}

function dwc_group_service_cta_label( $post_id ) {
	$label = get_post_meta( $post_id, '_dwc_cta_label', true );
	return $label ? $label : __( 'Learn More', 'dwc-group' );
}

function dwc_group_service_cta_url( $post_id ) {
	$url = get_post_meta( $post_id, '_dwc_cta_url', true );
	return $url ? $url : dwc_group_contact_url();
}

function dwc_group_service_is_featured( $post_id ) {
	return '1' === get_post_meta( $post_id, '_dwc_featured', true );
}
