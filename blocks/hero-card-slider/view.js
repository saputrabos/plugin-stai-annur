( function () {
	'use strict';

	function initSlider( widget ) {
		if ( widget.dataset.initialized === 'true' ) {
			return;
		}

		const track = widget.querySelector( '.anur-benefits-track' );
		const viewport = widget.querySelector( '.anur-benefits-slider' );
		const slides = Array.from( widget.querySelectorAll( '.anur-benefit-slide' ) );
		const previous = widget.querySelector( '.anur-benefits-prev' );
		const next = widget.querySelector( '.anur-benefits-next' );

		if ( ! track || ! viewport || ! slides.length || ! previous || ! next ) {
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

			previous.disabled = current === 0;
			next.disabled = current === slides.length - 1;
		}

		let pointerStart = null;

		previous.addEventListener( 'click', function () {
			if ( current > 0 ) {
				goTo( current - 1 );
			}
		} );

		next.addEventListener( 'click', function () {
			if ( current < slides.length - 1 ) {
				goTo( current + 1 );
			}
		} );

		viewport.addEventListener( 'pointerdown', function ( event ) {
			if ( ! event.isPrimary || ( event.button !== undefined && event.button !== 0 ) ) {
				return;
			}

			pointerStart = {
				x: event.clientX,
				y: event.clientY,
				id: event.pointerId,
			};

			if ( typeof viewport.setPointerCapture === 'function' ) {
				viewport.setPointerCapture( event.pointerId );
			}
		} );

		viewport.addEventListener( 'pointerup', function ( event ) {
			if ( ! pointerStart || event.pointerId !== pointerStart.id ) {
				return;
			}

			const deltaX = event.clientX - pointerStart.x;
			const deltaY = event.clientY - pointerStart.y;
			const threshold = Math.max( 40, viewport.clientWidth * 0.12 );
			pointerStart = null;

			if ( Math.abs( deltaX ) < threshold || Math.abs( deltaX ) <= Math.abs( deltaY ) ) {
				return;
			}

			goTo( current + ( deltaX < 0 ? 1 : -1 ) );
		} );

		viewport.addEventListener( 'pointercancel', function () {
			pointerStart = null;
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
