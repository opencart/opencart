/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'removeformat',
{
	requires : [ 'selection' ],

	init : function( editor )
	{
		editor.addCommand( 'removeFormat', CKEDITOR.plugins.removeformat.commands.removeformat );
		editor.ui.addButton( 'RemoveFormat',
			{
				label : editor.lang.removeFormat,
				command : 'removeFormat'
			});
	}
});

CKEDITOR.plugins.removeformat =
{
	commands :
	{
		removeformat :
		{
			exec : function( editor )
			{
				var tagsRegex = editor._.removeFormatRegex ||
					( editor._.removeFormatRegex = new RegExp( '^(?:' + editor.config.removeFormatTags.replace( /,/g,'|' ) + ')$', 'i' ) );

				var removeAttributes = editor._.removeAttributes ||
					( editor._.removeAttributes = editor.config.removeFormatAttributes.split( ',' ) );

				var ranges = editor.getSelection().getRanges();

				for ( var i = 0, range ; range = ranges[ i ] ; i++ )
				{
					if ( range.collapsed )
						continue;

					range.enlarge( CKEDITOR.ENLARGE_ELEMENT );

					// Bookmark the range so we can re-select it after processing.
					var bookmark = range.createBookmark();

					// The style will be applied within the bookmark boundaries.
					var startNode	= bookmark.startNode;
					var endNode		= bookmark.endNode;

					// We need to check the selection boundaries (bookmark spans) to break
					// the code in a way that we can properly remove partially selected nodes.
					// For example, removing a <b> style from
					//		<b>This is [some text</b> to show <b>the] problem</b>
					// ... where [ and ] represent the selection, must result:
					//		<b>This is </b>[some text to show the]<b> problem</b>
					// The strategy is simple, we just break the partial nodes before the
					// removal logic, having something that could be represented this way:
					//		<b>This is </b>[<b>some text</b> to show <b>the</b>]<b> problem</b>

					var breakParent = function( node )
					{
						// Let's start checking the start boundary.
						var path = new CKEDITOR.dom.elementPath( node );
						var pathElements = path.elements;

						for ( var i = 1, pathElement ; pathElement = pathElements[ i ] ; i++ )
						{
							if ( pathElement.equals( path.block ) || pathElement.equals( path.blockLimit ) )
								break;

							// If this element can be removed (even partially).
							if ( tagsRegex.test( pathElement.getName() ) )
								node.breakParent( pathElement );
						}
					};

					breakParent( startNode );
					breakParent( endNode );

					// Navigate through all nodes between the bookmarks.
					var currentNode = startNode.getNextSourceNode( true, CKEDITOR.NODE_ELEMENT );

					while ( currentNode )
					{
						// If we have reached the end of the selection, stop looping.
						if ( currentNode.equals( endNode ) )
							break;

						// Cache the next node to be processed. Do it now, because
						// currentNode may be removed.
						var nextNode = currentNode.getNextSourceNode( false, CKEDITOR.NODE_ELEMENT );

						// This node must not be a fake element.
						if ( currentNode.getName() != 'img' || !currentNode.getAttribute( '_cke_protected_html' ) )
						{
							// Remove elements nodes that match with this style rules.
							if ( tagsRegex.test( currentNode.getName() ) )
								currentNode.remove( true );
							else
								currentNode.removeAttributes( removeAttributes );
						}

						currentNode = nextNode;
					}

					range.moveToBookmark( bookmark );
				}

				editor.getSelection().selectRanges( ranges );
			}
		}
	}
};

/**
 * A comma separated list of elements to be removed when executing the "remove
 " format" command. Note that only inline elements are allowed.
 * @type String
 * @default 'b,big,code,del,dfn,em,font,i,ins,kbd,q,samp,small,span,strike,strong,sub,sup,tt,u,var'
 * @example
 */
CKEDITOR.config.removeFormatTags = 'b,big,code,del,dfn,em,font,i,ins,kbd,q,samp,small,span,strike,strong,sub,sup,tt,u,var';

/**
 * A comma separated list of elements attributes to be removed when executing
 * the "remove format" command.
 * @type String
 * @default 'class,style,lang,width,height,align,hspace,valign'
 * @example
 */
CKEDITOR.config.removeFormatAttributes = 'class,style,lang,width,height,align,hspace,valign';
