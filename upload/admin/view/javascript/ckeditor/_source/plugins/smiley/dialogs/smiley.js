/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.dialog.add( 'smiley', function( editor )
{
	var config = editor.config,
		images = config.smiley_images,
		columns = 8,
		i;

	/**
	 * Simulate "this" of a dialog for non-dialog events.
	 * @type {CKEDITOR.dialog}
	 */
	var dialog;
	var onClick = function( evt )
	{
		var target = evt.data.getTarget(),
			targetName = target.getName();

		if ( targetName == 'td' )
			target = target.getChild( [ 0, 0 ] );
		else if ( targetName == 'a' )
			target = target.getChild( 0 );
		else if ( targetName != 'img' )
			return;

		var src = target.getAttribute( 'cke_src' ),
			title = target.getAttribute( 'title' );

		var img = editor.document.createElement( 'img',
			{
				attributes :
				{
					src : src,
					_cke_saved_src : src,
					title : title,
					alt : title
				}
			});

		editor.insertElement( img );

		dialog.hide();
	};

	var onKeydown = CKEDITOR.tools.addFunction( function( ev, element )
	{
		ev = new CKEDITOR.dom.event( ev );
		element = new CKEDITOR.dom.element( element );
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
					nodeToMove.focus();
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
				}
				ev.preventDefault();
				break;
			// DOWN-ARROW
			case 40 :
				// relative is TR
				if ( ( relative = element.getParent().getParent().getNext() ) )
				{
					nodeToMove = relative.getChild( [element.getParent().getIndex(), 0] );
					if ( nodeToMove )
						nodeToMove.focus();
				}
				ev.preventDefault();
				break;
			// ENTER
			// SPACE
			case 32 :
				onClick( { data: ev } );
				ev.preventDefault();
				break;
			// TAB
			case 9 :
				// relative is TD
				if ( ( relative = element.getParent().getNext() ) )
				{
					nodeToMove = relative.getChild( 0 );
					nodeToMove.focus();
					ev.preventDefault(true);
				}
				// relative is TR
				else if ( ( relative = element.getParent().getParent().getNext() ) )
				{
					nodeToMove = relative.getChild( [0, 0] );
					if ( nodeToMove )
						nodeToMove.focus();
					ev.preventDefault(true);
				}
				break;
			// SHIFT + TAB
			case CKEDITOR.SHIFT + 9 :
				// relative is TD
				if ( ( relative = element.getParent().getPrevious() ) )
				{
					nodeToMove = relative.getChild( 0 );
					nodeToMove.focus();
					ev.preventDefault(true);
				}
				// relative is TR
				else if ( ( relative = element.getParent().getParent().getPrevious() ) )
				{
					nodeToMove = relative.getLast().getChild( 0 );
					nodeToMove.focus();
					ev.preventDefault(true);
				}
				break;
			default :
				// Do not stop not handled events.
				return;
		}
	});

	// Build the HTML for the smiley images table.
	var html =
	[
		'<table cellspacing="2" cellpadding="2"',
		CKEDITOR.env.ie && CKEDITOR.env.quirks ? ' style="position:absolute;"' : '',
		'><tbody>'
	];

	for ( i = 0 ; i < images.length ; i++ )
	{
		if ( i % columns === 0 )
			html.push( '<tr>' );

		html.push(
			'<td class="cke_dark_background cke_hand cke_centered" style="vertical-align: middle;">' +
				'<a href="javascript:void(0)" class="cke_smile" tabindex="-1" onkeydown="CKEDITOR.tools.callFunction( ', onKeydown, ', event, this );">',
					'<img class="hand" title="', config.smiley_descriptions[i], '"' +
						' cke_src="', CKEDITOR.tools.htmlEncode( config.smiley_path + images[ i ] ), '" alt="', config.smiley_descriptions[i], '"',
						' src="', CKEDITOR.tools.htmlEncode( config.smiley_path + images[ i ] ), '"',
						// IE BUG: Below is a workaround to an IE image loading bug to ensure the image sizes are correct.
						( CKEDITOR.env.ie ? ' onload="this.setAttribute(\'width\', 2); this.removeAttribute(\'width\');" ' : '' ),
					'>' +
				'</a>',
 			'</td>' );

		if ( i % columns == columns - 1 )
			html.push( '</tr>' );
	}

	if ( i < columns - 1 )
	{
		for ( ; i < columns - 1 ; i++ )
			html.push( '<td></td>' );
		html.push( '</tr>' );
	}

	html.push( '</tbody></table>' );

	var smileySelector =
	{
		type : 'html',
		html : html.join( '' ),
		onLoad : function( event )
		{
			dialog = event.sender;
		},
		focus : function()
 		{
			var firstSmile = this.getElement().getChild( [0, 0, 0, 0] );
			firstSmile.focus();
 		},
		onClick : onClick,
		style : 'width: 100%; height: 100%; border-collapse: separate;'
	};

	return {
		title : editor.lang.smiley.title,
		minWidth : 270,
		minHeight : 120,
		contents : [
			{
				id : 'tab1',
				label : '',
				title : '',
				expand : true,
				padding : 0,
				elements : [
						smileySelector
					]
			}
		],
		buttons : [ CKEDITOR.dialog.cancelButton ]
	};
} );
