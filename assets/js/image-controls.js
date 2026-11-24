( function ( wp ) {
	'use strict';

	const el = wp.element.createElement;
	const Fragment = wp.element.Fragment;
	const __ = wp.i18n.__;
	const addFilter = wp.hooks.addFilter;
	const createHigherOrderComponent = wp.compose.createHigherOrderComponent;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const PanelBody = wp.components.PanelBody;
	const SelectControl = wp.components.SelectControl;
	const TextControl = wp.components.TextControl;
	const ToggleControl = wp.components.ToggleControl;

	const imageBlocks = {
		'stai-annur/hero-banner': 'large',
		'stai-annur/hero-banner-2': 'large',
		'stai-annur/hero-slider': 'large',
		'stai-annur/hero-card-slider': 'thumbnail',
		'stai-annur/hero-card-slider-2': 'thumbnail',
		'stai-annur/hero-post-slider': 'medium_large',
	};

	const imageSizeOptions = [
		{ label: __( 'Thumbnail (150 px)', 'stai-annur' ), value: 'thumbnail' },
		{ label: __( 'Medium (300 px)', 'stai-annur' ), value: 'medium' },
		{ label: __( 'Medium Large (768 px)', 'stai-annur' ), value: 'medium_large' },
		{ label: __( 'Large (1024 px)', 'stai-annur' ), value: 'large' },
		{ label: __( 'Full (asli)', 'stai-annur' ), value: 'full' },
	];

	const withImageSizeControl = createHigherOrderComponent( function ( BlockEdit ) {
		return function ( props ) {
			const defaultSize = imageBlocks[ props.name ];

			if ( ! defaultSize ) {
				return el( BlockEdit, props );
			}

			const useCustomImageSize = props.attributes.useCustomImageSize === true;

			return el(
				Fragment,
				null,
				el( BlockEdit, props ),
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Ukuran gambar', 'stai-annur' ), initialOpen: false },
						el( SelectControl, {
							label: __( 'Ukuran sumber gambar', 'stai-annur' ),
							value: props.attributes.imageSize || defaultSize,
							options: imageSizeOptions,
							disabled: useCustomImageSize,
							help: __( 'Mengatur resolusi sumber semua gambar di widget ini. Media Library, Unsplash, dan gambar kampus bawaan mendapat ukuran responsif; untuk URL eksternal lain, unggah gambar ke Media Library agar ukurannya dapat dioptimalkan.', 'stai-annur' ),
							onChange: function ( imageSize ) {
								props.setAttributes( { imageSize: imageSize } );
							},
						} ),
						el( ToggleControl, {
							label: __( 'Gunakan ukuran custom', 'stai-annur' ),
							checked: useCustomImageSize,
							onChange: function ( enabled ) {
								props.setAttributes( { useCustomImageSize: enabled } );
							},
						} ),
						useCustomImageSize
							? el(
									Fragment,
									null,
									el( TextControl, {
										type: 'number',
										min: 1,
										max: 3000,
										step: 1,
										label: __( 'Lebar gambar (px)', 'stai-annur' ),
										value: String( props.attributes.customImageWidth === undefined ? 147 : props.attributes.customImageWidth ),
										onChange: function ( width ) {
											const parsedWidth = parseInt( width, 10 );
											props.setAttributes( {
												customImageWidth: Number.isFinite( parsedWidth ) ? Math.max( 1, Math.min( 3000, parsedWidth ) ) : 0,
											} );
										},
									} ),
									el( TextControl, {
										type: 'number',
										min: 1,
										max: 3000,
										step: 1,
										label: __( 'Tinggi gambar (px)', 'stai-annur' ),
										value: String( props.attributes.customImageHeight === undefined ? 98 : props.attributes.customImageHeight ),
										onChange: function ( height ) {
											const parsedHeight = parseInt( height, 10 );
											props.setAttributes( {
												customImageHeight: Number.isFinite( parsedHeight ) ? Math.max( 1, Math.min( 3000, parsedHeight ) ) : 0,
											} );
										},
									} ),
									el( 'p', { className: 'components-base-control__help' }, __( 'Unsplash dibuat sesuai dimensi ini; Media Library memakai ukuran responsif terdekat. URL eksternal tanpa dukungan resize tetap memakai file aslinya.', 'stai-annur' ) )
							  )
							: null
					)
				)
			);
		};
	}, 'withSTAIImageSizeControl' );

	addFilter( 'editor.BlockEdit', 'stai-annur/image-size-control', withImageSizeControl );
} )( window.wp );
