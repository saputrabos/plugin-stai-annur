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

	function updateSlide( slides, index, values, setAttributes ) {
		const updatedSlides = slides.slice();
		updatedSlides[ index ] = Object.assign( {}, updatedSlides[ index ], values );
		setAttributes( { slides: updatedSlides } );
	}

	function textField( label, value, onChange ) {
		return el( TextControl, {
			label: label,
			value: value || '',
			onChange: onChange,
		} );
	}

	function programIcon() {
		return el(
			'svg',
			{ viewBox: '0 0 24 24', 'aria-hidden': true, focusable: false },
			el( 'path', { d: 'm2 8 10-5 10 5-10 5L2 8Z' } ),
			el( 'path', { d: 'M6 10v5c3.5 2.5 8.5 2.5 12 0v-5M22 8v6' } )
		);
	}

	registerBlockType( 'stai-annur/hero-card-slider-2', {
		edit: function ( props ) {
			const attributes = props.attributes;
			const setAttributes = props.setAttributes;
			const slides = Array.isArray( attributes.slides ) ? attributes.slides : [];

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Pengaturan bagian', 'stai-annur' ), initialOpen: true },
						textField( __( 'Judul bagian', 'stai-annur' ), attributes.title, function ( title ) {
							setAttributes( { title: title } );
						} ),
						textField( __( 'Teks tautan', 'stai-annur' ), attributes.moreText, function ( moreText ) {
							setAttributes( { moreText: moreText } );
						} ),
						textField( __( 'URL semua program studi', 'stai-annur' ), attributes.moreUrl, function ( moreUrl ) {
							setAttributes( { moreUrl: moreUrl } );
						} )
					),
					el(
						PanelBody,
						{ title: __( 'Program studi', 'stai-annur' ), initialOpen: true },
						slides.map( function ( slide, index ) {
							return el(
								PanelBody,
								{
									key: 'program-' + index,
									title: slide.name || __( 'Program tanpa nama', 'stai-annur' ),
									initialOpen: false,
								},
								el(
									MediaUploadCheck,
									null,
									el( MediaUpload, {
										onSelect: function ( media ) {
											updateSlide(
												slides,
												index,
												{
													imageUrl: media.url,
													imageId: media.id,
													alt: media.alt || '',
												},
												setAttributes
											);
										},
										allowedTypes: [ 'image' ],
										value: slide.imageId,
										render: function ( mediaProps ) {
											return el(
												Button,
												{
													variant: 'secondary',
													onClick: mediaProps.open,
												},
												slide.imageUrl
													? __( 'Ganti gambar', 'stai-annur' )
													: __( 'Pilih gambar', 'stai-annur' )
											);
										},
									} )
								),
								slide.imageUrl
									? el(
											Button,
											{
												variant: 'link',
												isDestructive: true,
												onClick: function () {
													updateSlide( slides, index, { imageUrl: '', imageId: 0 }, setAttributes );
												},
											},
											__( 'Hapus gambar', 'stai-annur' )
									  )
									: null,
								textField( __( 'Teks alternatif', 'stai-annur' ), slide.alt, function ( alt ) {
									updateSlide( slides, index, { alt: alt }, setAttributes );
								} ),
								textField( __( 'Nama program studi', 'stai-annur' ), slide.name, function ( name ) {
									updateSlide( slides, index, { name: name }, setAttributes );
								} ),
								textField( __( 'Kode program studi', 'stai-annur' ), slide.code, function ( code ) {
									updateSlide( slides, index, { code: code }, setAttributes );
								} ),
								el(
									Button,
									{
										variant: 'link',
										isDestructive: true,
										onClick: function () {
											setAttributes( {
												slides: slides.filter( function ( unusedSlide, slideIndex ) {
													return slideIndex !== index;
												} ),
											} );
										},
									},
									__( 'Hapus program', 'stai-annur' )
								)
							);
						} ),
						el(
							Button,
							{
								variant: 'primary',
								onClick: function () {
									setAttributes( {
										slides: slides.concat( [
											{
												imageUrl: '',
												imageId: 0,
												alt: '',
												name: __( 'Program studi baru', 'stai-annur' ),
												code: '',
											},
										] ),
									} );
								},
							},
							__( 'Tambah program studi', 'stai-annur' )
						)
					)
				),
				el(
					'div',
					{ className: 'stai-program-study-widget' },
					el(
						'div',
						{ className: 'stai-program-study-header' },
						el( 'h2', { className: 'stai-program-study-title' }, attributes.title ),
						attributes.moreUrl
							? el( 'span', { className: 'stai-program-study-more' }, attributes.moreText )
							: null
					),
					el(
						'div',
						{ className: 'stai-program-study-slider' },
						slides.map( function ( slide, index ) {
							return el(
								'div',
								{
									key: 'program-preview-' + index,
									className: 'stai-program-study-card',
								},
								slide.imageUrl
									? el( 'img', { src: slide.imageUrl, alt: slide.alt || slide.name || '' } )
									: el( 'span', { className: 'stai-program-study-image-placeholder' }, __( 'Pilih gambar', 'stai-annur' ) ),
								el(
									'div',
									{ className: 'stai-program-study-info' },
									el( 'div', { className: 'stai-program-study-name' }, slide.name ),
									el(
										'div',
										{ className: 'stai-program-study-code' },
										programIcon(),
										el( 'span', null, slide.code )
									)
								)
							);
						} )
					)
				)
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp );
