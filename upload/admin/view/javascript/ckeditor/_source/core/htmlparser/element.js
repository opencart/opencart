/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * A lightweight representation of an HTML element.
 * @param {String} name The element name.
 * @param {Object} attributes And object holding all attributes defined for
 *		this element.
 * @constructor
 * @example
 */
CKEDITOR.htmlParser.element = function( name, attributes )
{
	/**
	 * The element name.
	 * @type String
	 * @example
	 */
	this.name = name;

	/**
	 * Holds the attributes defined for this element.
	 * @type Object
	 * @example
	 */
	this.attributes = attributes;

	/**
	 * The nodes that are direct children of this element.
	 * @type Array
	 * @example
	 */
	this.children = [];

	var dtd			= CKEDITOR.dtd,
		isBlockLike	= !!( dtd.$block[ name ] || dtd.$listItem[ name ] || dtd.$tableContent[ name ] ),
		isEmpty		= !!dtd.$empty[ name ];

	this.isEmpty	= isEmpty;
	this.isUnknown	= !dtd[ name ];

	/** @private */
	this._ =
	{
		isBlockLike : isBlockLike,
		hasInlineStarted : isEmpty || !isBlockLike
	};
};

(function()
{
	// Used to sort attribute entries in an array, where the first element of
	// each object is the attribute name.
	var sortAttribs = function( a, b )
	{
		a = a[0];
		b = b[0];
		return a < b ? -1 : a > b ? 1 : 0;
	};

	CKEDITOR.htmlParser.element.prototype =
	{
		/**
		 * The node type. This is a constant value set to {@link CKEDITOR.NODE_ELEMENT}.
		 * @type Number
		 * @example
		 */
		type : CKEDITOR.NODE_ELEMENT,

		/**
		 * Adds a node to the element children list.
		 * @param {Object} node The node to be added. It can be any of of the
		 *		following types: {@link CKEDITOR.htmlParser.element},
		 *		{@link CKEDITOR.htmlParser.text} and
		 *		{@link CKEDITOR.htmlParser.comment}.
		 * @function
		 * @example
		 */
		add : CKEDITOR.htmlParser.fragment.prototype.add,

		/**
		 * Clone this element.
		 * @returns {CKEDITOR.htmlParser.element} The element clone.
		 * @example
		 */
		clone : function()
		{
			return new CKEDITOR.htmlParser.element( this.name, this.attributes );
		},

		/**
		 * Writes the element HTML to a CKEDITOR.htmlWriter.
		 * @param {CKEDITOR.htmlWriter} writer The writer to which write the HTML.
		 * @example
		 */
		writeHtml : function( writer, filter )
		{
			var attributes = this.attributes;

			// The "_cke_replacedata" indicates that this element is replacing
			// a data snippet, which should be outputted as is.
			if ( attributes._cke_replacedata )
			{
				writer.write( attributes._cke_replacedata );
				return;
			}

			// Ignore cke: prefixes when writing HTML.
			var element = this,
				writeName = element.name,
				a, value;

			if ( filter )
			{
				while ( true )
				{
					if ( !( writeName = filter.onElementName( writeName ) ) )
						return;

					element.name = writeName;

					if ( !( element = filter.onElement( element ) ) )
						return;

					if ( element.name == writeName )
						break;

					writeName = element.name;
					if ( !writeName )	// Send children.
					{
						CKEDITOR.htmlParser.fragment.prototype.writeHtml.apply( element, arguments );
						return;
					}
				}

				// The element may have been changed, so update the local
				// references.
				attributes = element.attributes;
			}

			// Open element tag.
			writer.openTag( writeName, attributes );

			if ( writer.sortAttributes )
			{
				// Copy all attributes to an array.
				var attribsArray = [];
				for ( a in attributes )
				{
					value = attributes[ a ];

					if ( filter && ( !( a = filter.onAttributeName( a ) ) || ( value = filter.onAttribute( element, a, value ) ) === false ) )
						continue;

					attribsArray.push( [ a, value ] );
				}

				// Sort the attributes by name.
				attribsArray.sort( sortAttribs );

				// Send the attributes.
				for ( var i = 0, len = attribsArray.length ; i < len ; i++ )
				{
					var attrib = attribsArray[ i ];
					writer.attribute( attrib[0], attrib[1] );
				}
			}
			else
			{
				for ( a in attributes )
				{
					value = attributes[ a ];

					if ( filter && ( !( a = filter.onAttributeName( a ) ) || ( value = filter.onAttribute( element, a, value ) ) === false ) )
						continue;

					writer.attribute( a, value );
				}
			}

			// Close the tag.
			writer.openTagClose( writeName, element.isEmpty );

			if ( !element.isEmpty )
			{
				// Send children.
				CKEDITOR.htmlParser.fragment.prototype.writeHtml.apply( element, arguments );

				// Close the element.
				writer.closeTag( writeName );
			}
		}
	};
})();
