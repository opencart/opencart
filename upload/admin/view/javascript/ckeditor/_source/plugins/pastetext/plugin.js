/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Paste as plain text plugin
 */

(function()
{
	// The pastetext command definition.
	var pasteTextCmd =
	{
		exec : function( editor )
		{
			// We use getClipboardData just to test if the clipboard access has
			// been granted by the user.
			if ( CKEDITOR.getClipboardData() === false || !window.clipboardData )
			{
				editor.openDialog( 'pastetext' );
				return;
			}

			editor.insertText( window.clipboardData.getData( 'Text' ) );
		}
	};

	// Register the plugin.
	CKEDITOR.plugins.add( 'pastetext',
	{
		init : function( editor )
		{
			var commandName = 'pastetext',
				command = editor.addCommand( commandName, pasteTextCmd );

			editor.ui.addButton( 'PasteText',
				{
					label : editor.lang.pasteText.button,
					command : commandName
				});

			CKEDITOR.dialog.add( commandName, CKEDITOR.getUrl( this.path + 'dialogs/pastetext.js' ) );

			if ( editor.config.forcePasteAsPlainText )
			{
				editor.on( 'beforePaste', function( event )
					{
						if ( editor.mode == "wysiwyg" )
						{
							setTimeout( function() { command.exec(); }, 0 );
							event.cancel();
						}
					},
					null, null, 20 );
			}
		},
		requires : [ 'clipboard' ]
	});

	var clipboardDiv;

	CKEDITOR.getClipboardData = function()
	{
		if ( !CKEDITOR.env.ie )
			return false;

		var doc = CKEDITOR.document,
			body = doc.getBody();

		if ( !clipboardDiv )
		{
			clipboardDiv = doc.createElement( 'div',
				{
					attributes :
						{
							id: 'cke_hiddenDiv'
						},
					styles :
						{
							position : 'absolute',
							visibility : 'hidden',
							overflow : 'hidden',
							width : '1px',
							height : '1px'
						}
				});

			clipboardDiv.setHtml( '' );

			clipboardDiv.appendTo( body );
		}

		// The "enabled" flag is used to check whether the paste operation has
		// been completed (the onpaste event has been fired).
		var	enabled = false;
		var setEnabled = function()
		{
			enabled = true;
		};

		body.on( 'paste', setEnabled );

		// Create a text range and move it inside the div.
		var textRange = body.$.createTextRange();
		textRange.moveToElementText( clipboardDiv.$ );

		// The execCommand in will fire the "onpaste", only if the
		// security settings are enabled.
		textRange.execCommand( 'Paste' );

		// Get the DIV html and reset it.
		var html = clipboardDiv.getHtml();
		clipboardDiv.setHtml( '' );

		body.removeListener( 'paste', setEnabled );

		// Return the HTML or false if not enabled.
		return enabled && html;
	};
})();

CKEDITOR.editor.prototype.insertText = function( text )
{
	text = CKEDITOR.tools.htmlEncode( text );

	// TODO: Replace the following with fill line break processing (see V2).
	text = text.replace( /(?:\r\n)|\n|\r/g, '<br>' );

	this.insertHtml( text );
};

/**
 * Whether to force all pasting operations to insert on plain text into the
 * editor, loosing any formatting information possibly available in the source
 * text.
 * @type Boolean
 * @default false
 * @example
 * config.forcePasteAsPlainText = true;
 */
CKEDITOR.config.forcePasteAsPlainText = false;
