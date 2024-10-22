
wp.domReady( () => {

		wp.blocks.unregisterBlockVariation( 'core/columns', [ 'three-columns-wider-center', 'two-columns-one-third-two-thirds', 'two-columns-two-thirds-one-third' ]);



    // //Section
    // wp.blocks.registerBlockVariation( 'core/group',
    //   {
    //     name: 'group-section',
    //     title: 'Section',
		// 		isDefault: true,
    //     attributes: {
    //       className: 'section-content',
    //     },
    //   }
    // );
		//
    // //Section grise
    // wp.blocks.registerBlockVariation( 'core/group',
    //   {
    //     name: 'group-section--white',
    //     title: 'Section blanche',
    //     attributes: {
    //       // customBackgroundColor: '#f4f5f9',
    //       className: 'section-content section-content--white',
    //     },
    //   }
    // );


	  // prevent click on cta
	  jQuery(document).on('click', '.cta', function(e){
	    e.preventDefault();
	    e.stopPropagation();
	  })

} );

// Sections
( function() {
	var registerBlockType = wp.blocks.registerBlockType;
	var el = wp.element.createElement;
	var InnerBlocks = wp.blockEditor.InnerBlocks;

	const iconGroup = el('svg', { width: 24, height: 24 },
	  el('path', { d: "M18 4h-7c-1.1 0-2 .9-2 2v3H6c-1.1 0-2 .9-2 2v7c0 1.1.9 2 2 2h7c1.1 0 2-.9 2-2v-3h3c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm-4.5 14c0 .3-.2.5-.5.5H6c-.3 0-.5-.2-.5-.5v-7c0-.3.2-.5.5-.5h3V13c0 1.1.9 2 2 2h2.5v3zm0-4.5H11c-.3 0-.5-.2-.5-.5v-2.5H13c.3 0 .5.2.5.5v2.5zm5-.5c0 .3-.2.5-.5.5h-3V11c0-1.1-.9-2-2-2h-2.5V6c0-.3.2-.5.5-.5h7c.3 0 .5.2.5.5v7z" } )
	);


	registerBlockType( 'mw/container', {
		title: 'Section',
		icon: iconGroup,
		category: 'common',
		parent: ['core/post-content'],
		attributes: {
			bgColor: {
				type: 'string',
				default: 'transparent'
			}
		},
		// edit: dataSelector( function( props ) {
		edit: ( props ) => {

			const ALLOWED_BLOCKS = wp.blocks.getBlockTypes().map(block => block.name).filter(blockName => blockName !== 'mw/container');
			// console.log('edit block ALLOWED_BLOCKS', ALLOWED_BLOCKS);
			const __ = wp.i18n.__; // The __() for internationalization.

			const { BlockControls, InspectorAdvancedControls } = wp.editor;
			const { InspectorControls } = wp.blockEditor;
			const { Fragment } = wp.element;

			const el = wp.element.createElement;

			const { PanelBody, RadioControl } = wp.components;

			var attributes = props.attributes;

			var bgColorClass = function(){
				return attributes.bgColor != 'transparent' ? ' section-content--' + attributes.bgColor : '';
			}

			var onChangeBgColor = function (bgColor) {
          return props.setAttributes({
              bgColor: bgColor
          });
      };


			return [
				el(
					InspectorControls,
					{ key: 'inspector' },
					el(
						PanelBody,
						{},
						el(
							RadioControl,
							{
								label: __('Couleur de fond', 'busmagique'),
								selected: attributes.bgColor,
								options: [
									{ label: 'Aucune', value: 'transparent' },
									{ label: 'Blanche', value: 'white' },
								],
								onChange: onChangeBgColor,
							},
						)
					)
				),
				el(
					'div',
					{ className: 'wp-block-group section-content'+bgColorClass() },
					el(
						'div',
						{ className: 'wp-block-group__inner-container'},
						el( InnerBlocks, {
							allowedBlocks: ALLOWED_BLOCKS,
						} )
					),
				),
			];
		},

		save( props ) {
			var bgColor = props.attributes.bgColor != 'transparent' ? ' section-content--' + props.attributes.bgColor : '';

			return el(
				'div',
				{ className: 'wp-block-group section-content'+bgColor },
				el(
					'div',
					{ className: 'wp-block-group__inner-container'},
					el( InnerBlocks.Content )
				)
			);
		},
	} );


} )();

