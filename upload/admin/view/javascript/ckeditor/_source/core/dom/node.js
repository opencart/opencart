/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.dom.node} class, which is the base
 *		class for classes that represent DOM nodes.
 */

/**
 * Base class for classes representing DOM nodes. This constructor may return
 * and instance of classes that inherits this class, like
 * {@link CKEDITOR.dom.element} or {@link CKEDITOR.dom.text}.
 * @augments CKEDITOR.dom.domObject
 * @param {Object} domNode A native DOM node.
 * @constructor
 * @see CKEDITOR.dom.element
 * @see CKEDITOR.dom.text
 * @example
 */
CKEDITOR.dom.node = function( domNode )
{
	if ( domNode )
	{
		switch ( domNode.nodeType )
		{
			case CKEDITOR.NODE_ELEMENT :
				return new CKEDITOR.dom.element( domNode );

			case CKEDITOR.NODE_TEXT :
				return new CKEDITOR.dom.text( domNode );
		}

		// Call the base constructor.
		CKEDITOR.dom.domObject.call( this, domNode );
	}

	return this;
};

CKEDITOR.dom.node.prototype = new CKEDITOR.dom.domObject();

/**
 * Element node type.
 * @constant
 * @example
 */
CKEDITOR.NODE_ELEMENT = 1;

/**
 * Text node type.
 * @constant
 * @example
 */
CKEDITOR.NODE_TEXT = 3;

/**
 * Comment node type.
 * @constant
 * @example
 */
CKEDITOR.NODE_COMMENT = 8;

CKEDITOR.NODE_DOCUMENT_FRAGMENT = 11;

CKEDITOR.POSITION_IDENTICAL = 0;
CKEDITOR.POSITION_DISCONNECTED = 1;
CKEDITOR.POSITION_FOLLOWING = 2;
CKEDITOR.POSITION_PRECEDING = 4;
CKEDITOR.POSITION_IS_CONTAINED = 8;
CKEDITOR.POSITION_CONTAINS = 16;

