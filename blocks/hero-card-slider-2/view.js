( function () {
	'use strict';

	function initSlider( slider ) {
		if ( slider.dataset.initialized === 'true' || window.matchMedia( '(min-width: 1025px)' ).matches ) {
			return;
		}

		slider.dataset.initialized = 'true';
		let dragging = false;
		let startX = 0;
		let startScroll = 0;

		slider.addEventListener( 'pointerdown', function ( event ) {
			if ( event.pointerType !== 'mouse' || event.button !== 0 || window.matchMedia( '(min-width: 1025px)' ).matches ) {
				return;
			}

			dragging = true;
			startX = event.pageX;
			startScroll = slider.scrollLeft;
			slider.classList.add( 'dragging' );
			slider.setPointerCapture( event.pointerId );
		} );

		slider.addEventListener( 'pointermove', function ( event ) {
			if ( ! dragging ) {
				return;
			}

			slider.scrollLeft = startScroll - ( event.pageX - startX ) * 1.5;
		} );

		function stopDragging() {
			dragging = false;
			slider.classList.remove( 'dragging' );
		}

		slider.addEventListener( 'pointerup', stopDragging );
		slider.addEventListener( 'pointercancel', stopDragging );
		slider.addEventListener( 'lostpointercapture', stopDragging );

		slider.addEventListener( 'wheel', function ( event ) {
			if ( window.matchMedia( '(min-width: 1025px)' ).matches ) {
				return;
			}

			if ( Math.abs( event.deltaY ) > Math.abs( event.deltaX ) ) {
				event.preventDefault();
				slider.scrollLeft += event.deltaY;
			}
		}, { passive: false } );

		slider.querySelectorAll( 'img' ).forEach( function ( image ) {
			image.addEventListener( 'dragstart', function ( event ) {
				event.preventDefault();
			} );
		} );
	}

	function initAll() {
		document.querySelectorAll( '[data-program-study-slider]' ).forEach( initSlider );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initAll );
	} else {
		initAll();
	}
} )();
