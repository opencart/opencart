/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'basicstyles',
{
	requires : [ 'styles', 'button' ],

	init : function( editor )
	{
		// All buttons use the same code to register. So, to avoid
		// duplications, let's use this tool function.
		var addButtonCommand = function( buttonName, buttonLabel, commandName, styleDefiniton )
		{
			var style = new CKEDITOR.style( styleDefiniton );

			editor.attachStyleStateChange( style, function( state )
				{
					editor.getCommand( commandName ).setState( state );
				});

			editor.addCommand( commandName, new CKEDITOR.styleCommand( style ) );

			editor.ui.addButton( buttonName,
				{
					label : buttonLabel,
					command : commandName
				});
		};

		var config = editor.config;
		var lang = editor.lang;

		addButtonCommand( 'Bold'		, lang.bold			, 'bold'		, config.coreStyles_bold );
		addButtonCommand( 'Italic'		, lang.italic		, 'italic'		, config.coreStyles_italic );
		addButtonCommand( 'Underline'	, lang.underline	, 'underline'	, config.coreStyles_underline );
		addButtonCommand( 'Strike'		, lang.strike		, 'strike'		, config.coreStyles_strike );
		addButtonCommand( 'Subscript'	, lang.subscript	, 'subscript'	, config.coreStyles_subscript );
		addButtonCommand( 'Superscript'	, lang.superscript	, 'superscript'	, config.coreStyles_superscript );
	}
});

// Basic Inline Styles.
CKEDITOR.config.coreStyles_bold			= { element : 'strong', overrides : 'b' };
CKEDITOR.config.coreStyles_italic		= { element : 'em', overrides : 'i' };
CKEDITOR.config.coreStyles_underline	= { element : 'u' };
CKEDITOR.config.coreStyles_strike		= { element : 'strike' };
CKEDITOR.config.coreStyles_subscript	= { element : 'sub' };
CKEDITOR.config.coreStyles_superscript	= { element : 'sup' };
