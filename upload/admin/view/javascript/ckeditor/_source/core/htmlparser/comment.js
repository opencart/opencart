/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * A lightweight representation of an HTML comment.
 * @constructor
 * @example
 */
CKEDITOR.htmlParser.comment = function( value )
{
	/**
	 * The comment text.
	 * @type String
	 * @example
	 */
	this.value = value;

	/** @private */
	this._ =
	{
		isBlockLike : false
	};
};

CKEDITOR.htmlParser.comment.prototype =
{
	/**
	 * The node type. This is a constant value set to {@link CKEDITOR.NODE_COMMENT}.
	 * @type Number
	 * @example
	 */
	type : CKEDITOR.NODE_COMMENT,

	/**
	 * Writes the HTML representation of this comment to a CKEDITOR.htmlWriter.
	 * @param {CKEDITOR.htmlWriter} writer The writer to which write the HTML.
	 * @example
	 */
	writeHtml : function( writer, filter )
	{
		var comment = this.value;

		if ( filter )
		{
			if ( !( comment = filter.onComment( comment ) ) )
				return;

			if ( typeof comment != 'string' )
			{
				comment.writeHtml( writer, filter );
				return;
			}
		}

		writer.comment( comment );
	}
};