function blocksFilterAttributes( settings, name ) {

		// alert('gniiiiiii');
	// const blocksParents = [ 'mw/container', 'core/column', 'core/media-text' ];
	const blocksParents = [ 'mw/container', 'core/media-text' ];
	const blocksChildren = [
		'core/image',
		'core/embed',
		'core/paragraph',
		'core/heading',
		'core/list',
		// 'core/quote',
		// 'core/media-text',
		'acf/cta-button',
		// 'acf/icon',
		'acf/video-with-image',
		'acf/gallery-mw',
	];
//
    if ( name == 'core/heading' ) {
			if( typeof settings.attributes !== 'undefined' ){
				// add hyphen option
				settings.attributes = Object.assign( settings.attributes, {
					hasHyphen:{
						type: 'boolean',
						default: false,
					},
				});

				// remove color options, fontSize and level 1 (try?)
				settings.supports = Object.assign( settings.supports, {
					color: false,
					__experimentalColor: false,
					__experimentalFontSize: false,
					__experimentalSelector: {
						'core/heading/h2': "h2",
						'core/heading/h3': "h3",
						'core/heading/h4': "h4",
						// 'core/heading/h5': "h5",
						// 'core/heading/h6': "h6",
					}
				});

			}
    }
		if ( name == 'core/group' || name == 'core/media-text' ) {

			settings.parent = [ 'mw/container', 'core/column' ];

			if( typeof settings.supports !== 'undefined' ){

				settings.supports = Object.assign( settings.supports, {
					color: false,
					__experimentalColor: false,
				});

			}

	    // if ( name == 'core/media-text' ) {
			// 	if( typeof settings.attributes !== 'undefined' ){
			// 		// add hyphen option
			// 		settings.attributes = Object.assign( settings.attributes, {
			// 			// legend:{
			// 			// 	type: 'string',
      //       //   source: 'html',
      //       //   selector: 'figure figcaption'
			// 			// },
			// 			videoURL:{
			// 				type: 'string'
			// 			},
      //       // href:{
      //       //   type: "string",
      //       //   attribute: "href",
      //       //   selector: "figure a.customLink",
      //       //   source: "attribute",
      //       // }
			// 		});
			// 	}
			// }
		}
		// limit children blocks to parent blocks
		if ( blocksChildren.includes( name ) ) {

			settings.parent = blocksParents;

			if( typeof settings.supports !== 'undefined' ){

				settings.supports = Object.assign( settings.supports, {
					color: false,
					__experimentalColor: false,
					__experimentalFeatures: {
						typography: {
							dropCap: false
						}
					}
				});

			}
		}
		// limit columns block to be in container block
		if ( name == 'core/columns' ) {

			settings.parent = [ 'mw/container' ];

			//store icon of columns
      // console.log('core/columns variations', settings.variations);
			// jQuery.each(settings.variations, function(i,variation){
			// 	if(variation.name == 'two-columns-one-third-two-thirds') {
			// 		iconTwoColumnsOneThirdTwoThirds = wp.element.cloneElement(variation.icon);
      //     console.log('iconTwoColumnsOneThirdTwoThirds', iconTwoColumnsOneThirdTwoThirds);
			// 	}
			// 	if(variation.name == 'two-columns-two-thirds-one-third') {
			// 		iconTwoColumnsTwoThirdsOneThird = wp.element.cloneElement(variation.icon);
			// 	}
			// });

		}

    return settings;

}
wp.hooks.addFilter(
    'blocks.registerBlockType',
    'mw/busmagique',
    blocksFilterAttributes
);
