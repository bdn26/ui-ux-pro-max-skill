/**
 * Product gallery fallback. WooCommerce's own wc-single-product script
 * (enqueued automatically because functions/theme-setup.php declares
 * wc-product-gallery-zoom/lightbox/slider support) turns the gallery into
 * a flexslider with zoom + lightbox. This file only guarantees clicking a
 * thumbnail swaps the main image in the rare case that script hasn't
 * initialized yet (slow connections, script blocked).
 */
( function () {
	'use strict';

	var doc = document;
	var gallery = doc.querySelector( '.woocommerce-product-gallery' );
	if ( ! gallery ) return;

	gallery.addEventListener( 'click', function ( e ) {
		var thumb = e.target.closest( '.flex-control-thumbs img' );
		if ( ! thumb || gallery.classList.contains( 'flexslider' ) ) return; // flexslider already handles clicks once initialized

		var mainImage = gallery.querySelector( '.woocommerce-product-gallery__image:first-child img' );
		if ( ! mainImage ) return;

		mainImage.src = thumb.getAttribute( 'data-large_image' ) || thumb.src;
		mainImage.srcset = '';

		gallery.querySelectorAll( '.flex-control-thumbs img' ).forEach( function ( img ) {
			img.classList.remove( 'flex-active' );
		} );
		thumb.classList.add( 'flex-active' );
	} );
} )();
