/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * A lightweight representation of an HTML DOM structure.
 * @constructor
 * @example
 */
CKEDITOR.htmlParser.fragment = function()
{
	/**
	 * The nodes contained in the root of this fragment.
	 * @type Array
	 * @example
	 * var fragment = CKEDITOR.htmlParser.fragment.fromHtml( '<b>Sample</b> Text' );
	 * alert( fragment.children.length );  "2"
	 */
	this.children = [];

	/**
	 * Get the fragment parent. Should always be null.
	 * @type Object
	 * @default null
	 * @example
	 */
	this.parent = null;

	/** @private */
	this._ =
	{
		isBlockLike : true,
		hasInlineStarted : false
	};
};

(function()
{
	// Elements which the end tag is marked as optional in the HTML 4.01 DTD
	// (expect empty elements).
	var optionalClose = {colgroup:1,dd:1,dt:1,li:1,option:1,p:1,td:1,tfoot:1,th:1,thead:1,tr:1};

	// Block-level elements whose internal structure should be respected during
	// parser fixing.
	var nonBreakingBlocks = CKEDITOR.tools.extend(
			{table:1,ul:1,ol:1,dl:1},
			CKEDITOR.dtd.table, CKEDITOR.dtd.ul, CKEDITOR.dtd.ol, CKEDITOR.dtd.dl ),
		listBlocks = CKEDITOR.dtd.$list, listItems = CKEDITOR.dtd.$listItem;

	/**
	 * Creates a {@link CKEDITOR.htmlParser.fragment} from an HTML string.
	 * @param {String} fragmentHtml The HTML to be parsed, filling the fragment.
	 * @param {Number} [fixForBody=false] Wrap body with specified element if needed.
	 * @returns CKEDITOR.htmlParser.fragment The fragment created.
	 * @example
	 * var fragment = CKEDITOR.htmlParser.fragment.fromHtml( '<b>Sample</b> Text' );
	 * alert( fragment.children[0].name );  "b"
	 * alert( fragment.children[1].value );  " Text"
	 */
	CKEDITOR.htmlParser.fragment.fromHtml = function( fragmentHtml, fixForBody )
	{
		var parser = new CKEDITOR.htmlParser(),
			html = [],
			fragment = new CKEDITOR.htmlParser.fragment(),
			pendingInline = [],
			currentNode = fragment,
		    // Indicate we're inside a <pre> element, spaces should be touched differently.
			inPre = false,
			returnPoint;

		function checkPending( newTagName )
		{
			if ( pendingInline.length > 0 )
			{
				for ( var i = 0 ; i < pendingInline.length ; i++ )
				{
					var pendingElement = pendingInline[ i ],
						pendingName = pendingElement.name,
						pendingDtd = CKEDITOR.dtd[ pendingName ],
						currentDtd = currentNode.name && CKEDITOR.dtd[ currentNode.name ];

					if ( ( !currentDtd || currentDtd[ pendingName ] ) && ( !newTagName || !pendingDtd || pendingDtd[ newTagName ] || !CKEDITOR.dtd[ newTagName ] ) )
					{
						// Get a clone for the pending element.
						pendingElement = pendingElement.clone();

						// Add it to the current node and make it the current,
						// so the new element will be added inside of it.
						pendingElement.parent = currentNode;
						currentNode = pendingElement;

						// Remove the pending element (back the index by one
						// to properly process the next entry).
						pendingInline.splice( i, 1 );
						i--;
					}
				}
			}
		}

		function addElement( element, target, enforceCurrent )
		{
			target = target || currentNode || fragment;

			// If the target is the fragment and this element can't go inside
			// body (if fixForBody).
			if ( fixForBody && !target.type )
			{
				var elementName, realElementName;
				if ( element.attributes
					 && ( realElementName =
						  element.attributes[ '_cke_real_element_type' ] ) )
					elementName = realElementName;
				else
					elementName =  element.name;
				if ( !( elementName in CKEDITOR.dtd.$body ) )
				{
					var savedCurrent = currentNode;

					// Create a <p> in the fragment.
					currentNode = target;
					parser.onTagOpen( fixForBody, {} );

					// The new target now is the <p>.
					target = currentNode;

					if ( enforceCurrent )
						currentNode = savedCurrent;
				}
			}

			// Rtrim empty spaces on block end boundary. (#3585)
			if ( element._.isBlockLike
				 && element.name != 'pre' )
			{

				var length = element.children.length,
					lastChild = element.children[ length - 1 ],
					text;
				if ( lastChild && lastChild.type == CKEDITOR.NODE_TEXT )
				{
					if ( !( text = CKEDITOR.tools.rtrim( lastChild.value ) ) )
						element.children.length = length -1;
					else
						lastChild.value = text;
				}
			}

			target.add( element );

			if ( element.returnPoint )
			{
				currentNode = element.returnPoint;
				delete element.returnPoint;
			}
		}

		parser.onTagOpen = function( tagName, attributes, selfClosing )
		{
			var element = new CKEDITOR.htmlParser.element( tagName, attributes );

			// "isEmpty" will be always "false" for unknown elements, so we
			// must force it if the parser has identified it as a selfClosing tag.
			if ( element.isUnknown && selfClosing )
				element.isEmpty = true;

			// This is a tag to be removed if empty, so do not add it immediately.
			if ( CKEDITOR.dtd.$removeEmpty[ tagName ] )
			{
				pendingInline.push( element );
				return;
			}
			else if ( tagName == 'pre' )
				inPre = true;
			else if ( tagName == 'br' && inPre )
			{
				currentNode.add( new CKEDITOR.htmlParser.text( '\n' ) );
				return;
			}

			var currentName = currentNode.name,
				currentDtd = ( currentName && CKEDITOR.dtd[ currentName ] ) || ( currentNode._.isBlockLike ? CKEDITOR.dtd.div : CKEDITOR.dtd.span );

			// If the element cannot be child of the current element.
			if ( !element.isUnknown && !currentNode.isUnknown && !currentDtd[ tagName ] )
			{
				// If this is the fragment node, just ignore this tag and add
				// its children.
				if ( !currentName )
					return;

				var reApply = false,
					addPoint;   // New position to start adding nodes.

				// Fixing malformed nested lists(#3828).
				if( tagName in listBlocks
					&& currentName in listBlocks )
				{
					var children = currentNode.children,
						lastChild = children[ children.length - 1 ];
					// Move inner list into to previous list item if any.
					if( lastChild && lastChild.name in listItems )
						returnPoint = currentNode, addPoint = lastChild;
					// Move inner list outside in the worst case.
					else
						addElement( currentNode, currentNode.parent );
				}
				// If the element name is the same as the current element name,
				// then just close the current one and append the new one to the
				// parent. This situation usually happens with <p>, <li>, <dt> and
				// <dd>, specially in IE. Do not enter in this if block in this case.
				else if ( tagName == currentName )
				{
					addElement( currentNode, currentNode.parent );
				}
				else
				{
					if ( nonBreakingBlocks[ currentName ] )
					{
						if ( !returnPoint )
							returnPoint = currentNode;
					}
					else
					{
						addElement( currentNode, currentNode.parent, true );

						if ( !optionalClose[ currentName ] )
						{
							// The current element is an inline element, which
							// cannot hold the new one. Put it in the pending list,
							// and try adding the new one after it.
							pendingInline.unshift( currentNode );
						}
					}

					reApply = true;
				}

				if( addPoint )
					currentNode = addPoint;
				// Try adding it to the return point, or the parent element.
				else
					currentNode = currentNode.returnPoint || currentNode.parent;

				if ( reApply )
				{
					parser.onTagOpen.apply( this, arguments );
					return;
				}
			}

			checkPending( tagName );

			element.parent = currentNode;
			element.returnPoint = returnPoint;
			returnPoint = 0;

			if ( element.isEmpty )
				addElement( element );
			else
				currentNode = element;
		};

		parser.onTagClose = function( tagName )
		{
			var index = 0,
				pendingAdd = [],
				candidate = currentNode;

			while ( candidate.type && candidate.name != tagName )
			{
				// If this is an inline element, add it to the pending list, so
				// it will continue after the closing tag.
				if ( !candidate._.isBlockLike )
				{
					pendingInline.unshift( candidate );

					// Increase the index, so it will not get checked again in
					// the pending list check that follows.
					index++;
				}

				// This node should be added to it's parent at this point. But,
				// it should happen only if the closing tag is really closing
				// one of the nodes. So, for now, we just cache it.
				pendingAdd.push( candidate );

				candidate = candidate.parent;
			}

			if ( candidate.type )
			{
				// Add all elements that have been found in the above loop.
				for ( var i = 0 ; i < pendingAdd.length ; i++ )
				{
					var node = pendingAdd[ i ];
					addElement( node, node.parent );
				}

				currentNode = candidate;

				if( currentNode.name == 'pre' )
					inPre = false;

				addElement( candidate, candidate.parent );

				// The parent should start receiving new nodes now, except if
				// addElement changed the currentNode.
				if ( candidate == currentNode )
					currentNode = currentNode.parent;
			}
			// The tag is not actually closing anything, thus we need invalidate
			// the pending elements.(#3862)
			else
			{
				pendingInline.splice( 0, index );
				index = 0;
			}

			// Check if there is any pending tag to be closed.
			for ( ; index < pendingInline.length ; index++ )
			{
				// If found, just remove it from the list.
				if ( tagName == pendingInline[ index ].name )
				{
					pendingInline.splice( index, 1 );

					// Decrease the index so we continue from the next one.
					index--;
				}
			}
		};

		parser.onText = function( text )
		{
			// Trim empty spaces at beginning of element contents except <pre>.
			if ( !currentNode._.hasInlineStarted && !inPre )
			{
				text = CKEDITOR.tools.ltrim( text );

				if ( text.length === 0 )
					return;
			}

			checkPending();

			if ( fixForBody && !currentNode.type )
				this.onTagOpen( fixForBody, {} );

			// Shrinking consequential spaces into one single for all elements
			// text contents.
			if ( !inPre )
				text = text.replace( /[\t\r\n ]{2,}|[\t\r\n]/g, ' ' );

			currentNode.add( new CKEDITOR.htmlParser.text( text ) );
		};

		parser.onCDATA = function( cdata )
		{
			currentNode.add( new CKEDITOR.htmlParser.cdata( cdata ) );
		};

		parser.onComment = function( comment )
		{
			currentNode.add( new CKEDITOR.htmlParser.comment( comment ) );
		};

		// Parse it.
		parser.parse( fragmentHtml );

		// Close all pending nodes.
		while ( currentNode.type )
		{
			var parent = currentNode.parent,
				node = currentNode;

			if ( fixForBody && !parent.type && !CKEDITOR.dtd.$body[ node.name ] )
			{
				currentNode = parent;
				parser.onTagOpen( fixForBody, {} );
				parent = currentNode;
			}

			parent.add( node );
			currentNode = parent;
		}

		return fragment;
	};

	CKEDITOR.htmlParser.fragment.prototype =
	{
		/**
		 * Adds a node to this fragment.
		 * @param {Object} node The node to be added. It can be any of of the
		 *		following types: {@link CKEDITOR.htmlParser.element},
		 *		{@link CKEDITOR.htmlParser.text} and
		 *		{@link CKEDITOR.htmlParser.comment}.
		 * @example
		 */
		add : function( node )
		{
			var len = this.children.length,
				previous = len > 0 && this.children[ len - 1 ] || null;

			if ( previous )
			{
				// If the block to be appended is following text, trim spaces at
				// the right of it.
				if ( node._.isBlockLike && previous.type == CKEDITOR.NODE_TEXT )
				{
					previous.value = CKEDITOR.tools.rtrim( previous.value );

					// If we have completely cleared the previous node.
					if ( previous.value.length === 0 )
					{
						// Remove it from the list and add the node again.
						this.children.pop();
						this.add( node );
						return;
					}
				}

				previous.next = node;
			}

			node.previous = previous;
			node.parent = this;

			this.children.push( node );

			this._.hasInlineStarted = node.type == CKEDITOR.NODE_TEXT || ( node.type == CKEDITOR.NODE_ELEMENT && !node._.isBlockLike );
		},

		/**
		 * Writes the fragment HTML to a CKEDITOR.htmlWriter.
		 * @param {CKEDITOR.htmlWriter} writer The writer to which write the HTML.
		 * @example
		 * var writer = new CKEDITOR.htmlWriter();
		 * var fragment = CKEDITOR.htmlParser.fragment.fromHtml( '&lt;P&gt;&lt;B&gt;Example' );
		 * fragment.writeHtml( writer )
		 * alert( writer.getHtml() );  "&lt;p&gt;&lt;b&gt;Example&lt;/b&gt;&lt;/p&gt;"
		 */
		writeHtml : function( writer, filter )
		{
			for ( var i = 0, len = this.children.length ; i < len ; i++ )
				this.children[i].writeHtml( writer, filter );
		}
	};
})();
