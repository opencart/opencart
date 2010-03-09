/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.dialog.add( 'paste', function( editor )
{
	var isCustomDomain = CKEDITOR.env.isCustomDomain();

	return {
		title : editor.lang.clipboard.title,

		minWidth : CKEDITOR.env.ie && CKEDITOR.env.quirks ? 370 : 350,
		minHeight : CKEDITOR.env.quirks ? 250 : 245,
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
				+ '};'
				+ '</script><style>body { margin: 3px; height: 95%; } </style><body></body>',

		onShow : function()
		{
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
					height : '130px',
					'background-color' : 'white',
					border : '1px solid black'
				} );
			iframe.setCustomData( 'dialog', this );

			var accTitle = lang.editorTitle.replace( '%1', lang.clipboard.title );

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

			if ( isCustomDomain )
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

		onOk : function()
		{
			var container = this.getContentElement( 'general', 'editing_area' ).getElement(),
				iframe = container.getElementsByTag( 'iframe' ).getItem( 0 ),
				editor = this.getParentEditor(),
				html = iframe.$.contentWindow.document.body.innerHTML;

			setTimeout( function(){
				editor.insertHtml( html );
			}, 0 );

		},

		contents : [
			{
				id : 'general',
				label : editor.lang.common.generalTab,
				elements : [
					{
						type : 'html',
						id : 'securityMsg',
						html : '<div style="white-space:normal;width:340px;">' + editor.lang.clipboard.securityMsg + '</div>'
					},
					{
						type : 'html',
						id : 'pasteMsg',
						html : '<div style="white-space:normal;width:340px;">'+editor.lang.clipboard.pasteMsg +'</div>'
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
					}
				]
			}
		]
	};
});
