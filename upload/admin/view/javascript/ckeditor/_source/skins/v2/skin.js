/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.skins.add( 'v2', (function()
{
	var preload = [];

	if ( CKEDITOR.env.ie && CKEDITOR.env.version < 7 )
	{
		// For IE6, we need to preload some images, otherwhise they will be
		// downloaded several times (CSS background bug).
		preload.push( 'icons.png', 'images/sprites_ie6.png', 'images/dialog_sides.gif' );
	}

	return {
		preload		: preload,
		editor		: { css : [ 'editor.css' ] },
		dialog		: { css : [ 'dialog.css' ] },
		templates	: { css : [ 'templates.css' ] },
		margins		: [ 0, 14, 18, 14 ]
	};
})() );

if ( CKEDITOR.dialog )
{
	CKEDITOR.dialog.on( 'resize', function( evt )
		{
			var data = evt.data,
				width = data.width,
				height = data.height,
				dialog = data.dialog,
				standardsMode = !CKEDITOR.env.quirk;

			if ( data.skin != 'v2' )
				return;

			dialog.parts.contents.setStyles(
				{
					width : width + 'px',
					height : height + 'px'
				});

			if ( !CKEDITOR.env.ie )
				return;

			// Fix the size of the elements which have flexible lengths.
			setTimeout( function()
				{
					var content = dialog.parts.contents,
						body = content.getParent(),
						innerDialog = body.getParent();

					// tc
					var el = innerDialog.getChild( 2 );
					el.setStyle( 'width', ( body.$.offsetWidth ) + 'px' );

					// bc
					el = innerDialog.getChild( 7 );
					el.setStyle( 'width', ( body.$.offsetWidth - 28 ) + 'px' );

					// ml
					el = innerDialog.getChild( 4 );
					el.setStyle( 'height', ( body.$.offsetHeight - 31 - 14 ) + 'px' );

					// mr
					el = innerDialog.getChild( 5 );
					el.setStyle( 'height', ( body.$.offsetHeight - 31 - 14 ) + 'px' );
				},
				100 );
		});
}
