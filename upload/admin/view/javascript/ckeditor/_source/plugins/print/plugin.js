/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Print Plugin
 */

CKEDITOR.plugins.add( 'print',
{
	init : function( editor )
	{
		var pluginName = 'print';

		// Register the command.
		var command = editor.addCommand( pluginName, CKEDITOR.plugins.print );

		// Register the toolbar button.
		editor.ui.addButton( 'Print',
			{
				label : editor.lang.print,
				command : pluginName
			});
	}
} );

CKEDITOR.plugins.print =
{
	exec : function( editor )
	{
		if ( CKEDITOR.env.opera )
			return;
		else if ( CKEDITOR.env.gecko )
			editor.window.$.print();
		else
			editor.document.$.execCommand( "Print" );
	},
	canUndo : false,
	modes : { wysiwyg : !( CKEDITOR.env.opera ) }		// It is imposible to print the inner document in Opera.
};
