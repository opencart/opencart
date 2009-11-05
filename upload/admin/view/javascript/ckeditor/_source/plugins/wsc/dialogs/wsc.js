/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.dialog.add( 'checkspell', function( editor )
{
	var number = CKEDITOR.tools.getNextNumber(),
		iframeId = 'cke_frame_' + number,
		textareaId = 'cke_data_' + number,
		errorBoxId = 'cke_error_' + number,
		interval,
		protocol = document.location.protocol || 'http:',
		errorMsg = editor.lang.spellCheck.notAvailable;

	var pasteArea = '<textarea'+
			' style="display: none"' +
			' id="' + textareaId + '"' +
			' rows="10"' +
			' cols="40">' +
		' </textarea><div' +
			' id="' + errorBoxId + '"' +
			' style="display:none;color:red;font-size:16px;font-weight:bold;padding-top:160px;text-align:center;z-index:11;">' +
		'</div><iframe' +
			' src=""' +
			' style="width:485px;background-color:#f1f1e3;height:380px"' +
			' frameborder="0"' +
			' name="' + iframeId + '"' +
			' id="' + iframeId + '"' +
			' allowtransparency="1">' +
		'</iframe>';

	var wscCoreUrl = editor.config.wsc_customLoaderScript || ( protocol +
			'//loader.spellchecker.net/sproxy_fck/sproxy.php'
			+ '?plugin=fck2'
			+ '&customerid=' + editor.config.wsc_customerId
			+ '&cmd=script&doc=wsc&schema=22'
		);

	if ( editor.config.wsc_customLoaderScript )
		errorMsg += '<p style="color:#000;font-size:11px;font-weight: normal;text-align:center;padding-top:10px">' +
			editor.lang.spellCheck.errorLoading.replace( /%s/g, editor.config.wsc_customLoaderScript ) + '</p>';

	function burnSpelling( dialog, errorMsg )
	{
		var i = 0;
		return function ()
		{
			if ( typeof( window.doSpell ) == 'function' )
			{
				//Call from window.setInteval expected at once.
				if ( typeof( interval ) != 'undefined' )
					window.clearInterval( interval );

				initAndSpell( dialog );
			}
			else if ( i++ == 180 )								// Timeout: 180 * 250ms = 45s.
				window._cancelOnError( errorMsg );
		};
	}

	window._cancelOnError = function( m )
	{
		if ( typeof( window.WSC_Error ) == 'undefined' )
		{
			CKEDITOR.document.getById( iframeId ).setStyle( 'display', 'none' );
			var errorBox = CKEDITOR.document.getById( errorBoxId );
			errorBox.setStyle( 'display', 'block' );
			errorBox.setHtml( m || editor.lang.spellCheck.notAvailable );
		}
	};

	function initAndSpell( dialog )
	{
		var LangComparer = new window._SP_FCK_LangCompare(),							// Language abbr standarts comparer.
			pluginPath = CKEDITOR.getUrl( editor.plugins.wsc.path + 'dialogs/' ),			// Service paths corecting/preparing.
			framesetPath = pluginPath + 'tmpFrameset.html';

		// global var is used in FCK specific core
		// change on equal var used in fckplugin.js
		window.gFCKPluginName = 'wsc';

		LangComparer.setDefaulLangCode( editor.config.defaultLanguage );

		window.doSpell({
			ctrl : textareaId,
			lang : LangComparer.getSPLangCode( editor.langCode ),
			winType : iframeId,		// If not defined app will run on winpopup.

			// Callback binding section.
			onCancel : function()
			{
				dialog.hide();
			},
			onFinish : function( dT )
			{
				editor.focus();
				dialog.getParentEditor().setData( dT.value );
				dialog.hide();
			},

			// Some manipulations with client static pages.
			staticFrame : framesetPath,
			framesetPath : framesetPath,
			iframePath : pluginPath + 'ciframe.html',

			// Styles defining.
			schemaURI : pluginPath + 'wsc.css'
		});

		// Hide user message console (if application was loaded more then after timeout).
		CKEDITOR.document.getById( errorBoxId ).setStyle( 'display', 'none' );
		CKEDITOR.document.getById( iframeId ).setStyle( 'display', 'block' );
	}

	return {
		title : editor.lang.spellCheck.title,
		minWidth : 485,
		minHeight : 380,
		buttons : [ CKEDITOR.dialog.cancelButton ],
		onShow : function()
		{
			var contentArea = this.getContentElement( 'general', 'content' ).getElement();
			contentArea.setHtml( pasteArea );

			if ( typeof( window.doSpell ) != 'function' )
			{
				// Load script.
				CKEDITOR.document.getHead().append(
					CKEDITOR.document.createElement( 'script',
						{
							attributes :
								{
									type : 'text/javascript',
									src : wscCoreUrl
								}
						})
				);
			}

			var sData = editor.getData();											// Get the data to be checked.
			CKEDITOR.document.getById( textareaId ).setValue( sData );

			interval = window.setInterval( burnSpelling( this, errorMsg ), 250 );
		},
		onHide : function()
		{
			window.ooo = undefined;
			window.int_framsetLoaded = undefined;
			window.framesetLoaded = undefined;
			window.is_window_opened = false;
		},
		contents : [
			{
				id : 'general',
				label : editor.lang.spellCheck.title,
				padding : 0,
				elements : [
					{
						type : 'html',
						id : 'content',
						style : 'width:485;height:380px',
						html : '<div></div>'
					}
				]
			}
		]
	};
});
