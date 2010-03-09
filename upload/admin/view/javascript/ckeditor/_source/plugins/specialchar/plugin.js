/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Special Character plugin
 */

CKEDITOR.plugins.add( 'specialchar',
{
	init : function( editor )
	{
		var pluginName = 'specialchar';

		// Register the dialog.
		CKEDITOR.dialog.add( pluginName, this.path + 'dialogs/specialchar.js' );

		// Register the command.
		editor.addCommand( pluginName, new CKEDITOR.dialogCommand( pluginName ) );

		// Register the toolbar button.
		editor.ui.addButton( 'SpecialChar',
			{
				label : editor.lang.specialChar.toolbar,
				command : pluginName
			});
	}
} );
