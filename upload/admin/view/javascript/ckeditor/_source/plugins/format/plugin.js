/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'format',
{
	requires : [ 'richcombo', 'styles' ],

	init : function( editor )
	{
		var config = editor.config,
			lang = editor.lang.format;

		// Gets the list of tags from the settings.
		var tags = config.format_tags.split( ';' );

		// Create style objects for all defined styles.
		var styles = {};
		for ( var i = 0 ; i < tags.length ; i++ )
		{
			var tag = tags[ i ];
			styles[ tag ] = new CKEDITOR.style( config[ 'format_' + tag ] );
		}

		editor.ui.addRichCombo( 'Format',
			{
				label : lang.label,
				title : lang.panelTitle,
				voiceLabel : lang.voiceLabel,
				className : 'cke_format',
				multiSelect : false,

				panel :
				{
					css : [ CKEDITOR.getUrl( editor.skinPath + 'editor.css' ) ].concat( config.contentsCss ),
					voiceLabel : lang.panelVoiceLabel
				},

				init : function()
				{
					this.startGroup( lang.panelTitle );

					for ( var tag in styles )
					{
						var label = lang[ 'tag_' + tag ];

						// Add the tag entry to the panel list.
						this.add( tag, '<' + tag + '>' + label + '</' + tag + '>', label );
					}
				},

				onClick : function( value )
				{
					editor.focus();
					editor.fire( 'saveSnapshot' );

					styles[ value ].apply( editor.document );

					editor.fire( 'saveSnapshot' );
				},

				onRender : function()
				{
					editor.on( 'selectionChange', function( ev )
						{
							var currentTag = this.getValue();

							var elementPath = ev.data.path;

							for ( var tag in styles )
							{
								if ( styles[ tag ].checkActive( elementPath ) )
								{
									if ( tag != currentTag )
										this.setValue( tag, editor.lang.format[ 'tag_' + tag ] );
									return;
								}
							}

							// If no styles match, just empty it.
							this.setValue( '' );
						},
						this);
				}
			});
	}
});

/**
 * A list of semi colon separated style names (by default tags) representing
 * the style definition for each entry to be displayed in the Format combo in
 * the toolbar. Each entry must have its relative definition configuration in a
 * setting named "format_(tagName)". For example, the "p" entry has its
 * definition taken from config.format_p.
 * @type String
 * @default 'p;h1;h2;h3;h4;h5;h6;pre;address;div'
 * @example
 * config.format_tags = 'p;h2;h3;pre'
 */
CKEDITOR.config.format_tags = 'p;h1;h2;h3;h4;h5;h6;pre;address;div';

/**
 * The style definition to be used to apply the "Normal" format.
 * @type Object
 * @default { element : 'p' }
 * @example
 * config.format_p = { element : 'p', attributes : { class : 'normalPara' } };
 */
CKEDITOR.config.format_p = { element : 'p' };

/**
 * The style definition to be used to apply the "Normal (DIV)" format.
 * @type Object
 * @default { element : 'div' }
 * @example
 * config.format_div = { element : 'div', attributes : { class : 'normalDiv' } };
 */
CKEDITOR.config.format_div = { element : 'div' };

/**
 * The style definition to be used to apply the "Formatted" format.
 * @type Object
 * @default { element : 'pre' }
 * @example
 * config.format_pre = { element : 'pre', attributes : { class : 'code' } };
 */
CKEDITOR.config.format_pre = { element : 'pre' };

/**
 * The style definition to be used to apply the "Address" format.
 * @type Object
 * @default { element : 'address' }
 * @example
 * config.format_address = { element : 'address', attributes : { class : 'styledAddress' } };
 */
CKEDITOR.config.format_address = { element : 'address' };

/**
 * The style definition to be used to apply the "Heading 1" format.
 * @type Object
 * @default { element : 'h1' }
 * @example
 * config.format_h1 = { element : 'h1', attributes : { class : 'contentTitle1' } };
 */
CKEDITOR.config.format_h1 = { element : 'h1' };

/**
 * The style definition to be used to apply the "Heading 1" format.
 * @type Object
 * @default { element : 'h2' }
 * @example
 * config.format_h2 = { element : 'h2', attributes : { class : 'contentTitle2' } };
 */
CKEDITOR.config.format_h2 = { element : 'h2' };

/**
 * The style definition to be used to apply the "Heading 1" format.
 * @type Object
 * @default { element : 'h3' }
 * @example
 * config.format_h3 = { element : 'h3', attributes : { class : 'contentTitle3' } };
 */
CKEDITOR.config.format_h3 = { element : 'h3' };

/**
 * The style definition to be used to apply the "Heading 1" format.
 * @type Object
 * @default { element : 'h4' }
 * @example
 * config.format_h4 = { element : 'h4', attributes : { class : 'contentTitle4' } };
 */
CKEDITOR.config.format_h4 = { element : 'h4' };

/**
 * The style definition to be used to apply the "Heading 1" format.
 * @type Object
 * @default { element : 'h5' }
 * @example
 * config.format_h5 = { element : 'h5', attributes : { class : 'contentTitle5' } };
 */
CKEDITOR.config.format_h5 = { element : 'h5' };

/**
 * The style definition to be used to apply the "Heading 1" format.
 * @type Object
 * @default { element : 'h6' }
 * @example
 * config.format_h6 = { element : 'h6', attributes : { class : 'contentTitle6' } };
 */
CKEDITOR.config.format_h6 = { element : 'h6' };
