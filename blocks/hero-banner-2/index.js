( function ( wp ) {
	'use strict';

	const el = wp.element.createElement;
	const Fragment = wp.element.Fragment;
	const registerBlockType = wp.blocks.registerBlockType;
	const __ = wp.i18n.__;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const MediaUpload = wp.blockEditor.MediaUpload;
	const MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
	const Button = wp.components.Button;
	const PanelBody = wp.components.PanelBody;
	const TextControl = wp.components.TextControl;

	function textField( label, value, attribute, setAttributes ) {
		return el( TextControl, {
			label: label,
			value: value || '',
			onChange: function ( nextValue ) {
				setAttributes( { [ attribute ]: nextValue } );
			},
		} );
	}

	function registrationIcon() {
		return el(
			'svg',
			{ className: 'anur-registration-icon-svg', viewBox: '0 0 24 24', 'aria-hidden': true, focusable: false },
			el( 'path', { d: 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2' } ),
			el( 'circle', { cx: 9, cy: 7, r: 4 } ),
			el( 'path', { d: 'M19 8v6M22 11h-6' } )
		);
	}

	function arrowIcon() {
		return el(
			'svg',
			{ className: 'anur-registration-arrow-svg', viewBox: '0 0 24 24', 'aria-hidden': true, focusable: false },
			el( 'path', { d: 'M5 12h14M12 5l7 7-7 7' } )
		);
	}

	registerBlockType( 'stai-annur/hero-banner-2', {
		edit: function ( props ) {
			const attributes = props.attributes;
			const setAttributes = props.setAttributes;
			const backgroundStyle = attributes.backgroundUrl
				? {
						backgroundImage:
							'linear-gradient(90deg, rgba(0,0,0,.70) 0%, rgba(0,0,0,.50) 30%, rgba(0,0,0,.18) 65%, rgba(0,0,0,.22) 100%), url("' +
							attributes.backgroundUrl +
							'")',
				  }
				: undefined;

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Konten pendaftaran', 'stai-annur' ), initialOpen: true },
						textField( __( 'Label', 'stai-annur' ), attributes.label, 'label', setAttributes ),
						textField( __( 'Judul baris pertama', 'stai-annur' ), attributes.titleFirst, 'titleFirst', setAttributes ),
						textField( __( 'Judul baris kedua', 'stai-annur' ), attributes.titleSecond, 'titleSecond', setAttributes ),
						textField( __( 'Teks tombol', 'stai-annur' ), attributes.ctaText, 'ctaText', setAttributes ),
						textField( __( 'URL tombol', 'stai-annur' ), attributes.ctaUrl, 'ctaUrl', setAttributes ),
						textField( __( 'Label aksesibilitas tombol', 'stai-annur' ), attributes.ctaAriaLabel, 'ctaAriaLabel', setAttributes )
					),
					el(
						PanelBody,
						{ title: __( 'Gambar latar', 'stai-annur' ), initialOpen: false },
						el(
							MediaUploadCheck,
							null,
							el( MediaUpload, {
								onSelect: function ( media ) {
									setAttributes( {
										backgroundId: media.id,
										backgroundUrl: media.url,
										backgroundSizes: {},
									} );
								},
								allowedTypes: [ 'image' ],
								value: attributes.backgroundId,
								render: function ( mediaProps ) {
									return el(
										Button,
										{
											variant: 'secondary',
											onClick: mediaProps.open,
										},
										attributes.backgroundUrl
											? __( 'Ganti gambar', 'stai-annur' )
											: __( 'Pilih gambar', 'stai-annur' )
									);
								},
							} )
						),
						attributes.backgroundUrl
							? el(
									Button,
									{
										variant: 'link',
										isDestructive: true,
										onClick: function () {
											setAttributes( { backgroundId: 0, backgroundUrl: '', backgroundSizes: {} } );
										},
									},
									__( 'Hapus gambar', 'stai-annur' )
							  )
							: null
					)
				),
				el(
					'div',
					{ className: 'anur-registration-hero' },
					el(
						'div',
						{ className: 'anur-registration-card', style: backgroundStyle },
						el(
							'div',
							{ className: 'anur-registration-content' },
							el( 'div', { className: 'anur-registration-label' }, attributes.label ),
							el(
								'h2',
								{ className: 'anur-registration-title' },
								attributes.titleFirst,
								el( 'br' ),
								attributes.titleSecond,
								el( 'span', { className: 'anur-registration-icon' }, registrationIcon() )
							),
							el(
								'span',
								{ className: 'anur-registration-button' },
								el( 'span', null, attributes.ctaText ),
								el( 'span', { className: 'anur-registration-arrow' }, arrowIcon() )
							)
						)
					)
				)
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp );
