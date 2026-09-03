<?php
/**
 * Template Name: BeautyDNA — Wishlist
 *
 * Assign this template to a page (e.g. /wishlist/) in the WordPress page
 * editor. Product IDs live in the visitor's localStorage (see
 * assets/js/main.js); this page asks the server to render the matching
 * real WooCommerce products via the beautydna_get_wishlist_products AJAX
 * action (inc/ajax.php).
 */

get_header();
?>
<div class="bdna-container bdna-section">
	<div class="bdna-section-heading">
		<p class="bdna-eyebrow"><?php esc_html_e( 'Saved For Later', 'beautydna' ); ?></p>
		<h1 class="bdna-heading-lg"><?php esc_html_e( 'Your Wishlist', 'beautydna' ); ?></h1>
	</div>

	<ul id="bdna-wishlist-grid" class="bdna-product-grid" data-bdna-wishlist-grid></ul>
	<p id="bdna-wishlist-empty" class="bdna-lede" hidden><?php esc_html_e( "You haven't saved anything yet. Tap the heart on any product to add it here.", 'beautydna' ); ?></p>
</div>

<script>
( function () {
	'use strict';
	var grid = document.getElementById( 'bdna-wishlist-grid' );
	var emptyMsg = document.getElementById( 'bdna-wishlist-empty' );
	if ( ! grid || ! window.beautydnaCart ) return;

	var ids = [];
	try {
		ids = JSON.parse( window.localStorage.getItem( 'beautydnaWishlist' ) || '[]' );
	} catch ( e ) {}

	if ( ! ids.length ) {
		emptyMsg.hidden = false;
		return;
	}

	var body = new URLSearchParams();
	body.set( 'action', 'beautydna_get_wishlist_products' );
	body.set( 'nonce', window.beautydnaCart.nonce );
	ids.forEach( function ( id ) {
		body.append( 'ids[]', id );
	} );

	fetch( window.beautydnaCart.ajaxUrl, {
		method: 'POST',
		credentials: 'same-origin',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
		body: body.toString(),
	} )
		.then( function ( res ) { return res.json(); } )
		.then( function ( json ) {
			if ( json && json.success && json.data && json.data.html ) {
				grid.innerHTML = json.data.html;
			} else {
				emptyMsg.hidden = false;
			}
		} )
		.catch( function () {
			emptyMsg.hidden = false;
		} );
} )();
</script>
<?php
get_footer();
