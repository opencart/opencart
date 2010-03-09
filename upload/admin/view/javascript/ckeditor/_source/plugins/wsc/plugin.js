/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Spell checker
 */

// Register a plugin named "wsc".
CKEDITOR.plugins.add( 'wsc',
{
	init : function( editor )
	{
		var commandName = 'checkspell';

		var command = editor.addCommand( commandName, new CKEDITOR.dialogCommand( commandName ) );

		// SpellChecker doesn't work in Opera and with custom domain
		command.modes = { wysiwyg : ( !CKEDITOR.env.opera && document.domain == window.location.hostname ) };

		editor.ui.addButton( 'SpellChecker',
			{
				label : editor.lang.spellCheck.toolbar,
				command : commandName
			});
		CKEDITOR.dialog.add( commandName, this.path + 'dialogs/wsc.js' );
	}
});

CKEDITOR.config.wsc_customerId			= CKEDITOR.config.wsc_customerId || '1:ua3xw1-2XyGJ3-GWruD3-6OFNT1-oXcuB1-nR6Bp4-hgQHc-EcYng3-sdRXG3-NOfFk' ;
CKEDITOR.config.wsc_customLoaderScript	= CKEDITOR.config.wsc_customLoaderScript || null;
