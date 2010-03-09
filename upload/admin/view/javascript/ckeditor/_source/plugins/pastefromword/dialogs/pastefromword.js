/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.dialog.add( 'pastefromword', function( editor )
{
	return {
		title : editor.lang.pastefromword.title,
		minWidth : CKEDITOR.env.ie && CKEDITOR.env.quirks ? 370 : 350,
		minHeight : CKEDITOR.env.ie && CKEDITOR.env.quirks ? 270 : 260,
		htmlToLoad : '<!doctype html><script type="text/javascript">'
				+ 'window.onload = function()'
				+ '{'
					+ 'if ( ' + CKEDITOR.env.ie + ' ) '
						+ 'document.body.contentEditable = "true";'
					+ 'else '
						+ 'document.designMode = "on";'
					+ 'var iframe = new window.parent.CKEDITOR.dom.element( frameElement );'
					+ 'var dialog = iframe.getCustomData( "dialog" );'
		      + ''
					+ 'iframe.getFrameDocument().on( "keydown", function( e )\
						{\
							if ( e.data.getKeystroke() == 27 )\
								dialog.hide();\
						});'
					+ 'dialog.fire( "iframeAdded", { iframe : iframe } );'
				+ '};'
				+ '</script><style>body { margin: 3px; height: 95%; } </style><body></body>',
		cleanWord : function( editor, html, ignoreFont, removeStyles )
		{
			// Remove comments [SF BUG-1481861].
			html = html.replace(/<\!--[\s\S]*?-->/g, '' ) ;

			html = html.replace(/<o:p>\s*<\/o:p>/g, '') ;
			html = html.replace(/<o:p>[\s\S]*?<\/o:p>/g, '&nbsp;') ;

			// Remove mso-xxx styles.
			html = html.replace( /\s*mso-[^:]+:[^;"]+;?/gi, '' ) ;

			// Remove margin styles.
			html = html.replace( /\s*MARGIN: 0(?:cm|in) 0(?:cm|in) 0pt\s*;/gi, '' ) ;
			html = html.replace( /\s*MARGIN: 0(?:cm|in) 0(?:cm|in) 0pt\s*"/gi, "\"" ) ;

			html = html.replace( /\s*TEXT-INDENT: 0cm\s*;/gi, '' ) ;
			html = html.replace( /\s*TEXT-INDENT: 0cm\s*"/gi, "\"" ) ;

			html = html.replace( /\s*TEXT-ALIGN: [^\s;]+;?"/gi, "\"" ) ;

			html = html.replace( /\s*PAGE-BREAK-BEFORE: [^\s;]+;?"/gi, "\"" ) ;

			html = html.replace( /\s*FONT-VARIANT: [^\s;]+;?"/gi, "\"" ) ;

			html = html.replace( /\s*tab-stops:[^;"]*;?/gi, '' ) ;
			html = html.replace( /\s*tab-stops:[^"]*/gi, '' ) ;

			// Remove FONT face attributes.
			if ( ignoreFont )
			{
				html = html.replace( /\s*face="[^"]*"/gi, '' ) ;
				html = html.replace( /\s*face=[^ >]*/gi, '' ) ;

				html = html.replace( /\s*FONT-FAMILY:[^;"]*;?/gi, '' ) ;
			}

			// Remove Class attributes
			html = html.replace(/<(\w[^>]*) class=([^ |>]*)([^>]*)/gi, "<$1$3") ;

			// Remove styles.
			if ( removeStyles )
				html = html.replace( /<(\w[^>]*) style="([^\"]*)"([^>]*)/gi, "<$1$3" ) ;

			// Remove style, meta and link tags
			html = html.replace( /<STYLE[^>]*>[\s\S]*?<\/STYLE[^>]*>/gi, '' ) ;
			html = html.replace( /<(?:META|LINK)[^>]*>\s*/gi, '' ) ;

			// Remove empty styles.
			html =  html.replace( /\s*style="\s*"/gi, '' ) ;

			html = html.replace( /<SPAN\s*[^>]*>\s*&nbsp;\s*<\/SPAN>/gi, '&nbsp;' ) ;

			html = html.replace( /<SPAN\s*[^>]*><\/SPAN>/gi, '' ) ;

			// Remove Lang attributes
			html = html.replace(/<(\w[^>]*) lang=([^ |>]*)([^>]*)/gi, "<$1$3") ;

			html = html.replace( /<SPAN\s*>([\s\S]*?)<\/SPAN>/gi, '$1' ) ;

			html = html.replace( /<FONT\s*>([\s\S]*?)<\/FONT>/gi, '$1' ) ;

			// Remove XML elements and declarations
			html = html.replace(/<\\?\?xml[^>]*>/gi, '' ) ;

			// Remove w: tags with contents.
			html = html.replace( /<w:[^>]*>[\s\S]*?<\/w:[^>]*>/gi, '' ) ;

			// Remove Tags with XML namespace declarations: <o:p><\/o:p>
			html = html.replace(/<\/?\w+:[^>]*>/gi, '' ) ;

			html = html.replace( /<(U|I|STRIKE)>&nbsp;<\/\1>/g, '&nbsp;' ) ;

			html = html.replace( /<H\d>\s*<\/H\d>/gi, '' ) ;

			// Remove "display:none" tags.
			html = html.replace( /<(\w+)[^>]*\sstyle="[^"]*DISPLAY\s?:\s?none[\s\S]*?<\/\1>/ig, '' ) ;

			// Remove language tags
			html = html.replace( /<(\w[^>]*) language=([^ |>]*)([^>]*)/gi, "<$1$3") ;

			// Remove onmouseover and onmouseout events (from MS Word comments effect)
			html = html.replace( /<(\w[^>]*) onmouseover="([^\"]*)"([^>]*)/gi, "<$1$3") ;
			html = html.replace( /<(\w[^>]*) onmouseout="([^\"]*)"([^>]*)/gi, "<$1$3") ;

			if ( editor.config.pasteFromWordKeepsStructure )
			{
				// The original <Hn> tag send from Word is something like this: <Hn style="margin-top:0px;margin-bottom:0px">
				html = html.replace( /<H(\d)([^>]*)>/gi, '<h$1>' ) ;

				// Word likes to insert extra <font> tags, when using MSIE. (Wierd).
				html = html.replace( /<(H\d)><FONT[^>]*>([\s\S]*?)<\/FONT><\/\1>/gi, '<$1>$2<\/$1>' );
				html = html.replace( /<(H\d)><EM>([\s\S]*?)<\/EM><\/\1>/gi, '<$1>$2<\/$1>' );
			}
			else
			{
				html = html.replace( /<H1([^>]*)>/gi, '<div$1><b><font size="6">' ) ;
				html = html.replace( /<H2([^>]*)>/gi, '<div$1><b><font size="5">' ) ;
				html = html.replace( /<H3([^>]*)>/gi, '<div$1><b><font size="4">' ) ;
				html = html.replace( /<H4([^>]*)>/gi, '<div$1><b><font size="3">' ) ;
				html = html.replace( /<H5([^>]*)>/gi, '<div$1><b><font size="2">' ) ;
				html = html.replace( /<H6([^>]*)>/gi, '<div$1><b><font size="1">' ) ;

				html = html.replace( /<\/H\d>/gi, '<\/font><\/b><\/div>' ) ;

				// Transform <P> to <DIV>
				var re = new RegExp( '(<P)([^>]*>[\\s\\S]*?)(<\/P>)', 'gi' ) ;	// Different because of a IE 5.0 error
				html = html.replace( re, '<div$2<\/div>' ) ;

				// Remove empty tags (three times, just to be sure).
				// This also removes any empty anchor
				html = html.replace( /<([^\s>]+)(\s[^>]*)?>\s*<\/\1>/g, '' ) ;
				html = html.replace( /<([^\s>]+)(\s[^>]*)?>\s*<\/\1>/g, '' ) ;
				html = html.replace( /<([^\s>]+)(\s[^>]*)?>\s*<\/\1>/g, '' ) ;
			}

			return html ;
		},
		onShow : function()
		{
			// To avoid JAWS putting virtual cursor back to the editor document,
			// disable main document 'contentEditable' during dialog opening.
			if ( CKEDITOR.env.ie )
				this.getParentEditor().document.getBody().$.contentEditable = 'false';

			// FIREFOX BUG: Force the browser to render the dialog to make the to-be-
			// inserted iframe editable. (#3366)
			this.parts.dialog.$.offsetHeight;

			var container = this.getContentElement( 'general', 'editing_area' ).getElement(),
				iframe = CKEDITOR.dom.element.createFromHtml( '<iframe src="javascript:void(0)" frameborder="0" allowtransparency="1"></iframe>' );

			var lang = this.getParentEditor().lang;

			iframe.setStyles(
				{
					width : '346px',
					height : '152px',
					'background-color' : 'white',
					border : '1px solid black'
				} );
			iframe.setCustomData( 'dialog', this );

			var accTitle = lang.editorTitle.replace( '%1', lang.pastefromword.title );

			if ( CKEDITOR.env.ie )
				container.setHtml( '<legend style="position:absolute;top:-1000000px;left:-1000000px;">'
						+ CKEDITOR.tools.htmlEncode( accTitle )
						+ '</legend>' );
			else
			{
				container.setHtml( '' );
				container.setAttributes(
					{
						role : 'region',
						title : accTitle
					} );
				iframe.setAttributes(
					{
						role : 'region',
						title : ' '
					} );
			}
			container.append( iframe );
			if ( CKEDITOR.env.ie )
				container.setStyle( 'height', ( iframe.$.offsetHeight + 2 ) + 'px' );

			if ( CKEDITOR.env.isCustomDomain() )
			{
				CKEDITOR._cke_htmlToLoad = this.definition.htmlToLoad;
				iframe.setAttribute( 'src',
					'javascript:void( (function(){' +
						   'document.open();' +
						   'document.domain="' + document.domain + '";' +
						   'document.write( window.parent.CKEDITOR._cke_htmlToLoad );' +
						   'delete window.parent.CKEDITOR._cke_htmlToLoad;' +
						   'document.close();' +
					'})() )' );
			}
			else
			{
				var doc = iframe.$.contentWindow.document;
				doc.open();
				doc.write( this.definition.htmlToLoad );
				doc.close();
			}
		},
		onOk : function()
		{
			var container = this.getContentElement( 'general', 'editing_area' ).getElement(),
				iframe = container.getElementsByTag( 'iframe' ).getItem( 0 ),
				editor = this.getParentEditor(),
				html = this.definition.cleanWord( editor, iframe.$.contentWindow.document.body.innerHTML,
						this.getValueOf( 'general', 'ignoreFontFace' ),
						this.getValueOf( 'general', 'removeStyle' ) );

				// Insertion should happen after main document design mode turned on.
				setTimeout( function(){
					editor.insertHtml( html );
				}, 0 );
		},
		onHide : function()
		{
			if ( CKEDITOR.env.ie )
				this.getParentEditor().document.getBody().$.contentEditable = 'true';
		},
		onLoad : function()
		{
			if ( ( CKEDITOR.env.ie7Compat || CKEDITOR.env.ie6Compat ) && editor.lang.dir == 'rtl' )
				this.parts.contents.setStyle( 'overflow', 'hidden' );
		},
		contents :
		[
			{
				id : 'general',
				label : editor.lang.pastefromword.title,
				elements :
				[
					{
						type : 'html',
						style : 'white-space:normal;width:346px;display:block',
						onShow : function()
						{
							/*
							 * SAFARI BUG: The advice label would overflow if the table layout
							 * isn't fixed.
							 */
							if ( CKEDITOR.env.webkit )
								this.getElement().getAscendant( 'table' ).setStyle( 'table-layout', 'fixed' );
						},
						html : editor.lang.pastefromword.advice
					},
					{
						type : 'html',
						id : 'editing_area',
						style : 'width: 100%; height: 100%;',
						html : '<fieldset></fieldset>',
						focus : function()
						{
							var div = this.getElement();
							var iframe = div.getElementsByTag( 'iframe' );
							if ( iframe.count() < 1 )
								return;
							iframe = iframe.getItem( 0 );

							// #3291 : JAWS needs the 500ms delay to detect that the editor iframe
							// iframe is no longer editable. So that it will put the focus into the
							// Paste from Word dialog's editable area instead.
							setTimeout( function()
							{
								iframe.$.contentWindow.focus();
							}, 500 );
						}
					},
					{
						type : 'vbox',
						padding : 0,
						children :
						[
							{
								type : 'checkbox',
								id : 'ignoreFontFace',
								label : editor.lang.pastefromword.ignoreFontFace,
								'default' : editor.config.pasteFromWordIgnoreFontFace
							},
							{
								type : 'checkbox',
								id : 'removeStyle',
								label : editor.lang.pastefromword.removeStyle,
								'default' : editor.config.pasteFromWordRemoveStyle
							}
						]
					}
				]
			}
		]
	};
} );
