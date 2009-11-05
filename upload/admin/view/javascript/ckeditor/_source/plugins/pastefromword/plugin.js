/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'pastefromword',
{
	init : function( editor )
	{
		// Register the command.
		editor.addCommand( 'pastefromword', new CKEDITOR.dialogCommand( 'pastefromword' ) );

		// Register the toolbar button.
		editor.ui.addButton( 'PasteFromWord',
			{
				label : editor.lang.pastefromword.toolbar,
				command : 'pastefromword'
			} );

		// Register the dialog.
		CKEDITOR.dialog.add( 'pastefromword', this.path + 'dialogs/pastefromword.js' );
	}
} );

/**
 * Whether the "Ignore font face definitions" checkbox is enabled by default in
 * the Paste from Word dialog.
 * @type Boolean
 * @default true
 * @example
 * config.pasteFromWordIgnoreFontFace = false;
 */
CKEDITOR.config.pasteFromWordIgnoreFontFace = true;

/**
 * Whether the "Remove styles definitions" checkbox is enabled by default in
 * the Paste from Word dialog.
 * @type Boolean
 * @default false
 * @example
 * config.pasteFromWordRemoveStyle = true;
 */
CKEDITOR.config.pasteFromWordRemoveStyle = false;

/**
 * Whether to keep structure markup (&lt;h1&gt;, &lt;h2&gt;, etc.) or replace
 * it with elements that create more similar pasting results when pasting
 * content from Microsoft Word into the Paste from Word dialog.
 * @type Boolean
 * @default false
 * @example
 * config.pasteFromWordKeepsStructure = true;
 */
CKEDITOR.config.pasteFromWordKeepsStructure = false;
