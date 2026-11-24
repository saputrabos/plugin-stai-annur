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
	const RangeControl = wp.components.RangeControl;
	const TextControl = wp.components.TextControl;
	const ToggleControl = wp.components.ToggleControl;

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

	registerBlockType( 'stai-annur/hero-slider', {
		edit: function ( props ) {
			const attributes = props.attributes;
			const setAttributes = props.setAttributes;
			const slides = Array.isArray( attributes.slides ) ? attributes.slides : [];
			const activeIndex = Math.min( 1, Math.max( 0, slides.length - 1 ) );

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Pengaturan slider', 'stai-annur' ), initialOpen: true },
						el( ToggleControl, {
							label: __( 'Putar otomatis', 'stai-annur' ),
							checked: attributes.autoplay,
							onChange: function ( autoplay ) {
								setAttributes( { autoplay: autoplay } );
							},
						} ),
						el( RangeControl, {
							label: __( 'Jeda antar slide (detik)', 'stai-annur' ),
							value: Math.round( ( attributes.autoplayDelay || 3500 ) / 1000 ),
							min: 2,
							max: 10,
							disabled: ! attributes.autoplay,
							onChange: function ( seconds ) {
								setAttributes( { autoplayDelay: seconds * 1000 } );
							},
						} ),
						textField( __( 'Baris alamat 1', 'stai-annur' ), attributes.addressLine1, function ( addressLine1 ) {
							setAttributes( { addressLine1: addressLine1 } );
						} ),
						textField( __( 'Baris alamat 2', 'stai-annur' ), attributes.addressLine2, function ( addressLine2 ) {
							setAttributes( { addressLine2: addressLine2 } );
						} ),
						textField( __( 'URL Google Maps', 'stai-annur' ), attributes.mapUrl, function ( mapUrl ) {
							setAttributes( { mapUrl: mapUrl } );
						} )
					),
					el(
						PanelBody,
						{ title: __( 'Slide gedung', 'stai-annur' ), initialOpen: true },
						slides.map( function ( slide, index ) {
							return el(
								PanelBody,
								{
									key: 'slide-' + index,
									title: slide.name || __( 'Slide tanpa judul', 'stai-annur' ),
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
													updateSlide(
														slides,
														index,
														{ imageUrl: '', imageId: 0 },
														setAttributes
													);
												},
											},
											__( 'Hapus gambar', 'stai-annur' )
									  )
									: null,
								textField( __( 'Teks alternatif gambar', 'stai-annur' ), slide.alt, function ( alt ) {
									updateSlide( slides, index, { alt: alt }, setAttributes );
								} ),
								textField( __( 'Nama gedung', 'stai-annur' ), slide.name, function ( name ) {
									updateSlide( slides, index, { name: name }, setAttributes );
								} ),
								textField( __( 'Lokasi gedung', 'stai-annur' ), slide.location, function ( location ) {
									updateSlide( slides, index, { location: location }, setAttributes );
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
									__( 'Hapus slide', 'stai-annur' )
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
												name: __( 'Gedung baru', 'stai-annur' ),
												location: '',
											},
										] ),
									} );
								},
							},
							__( 'Tambah slide', 'stai-annur' )
						)
					)
				),
				el(
					'div',
					{ className: 'anur-building-widget' },
					slides.length
						? el(
								'div',
								{ className: 'anur-building-area' },
								el(
									'button',
									{ type: 'button', className: 'anur-building-nav anur-building-prev', 'aria-label': __( 'Slide sebelumnya', 'stai-annur' ), disabled: true },
									'‹'
								),
								el(
									'div',
									{ className: 'anur-building-wrapper' },
									el(
										'div',
										{ className: 'anur-building-slider' },
										slides.map( function ( slide, index ) {
											return el(
												'div',
												{
													key: 'preview-slide-' + index,
													className: 'anur-building-card' + ( index === activeIndex ? ' anur-building-active' : '' ),
												},
												slide.imageUrl
													? el( 'img', { src: slide.imageUrl, alt: slide.alt || slide.name || '' } )
													: el( 'div', { className: 'anur-building-image-placeholder' }, __( 'Pilih gambar slide', 'stai-annur' ) ),
												el( 'div', { className: 'anur-building-overlay' } ),
												el(
													'div',
													{ className: 'anur-building-info' },
													el( 'div', { className: 'anur-building-name' }, slide.name ),
													el( 'div', { className: 'anur-building-location' }, slide.location )
												)
											);
										} )
									)
								),
								el(
									'button',
									{ type: 'button', className: 'anur-building-nav anur-building-next', 'aria-label': __( 'Slide berikutnya', 'stai-annur' ), disabled: true },
									'›'
								)
						  )
						: el( 'p', { className: 'anur-building-empty' }, __( 'Tambahkan slide gedung dari panel pengaturan.', 'stai-annur' ) ),
					el(
						'div',
						{ className: 'anur-campus-location' },
						el(
							'div',
							{ className: 'anur-location-address' },
							el( 'strong', null, attributes.addressLine1 ),
							el( 'br' ),
							attributes.addressLine2
						),
						el(
							'div',
							{ className: 'anur-location-button', 'aria-hidden': true },
							'●'
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
