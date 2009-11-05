/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	CKEDITOR.htmlParser.filter = CKEDITOR.tools.createClass(
	{
		$ : function( rules )
		{
			this._ =
			{
				elementNames : [],
				attributeNames : [],
				elements : { $length : 0 },
				attributes : { $length : 0 }
			};

			if ( rules )
				this.addRules( rules, 10 );
		},

		proto :
		{
			addRules : function( rules, priority )
			{
				if ( typeof priority != 'number' )
					priority = 10;

				// Add the elementNames.
				addItemsToList( this._.elementNames, rules.elementNames, priority );

				// Add the attributeNames.
				addItemsToList( this._.attributeNames, rules.attributeNames, priority );

				// Add the elements.
				addNamedItems( this._.elements, rules.elements, priority );

				// Add the attributes.
				addNamedItems( this._.attributes, rules.attributes, priority );

				// Add the text.
				this._.text = transformNamedItem( this._.text, rules.text, priority ) || this._.text;

				// Add the comment.
				this._.comment = transformNamedItem( this._.comment, rules.comment, priority ) || this._.comment;
			},

			onElementName : function( name )
			{
				return filterName( name, this._.elementNames );
			},

			onAttributeName : function( name )
			{
				return filterName( name, this._.attributeNames );
			},

			onText : function( text )
			{
				var textFilter = this._.text;
				return textFilter ? textFilter.filter( text ) : text;
			},

			onComment : function( commentText )
			{
				var textFilter = this._.comment;
				return textFilter ? textFilter.filter( commentText ) : commentText;
			},

			onElement : function( element )
			{
				// We must apply filters set to the specific element name as
				// well as those set to the generic $ name. So, add both to an
				// array and process them in a small loop.
				var filters = [ this._.elements[ element.name ], this._.elements.$ ],
					filter, ret;

				for ( var i = 0 ; i < 2 ; i++ )
				{
					filter = filters[ i ];
					if ( filter )
					{
						ret = filter.filter( element, this );

						if ( ret === false )
							return null;

						if ( ret && ret != element )
							return this.onElement( ret );
					}
				}

				return element;
			},

			onAttribute : function( element, name, value )
			{
				var filter = this._.attributes[ name ];

				if ( filter )
				{
					var ret = filter.filter( value, element, this );

					if ( ret === false )
						return false;

					if ( typeof ret != 'undefined' )
						return ret;
				}

				return value;
			}
		}
	});

	function filterName( name, filters )
	{
		for ( var i = 0 ; name && i < filters.length ; i++ )
		{
			var filter = filters[ i ];
			name = name.replace( filter[ 0 ], filter[ 1 ] );
		}
		return name;
	}

	function addItemsToList( list, items, priority )
	{
		var i, j,
			listLength = list.length,
			itemsLength = items && items.length;

		if ( itemsLength )
		{
			// Find the index to insert the items at.
			for ( i = 0 ; i < listLength && list[ i ].pri < priority ; i++ )
			{ /*jsl:pass*/ }

			// Add all new items to the list at the specific index.
			for ( j = itemsLength - 1 ; j >= 0 ; j-- )
			{
				var item = items[ j ];
				item.pri = priority;
				list.splice( i, 0, item );
			}
		}
	}

	function addNamedItems( hashTable, items, priority )
	{
		if ( items )
		{
			for ( var name in items )
			{
				var current = hashTable[ name ];

				hashTable[ name ] =
					transformNamedItem(
						current,
						items[ name ],
						priority );

				if ( !current )
					hashTable.$length++;
			}
		}
	}

	function transformNamedItem( current, item, priority )
	{
		if ( item )
		{
			item.pri = priority;

			if ( current )
			{
				// If the current item is not an Array, transform it.
				if ( !current.splice )
				{
					if ( current.pri > priority )
						current = [ item, current ];
					else
						current = [ current, item ];

					current.filter = callItems;
				}
				else
					addItemsToList( current, item, priority );

				return current;
			}
			else
			{
				item.filter = item;
				return item;
			}
		}
	}

	function callItems( currentEntry )
	{
		var isObject = ( typeof currentEntry == 'object' );

		for ( var i = 0 ; i < this.length ; i++ )
		{
			var item = this[ i ],
				ret = item.apply( window, arguments );

			if ( typeof ret != 'undefined' )
			{
				if ( ret === false )
					return false;

				if ( isObject && ret != currentEntry )
					return ret;
			}
		}

		return null;
	}
})();

// "entities" plugin
/*
{
	text : function( text )
	{
		// TODO : Process entities.
		return text.toUpperCase();
	}
};
*/
