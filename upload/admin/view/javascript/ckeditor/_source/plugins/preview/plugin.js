/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Preview plugin.
 */

(function()
{
	var previewCmd =
	{
		modes : { wysiwyg:1, source:1 },
		canUndo : false,
		exec : function( editor )
		{
			var sHTML,
				isCustomDomain = CKEDITOR.env.isCustomDomain();
			if ( editor.config.fullPage )
				sHTML = editor.getData();
			else
			{
				var bodyHtml = '<body ',
					body = CKEDITOR.document.getBody(),
					baseTag = ( editor.config.baseHref.length > 0 ) ? '<base href="' + editor.config.baseHref + '" _cktemp="true"></base>' : '';

				if ( body.getAttribute( 'id' ) )
					bodyHtml += 'id="' + body.getAttribute( 'id' ) + '" ';
				if ( body.getAttribute( 'class' ) )
					bodyHtml += 'class="' + body.getAttribute( 'class' ) + '" ';
				bodyHtml += '>';

				sHTML =
					editor.config.docType +
					'<html dir="' + editor.config.contentsLangDirection + '">' +
					'<head>' +
					baseTag +
					'<title>' + editor.lang.preview + '</title>' +
					'<link type="text/css" rel="stylesheet" href="' +
					[].concat( editor.config.contentsCss ).join( '"><link type="text/css" rel="stylesheet" href="' ) +
					'">' +
					'</head>' + bodyHtml +
					editor.getData() +
					'</body></html>';
			}

			var iWidth	= 640,	// 800 * 0.8,
				iHeight	= 420,	// 600 * 0.7,
				iLeft	= 80;	// (800 - 0.8 * 800) /2 = 800 * 0.1.
			try
			{
				var screen = window.screen;
				iWidth = Math.round( screen.width * 0.8 );
				iHeight = Math.round( screen.height * 0.7 );
				iLeft = Math.round( screen.width * 0.1 );
			}
			catch ( e ){}

			var sOpenUrl = '';
			if ( isCustomDomain )
			{
				window._cke_htmlToLoad = sHTML;
				sOpenUrl = 'javascript:void( (function(){' +
					'document.open();' +
					'document.domain="' + document.domain + '";' +
					'document.write( window.opener._cke_htmlToLoad );' +
					'document.close();' +
					'window.opener._cke_htmlToLoad = null;' +
					'})() )';
			}

			var oWindow = window.open( sOpenUrl, null, 'toolbar=yes,location=no,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=' +
				iWidth + ',height=' + iHeight + ',left=' + iLeft );

			if ( !isCustomDomain )
			{
				oWindow.document.write( sHTML );
				oWindow.document.close();
			}
		}
	};

	var pluginName = 'preview';

	// Register a plugin named "preview".
	CKEDITOR.plugins.add( pluginName,
	{
		init : function( editor )
		{
			editor.addCommand( pluginName, previewCmd );
			editor.ui.addButton( 'Preview',
				{
					label : editor.lang.preview,
					command : pluginName
				});
		}
	});
})();
