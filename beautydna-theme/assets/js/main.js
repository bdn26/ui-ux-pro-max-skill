/**
 * Core interactions: sticky header state, mobile menu drawer, search
 * overlay, wishlist (localStorage), scroll fade-ins, sticky mobile
 * add-to-cart bar. No framework — vanilla DOM APIs only.
 */
( function () {
	'use strict';

	var doc = document;
	var body = doc.body;

	/* ---- Sticky header shadow on scroll --------------------------------- */
	var header = doc.querySelector( '[data-bdna-header]' );
	if ( header ) {
		var onScroll = function () {
			header.classList.toggle( 'is-scrolled', window.scrollY > 4 );
		};
		window.addEventListener( 'scroll', onScroll, { passive: true } );
		onScroll();
	}

	/* ---- Shared scrim + drawer open/close helpers ------------------------ */
	var scrim = doc.querySelector( '[data-bdna-scrim]' );

	function openDrawer( el, trigger ) {
		if ( ! el ) return;
		el.hidden = false;
		requestAnimationFrame( function () {
			el.classList.add( 'is-open' );
		} );
		if ( scrim ) {
			scrim.hidden = false;
			requestAnimationFrame( function () {
				scrim.classList.add( 'is-visible' );
			} );
		}
		body.classList.add( 'bdna-no-scroll' );
		if ( trigger ) trigger.setAttribute( 'aria-expanded', 'true' );
	}

	function closeDrawer( el, trigger ) {
		if ( ! el ) return;
		el.classList.remove( 'is-open' );
		if ( scrim ) scrim.classList.remove( 'is-visible' );
		body.classList.remove( 'bdna-no-scroll' );
		if ( trigger ) trigger.setAttribute( 'aria-expanded', 'false' );
		window.setTimeout( function () {
			el.hidden = true;
			if ( scrim && ! doc.querySelector( '.is-open' ) ) scrim.hidden = true;
		}, 320 );
	}

	/* ---- Mobile menu ------------------------------------------------------ */
	var mobileMenu = doc.getElementById( 'bdna-mobile-menu' );
	var menuOpenBtn = doc.querySelector( '[data-bdna-menu-open]' );
	var menuCloseBtn = doc.querySelector( '[data-bdna-menu-close]' );

	if ( menuOpenBtn ) {
		menuOpenBtn.addEventListener( 'click', function () {
			openDrawer( mobileMenu, menuOpenBtn );
		} );
	}
	if ( menuCloseBtn ) {
		menuCloseBtn.addEventListener( 'click', function () {
			closeDrawer( mobileMenu, menuOpenBtn );
		} );
	}

	/* ---- Search overlay ---------------------------------------------------- */
	var searchOverlay = doc.querySelector( '[data-bdna-search-overlay]' );
	var searchOpenBtn = doc.querySelector( '[data-bdna-search-open]' );
	var searchCloseBtn = doc.querySelector( '[data-bdna-search-close]' );

	if ( searchOpenBtn && searchOverlay ) {
		searchOpenBtn.addEventListener( 'click', function () {
			searchOverlay.hidden = false;
			var field = searchOverlay.querySelector( 'input[type="search"], .search-field' );
			if ( field ) field.focus();
		} );
	}
	if ( searchCloseBtn && searchOverlay ) {
		searchCloseBtn.addEventListener( 'click', function () {
			searchOverlay.hidden = true;
		} );
	}

	/* ---- Shared scrim click closes whichever drawer is open ---------------- */
	if ( scrim ) {
		scrim.addEventListener( 'click', function () {
			closeDrawer( mobileMenu, menuOpenBtn );
			var cartDrawer = doc.getElementById( 'bdna-cart-drawer' );
			if ( cartDrawer && cartDrawer.classList.contains( 'is-open' ) ) {
				closeDrawer( cartDrawer, doc.querySelector( '[data-bdna-cart-open]' ) );
			}
		} );
	}

	/* ---- Escape key closes any open overlay --------------------------------- */
	doc.addEventListener( 'keydown', function ( e ) {
		if ( e.key !== 'Escape' ) return;
		if ( mobileMenu && mobileMenu.classList.contains( 'is-open' ) ) closeDrawer( mobileMenu, menuOpenBtn );
		if ( searchOverlay && ! searchOverlay.hidden ) searchOverlay.hidden = true;
		var cartDrawer = doc.getElementById( 'bdna-cart-drawer' );
		if ( cartDrawer && cartDrawer.classList.contains( 'is-open' ) ) closeDrawer( cartDrawer, doc.querySelector( '[data-bdna-cart-open]' ) );
		var filters = doc.getElementById( 'bdna-shop-filters' );
		if ( filters && filters.classList.contains( 'is-open' ) ) closeDrawer( filters, doc.querySelector( '[data-bdna-filters-open]' ) );
	} );

	window.beautydnaOpenDrawer = openDrawer;
	window.beautydnaCloseDrawer = closeDrawer;

	/* ---- Scroll fade-ins (IntersectionObserver) ------------------------------ */
	var fadeEls = doc.querySelectorAll( '.bdna-fade-in' );
	if ( 'IntersectionObserver' in window && fadeEls.length ) {
		var io = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						entry.target.classList.add( 'is-visible' );
						io.unobserve( entry.target );
					}
				} );
			},
			{ threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
		);
		fadeEls.forEach( function ( el ) {
			io.observe( el );
		} );
	} else {
		fadeEls.forEach( function ( el ) {
			el.classList.add( 'is-visible' );
		} );
	}

	/* ---- Wishlist (client-side, localStorage) ----------------------------------- */
	var WISHLIST_KEY = 'beautydnaWishlist';

	function getWishlist() {
		try {
			return JSON.parse( window.localStorage.getItem( WISHLIST_KEY ) || '[]' );
		} catch ( e ) {
			return [];
		}
	}

	function setWishlist( ids ) {
		try {
			window.localStorage.setItem( WISHLIST_KEY, JSON.stringify( ids ) );
		} catch ( e ) { /* storage unavailable — wishlist just won't persist */ }
	}

	function updateWishlistUI() {
		var ids = getWishlist();
		doc.querySelectorAll( '.bdna-wishlist-btn' ).forEach( function ( btn ) {
			var id = btn.getAttribute( 'data-product-id' );
			var active = ids.indexOf( id ) !== -1;
			btn.classList.toggle( 'is-active', active );
			btn.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
		} );
		var countEl = doc.querySelector( '[data-bdna-wishlist-count]' );
		if ( countEl ) {
			countEl.textContent = ids.length;
			countEl.hidden = ids.length === 0;
		}
	}

	doc.addEventListener( 'click', function ( e ) {
		var btn = e.target.closest( '.bdna-wishlist-btn' );
		if ( ! btn ) return;
		e.preventDefault();
		var id = btn.getAttribute( 'data-product-id' );
		var ids = getWishlist();
		var index = ids.indexOf( id );
		if ( index === -1 ) {
			ids.push( id );
		} else {
			ids.splice( index, 1 );
		}
		setWishlist( ids );
		updateWishlistUI();
	} );

	updateWishlistUI();

	/* ---- Sticky mobile add-to-cart bar (product page) ----------------------------- */
	var stickyAtc = doc.querySelector( '[data-bdna-sticky-atc]' );
	var atcAnchor = doc.querySelector( '[data-bdna-atc-anchor]' );

	if ( stickyAtc && atcAnchor && 'IntersectionObserver' in window ) {
		var atcObserver = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					stickyAtc.classList.toggle( 'is-visible', ! entry.isIntersecting && entry.boundingClientRect.top < 0 );
					if ( ! entry.isIntersecting && entry.boundingClientRect.top < 0 ) {
						stickyAtc.hidden = false;
					}
				} );
			},
			{ threshold: 0 }
		);
		atcObserver.observe( atcAnchor );
	}

	var scrollToAtc = doc.querySelector( '[data-bdna-scroll-to-atc]' );
	if ( scrollToAtc ) {
		scrollToAtc.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			if ( atcAnchor ) atcAnchor.scrollIntoView( { behavior: 'smooth', block: 'center' } );
		} );
	}

	/* ---- Quantity stepper buttons (progressive enhancement over WC's <input>) ------ */
	doc.addEventListener( 'click', function ( e ) {
		var stepBtn = e.target.closest( '.bdna-qty-btn' );
		if ( ! stepBtn ) return;
		var wrapper = stepBtn.closest( '.quantity' );
		if ( ! wrapper ) return;
		var input = wrapper.querySelector( 'input.qty' );
		if ( ! input ) return;
		var step = parseFloat( input.step ) || 1;
		var min = parseFloat( input.min ) || 0;
		var max = input.max ? parseFloat( input.max ) : Infinity;
		var value = parseFloat( input.value ) || min;
		value = stepBtn.classList.contains( 'bdna-qty-btn--minus' ) ? Math.max( min, value - step ) : Math.min( max, value + step );
		input.value = value;
		input.dispatchEvent( new Event( 'change', { bubbles: true } ) );
	} );

	/* Insert +/- buttons around every WooCommerce quantity input for touch-friendly stepping. */
	doc.querySelectorAll( '.quantity' ).forEach( function ( wrapper ) {
		var input = wrapper.querySelector( 'input.qty' );
		if ( ! input || wrapper.dataset.bdnaEnhanced ) return;
		wrapper.dataset.bdnaEnhanced = 'true';

		var minus = doc.createElement( 'button' );
		minus.type = 'button';
		minus.className = 'bdna-qty-btn bdna-qty-btn--minus';
		minus.setAttribute( 'aria-label', 'Decrease quantity' );
		minus.textContent = '−';

		var plus = doc.createElement( 'button' );
		plus.type = 'button';
		plus.className = 'bdna-qty-btn bdna-qty-btn--plus';
		plus.setAttribute( 'aria-label', 'Increase quantity' );
		plus.textContent = '+';

		wrapper.insertBefore( minus, input );
		wrapper.appendChild( plus );
	} );
} )();
