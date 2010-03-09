/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	// Elements that may be considered the "Block boundary" in an element path.
	var pathBlockElements = { address:1,blockquote:1,dl:1,h1:1,h2:1,h3:1,h4:1,h5:1,h6:1,p:1,pre:1,li:1,dt:1,de:1 };

	// Elements that may be considered the "Block limit" in an element path.
	var pathBlockLimitElements = { body:1,div:1,table:1,tbody:1,tr:1,td:1,th:1,caption:1,form:1 };

	// Check if an element contains any block element.
	var checkHasBlock = function( element )
	{
		var childNodes = element.getChildren();

		for ( var i = 0, count = childNodes.count() ; i < count ; i++ )
		{
			var child = childNodes.getItem( i );

			if ( child.type == CKEDITOR.NODE_ELEMENT && CKEDITOR.dtd.$block[ child.getName() ] )
				return true;
		}

		return false;
	};

	CKEDITOR.dom.elementPath = function( lastNode )
	{
		var block = null;
		var blockLimit = null;
		var elements = [];

		var e = lastNode;

		while ( e )
		{
			if ( e.type == CKEDITOR.NODE_ELEMENT )
			{
				if ( !this.lastElement )
					this.lastElement = e;

				var elementName = e.getName();
				if ( CKEDITOR.env.ie && e.$.scopeName != 'HTML' )
					elementName = e.$.scopeName.toLowerCase() + ':' + elementName;

				if ( !blockLimit )
				{
					if ( !block && pathBlockElements[ elementName ] )
						block = e;

					if ( pathBlockLimitElements[ elementName ] )
					{
						// DIV is considered the Block, if no block is available (#525)
						// and if it doesn't contain other blocks.
						if ( !block && elementName == 'div' && !checkHasBlock( e ) )
							block = e;
						else
							blockLimit = e;
					}
				}

				elements.push( e );

				if ( elementName == 'body' )
					break;
			}
			e = e.getParent();
		}

		this.block = block;
		this.blockLimit = blockLimit;
		this.elements = elements;
	};
})();

CKEDITOR.dom.elementPath.prototype =
{
	/**
	 * Compares this element path with another one.
	 * @param {CKEDITOR.dom.elementPath} otherPath The elementPath object to be
	 * compared with this one.
	 * @returns {Boolean} "true" if the paths are equal, containing the same
	 * number of elements and the same elements in the same order.
	 */
	compare : function( otherPath )
	{
		var thisElements = this.elements;
		var otherElements = otherPath && otherPath.elements;

		if ( !otherElements || thisElements.length != otherElements.length )
			return false;

		for ( var i = 0 ; i < thisElements.length ; i++ )
		{
			if ( !thisElements[ i ].equals( otherElements[ i ] ) )
				return false;
		}

		return true;
	}
};
