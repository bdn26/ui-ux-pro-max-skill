/**
 * Mobile filter drawer for shop/category archives. Reuses the shared
 * drawer/scrim helpers exposed by main.js.
 */
( function () {
	'use strict';

	var doc = document;
	var filters = doc.getElementById( 'bdna-shop-filters' );
	var openBtn = doc.querySelector( '[data-bdna-filters-open]' );
	var closeBtn = doc.querySelector( '[data-bdna-filters-close]' );
	var applyBtn = doc.querySelector( '[data-bdna-filters-apply]' );

	if ( ! filters ) return;

	if ( openBtn ) {
		openBtn.addEventListener( 'click', function () {
			if ( window.beautydnaOpenDrawer ) window.beautydnaOpenDrawer( filters, openBtn );
		} );
	}
	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', function () {
			if ( window.beautydnaCloseDrawer ) window.beautydnaCloseDrawer( filters, openBtn );
		} );
	}
	if ( applyBtn ) {
		applyBtn.addEventListener( 'click', function () {
			if ( window.beautydnaCloseDrawer ) window.beautydnaCloseDrawer( filters, openBtn );
		} );
	}
} )();
