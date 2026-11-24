( function ( wp ) {
	'use strict';

	const el = wp.element.createElement;
	const Fragment = wp.element.Fragment;
	const registerBlockType = wp.blocks.registerBlockType;
	const __ = wp.i18n.__;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const PanelBody = wp.components.PanelBody;
	const TextControl = wp.components.TextControl;
	const TextareaControl = wp.components.TextareaControl;
	const ToggleControl = wp.components.ToggleControl;

	function textField( Component, label, value, attribute, setAttributes ) {
		return el( Component, {
			key: attribute,
			label: label,
			value: value || '',
			onChange: function ( nextValue ) {
				setAttributes( { [ attribute ]: nextValue } );
			},
		} );
	}

	registerBlockType( 'stai-annur/hero-title', {
		edit: function ( props ) {
			const attributes = props.attributes;
			const setAttributes = props.setAttributes;

			return el(
				Fragment,
				null,
				el(
					InspectorControls,
					null,
					el(
						PanelBody,
						{ title: __( 'Konten Hero Title', 'stai-annur' ), initialOpen: true },
						el( ToggleControl, {
							label: __( 'Tampilkan tulisan pertama', 'stai-annur' ),
							checked: attributes.showLabel,
							onChange: function ( showLabel ) {
								setAttributes( { showLabel: showLabel } );
							},
						} ),
						attributes.showLabel
							? textField( TextControl, __( 'Tulisan pertama', 'stai-annur' ), attributes.label, 'label', setAttributes )
							: null,
						el( ToggleControl, {
							label: __( 'Tampilkan judul H2', 'stai-annur' ),
							checked: attributes.showTitle,
							onChange: function ( showTitle ) {
								setAttributes( { showTitle: showTitle } );
							},
						} ),
						attributes.showTitle
							? el(
									Fragment,
									null,
									textField( TextControl, __( 'Judul baris pertama', 'stai-annur' ), attributes.titleFirst, 'titleFirst', setAttributes ),
									textField( TextControl, __( 'Judul baris kedua', 'stai-annur' ), attributes.titleSecond, 'titleSecond', setAttributes )
							  )
							: null,
						el( ToggleControl, {
							label: __( 'Tampilkan deskripsi', 'stai-annur' ),
							checked: attributes.showDescription,
							onChange: function ( showDescription ) {
								setAttributes( { showDescription: showDescription } );
							},
						} ),
						attributes.showDescription
							? textField( TextareaControl, __( 'Deskripsi', 'stai-annur' ), attributes.description, 'description', setAttributes )
							: null
					)
				),
				el(
					'div',
					{ className: 'anur-campus-intro' },
					el( 'div', { className: 'anur-campus-shape anur-campus-shape-1' } ),
					el( 'div', { className: 'anur-campus-shape anur-campus-shape-2' } ),
					el(
						'div',
						{ className: 'anur-campus-content' },
						attributes.showLabel
							? el(
									'div',
									{ className: 'anur-campus-label' },
									el( 'span', { className: 'anur-campus-label-star' }, '✦' ),
									el( 'span', null, attributes.label )
							  )
							: null,
						attributes.showTitle
							? el(
									'h2',
									{ className: 'anur-campus-title' },
									el( 'span', { className: 'anur-campus-title-dark' }, attributes.titleFirst ),
									el(
										'span',
										{ className: 'anur-campus-title-highlight' },
										el( 'br' ),
										attributes.titleSecond
									)
							  )
							: null,
						attributes.showDescription
							? el( 'p', { className: 'anur-campus-description' }, attributes.description )
							: null
					)
				)
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp );
