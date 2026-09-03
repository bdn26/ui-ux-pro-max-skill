/**
 * Cart drawer open/close + "Frequently Bought Together" add-all button.
 * Relies on WooCommerce's own AJAX add-to-cart (wc-ajax=add_to_cart) and
 * its jQuery cart-fragments script for state — this file never tracks
 * cart contents itself.
 */
( function () {
	'use strict';

	var doc = document;
	var cartDrawer = doc.getElementById( 'bdna-cart-drawer' );
	var openBtn = doc.querySelector( '[data-bdna-cart-open]' );
	var closeBtn = doc.querySelector( '[data-bdna-cart-close]' );

	if ( openBtn && cartDrawer ) {
		openBtn.addEventListener( 'click', function () {
			if ( window.beautydnaOpenDrawer ) window.beautydnaOpenDrawer( cartDrawer, openBtn );
		} );
	}
	if ( closeBtn && cartDrawer ) {
		closeBtn.addEventListener( 'click', function () {
			if ( window.beautydnaCloseDrawer ) window.beautydnaCloseDrawer( cartDrawer, openBtn );
		} );
	}

	/* Auto-open the drawer whenever WooCommerce's own AJAX add-to-cart fires. */
	if ( window.jQuery ) {
		window.jQuery( document.body ).on( 'added_to_cart', function () {
			if ( cartDrawer && window.beautydnaOpenDrawer ) {
				window.beautydnaOpenDrawer( cartDrawer, openBtn );
			}
		} );
	}

	/* ---- Frequently Bought Together: add every checked item, then open drawer ---- */
	var fbtForm = doc.querySelector( '[data-bdna-fbt]' );
	if ( fbtForm ) {
		fbtForm.addEventListener( 'submit', function ( e ) {
			e.preventDefault();
			var submitBtn = fbtForm.querySelector( '[data-bdna-fbt-submit]' );
			var mainId = submitBtn ? submitBtn.getAttribute( 'data-main-id' ) : null;
			var ids = Array.prototype.map.call(
				fbtForm.querySelectorAll( 'input[name="bdna_fbt_ids[]"]:checked' ),
				function ( el ) {
					return el.value;
				}
			);
			if ( mainId ) ids.unshift( mainId );
			if ( ! ids.length || ! window.beautydnaCart ) return;

			if ( submitBtn ) {
				submitBtn.disabled = true;
			}

			var endpoint = window.beautydnaCart.ajaxUrl.replace( 'admin-ajax.php', '?wc-ajax=add_to_cart' );

			ids
				.reduce( function ( chain, productId ) {
					return chain.then( function () {
						var body = new URLSearchParams();
						body.set( 'product_id', productId );
						body.set( 'quantity', '1' );
						return fetch( endpoint, {
							method: 'POST',
							credentials: 'same-origin',
							headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
							body: body.toString(),
						} );
					} );
				}, Promise.resolve() )
				.then( function () {
					if ( window.jQuery ) {
						window.jQuery( document.body ).trigger( 'wc_fragment_refresh' );
						window.jQuery( document.body ).trigger( 'added_to_cart' );
					}
				} )
				.finally( function () {
					if ( submitBtn ) submitBtn.disabled = false;
				} );
		} );
	}
} )();
