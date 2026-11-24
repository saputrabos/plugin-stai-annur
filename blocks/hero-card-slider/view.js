( function () {
	'use strict';

	function initSlider( widget ) {
		if ( widget.dataset.initialized === 'true' ) {
			return;
		}

		const track = widget.querySelector( '.anur-benefits-track' );
		const slides = Array.from( widget.querySelectorAll( '.anur-benefit-slide' ) );
		const previous = widget.querySelector( '.anur-benefits-prev' );
		const next = widget.querySelector( '.anur-benefits-next' );

		if ( ! track || ! slides.length || ! previous || ! next ) {
			return;
		}

		widget.dataset.initialized = 'true';
		let current = 0;

		function goTo( index ) {
			current = Math.max( 0, Math.min( slides.length - 1, index ) );
			track.style.transform = 'translate3d(' + ( -current * 100 ) + '%, 0, 0)';

			slides.forEach( function ( slide, slideIndex ) {
				slide.setAttribute( 'aria-hidden', slideIndex === current ? 'false' : 'true' );
			} );

			previous.disabled = false;
			next.disabled = current === slides.length - 1;
		}

		previous.addEventListener( 'click', function () {
			goTo( current > 0 ? current - 1 : slides.length - 1 );
		} );

		next.addEventListener( 'click', function () {
			if ( current < slides.length - 1 ) {
				goTo( current + 1 );
			}
		} );

		track.addEventListener( 'dragstart', function ( event ) {
			event.preventDefault();
		} );

		goTo( 0 );
	}

	function initAll() {
		document.querySelectorAll( '[data-anur-benefits-slider]' ).forEach( initSlider );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initAll );
	} else {
		initAll();
	}
} )();
