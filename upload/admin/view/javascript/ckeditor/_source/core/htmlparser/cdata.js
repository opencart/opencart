/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{

	/**
	 * A lightweight representation of HTML text.
	 * @constructor
	 * @example
	 */
	CKEDITOR.htmlParser.cdata = function( value )
	{
		/**
		 * The CDATA value.
		 * @type String
		 * @example
		 */
		this.value = value;

	};

	CKEDITOR.htmlParser.cdata.prototype =
	{
		/**
		 * CDATA has the same type as {@link CKEDITOR.htmlParser.text} This is
		 * a constant value set to {@link CKEDITOR.NODE_TEXT}.
		 * @type Number
		 * @example
		 */
		type : CKEDITOR.NODE_TEXT,

		/**
		 * Writes write the CDATA with no special manipulations.
		 * @param {CKEDITOR.htmlWriter} writer The writer to which write the HTML.
		 */
		writeHtml : function( writer )
		{
			writer.write( this.value );
		}
	};
})();
