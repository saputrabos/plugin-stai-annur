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

	function checkIcon() {
		return el(
			'svg',
			{ viewBox: '0 0 24 24', 'aria-hidden': true, focusable: false },
			el( 'path', { d: 'm5 12 4 4L19 6' } )
		);
	}

	function arrowIcon( direction ) {
		const path = direction === 'left' ? 'm15 18-6-6 6-6' : 'm9 18 6-6-6-6';

		return el(
			'svg',
			{ viewBox: '0 0 24 24', 'aria-hidden': true, focusable: false },
			el( 'path', { d: path } )
		);
	}

	registerBlockType( 'stai-annur/hero-card-slider', {
		edit: function ( props ) {
			const attributes = props.attributes;
			const setAttributes = props.setAttributes;
			const slides = Array.isArray( attributes.slides ) ? attributes.slides : [];
			const previewSlide = slides.length ? slides[ 0 ] : null;

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Pengaturan slider', 'stai-annur' ), initialOpen: true },
						textField( __( 'Judul bagian', 'stai-annur' ), attributes.title, function ( title ) {
							setAttributes( { title: title } );
						} )
					),
					el(
						PanelBody,
						{ title: __( 'Kartu keunggulan', 'stai-annur' ), initialOpen: true },
						slides.map( function ( slide, index ) {
							return el(
								PanelBody,
								{
									key: 'benefit-' + index,
									title: slide.name || __( 'Kartu tanpa judul', 'stai-annur' ),
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
													? __( 'Ganti ikon', 'stai-annur' )
													: __( 'Pilih ikon', 'stai-annur' )
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
											__( 'Hapus ikon', 'stai-annur' )
									  )
									: null,
								textField( __( 'Teks alternatif', 'stai-annur' ), slide.alt, function ( alt ) {
									updateSlide( slides, index, { alt: alt }, setAttributes );
								} ),
								textField( __( 'Nama keunggulan', 'stai-annur' ), slide.name, function ( name ) {
									updateSlide( slides, index, { name: name }, setAttributes );
								} ),
								textField( __( 'Detail (pisahkan dengan |)', 'stai-annur' ), slide.detail, function ( detail ) {
									updateSlide( slides, index, { detail: detail }, setAttributes );
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
									__( 'Hapus kartu', 'stai-annur' )
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
												name: __( 'Keunggulan baru', 'stai-annur' ),
												detail: '',
											},
										] ),
									} );
								},
							},
							__( 'Tambah kartu', 'stai-annur' )
						)
					)
				),
				el(
					'div',
					{ className: 'anur-benefits-widget' },
					el(
						'div',
						{ className: 'anur-benefits-header' },
						el(
							'div',
							{ className: 'anur-benefits-heading' },
							el( 'h2', { className: 'anur-benefits-title' }, attributes.title )
						),
						el(
							'div',
							{ className: 'anur-benefits-controls' },
							el( 'button', { type: 'button', className: 'anur-benefits-arrow', disabled: true, 'aria-hidden': true }, arrowIcon( 'left' ) ),
							el( 'button', { type: 'button', className: 'anur-benefits-arrow', disabled: true, 'aria-hidden': true }, arrowIcon( 'right' ) )
						)
					),
					previewSlide
						? el(
								'div',
								{ className: 'anur-benefits-slider' },
								el(
									'div',
									{ className: 'anur-benefits-track' },
									el(
										'div',
										{ className: 'anur-benefit-slide' },
										el(
											'div',
											{ className: 'anur-benefit-card' },
											el(
												'div',
												{ className: 'anur-benefit-icon' },
												previewSlide.imageUrl
													? el( 'img', { src: previewSlide.imageUrl, alt: previewSlide.alt || previewSlide.name || '' } )
													: el( 'span', { className: 'anur-benefit-image-placeholder' }, __( 'Pilih ikon', 'stai-annur' ) )
											),
											el(
												'div',
												{ className: 'anur-benefit-info' },
												el( 'div', { className: 'anur-benefit-name' }, previewSlide.name ),
												el( 'div', { className: 'anur-benefit-detail' }, ( previewSlide.detail || '' ).split( '|' ).map( function ( item, index ) {
													return el(
														Fragment,
														{ key: 'detail-' + index },
														index ? el( 'b', null, '•' ) : null,
														el( 'span', null, item.trim() )
													);
												} ) )
											),
											el( 'div', { className: 'anur-benefit-check', 'aria-hidden': true }, checkIcon() )
										)
									)
								)
						  )
						: el( 'p', { className: 'anur-benefit-empty' }, __( 'Tambahkan kartu keunggulan dari panel pengaturan.', 'stai-annur' ) )
				)
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp );
