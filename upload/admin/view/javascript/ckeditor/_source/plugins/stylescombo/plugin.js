/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	CKEDITOR.plugins.add( 'stylescombo',
	{
		requires : [ 'richcombo', 'styles' ],

		init : function( editor )
		{
			var config = editor.config,
				lang = editor.lang.stylesCombo,
				pluginPath = this.path,
				styles;

			editor.ui.addRichCombo( 'Styles',
				{
					label : lang.label,
					title : lang.panelTitle,
					voiceLabel : lang.voiceLabel,
					className : 'cke_styles',
					multiSelect : true,

					panel :
					{
						css : [ CKEDITOR.getUrl( editor.skinPath + 'editor.css' ) ].concat( config.contentsCss ),
						voiceLabel : lang.panelVoiceLabel
					},

					init : function()
					{
						var combo = this,
							stylesSet = config.stylesCombo_stylesSet.split( ':' );

						var stylesSetPath = stylesSet[ 1 ] ?
								stylesSet.slice( 1 ).join( ':' ) :		// #4481
								CKEDITOR.getUrl( pluginPath + 'styles/' + stylesSet[ 0 ] + '.js' ) ;

						stylesSet = stylesSet[ 0 ];

						CKEDITOR.loadStylesSet( stylesSet, stylesSetPath, function( stylesDefinitions )
							{
								var style,
									styleName,
									stylesList = [];

								styles = {};

								// Put all styles into an Array.
								for ( var i = 0 ; i < stylesDefinitions.length ; i++ )
								{
									var styleDefinition = stylesDefinitions[ i ];

									styleName = styleDefinition.name;

									style = styles[ styleName ] = new CKEDITOR.style( styleDefinition );
									style._name = styleName;

									stylesList.push( style );
								}

								// Sorts the Array, so the styles get grouped
								// by type.
								stylesList.sort( sortStyles );

								// Loop over the Array, adding all items to the
								// combo.
								var lastType;
								for ( i = 0 ; i < stylesList.length ; i++ )
								{
									style = stylesList[ i ];
									styleName = style._name;

									var type = style.type;

									if ( type != lastType )
									{
										combo.startGroup( lang[ 'panelTitle' + String( type ) ] );
										lastType = type;
									}

									combo.add(
										styleName,
										style.type == CKEDITOR.STYLE_OBJECT ? styleName : buildPreview( style._.definition ),
										styleName );
								}

								combo.commit();

								combo.onOpen();
							});
					},

					onClick : function( value )
					{
						editor.focus();
						editor.fire( 'saveSnapshot' );

						var style = styles[ value ],
							selection = editor.getSelection();

						if ( style.type == CKEDITOR.STYLE_OBJECT )
						{
							var element = selection.getSelectedElement();
							if ( element )
								style.applyToObject( element );

							return;
						}

						var elementPath = new CKEDITOR.dom.elementPath( selection.getStartElement() );

						if ( style.type == CKEDITOR.STYLE_INLINE && style.checkActive( elementPath ) )
							style.remove( editor.document );
						else
							style.apply( editor.document );

						editor.fire( 'saveSnapshot' );
					},

					onRender : function()
					{
						editor.on( 'selectionChange', function( ev )
							{
								var currentValue = this.getValue();

								var elementPath = ev.data.path,
									elements = elementPath.elements;

								// For each element into the elements path.
								for ( var i = 0, element ; i < elements.length ; i++ )
								{
									element = elements[i];

									// Check if the element is removable by any of
									// the styles.
									for ( var value in styles )
									{
										if ( styles[ value ].checkElementRemovable( element, true ) )
										{
											if ( value != currentValue )
												this.setValue( value );
											return;
										}
									}
								}

								// If no styles match, just empty it.
								this.setValue( '' );
							},
							this);
					},

					onOpen : function()
					{
						if ( CKEDITOR.env.ie )
							editor.focus();

						var selection = editor.getSelection();

						var element = selection.getSelectedElement(),
							elementName = element && element.getName(),
							elementPath = new CKEDITOR.dom.elementPath( element || selection.getStartElement() );

						var counter = [ 0, 0, 0, 0 ];
						this.showAll();
						this.unmarkAll();
						for ( var name in styles )
						{
							var style = styles[ name ],
								type = style.type;

							if ( type == CKEDITOR.STYLE_OBJECT )
							{
								if ( element && style.element == elementName )
								{
									if ( style.checkElementRemovable( element, true ) )
										this.mark( name );

									counter[ type ]++;
								}
								else
									this.hideItem( name );
							}
							else
							{
								if ( style.checkActive( elementPath ) )
									this.mark( name );

								counter[ type ]++;
							}
						}

						if ( !counter[ CKEDITOR.STYLE_BLOCK ] )
							this.hideGroup( lang[ 'panelTitle' + String( CKEDITOR.STYLE_BLOCK ) ] );

						if ( !counter[ CKEDITOR.STYLE_INLINE ] )
							this.hideGroup( lang[ 'panelTitle' + String( CKEDITOR.STYLE_INLINE ) ] );

						if ( !counter[ CKEDITOR.STYLE_OBJECT ] )
							this.hideGroup( lang[ 'panelTitle' + String( CKEDITOR.STYLE_OBJECT ) ] );
					}
				});
		}
	});

	var stylesSets = {};

	CKEDITOR.addStylesSet = function( name, styles )
	{
		stylesSets[ name ] = styles;
	};

	CKEDITOR.loadStylesSet = function( name, url, callback )
	{
		var stylesSet = stylesSets[ name ];

		if ( stylesSet )
		{
			callback( stylesSet );
			return ;
		}

		CKEDITOR.scriptLoader.load( url, function()
			{
				callback( stylesSets[ name ] );
			});
	};

	function buildPreview( styleDefinition )
	{
		var html = [];

		var elementName = styleDefinition.element;

		// Avoid <bdo> in the preview.
		if ( elementName == 'bdo' )
			elementName = 'span';

		html = [ '<', elementName ];

		// Assign all defined attributes.
		var attribs	= styleDefinition.attributes;
		if ( attribs )
		{
			for ( var att in attribs )
			{
				html.push( ' ', att, '="', attribs[ att ], '"' );
			}
		}

		// Assign the style attribute.
		var cssStyle = CKEDITOR.style.getStyleText( styleDefinition );
		if ( cssStyle )
			html.push( ' style="', cssStyle, '"' );

		html.push( '>', styleDefinition.name, '</', elementName, '>' );

		return html.join( '' );
	}

	function sortStyles( styleA, styleB )
	{
		var typeA = styleA.type,
			typeB = styleB.type;

		return typeA == typeB ? 0 :
			typeA == CKEDITOR.STYLE_OBJECT ? -1 :
			typeB == CKEDITOR.STYLE_OBJECT ? 1 :
			typeB == CKEDITOR.STYLE_BLOCK ? 1 :
			-1;
	}
})();

/**
 * The "styles definition set" to load into the styles combo. The styles may
 * be defined in the page containing the editor, or can be loaded on demand
 * from an external file when opening the styles combo for the fist time. In
 * the second case, if this setting contains only a name, the styles definition
 * file will be loaded from the "styles" folder inside the stylescombo plugin
 * folder. Otherwise, this setting has the "name:url" syntax, making it
 * possible to set the URL from which loading the styles file.
 * @type string
 * @default 'default'
 * @example
 * // Load from the stylescombo styles folder (mystyles.js file).
 * config.stylesCombo_stylesSet = 'mystyles';
 * @example
 * // Load from a relative URL.
 * config.stylesCombo_stylesSet = 'mystyles:/editorstyles/styles.js';
 * @example
 * // Load from a full URL.
 * config.stylesCombo_stylesSet = 'mystyles:http://www.example.com/editorstyles/styles.js';
 */
CKEDITOR.config.stylesCombo_stylesSet = 'default';