CKEDITOR.tools.extend( CKEDITOR.dom.node.prototype,
	/** @lends CKEDITOR.dom.node.prototype */
	{
		/**
		 * Makes this node child of another element.
		 * @param {CKEDITOR.dom.element} element The target element to which append
		 *		this node.
		 * @returns {CKEDITOR.dom.element} The target element.
		 * @example
		 * var p = new CKEDITOR.dom.element( 'p' );
		 * var strong = new CKEDITOR.dom.element( 'strong' );
		 * strong.appendTo( p );
		 *
		 * // result: "&lt;p&gt;&lt;strong&gt;&lt;/strong&gt;&lt;/p&gt;"
		 */
		appendTo : function( element, toStart )
		{
			element.append( this, toStart );
			return element;
		},

		clone : function( includeChildren, cloneId )
		{
			var $clone = this.$.cloneNode( includeChildren );

			if ( !cloneId )
			{
				var removeIds = function( node )
				{
					if ( node.nodeType != CKEDITOR.NODE_ELEMENT )
						return;

					node.removeAttribute( 'id', false ) ;
					node.removeAttribute( '_cke_expando', false ) ;

					var childs = node.childNodes;
					for ( var i=0 ; i < childs.length ; i++ )
						removeIds( childs[ i ] );
				};

				// The "id" attribute should never be cloned to avoid duplication.
				removeIds( $clone );
			}

			return new CKEDITOR.dom.node( $clone );
		},

		hasPrevious : function()
		{
			return !!this.$.previousSibling;
		},

		hasNext : function()
		{
			return !!this.$.nextSibling;
		},

		/**
		 * Inserts this element after a node.
		 * @param {CKEDITOR.dom.node} node The that will preceed this element.
		 * @returns {CKEDITOR.dom.node} The node preceeding this one after
		 *		insertion.
		 * @example
		 * var em = new CKEDITOR.dom.element( 'em' );
		 * var strong = new CKEDITOR.dom.element( 'strong' );
		 * strong.insertAfter( em );
		 *
		 * // result: "&lt;em&gt;&lt;/em&gt;&lt;strong&gt;&lt;/strong&gt;"
		 */
		insertAfter : function( node )
		{
			node.$.parentNode.insertBefore( this.$, node.$.nextSibling );
			return node;
		},

		/**
		 * Inserts this element before a node.
		 * @param {CKEDITOR.dom.node} node The that will be after this element.
		 * @returns {CKEDITOR.dom.node} The node being inserted.
		 * @example
		 * var em = new CKEDITOR.dom.element( 'em' );
		 * var strong = new CKEDITOR.dom.element( 'strong' );
		 * strong.insertBefore( em );
		 *
		 * // result: "&lt;strong&gt;&lt;/strong&gt;&lt;em&gt;&lt;/em&gt;"
		 */
		insertBefore : function( node )
		{
			node.$.parentNode.insertBefore( this.$, node.$ );
			return node;
		},

		insertBeforeMe : function( node )
		{
			this.$.parentNode.insertBefore( node.$, this.$ );
			return node;
		},

		/**
		 * Retrieves a uniquely identifiable tree address for this node.
		 * The tree address returns is an array of integers, with each integer
		 * indicating a child index of a DOM node, starting from
		 * document.documentElement.
		 *
		 * For example, assuming <body> is the second child from <html> (<head>
		 * being the first), and we'd like to address the third child under the
		 * fourth child of body, the tree address returned would be:
		 * [1, 3, 2]
		 *
		 * The tree address cannot be used for finding back the DOM tree node once
		 * the DOM tree structure has been modified.
		 */
		getAddress : function( normalized )
		{
			var address = [];
			var $documentElement = this.getDocument().$.documentElement;
			var node = this.$;

			while ( node && node != $documentElement )
			{
				var parentNode = node.parentNode;
				var currentIndex = -1;

				for ( var i = 0 ; i < parentNode.childNodes.length ; i++ )
				{
					var candidate = parentNode.childNodes[i];

					if ( normalized &&
							candidate.nodeType == 3 &&
							candidate.previousSibling &&
							candidate.previousSibling.nodeType == 3 )
					{
						continue;
					}

					currentIndex++;

					if ( candidate == node )
						break;
				}

				address.unshift( currentIndex );

				node = node.parentNode;
			}

			return address;
		},

		/**
		 * Gets the document containing this element.
		 * @returns {CKEDITOR.dom.document} The document.
		 * @example
		 * var element = CKEDITOR.document.getById( 'example' );
		 * alert( <b>element.getDocument().equals( CKEDITOR.document )</b> );  // "true"
		 */
		getDocument : function()
		{
			var document = new CKEDITOR.dom.document( this.$.ownerDocument || this.$.parentNode.ownerDocument );

			return (
			/** @ignore */
			this.getDocument = function()
				{
					return document;
				})();
		},

		getIndex : function()
		{
			var $ = this.$;

			var currentNode = $.parentNode && $.parentNode.firstChild;
			var currentIndex = -1;

			while ( currentNode )
			{
				currentIndex++;

				if ( currentNode == $ )
					return currentIndex;

				currentNode = currentNode.nextSibling;
			}

			return -1;
		},

		getNextSourceNode : function( startFromSibling, nodeType, guard )
		{
			// If "guard" is a node, transform it in a function.
			if ( guard && !guard.call )
			{
				var guardNode = guard;
				guard = function( node )
				{
					return !node.equals( guardNode );
				};
			}

			var node = ( !startFromSibling && this.getFirst && this.getFirst() ),
				parent;

			// Guarding when we're skipping the current element( no children or 'startFromSibling' ).
			// send the 'moving out' signal even we don't actually dive into.
			if ( !node )
			{
				if ( this.type == CKEDITOR.NODE_ELEMENT && guard && guard( this, true ) === false )
					return null;
				node = this.getNext();
			}

			while ( !node && ( parent = ( parent || this ).getParent() ) )
			{
				// The guard check sends the "true" paramenter to indicate that
				// we are moving "out" of the element.
				if ( guard && guard( parent, true ) === false )
					return null;

				node = parent.getNext();
			}

			if ( !node )
				return null;

			if ( guard && guard( node ) === false )
				return null;

			if ( nodeType && nodeType != node.type )
				return node.getNextSourceNode( false, nodeType, guard );

			return node;
		},

		getPreviousSourceNode : function( startFromSibling, nodeType, guard )
		{
			if ( guard && !guard.call )
			{
				var guardNode = guard;
				guard = function( node )
				{
					return !node.equals( guardNode );
				};
			}

			var node = ( !startFromSibling && this.getLast && this.getLast() ),
				parent;

			// Guarding when we're skipping the current element( no children or 'startFromSibling' ).
			// send the 'moving out' signal even we don't actually dive into.
			if ( !node )
			{
				if ( this.type == CKEDITOR.NODE_ELEMENT && guard && guard( this, true ) === false )
					return null;
				node = this.getPrevious();
			}

			while ( !node && ( parent = ( parent || this ).getParent() ) )
			{
				// The guard check sends the "true" paramenter to indicate that
				// we are moving "out" of the element.
				if ( guard && guard( parent, true ) === false )
					return null;

				node = parent.getPrevious();
			}

			if ( !node )
				return null;

			if ( guard && guard( node ) === false )
				return null;

			if ( nodeType && node.type != nodeType )
				return node.getPreviousSourceNode( false, nodeType, guard );

			return node;
		},

		getPrevious : function( evaluator )
		{
			var previous = this.$, retval;
			do
			{
				previous = previous.previousSibling;
				retval = previous && new CKEDITOR.dom.node( previous );
			}
			while ( retval && evaluator && !evaluator( retval ) )
			return retval;
		},

		/**
		 * Gets the node that follows this element in its parent's child list.
		 * @param {Function} evaluator Filtering the result node.
		 * @returns {CKEDITOR.dom.node} The next node or null if not available.
		 * @example
		 * var element = CKEDITOR.dom.element.createFromHtml( '&lt;div&gt;&lt;b&gt;Example&lt;/b&gt; &lt;i&gt;next&lt;/i&gt;&lt;/div&gt;' );
		 * var first = <b>element.getFirst().getNext()</b>;
		 * alert( first.getName() );  // "i"
		 */
		getNext : function( evaluator )
		{
			var next = this.$, retval;
			do
			{
				next = next.nextSibling;
				retval = next && new CKEDITOR.dom.node( next );
			}
			while ( retval && evaluator && !evaluator( retval ) )
			return retval;
		},

		/**
		 * Gets the parent element for this node.
		 * @returns {CKEDITOR.dom.element} The parent element.
		 * @example
		 * var node = editor.document.getBody().getFirst();
		 * var parent = node.<b>getParent()</b>;
		 * alert( node.getName() );  // "body"
		 */
		getParent : function()
		{
			var parent = this.$.parentNode;
			return ( parent && parent.nodeType == 1 ) ? new CKEDITOR.dom.node( parent ) : null;
		},

		getParents : function( closerFirst )
		{
			var node = this;
			var parents = [];

			do
			{
				parents[  closerFirst ? 'push' : 'unshift' ]( node );
			}
			while ( ( node = node.getParent() ) )

			return parents;
		},

		getCommonAncestor : function( node )
		{
			if ( node.equals( this ) )
				return this;

			if ( node.contains && node.contains( this ) )
				return node;

			var start = this.contains ? this : this.getParent();

			do
			{
				if ( start.contains( node ) )
					return start;
			}
			while ( ( start = start.getParent() ) );

			return null;
		},

		getPosition : function( otherNode )
		{
			var $ = this.$;
			var $other = otherNode.$;

			if ( $.compareDocumentPosition )
				return $.compareDocumentPosition( $other );

			// IE and Safari have no support for compareDocumentPosition.

			if ( $ == $other )
				return CKEDITOR.POSITION_IDENTICAL;

			// Only element nodes support contains and sourceIndex.
			if ( this.type == CKEDITOR.NODE_ELEMENT && otherNode.type == CKEDITOR.NODE_ELEMENT )
			{
				if ( $.contains )
				{
					if ( $.contains( $other ) )
						return CKEDITOR.POSITION_CONTAINS + CKEDITOR.POSITION_PRECEDING;

					if ( $other.contains( $ ) )
						return CKEDITOR.POSITION_IS_CONTAINED + CKEDITOR.POSITION_FOLLOWING;
				}

				if ( 'sourceIndex' in $ )
				{
					return ( $.sourceIndex < 0 || $other.sourceIndex < 0 ) ? CKEDITOR.POSITION_DISCONNECTED :
						( $.sourceIndex < $other.sourceIndex ) ? CKEDITOR.POSITION_PRECEDING :
						CKEDITOR.POSITION_FOLLOWING;
				}
			}

			// For nodes that don't support compareDocumentPosition, contains
			// or sourceIndex, their "address" is compared.

			var addressOfThis = this.getAddress(),
				addressOfOther = otherNode.getAddress(),
				minLevel = Math.min( addressOfThis.length, addressOfOther.length );

				// Determinate preceed/follow relationship.
				for ( var i = 0 ; i <= minLevel - 1 ; i++ )
 				{
					if ( addressOfThis[ i ] != addressOfOther[ i ] )
					{
						if ( i < minLevel )
						{
							return addressOfThis[ i ] < addressOfOther[ i ] ?
						            CKEDITOR.POSITION_PRECEDING : CKEDITOR.POSITION_FOLLOWING;
						}
						break;
					}
 				}

				// Determinate contains/contained relationship.
				return ( addressOfThis.length < addressOfOther.length ) ?
							CKEDITOR.POSITION_CONTAINS + CKEDITOR.POSITION_PRECEDING :
							CKEDITOR.POSITION_IS_CONTAINED + CKEDITOR.POSITION_FOLLOWING;
		},

		/**
		 * Gets the closes ancestor node of a specified node name.
		 * @param {String} name Node name of ancestor node.
		 * @param {Boolean} includeSelf (Optional) Whether to include the current
		 * node in the calculation or not.
		 * @returns {CKEDITOR.dom.node} Ancestor node.
		 */
		getAscendant : function( name, includeSelf )
		{
			var $ = this.$;

			if ( !includeSelf )
				$ = $.parentNode;

			while ( $ )
			{
				if ( $.nodeName && $.nodeName.toLowerCase() == name )
					return new CKEDITOR.dom.node( $ );

				$ = $.parentNode;
			}
			return null;
		},

		hasAscendant : function( name, includeSelf )
		{
			var $ = this.$;

			if ( !includeSelf )
				$ = $.parentNode;

			while ( $ )
			{
				if ( $.nodeName && $.nodeName.toLowerCase() == name )
					return true;

				$ = $.parentNode;
			}
			return false;
		},

		move : function( target, toStart )
		{
			target.append( this.remove(), toStart );
		},

		/**
		 * Removes this node from the document DOM.
		 * @param {Boolean} [preserveChildren] Indicates that the children
		 *		elements must remain in the document, removing only the outer
		 *		tags.
		 * @example
		 * var element = CKEDITOR.dom.element.getById( 'MyElement' );
		 * <b>element.remove()</b>;
		 */
		remove : function( preserveChildren )
		{
			var $ = this.$;
			var parent = $.parentNode;

			if ( parent )
			{
				if ( preserveChildren )
				{
					// Move all children before the node.
					for ( var child ; ( child = $.firstChild ) ; )
					{
						parent.insertBefore( $.removeChild( child ), $ );
					}
				}

				parent.removeChild( $ );
			}

			return this;
		},

		replace : function( nodeToReplace )
		{
			this.insertBefore( nodeToReplace );
			nodeToReplace.remove();
		},

		trim : function()
		{
			this.ltrim();
			this.rtrim();
		},

		ltrim : function()
		{
			var child;
			while ( this.getFirst && ( child = this.getFirst() ) )
			{
				if ( child.type == CKEDITOR.NODE_TEXT )
				{
					var trimmed = CKEDITOR.tools.ltrim( child.getText() ),
						originalLength = child.getLength();

					if ( !trimmed )
					{
						child.remove();
						continue;
					}
					else if ( trimmed.length < originalLength )
					{
						child.split( originalLength - trimmed.length );

						// IE BUG: child.remove() may raise JavaScript errors here. (#81)
						this.$.removeChild( this.$.firstChild );
					}
				}
				break;
			}
		},

		rtrim : function()
		{
			var child;
			while ( this.getLast && ( child = this.getLast() ) )
			{
				if ( child.type == CKEDITOR.NODE_TEXT )
				{
					var trimmed = CKEDITOR.tools.rtrim( child.getText() ),
						originalLength = child.getLength();

					if ( !trimmed )
					{
						child.remove();
						continue;
					}
					else if ( trimmed.length < originalLength )
					{
						child.split( trimmed.length );

						// IE BUG: child.getNext().remove() may raise JavaScript errors here.
						// (#81)
						this.$.lastChild.parentNode.removeChild( this.$.lastChild );
					}
				}
				break;
			}

			if ( !CKEDITOR.env.ie && !CKEDITOR.env.opera )
			{
				child = this.$.lastChild;

				if ( child && child.type == 1 && child.nodeName.toLowerCase() == 'br' )
				{
					// Use "eChildNode.parentNode" instead of "node" to avoid IE bug (#324).
					child.parentNode.removeChild( child ) ;
				}
			}
		}
	}
);
