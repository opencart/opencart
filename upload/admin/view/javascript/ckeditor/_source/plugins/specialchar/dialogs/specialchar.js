/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.dialog.add( 'specialchar', function( editor )
{
	/**
	 * Simulate "this" of a dialog for non-dialog events.
	 * @type {CKEDITOR.dialog}
	 */
	var dialog;
	var onChoice = function( evt )
	{
		var target, value;
		if ( evt.data )
			target = evt.data.getTarget();
		else
			target = new CKEDITOR.dom.element( evt );

		if ( target.getName() == 'a' && ( value = target.getChild( 0 ).getHtml() ) )
		{
			target.removeClass( "cke_light_background" );
			dialog.hide();
			editor.insertHtml( value );
		}
	};

	var onClick = CKEDITOR.tools.addFunction( onChoice );

	var focusedNode;

	var onFocus = function( evt, target )
	{
		var value;
		target = target || evt.data.getTarget();

		if ( target.getName() == 'span' )
			target = target.getParent();

		if ( target.getName() == 'a' && ( value = target.getChild( 0 ).getHtml() ) )
		{
			// Trigger blur manually if there is focused node.
			if ( focusedNode )
				onBlur( null, focusedNode );

			var htmlPreview = dialog.getContentElement( 'info', 'htmlPreview' ).getElement();

			dialog.getContentElement( 'info', 'charPreview' ).getElement().setHtml( value );
			htmlPreview.setHtml( CKEDITOR.tools.htmlEncode( value ) );
			target.getParent().addClass( "cke_light_background" );

			// Memorize focused node.
			focusedNode = target;
		}
	};

	var onBlur = function( evt, target )
	{
		target = target || evt.data.getTarget();

		if ( target.getName() == 'span' )
			target = target.getParent();

		if ( target.getName() == 'a' )
		{
			dialog.getContentElement( 'info', 'charPreview' ).getElement().setHtml( '&nbsp;' );
			dialog.getContentElement( 'info', 'htmlPreview' ).getElement().setHtml( '&nbsp;' );
			target.getParent().removeClass( "cke_light_background" );

			focusedNode = undefined;
		}
	};

	var onKeydown = CKEDITOR.tools.addFunction( function( ev )
	{
		ev = new CKEDITOR.dom.event( ev );

		// Get an Anchor element.
		var element = ev.getTarget();
		var relative, nodeToMove;
		var keystroke = ev.getKeystroke();

		switch ( keystroke )
		{
			// RIGHT-ARROW
			case 39 :
				// relative is TD
				if ( ( relative = element.getParent().getNext() ) )
				{
					nodeToMove = relative.getChild( 0 );
					if ( nodeToMove.type == 1 )
					{
						nodeToMove.focus();
						onBlur( null, element );
						onFocus( null, nodeToMove );
					}
				}
				ev.preventDefault();
				break;
			// LEFT-ARROW
			case 37 :
				// relative is TD
				if ( ( relative = element.getParent().getPrevious() ) )
				{
					nodeToMove = relative.getChild( 0 );
					nodeToMove.focus();
					onBlur( null, element );
					onFocus( null, nodeToMove );
				}
				ev.preventDefault();
				break;
			// UP-ARROW
			case 38 :
				// relative is TR
				if ( ( relative = element.getParent().getParent().getPrevious() ) )
				{
					nodeToMove = relative.getChild( [element.getParent().getIndex(), 0] );
					nodeToMove.focus();
					onBlur( null, element );
					onFocus( null, nodeToMove );
				}
				ev.preventDefault();
				break;
			// DOWN-ARROW
			case 40 :
				// relative is TR
				if ( ( relative = element.getParent().getParent().getNext() ) )
				{
					nodeToMove = relative.getChild( [ element.getParent().getIndex(), 0 ] );
					if ( nodeToMove && nodeToMove.type == 1 )
					{
						nodeToMove.focus();
						onBlur( null, element );
						onFocus( null, nodeToMove );
					}
				}
				ev.preventDefault();
				break;
			// SPACE
			// ENTER is already handled as onClick
			case 32 :
				onChoice( { data: ev } );
				ev.preventDefault();
				break;
			// TAB
			case 9 :
				// relative is TD
				if ( ( relative = element.getParent().getNext() ) )
				{
					nodeToMove = relative.getChild( 0 );
					if ( nodeToMove.type == 1 )
					{
						nodeToMove.focus();
						onBlur( null, element );
						onFocus( null, nodeToMove );
						ev.preventDefault( true );
					}
					else
						onBlur( null, element );
				}
				// relative is TR
				else if ( ( relative = element.getParent().getParent().getNext() ) )
				{
					nodeToMove = relative.getChild( [ 0, 0 ] );
					if ( nodeToMove && nodeToMove.type == 1 )
					{
						nodeToMove.focus();
						onBlur( null, element );
						onFocus( null, nodeToMove );
						ev.preventDefault( true );
					}
					else
						onBlur( null, element );
				}
				break;
			// SHIFT + TAB
			case CKEDITOR.SHIFT + 9 :
				// relative is TD
				if ( ( relative = element.getParent().getPrevious() ) )
				{
					nodeToMove = relative.getChild( 0 );
					nodeToMove.focus();
					onBlur( null, element );
					onFocus( null, nodeToMove );
					ev.preventDefault( true );
				}
				// relative is TR
				else if ( ( relative = element.getParent().getParent().getPrevious() ) )
				{
					nodeToMove = relative.getLast().getChild( 0 );
					nodeToMove.focus();
					onBlur( null, element );
					onFocus( null, nodeToMove );
					ev.preventDefault( true );
				}
				else
					onBlur( null, element );
				break;
			default :
				// Do not stop not handled events.
				return;
		}
	});

	return {
		title : editor.lang.specialChar.title,
		minWidth : 430,
		minHeight : 280,
		buttons : [ CKEDITOR.dialog.cancelButton ],
		charColumns : 17,
		chars :
			[
				'!','&quot;','#','$','%','&amp;',"'",'(',')','*','+','-','.','/',
				'0','1','2','3','4','5','6','7','8','9',':',';',
				'&lt;','=','&gt;','?','@',
				'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O',
				'P','Q','R','S','T','U','V','W','X','Y','Z',
				'[',']','^','_','`',
				'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p',
				'q','r','s','t','u','v','w','x','y','z',
				'{','|','}','~','&euro;','&lsquo;','&rsquo;','&rsquo;','&ldquo;',
				'&rdquo;','&ndash;','&mdash;','&iexcl;','&cent;','&pound;',
				'&curren;','&yen;','&brvbar;','&sect;','&uml;','&copy;','&ordf;',
				'&laquo;','&not;','&reg;','&macr;','&deg;','&plusmn;','&sup2;',
				'&sup3;','&acute;','&micro;','&para;','&middot;','&cedil;',
				'&sup1;','&ordm;','&raquo;','&frac14;','&frac12;','&frac34;',
				'&iquest;','&Agrave;','&Aacute;','&Acirc;','&Atilde;','&Auml;',
				'&Aring;','&AElig;','&Ccedil;','&Egrave;','&Eacute;','&Ecirc;',
				'&Euml;','&Igrave;','&Iacute;','&Icirc;','&Iuml;','&ETH;',
				'&Ntilde;','&Ograve;','&Oacute;','&Ocirc;','&Otilde;','&Ouml;',
				'&times;','&Oslash;','&Ugrave;','&Uacute;','&Ucirc;','&Uuml;',
				'&Yacute;','&THORN;','&szlig;','&agrave;','&aacute;','&acirc;',
				'&atilde;','&auml;','&aring;','&aelig;','&ccedil;','&egrave;',
				'&eacute;','&ecirc;','&euml;','&igrave;','&iacute;','&icirc;',
				'&iuml;','&eth;','&ntilde;','&ograve;','&oacute;','&ocirc;',
				'&otilde;','&ouml;','&divide;','&oslash;','&ugrave;','&uacute;',
				'&ucirc;','&uuml;','&uuml;','&yacute;','&thorn;','&yuml;',
				'&OElig;','&oelig;','&#372;','&#374','&#373','&#375;','&sbquo;',
				'&#8219;','&bdquo;','&hellip;','&trade;','&#9658;','&bull;',
				'&rarr;','&rArr;','&hArr;','&diams;','&asymp;'
			],
		onLoad :  function()
		{
			var columns = this.definition.charColumns,
				chars = this.definition.chars;

			var html = [ '<table style="width: 320px; height: 100%; border-collapse: separate;" align="center" cellspacing="2" cellpadding="2" border="0">' ];

			var i = 0 ;
			while ( i < chars.length )
			{
				html.push( '<tr>' ) ;

				for( var j = 0 ; j < columns ; j++, i++ )
				{
					if ( chars[ i ] )
					{
						html.push(
							'<td class="cke_dark_background" style="cursor: default">' +
							'<a href="javascript: void(0);" style="cursor: inherit; display: block; height: 1.25em; margin-top: 0.25em; text-align: center;" title="', chars[i].replace( /&/g, '&amp;' ), '"' +
							' onkeydown="CKEDITOR.tools.callFunction( ' + onKeydown + ', event, this )"' +
							' onclick="CKEDITOR.tools.callFunction(' + onClick + ', this); return false;"' +
							' tabindex="-1">' +
							'<span style="margin: 0 auto;cursor: inherit">' +
							chars[i] +
							'</span></a>');
					}
					else
						html.push( '<td class="cke_dark_background">&nbsp;' );

					html.push( '</td>' );
				}
				html.push( '</tr>' );
			}

			html.push( '</tbody></table>' );

			this.getContentElement( 'info', 'charContainer' ).getElement().setHtml( html.join( '' ) );
		},
		contents : [
			{
				id : 'info',
				label : editor.lang.common.generalTab,
				title : editor.lang.common.generalTab,
				padding : 0,
				align : 'top',
				elements : [
					{
						type : 'hbox',
						align : 'top',
						widths : [ '320px', '90px' ],
						children :
						[
							{
								type : 'html',
								id : 'charContainer',
								html : '',
								onMouseover : onFocus,
								onMouseout : onBlur,
								focus : function()
								{
									var firstChar = this.getElement().getChild( [0, 0, 0, 0, 0] );
									setTimeout(function()
									{
										firstChar.focus();
										onFocus( null, firstChar );
									});
								},
								// Needed only for webkit.
								onShow : function()
								{
									var firstChar = this.getElement().getChild( [0, 0, 0, 0, 0] );
									setTimeout(function()
									{
										firstChar.focus();
										onFocus( null, firstChar );
									});
								},
								onLoad : function( event )
								{
									dialog = event.sender;
								}
							},
							{
								type : 'hbox',
								align : 'top',
								widths : [ '100%' ],
								children :
								[
									{
										type : 'vbox',
										align : 'top',
										children :
										[
											{
												type : 'html',
												html : '<div></div>'
											},
											{
												type : 'html',
												id : 'charPreview',
												style : 'border:1px solid #eeeeee;background-color:#EAEAD1;font-size:28px;height:40px;width:70px;padding-top:9px;font-family:\'Microsoft Sans Serif\',Arial,Helvetica,Verdana;text-align:center;',
												html : '<div>&nbsp;</div>'
											},
											{
												type : 'html',
												id : 'htmlPreview',
												style : 'border:1px solid #eeeeee;background-color:#EAEAD1;font-size:14px;height:20px;width:70px;padding-top:2px;font-family:\'Microsoft Sans Serif\',Arial,Helvetica,Verdana;text-align:center;',
												html : '<div>&nbsp;</div>'
											}
										]
									}
								]
							}
						]
					}
				]
			}
		]
	};
} );
