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
	const TextareaControl = wp.components.TextareaControl;

	function field( Component, label, value, attribute, setAttributes ) {
		return el( Component, {
			key: attribute,
			label: label,
			value: value || '',
			onChange: function ( nextValue ) {
				setAttributes( { [ attribute ]: nextValue } );
			},
		} );
	}

	registerBlockType( 'stai-annur/hero-banner', {
		edit: function ( props ) {
			const attributes = props.attributes;
			const setAttributes = props.setAttributes;
			const backgroundStyle = attributes.backgroundUrl
				? { backgroundImage: 'url("' + attributes.backgroundUrl + '")' }
				: undefined;

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Konten Hero', 'stai-annur' ), initialOpen: true },
						field( TextControl, __( 'Label atas', 'stai-annur' ), attributes.label, 'label', setAttributes ),
						field( TextControl, __( 'Judul utama', 'stai-annur' ), attributes.title, 'title', setAttributes ),
						field( TextControl, __( 'Judul baris kedua', 'stai-annur' ), attributes.titleAccent, 'titleAccent', setAttributes ),
						field( TextareaControl, __( 'Deskripsi', 'stai-annur' ), attributes.subtitle, 'subtitle', setAttributes ),
						field( TextControl, __( 'Angka keunggulan', 'stai-annur' ), attributes.statNumber, 'statNumber', setAttributes ),
						field( TextareaControl, __( 'Keterangan keunggulan', 'stai-annur' ), attributes.statDescription, 'statDescription', setAttributes )
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
											setAttributes( { backgroundId: 0, backgroundUrl: '' } );
										},
									},
									__( 'Hapus gambar', 'stai-annur' )
							  )
							: null
					),
					el(
						PanelBody,
						{ title: __( 'Tautan tombol', 'stai-annur' ), initialOpen: false },
						field( TextControl, __( 'Teks tombol brosur', 'stai-annur' ), attributes.brochureText, 'brochureText', setAttributes ),
						field( TextControl, __( 'URL brosur', 'stai-annur' ), attributes.brochureUrl, 'brochureUrl', setAttributes ),
						field( TextControl, __( 'URL WhatsApp / kontak', 'stai-annur' ), attributes.contactUrl, 'contactUrl', setAttributes )
					)
				),
				el(
					'div',
					{ className: 'anur-hero-widget' },
					el(
						'div',
						{ className: 'anur-hero-wrapper' },
						el(
							'div',
							{ className: 'anur-hero-card', style: backgroundStyle },
							el(
								'div',
								{ className: 'anur-hero-label' },
								el( 'span', { 'aria-hidden': true }, '▦' ),
								el( 'span', null, attributes.label )
							),
							el(
								'h2',
								{ className: 'anur-hero-title' },
								attributes.title,
								el( 'br' ),
								el( 'span', null, attributes.titleAccent )
							),
							el( 'p', { className: 'anur-hero-subtitle' }, attributes.subtitle || '' ),
							el(
								'div',
								{ className: 'anur-hero-highlight' },
								el( 'span', { className: 'anur-hero-number' }, attributes.statNumber || '' ),
								el( 'span', { className: 'anur-hero-description' }, attributes.statDescription || '' )
							),
							el(
								'div',
								{ className: 'anur-hero-actions' },
								el( 'span', { className: 'anur-hero-brochure' }, attributes.brochureText ),
								el( 'span', { className: 'anur-hero-action', 'aria-hidden': true }, '➤' ),
								el( 'span', { className: 'anur-hero-action', 'aria-hidden': true }, '☎' )
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
