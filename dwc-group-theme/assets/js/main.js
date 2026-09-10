/**
 * DWC Group -- minimal vanilla JS. No framework, no jQuery dependency.
 * Handles: sticky-header compaction, mobile nav, submenu toggles, and a
 * lightweight scroll-reveal that fully respects prefers-reduced-motion.
 */
( function () {
	'use strict';

	document.documentElement.classList.add( 'js' );

	var reduceMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	/* ---------------------------------------------------------------------
	 * Sticky header compaction
	 * ------------------------------------------------------------------ */
	var header = document.querySelector( '[data-dwc-header]' );
	if ( header ) {
		var toggleScrolled = function () {
			header.classList.toggle( 'is-scrolled', window.scrollY > 12 );
		};
		toggleScrolled();
		window.addEventListener( 'scroll', toggleScrolled, { passive: true } );
	}

	/* ---------------------------------------------------------------------
	 * Mobile navigation
	 * ------------------------------------------------------------------ */
	var menuToggle = document.getElementById( 'menu-toggle' );
	var mobileNav = document.getElementById( 'mobile-navigation' );

	if ( menuToggle && mobileNav ) {
		menuToggle.addEventListener( 'click', function () {
			var isOpen = menuToggle.getAttribute( 'aria-expanded' ) === 'true';

			menuToggle.setAttribute( 'aria-expanded', String( ! isOpen ) );
			menuToggle.setAttribute(
				'aria-label',
				isOpen ? menuToggle.dataset.labelOpen || 'Open menu' : menuToggle.dataset.labelClose || 'Close menu'
			);
			mobileNav.hidden = isOpen;
			document.body.classList.toggle( 'mobile-nav-open', ! isOpen );

			if ( ! isOpen ) {
				var firstLink = mobileNav.querySelector( 'a' );
				if ( firstLink ) {
					firstLink.focus();
				}
			}
		} );

		mobileNav.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' ) {
				menuToggle.click();
				menuToggle.focus();
			}
		} );

		// Close the mobile menu automatically if the viewport grows past the
		// mobile breakpoint (e.g. device rotation).
		window.addEventListener( 'resize', function () {
			if ( window.innerWidth > 900 && ! mobileNav.hidden ) {
				menuToggle.click();
			}
		} );
	}

	/* ---------------------------------------------------------------------
	 * Keyboard-accessible submenu toggles (desktop nav dropdowns)
	 * ------------------------------------------------------------------ */
	document.querySelectorAll( '.submenu-toggle' ).forEach( function ( button ) {
		button.addEventListener( 'click', function () {
			var parentItem = button.closest( '.menu-item' );
			if ( ! parentItem ) {
				return;
			}
			var expanded = button.getAttribute( 'aria-expanded' ) === 'true';
			button.setAttribute( 'aria-expanded', String( ! expanded ) );
			parentItem.classList.toggle( 'submenu-open', ! expanded );
		} );
	} );

	/* ---------------------------------------------------------------------
	 * Scroll reveal (skipped entirely for reduced-motion users)
	 * ------------------------------------------------------------------ */
	if ( ! reduceMotion && 'IntersectionObserver' in window ) {
		var revealTargets = document.querySelectorAll( '[data-dwc-reveal]' );

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						entry.target.classList.add( 'is-visible' );
						observer.unobserve( entry.target );
					}
				} );
			},
			{ threshold: 0.12, rootMargin: '0px 0px -60px 0px' }
		);

		revealTargets.forEach( function ( target ) {
			observer.observe( target );
		} );
	} else {
		document.querySelectorAll( '[data-dwc-reveal]' ).forEach( function ( target ) {
			target.classList.add( 'is-visible' );
		} );
	}
} )();
