/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'colorbutton',
{
	requires : [ 'panelbutton', 'floatpanel', 'styles' ],

	init : function( editor )
	{
		var config = editor.config,
			lang = editor.lang.colorButton;

		var clickFn;

		if ( !CKEDITOR.env.hc )
		{
			addButton( 'TextColor', 'fore', lang.textColorTitle );
			addButton( 'BGColor', 'back', lang.bgColorTitle );
		}

		function addButton( name, type, title )
		{
			editor.ui.add( name, CKEDITOR.UI_PANELBUTTON,
				{
					label : title,
					title : title,
					className : 'cke_button_' + name.toLowerCase(),
					modes : { wysiwyg : 1 },

					panel :
					{
						css : [ CKEDITOR.getUrl( editor.skinPath + 'editor.css' ) ]
					},

					onBlock : function( panel, blockName )
					{
						var block = panel.addBlock( blockName );
						block.autoSize = true;
						block.element.addClass( 'cke_colorblock' );
						block.element.setHtml( renderColors( panel, type ) );

						var keys = block.keys;
						keys[ 39 ]	= 'next';					// ARROW-RIGHT
						keys[ 9 ]	= 'next';					// TAB
						keys[ 37 ]	= 'prev';					// ARROW-LEFT
						keys[ CKEDITOR.SHIFT + 9 ]	= 'prev';	// SHIFT + TAB
						keys[ 32 ]	= 'click';					// SPACE
					}
				});
		}


		function renderColors( panel, type )
		{
			var output = [],
				colors = config.colorButton_colors.split( ',' );

			var clickFn = CKEDITOR.tools.addFunction( function( color, type )
				{
					if ( color == '?' )
					{
						// TODO : Implement the colors dialog.
						// editor.openDialog( '' );
						return;
					}

					editor.focus();

					panel.hide();

					var style = new CKEDITOR.style( config['colorButton_' + type + 'Style'], color && { color : color } );

					editor.fire( 'saveSnapshot' );
					if ( color )
						style.apply( editor.document );
					else
						style.remove( editor.document );
					editor.fire( 'saveSnapshot' );
				});

			// Render the "Automatic" button.
			output.push(
				'<a class="cke_colorauto" _cke_focus=1 hidefocus=true' +
					' title="', lang.auto, '"' +
					' onclick="CKEDITOR.tools.callFunction(', clickFn, ',null,\'', type, '\');return false;"' +
					' href="javascript:void(\'', lang.auto, '\')">' +
					'<table cellspacing=0 cellpadding=0 width="100%">' +
						'<tr>' +
							'<td>' +
								'<span class="cke_colorbox" style="background-color:#000"></span>' +
							'</td>' +
							'<td colspan=7 align=center>',
								lang.auto,
							'</td>' +
						'</tr>' +
					'</table>' +
				'</a>' +
				'<table cellspacing=0 cellpadding=0 width="100%">' );

			// Render the color boxes.
			for ( var i = 0 ; i < colors.length ; i++ )
			{
				if ( ( i % 8 ) === 0 )
					output.push( '</tr><tr>' );

				var colorCode = colors[ i ];
				var colorLabel = editor.lang.colors[ colorCode ] || colorCode;
				output.push(
					'<td>' +
						'<a class="cke_colorbox" _cke_focus=1 hidefocus=true' +
							' title="', colorLabel, '"' +
							' onclick="CKEDITOR.tools.callFunction(', clickFn, ',\'#', colorCode, '\',\'', type, '\'); return false;"' +
							' href="javascript:void(\'', colorLabel, '\')">' +
							'<span class="cke_colorbox" style="background-color:#', colorCode, '"></span>' +
						'</a>' +
					'</td>' );
			}

			// Render the "More Colors" button.
			if ( config.colorButton_enableMore )
			{
				output.push(
					'</tr>' +
					'<tr>' +
						'<td colspan=8 align=center>' +
							'<a class="cke_colormore" _cke_focus=1 hidefocus=true' +
								' title="', lang.more, '"' +
								' onclick="CKEDITOR.tools.callFunction(', clickFn, ',\'?\',\'', type, '\');return false;"' +
								' href="javascript:void(\'', lang.more, '\')">',
								lang.more,
							'</a>' +
						'</td>' );	// It is later in the code.
			}

			output.push( '</tr></table>' );

			return output.join( '' );
		}
	}
});

/**
 * Whether to enable the "More Colors..." button in the color selectors.
 * @default false
 * @type Boolean
 * @example
 * config.colorButton_enableMore = false;
 */
CKEDITOR.config.colorButton_enableMore = false;

/**
 * Defines the colors to be displayed in the color selectors. It's a string
 * containing the hexadecimal notation for HTML colors, without the "#" prefix.
 * @type String
 * @default '000,800000,8B4513,2F4F4F,008080,000080,4B0082,696969,B22222,A52A2A,DAA520,006400,40E0D0,0000CD,800080,808080,F00,FF8C00,FFD700,008000,0FF,00F,EE82EE,A9A9A9,FFA07A,FFA500,FFFF00,00FF00,AFEEEE,ADD8E6,DDA0DD,D3D3D3,FFF0F5,FAEBD7,FFFFE0,F0FFF0,F0FFFF,F0F8FF,E6E6FA,FFF'
 * @example
 * // Brazil colors only.
 * config.colorButton_colors = '00923E,F8C100,28166F';
 */
CKEDITOR.config.colorButton_colors =
	'000,800000,8B4513,2F4F4F,008080,000080,4B0082,696969,' +
	'B22222,A52A2A,DAA520,006400,40E0D0,0000CD,800080,808080,' +
	'F00,FF8C00,FFD700,008000,0FF,00F,EE82EE,A9A9A9,' +
	'FFA07A,FFA500,FFFF00,00FF00,AFEEEE,ADD8E6,DDA0DD,D3D3D3,' +
	'FFF0F5,FAEBD7,FFFFE0,F0FFF0,F0FFFF,F0F8FF,E6E6FA,FFF';

/**
 * Holds the style definition to be used to apply the text foreground color.
 * @type Object
 * @example
 * // This is basically the default setting value.
 * config.colorButton_foreStyle =
 *     {
 *         element : 'span',
 *         styles : { 'color' : '#(color)' }
 *     };
 */
CKEDITOR.config.colorButton_foreStyle =
	{
		element		: 'span',
		styles		: { 'color' : '#(color)' },
		overrides	: [ { element : 'font', attributes : { 'color' : null } } ]
	};

/**
 * Holds the style definition to be used to apply the text background color.
 * @type Object
 * @example
 * // This is basically the default setting value.
 * config.colorButton_backStyle =
 *     {
 *         element : 'span',
 *         styles : { 'background-color' : '#(color)' }
 *     };
 */
CKEDITOR.config.colorButton_backStyle =
	{
		element		: 'span',
		styles		: { 'background-color' : '#(color)' }
	};
