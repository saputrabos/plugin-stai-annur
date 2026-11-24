( function () {
	'use strict';

	document.addEventListener( 'click', function ( event ) {
		var button = event.target.closest( '[data-anur-copy-link]' );

		if ( ! button ) {
			return;
		}

		var status = button.closest( '.anur-share' ).querySelector( '.anur-share-status' );
		var url = button.getAttribute( 'data-anur-copy-link' );

		if ( ! navigator.clipboard || ! window.isSecureContext ) {
			status.textContent = 'Browser tidak mendukung salin tautan otomatis. Salin URL halaman dari bilah alamat.';
			return;
		}

		navigator.clipboard.writeText( url ).then(
			function () {
				status.textContent = 'Tautan artikel berhasil disalin.';
			},
			function () {
				status.textContent = 'Tautan tidak dapat disalin. Salin URL halaman dari bilah alamat.';
			}
		);
	} );
} )();
