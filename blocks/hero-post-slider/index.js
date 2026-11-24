( function ( wp ) {
	'use strict';

	const el = wp.element.createElement;
	const Fragment = wp.element.Fragment;
	const __ = wp.i18n.__;
	const InspectorControls = wp.blockEditor.InspectorControls;
	const PanelBody = wp.components.PanelBody;
	const RangeControl = wp.components.RangeControl;
	const TextControl = wp.components.TextControl;
	const ServerSideRender = wp.serverSideRender;

	wp.blocks.registerBlockType( 'stai-annur/hero-post-slider', {
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
						{ title: __( 'Pengaturan artikel', 'stai-annur' ), initialOpen: true },
						el( TextControl, {
							label: __( 'Judul bagian', 'stai-annur' ),
							value: attributes.sectionTitle || '',
							onChange: function ( sectionTitle ) {
								setAttributes( { sectionTitle: sectionTitle } );
							},
						} ),
						el( RangeControl, {
							label: __( 'Jumlah artikel', 'stai-annur' ),
							value: attributes.postsToShow || 12,
							min: 1,
							max: 12,
							onChange: function ( postsToShow ) {
								setAttributes( { postsToShow: postsToShow } );
							},
						} )
					)
				),
				el( ServerSideRender, {
					block: 'stai-annur/hero-post-slider',
					attributes: attributes,
					httpMethod: 'POST',
				} )
			);
		},
		save: function () {
			return null;
		},
	} );
} )( window.wp );
