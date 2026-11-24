( function () {
	'use strict';

	function initArticleSlider( slider ) {
		if ( slider.dataset.initialized === 'true' ) {
			return;
		}

		const track = slider.querySelector( '.anur-article-track' );
		const widget = slider.closest( '.anur-article-widget' );

		if ( ! track || ! widget ) {
			return;
		}

		const previousButton = widget.querySelector( '.anur-article-prev' );
		const nextButton = widget.querySelector( '.anur-article-next' );

		if ( ! previousButton || ! nextButton ) {
			return;
		}

		slider.dataset.initialized = 'true';
		let position = 0;
		let resizeTimer = null;

		function maxPosition() {
			return Math.max( 0, track.scrollWidth - slider.clientWidth );
		}

		function applyPosition( animate ) {
			const maximum = maxPosition();
			position = Math.max( -maximum, Math.min( 0, position ) );
			track.style.transition = animate
				? 'transform .45s cubic-bezier(.22, .61, .36, 1)'
				: 'none';
			track.style.transform = 'translate3d(' + position + 'px, 0, 0)';
			previousButton.disabled = position >= 0;
			nextButton.disabled = position <= -maximum;
		}

		previousButton.addEventListener( 'click', function () {
			position += slider.clientWidth * .7;
			applyPosition( true );
		} );

		nextButton.addEventListener( 'click', function () {
			position -= slider.clientWidth * .7;
			applyPosition( true );
		} );

		window.addEventListener( 'resize', function () {
			window.clearTimeout( resizeTimer );
			resizeTimer = window.setTimeout( function () {
				applyPosition( false );
			}, 150 );
		}, { passive: true } );

		applyPosition( false );
	}

	function initAll() {
		document.querySelectorAll( '[data-anur-article-slider]' ).forEach( initArticleSlider );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', initAll );
	} else {
		initAll();
	}
} )();
