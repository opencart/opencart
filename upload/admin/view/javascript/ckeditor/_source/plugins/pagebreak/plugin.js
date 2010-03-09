/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Horizontal Page Break
 */

// Register a plugin named "pagebreak".
CKEDITOR.plugins.add( 'pagebreak',
{
	init : function( editor )
	{
		// Register the command.
		editor.addCommand( 'pagebreak', CKEDITOR.plugins.pagebreakCmd );

		// Register the toolbar button.
		editor.ui.addButton( 'PageBreak',
			{
				label : editor.lang.pagebreak,
				command : 'pagebreak'
			});

		// Add the style that renders our placeholder.
		editor.addCss(
			'img.cke_pagebreak' +
			'{' +
				'background-image: url(' + CKEDITOR.getUrl( this.path + 'images/pagebreak.gif' ) + ');' +
				'background-position: center center;' +
				'background-repeat: no-repeat;' +
				'clear: both;' +
				'display: block;' +
				'float: none;' +
				'width: 100%;' +
				'border-top: #999999 1px dotted;' +
				'border-bottom: #999999 1px dotted;' +
				'height: 5px;' +

			'}' );
	},

	afterInit : function( editor )
	{
		// Register a filter to displaying placeholders after mode change.

		var dataProcessor = editor.dataProcessor,
			dataFilter = dataProcessor && dataProcessor.dataFilter;

		if ( dataFilter )
		{
			dataFilter.addRules(
				{
					elements :
					{
						div : function( element )
						{
							var style = element.attributes.style,
								child = style && element.children.length == 1 && element.children[ 0 ],
								childStyle = child && ( child.name == 'span' ) && child.attributes.style;

							if ( childStyle && ( /page-break-after\s*:\s*always/i ).test( style ) && ( /display\s*:\s*none/i ).test( childStyle ) )
								return editor.createFakeParserElement( element, 'cke_pagebreak', 'div' );
						}
					}
				});
		}
	},

	requires : [ 'fakeobjects' ]
});

CKEDITOR.plugins.pagebreakCmd =
{
	exec : function( editor )
	{
		// Create the element that represents a print break.
		var breakObject = CKEDITOR.dom.element.createFromHtml( '<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>' );

		// Creates the fake image used for this element.
		breakObject = editor.createFakeElement( breakObject, 'cke_pagebreak', 'div' );

		var ranges = editor.getSelection().getRanges();

		for ( var range, i = 0 ; i < ranges.length ; i++ )
		{
			range = ranges[ i ];

			if ( i > 0 )
				breakObject = breakObject.clone( true );

			range.splitBlock( 'p' );
			range.insertNode( breakObject );
		}
	}
};
