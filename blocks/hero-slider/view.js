( function () {
	'use strict';

	function initSlider( area ) {
		if ( area.dataset.initialized === 'true' ) {
			return;
		}

		const wrapper = area.querySelector( '.anur-building-wrapper' );
		const slider = area.querySelector( '.anur-building-slider' );
		const cards = Array.from( area.querySelectorAll( '.anur-building-card' ) );
		const previousButton = area.querySelector( '.anur-building-prev' );
		const nextButton = area.querySelector( '.anur-building-next' );

		if ( ! wrapper || ! slider || ! cards.length ) {
			return;
		}

		area.dataset.initialized = 'true';

		let currentIndex = Math.min( 1, cards.length - 1 );
		let intervalId = null;
		let resizeTimer = null;
		let touchStartX = 0;
		const delay = Math.max( 2000, Number( area.dataset.autoplayDelay ) || 3500 );
		const autoplayEnabled = area.dataset.autoplay === 'true' && cards.length > 1;
		const prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' );

		function setActiveCard() {
			cards.forEach( function ( card, index ) {
				const active = index === currentIndex;
				card.classList.toggle( 'anur-building-active', active );
				card.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
			} );
		}

		function updateSlider() {
			const activeCard = cards[ currentIndex ];

			if ( ! activeCard ) {
				return;
			}

			setActiveCard();
			requestAnimationFrame( function () {
				const wrapperWidth = wrapper.getBoundingClientRect().width;
				const cardCenter = activeCard.offsetLeft + activeCard.offsetWidth / 2;
				const centeredOffset = wrapperWidth / 2 - cardCenter;
				const minOffset = Math.min( 0, wrapperWidth - slider.scrollWidth );
				const offset = Math.max( minOffset, Math.min( 0, centeredOffset ) );

				slider.style.transform = 'translate3d(' + offset + 'px, 0, 0)';
			} );
		}

		function goToSlide( index ) {
			currentIndex = ( index + cards.length ) % cards.length;
			updateSlider();
		}

		function stopAutoplay() {
			window.clearInterval( intervalId );
			intervalId = null;
		}

		function startAutoplay() {
			stopAutoplay();

			if ( autoplayEnabled && ! prefersReducedMotion.matches && ! document.hidden ) {
				intervalId = window.setInterval( function () {
					goToSlide( currentIndex + 1 );
				}, delay );
			}
		}

		if ( previousButton ) {
			previousButton.addEventListener( 'click', function () {
				goToSlide( currentIndex - 1 );
				startAutoplay();
			} );
		}

		if ( nextButton ) {
			nextButton.addEventListener( 'click', function () {
				goToSlide( currentIndex + 1 );
				startAutoplay();
			} );
		}

		cards.forEach( function ( card, index ) {
			card.addEventListener( 'click', function () {
				goToSlide( index );
			} );
		} );

		wrapper.addEventListener( 'pointerenter', stopAutoplay );
		wrapper.addEventListener( 'pointerleave', startAutoplay );
		wrapper.addEventListener( 'focusin', stopAutoplay );
		wrapper.addEventListener( 'focusout', function ( event ) {
			if ( ! wrapper.contains( event.relatedTarget ) ) {
				startAutoplay();
			}
		} );

		wrapper.addEventListener( 'touchstart', function ( event ) {
			touchStartX = event.changedTouches[ 0 ].screenX;
			stopAutoplay();
		}, { passive: true } );

		wrapper.addEventListener( 'touchend', function ( event ) {
			const difference = touchStartX - event.changedTouches[ 0 ].screenX;

			if ( Math.abs( difference ) > 40 ) {
				goToSlide( currentIndex + ( difference > 0 ? 1 : -1 ) );
			}

			startAutoplay();
		}, { passive: true } );

		window.addEventListener( 'resize', function () {
			window.clearTimeout( resizeTimer );
			resizeTimer = window.setTimeout( updateSlider, 150 );
		}, { passive: true } );

		document.addEventListener( 'visibilitychange', function () {
			if ( document.hidden ) {
				stopAutoplay();
			} else {
				startAutoplay();
			}
		} );

		if ( prefersReducedMotion.addEventListener ) {
			prefersReducedMotion.addEventListener( 'change', startAutoplay );
		}

		updateSlider();
		startAutoplay();
	}

	function initAll() {
		document.querySelectorAll( '[data-anur-building-slider]' ).forEach( initSlider );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initAll );
	} else {
		initAll();
	}
} )();
