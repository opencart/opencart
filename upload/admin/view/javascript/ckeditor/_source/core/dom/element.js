/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.dom.element} class, which
 *		represents a DOM element.
 */

/**
 * Represents a DOM element.
 * @constructor
 * @augments CKEDITOR.dom.node
 * @param {Object|String} element A native DOM element or the element name for
 *		new elements.
 * @param {CKEDITOR.dom.document} [ownerDocument] The document that will contain
 *		the element in case of element creation.
 * @example
 * // Create a new &lt;span&gt; element.
 * var element = new CKEDITOR.dom.element( 'span' );
 * @example
 * // Create an element based on a native DOM element.
 * var element = new CKEDITOR.dom.element( document.getElementById( 'myId' ) );
 */
CKEDITOR.dom.element = function( element, ownerDocument )
{
	if ( typeof element == 'string' )
		element = ( ownerDocument ? ownerDocument.$ : document ).createElement( element );

	// Call the base constructor (we must not call CKEDITOR.dom.node).
	CKEDITOR.dom.domObject.call( this, element );
};

// PACKAGER_RENAME( CKEDITOR.dom.element )

/**
 * The the {@link CKEDITOR.dom.element} representing and element. If the
 * element is a native DOM element, it will be transformed into a valid
 * CKEDITOR.dom.element object.
 * @returns {CKEDITOR.dom.element} The transformed element.
 * @example
 * var element = new CKEDITOR.dom.element( 'span' );
 * alert( element == <b>CKEDITOR.dom.element.get( element )</b> );  "true"
 * @example
 * var element = document.getElementById( 'myElement' );
 * alert( <b>CKEDITOR.dom.element.get( element )</b>.getName() );  e.g. "p"
 */
CKEDITOR.dom.element.get = function( element )
{
	return element && ( element.$ ? element : new CKEDITOR.dom.element( element ) );
};

CKEDITOR.dom.element.prototype = new CKEDITOR.dom.node();

/**
 * Creates an instance of the {@link CKEDITOR.dom.element} class based on the
 * HTML representation of an element.
 * @param {String} html The element HTML. It should define only one element in
 *		the "root" level. The "root" element can have child nodes, but not
 *		siblings.
 * @returns {CKEDITOR.dom.element} The element instance.
 * @example
 * var element = <b>CKEDITOR.dom.element.createFromHtml( '&lt;strong class="anyclass"&gt;My element&lt;/strong&gt;' )</b>;
 * alert( element.getName() );  // "strong"
 */
CKEDITOR.dom.element.createFromHtml = function( html, ownerDocument )
{
	var temp = new CKEDITOR.dom.element( 'div', ownerDocument );
	temp.setHtml( html );

	// When returning the node, remove it from its parent to detach it.
	return temp.getFirst().remove();
};

CKEDITOR.dom.element.setMarker = function( database, element, name, value )
{
	var id = element.getCustomData( 'list_marker_id' ) ||
			( element.setCustomData( 'list_marker_id', CKEDITOR.tools.getNextNumber() ).getCustomData( 'list_marker_id' ) ),
		markerNames = element.getCustomData( 'list_marker_names' ) ||
			( element.setCustomData( 'list_marker_names', {} ).getCustomData( 'list_marker_names' ) );
	database[id] = element;
	markerNames[name] = 1;

	return element.setCustomData( name, value );
};

CKEDITOR.dom.element.clearAllMarkers = function( database )
{
	for ( var i in database )
		CKEDITOR.dom.element.clearMarkers( database, database[i], true );
};

CKEDITOR.dom.element.clearMarkers = function( database, element, removeFromDatabase )
{
	var names = element.getCustomData( 'list_marker_names' ),
		id = element.getCustomData( 'list_marker_id' );
	for ( var i in names )
		element.removeCustomData( i );
	element.removeCustomData( 'list_marker_names' );
	if ( removeFromDatabase )
	{
		element.removeCustomData( 'list_marker_id' );
		delete database[id];
	}
};

CKEDITOR.tools.extend( CKEDITOR.dom.element.prototype,
	/** @lends CKEDITOR.dom.element.prototype */
	{
		/**
		 * The node type. This is a constant value set to
		 * {@link CKEDITOR.NODE_ELEMENT}.
		 * @type Number
		 * @example
		 */
		type : CKEDITOR.NODE_ELEMENT,

		/**
		 * Adds a CSS class to the element. It appends the class to the
		 * already existing names.
		 * @param {String} className The name of the class to be added.
		 * @example
		 * var element = new CKEDITOR.dom.element( 'div' );
		 * element.addClass( 'classA' );  // &lt;div class="classA"&gt;
		 * element.addClass( 'classB' );  // &lt;div class="classA classB"&gt;
		 * element.addClass( 'classA' );  // &lt;div class="classA classB"&gt;
		 */
		addClass : function( className )
		{
			var c = this.$.className;
			if ( c )
			{
				var regex = new RegExp( '(?:^|\\s)' + className + '(?:\\s|$)', '' );
				if ( !regex.test( c ) )
					c += ' ' + className;
			}
			this.$.className = c || className;
		},

		/**
		 * Removes a CSS class name from the elements classes. Other classes
		 * remain untouched.
		 * @param {String} className The name of the class to remove.
		 * @example
		 * var element = new CKEDITOR.dom.element( 'div' );
		 * element.addClass( 'classA' );  // &lt;div class="classA"&gt;
		 * element.addClass( 'classB' );  // &lt;div class="classA classB"&gt;
		 * element.removeClass( 'classA' );  // &lt;div class="classB"&gt;
		 * element.removeClass( 'classB' );  // &lt;div&gt;
		 */
		removeClass : function( className )
		{
			var c = this.getAttribute( 'class' );
			if ( c )
			{
				var regex = new RegExp( '(?:^|\\s+)' + className + '(?=\\s|$)', 'i' );
				if ( regex.test( c ) )
				{
					c = c.replace( regex, '' ).replace( /^\s+/, '' );

					if ( c )
						this.setAttribute( 'class', c );
					else
						this.removeAttribute( 'class' );
				}
			}
		},

		hasClass : function( className )
		{
			var regex = new RegExp( '(?:^|\\s+)' + className + '(?=\\s|$)', '' );
			return regex.test( this.getAttribute('class') );
		},

		/**
		 * Append a node as a child of this element.
		 * @param {CKEDITOR.dom.node|String} node The node or element name to be
		 *		appended.
		 * @param {Boolean} [toStart] Indicates that the element is to be
		 *		appended at the start.
		 * @returns {CKEDITOR.dom.node} The appended node.
		 * @example
		 * var p = new CKEDITOR.dom.element( 'p' );
		 *
		 * var strong = new CKEDITOR.dom.element( 'strong' );
		 * <b>p.append( strong );</b>
		 *
		 * var em = <b>p.append( 'em' );</b>
		 *
		 * // result: "&lt;p&gt;&lt;strong&gt;&lt;/strong&gt;&lt;em&gt;&lt;/em&gt;&lt;/p&gt;"
		 */
		append : function( node, toStart )
		{
			if ( typeof node == 'string' )
				node = this.getDocument().createElement( node );

			if ( toStart )
				this.$.insertBefore( node.$, this.$.firstChild );
			else
				this.$.appendChild( node.$ );

			return node;
		},

		appendHtml : function( html )
		{
			if ( !this.$.childNodes.length )
				this.setHtml( html );
			else
			{
				var temp = new CKEDITOR.dom.element( 'div', this.getDocument() );
				temp.setHtml( html );
				temp.moveChildren( this );
			}
		},

		/**
		 * Append text to this element.
		 * @param {String} text The text to be appended.
		 * @returns {CKEDITOR.dom.node} The appended node.
		 * @example
		 * var p = new CKEDITOR.dom.element( 'p' );
		 * p.appendText( 'This is' );
		 * p.appendText( ' some text' );
		 *
		 * // result: "&lt;p&gt;This is some text&lt;/p&gt;"
		 */
		appendText : function( text )
		{
			if ( this.$.text != undefined )
				this.$.text += text;
			else
				this.append( new CKEDITOR.dom.text( text ) );
		},

		appendBogus : function()
		{
			var lastChild = this.getLast() ;

			// Ignore empty/spaces text.
			while ( lastChild && lastChild.type == CKEDITOR.NODE_TEXT && !CKEDITOR.tools.rtrim( lastChild.getText() ) )
				lastChild = lastChild.getPrevious();
			if ( !lastChild || !lastChild.is || !lastChild.is( 'br' ) )
			{
				this.append(
					CKEDITOR.env.opera ?
						this.getDocument().createText('') :
						this.getDocument().createElement( 'br' ) );
			}
		},

		/**
		 * Breaks one of the ancestor element in the element position, moving
		 * this element between the broken parts.
		 * @param {CKEDITOR.dom.element} parent The anscestor element to get broken.
		 * @example
		 * // Before breaking:
		 * //     <b>This <i>is some<span /> sample</i> test text</b>
		 * // If "element" is <span /> and "parent" is <i>:
		 * //     <b>This <i>is some</i><span /><i> sample</i> test text</b>
		 * element.breakParent( parent );
		 * @example
		 * // Before breaking:
		 * //     <b>This <i>is some<span /> sample</i> test text</b>
		 * // If "element" is <span /> and "parent" is <b>:
		 * //     <b>This <i>is some</i></b><span /><b><i> sample</i> test text</b>
		 * element.breakParent( parent );
		 */
		breakParent : function( parent )
		{
			var range = new CKEDITOR.dom.range( this.getDocument() );

			// We'll be extracting part of this element, so let's use our
			// range to get the correct piece.
			range.setStartAfter( this );
			range.setEndAfter( parent );

			// Extract it.
			var docFrag = range.extractContents();

			// Move the element outside the broken element.
			range.insertNode( this.remove() );

			// Re-insert the extracted piece after the element.
			docFrag.insertAfterNode( this );
		},

		contains :
			CKEDITOR.env.ie || CKEDITOR.env.webkit ?
				function( node )
				{
					var $ = this.$;

					return node.type != CKEDITOR.NODE_ELEMENT ?
						$.contains( node.getParent().$ ) :
						$ != node.$ && $.contains( node.$ );
				}
			:
				function( node )
				{
					return !!( this.$.compareDocumentPosition( node.$ ) & 16 );
				},

		/**
		 * Moves the selection focus to this element.
		 * @example
		 * var element = CKEDITOR.document.getById( 'myTextarea' );
		 * <b>element.focus()</b>;
		 */
		focus : function()
		{
			// IE throws error if the element is not visible.
			try
			{
				this.$.focus();
			}
			catch (e)
			{}
		},

		/**
		 * Gets the inner HTML of this element.
		 * @returns {String} The inner HTML of this element.
		 * @example
		 * var element = CKEDITOR.dom.element.createFromHtml( '&lt;div&gt;&lt;b&gt;Example&lt;/b&gt;&lt;/div&gt;' );
		 * alert( <b>p.getHtml()</b> );  // "&lt;b&gt;Example&lt;/b&gt;"
		 */
		getHtml : function()
		{
			return this.$.innerHTML;
		},

		getOuterHtml : function()
		{
			if ( this.$.outerHTML )
			{
				// IE includes the <?xml:namespace> tag in the outerHTML of
				// namespaced element. So, we must strip it here. (#3341)
				return this.$.outerHTML.replace( /<\?[^>]*>/, '' );
			}

			var tmpDiv = this.$.ownerDocument.createElement( 'div' );
			tmpDiv.appendChild( this.$.cloneNode( true ) );
			return tmpDiv.innerHTML;
		},

		/**
		 * Sets the inner HTML of this element.
		 * @param {String} html The HTML to be set for this element.
		 * @returns {String} The inserted HTML.
		 * @example
		 * var p = new CKEDITOR.dom.element( 'p' );
		 * <b>p.setHtml( '&lt;b&gt;Inner&lt;/b&gt; HTML' );</b>
		 *
		 * // result: "&lt;p&gt;&lt;b&gt;Inner&lt;/b&gt; HTML&lt;/p&gt;"
		 */
		setHtml : function( html )
		{
			return ( this.$.innerHTML = html );
		},

		/**
		 * Sets the element contents as plain text.
		 * @param {String} text The text to be set.
		 * @returns {String} The inserted text.
		 * @example
		 * var element = new CKEDITOR.dom.element( 'div' );
		 * element.setText( 'A > B & C < D' );
		 * alert( element.innerHTML );  // "A &amp;gt; B &amp;amp; C &amp;lt; D"
		 */
		setText : function( text )
		{
			CKEDITOR.dom.element.prototype.setText = ( this.$.innerText != undefined ) ?
				function ( text )
				{
					return this.$.innerText = text;
				} :
				function ( text )
				{
					return this.$.textContent = text;
				};

			return this.setText( text );
		},

		/**
		 * Gets the value of an element attribute.
		 * @function
		 * @param {String} name The attribute name.
		 * @returns {String} The attribute value or null if not defined.
		 * @example
		 * var element = CKEDITOR.dom.element.createFromHtml( '&lt;input type="text" /&gt;' );
		 * alert( <b>element.getAttribute( 'type' )</b> );  // "text"
		 */
		getAttribute : (function()
		{
			var standard = function( name )
			{
				return this.$.getAttribute( name, 2 );
			};

			if ( CKEDITOR.env.ie && ( CKEDITOR.env.ie7Compat || CKEDITOR.env.ie6Compat ) )
			{
				return function( name )
				{
					switch ( name )
					{
						case 'class':
							name = 'className';
							break;

						case 'tabindex':
							var tabIndex = standard.call( this, name );

							// IE returns tabIndex=0 by default for all
							// elements. For those elements,
							// getAtrribute( 'tabindex', 2 ) returns 32768
							// instead. So, we must make this check to give a
							// uniform result among all browsers.
							if ( tabIndex !== 0 && this.$.tabIndex === 0 )
								tabIndex = null;

							return tabIndex;
							break;

						case 'checked':
							return this.$.checked;
							break;

						case 'style':
							// IE does not return inline styles via getAttribute(). See #2947.
							return this.$.style.cssText;
					}

					return standard.call( this, name );
				};
			}
			else
				return standard;
		})(),

		getChildren : function()
		{
			return new CKEDITOR.dom.nodeList( this.$.childNodes );
		},

		/**
		 * Gets the current computed value of one of the element CSS style
		 * properties.
		 * @function
		 * @param {String} propertyName The style property name.
		 * @returns {String} The property value.
		 * @example
		 * var element = new CKEDITOR.dom.element( 'span' );
		 * alert( <b>element.getComputedStyle( 'display' )</b> );  // "inline"
		 */
		getComputedStyle :
			CKEDITOR.env.ie ?
				function( propertyName )
				{
					return this.$.currentStyle[ CKEDITOR.tools.cssStyleToDomStyle( propertyName ) ];
				}
			:
				function( propertyName )
				{
					return this.getWindow().$.getComputedStyle( this.$, '' ).getPropertyValue( propertyName );
				},

		/**
		 * Gets the DTD entries for this element.
		 * @returns {Object} An object containing the list of elements accepted
		 *		by this element.
		 */
		getDtd : function()
		{
			var dtd = CKEDITOR.dtd[ this.getName() ];

			this.getDtd = function()
			{
				return dtd;
			};

			return dtd;
		},

		getElementsByTag : CKEDITOR.dom.document.prototype.getElementsByTag,

		/**
		 * Gets the computed tabindex for this element.
		 * @function
		 * @returns {Number} The tabindex value.
		 * @example
		 * var element = CKEDITOR.document.getById( 'myDiv' );
		 * alert( <b>element.getTabIndex()</b> );  // e.g. "-1"
		 */
		getTabIndex :
			CKEDITOR.env.ie ?
				function()
				{
					var tabIndex = this.$.tabIndex;

					// IE returns tabIndex=0 by default for all elements. In
					// those cases we must check that the element really has
					// the tabindex attribute set to zero, or it is one of
					// those element that should have zero by default.
					if ( tabIndex === 0 && !CKEDITOR.dtd.$tabIndex[ this.getName() ] && parseInt( this.getAttribute( 'tabindex' ), 10 ) !== 0 )
						tabIndex = -1;

						return tabIndex;
				}
			: CKEDITOR.env.webkit ?
				function()
				{
					var tabIndex = this.$.tabIndex;

					// Safari returns "undefined" for elements that should not
					// have tabindex (like a div). So, we must try to get it
					// from the attribute.
					// https://bugs.webkit.org/show_bug.cgi?id=20596
					if ( tabIndex == undefined )
					{
						tabIndex = parseInt( this.getAttribute( 'tabindex' ), 10 );

						// If the element don't have the tabindex attribute,
						// then we should return -1.
						if ( isNaN( tabIndex ) )
							tabIndex = -1;
					}

					return tabIndex;
				}
			:
				function()
				{
					return this.$.tabIndex;
				},

		/**
		 * Gets the text value of this element.
		 *
		 * Only in IE (which uses innerText), &lt;br&gt; will cause linebreaks,
		 * and sucessive whitespaces (including line breaks) will be reduced to
		 * a single space. This behavior is ok for us, for now. It may change
		 * in the future.
		 * @returns {String} The text value.
		 * @example
		 * var element = CKEDITOR.dom.element.createFromHtml( '&lt;div&gt;Same &lt;i&gt;text&lt;/i&gt;.&lt;/div&gt;' );
		 * alert( <b>element.getText()</b> );  // "Sample text."
		 */
		getText : function()
		{
			return this.$.textContent || this.$.innerText || '';
		},

		/**
		 * Gets the window object that contains this element.
		 * @returns {CKEDITOR.dom.window} The window object.
		 * @example
		 */
		getWindow : function()
		{
			return this.getDocument().getWindow();
		},

		/**
		 * Gets the value of the "id" attribute of this element.
		 * @returns {String} The element id, or null if not available.
		 * @example
		 * var element = CKEDITOR.dom.element.createFromHtml( '&lt;p id="myId"&gt;&lt;/p&gt;' );
		 * alert( <b>element.getId()</b> );  // "myId"
		 */
		getId : function()
		{
			return this.$.id || null;
		},

		/**
		 * Gets the value of the "name" attribute of this element.
		 * @returns {String} The element name, or null if not available.
		 * @example
		 * var element = CKEDITOR.dom.element.createFromHtml( '&lt;input name="myName"&gt;&lt;/input&gt;' );
		 * alert( <b>element.getNameAtt()</b> );  // "myName"
		 */
		getNameAtt : function()
		{
			return this.$.name || null;
		},

		/**
		 * Gets the element name (tag name). The returned name is guaranteed to
		 * be always full lowercased.
		 * @returns {String} The element name.
		 * @example
		 * var element = new CKEDITOR.dom.element( 'span' );
		 * alert( <b>element.getName()</b> );  // "span"
		 */
		getName : function()
		{
			// Cache the lowercased name inside a closure.
			var nodeName = this.$.nodeName.toLowerCase();

			if ( CKEDITOR.env.ie )
			{
				var scopeName = this.$.scopeName;
				if ( scopeName != 'HTML' )
					nodeName = scopeName.toLowerCase() + ':' + nodeName;
			}

			return (
			/** @ignore */
			this.getName = function()
				{
					return nodeName;
				})();
		},

		/**
		 * Gets the value set to this element. This value is usually available
		 * for form field elements.
		 * @returns {String} The element value.
		 */
		getValue : function()
		{
			return this.$.value;
		},

		/**
		 * Gets the first child node of this element.
		 * @returns {CKEDITOR.dom.node} The first child node or null if not
		 *		available.
		 * @example
		 * var element = CKEDITOR.dom.element.createFromHtml( '&lt;div&gt;&lt;b&gt;Example&lt;/b&gt;&lt;/div&gt;' );
		 * var first = <b>element.getFirst()</b>;
		 * alert( first.getName() );  // "b"
		 */
		getFirst : function()
		{
			var $ = this.$.firstChild;
			return $ ? new CKEDITOR.dom.node( $ ) : null;
		},

		/**
		 * @param {Function} evaluator Filtering the result node.
		 */
		getLast : function( evaluator )
		{
			var last = this.$.lastChild,
				retval = last && new CKEDITOR.dom.node( last );
			if ( retval && evaluator && !evaluator( retval ) )
				retval = retval.getPrevious( evaluator );

			return retval;
		},

		getStyle : function( name )
		{
			return this.$.style[ CKEDITOR.tools.cssStyleToDomStyle( name ) ];
		},

		/**
		 * Checks if the element name matches one or more names.
		 * @param {String} name[,name[,...]] One or more names to be checked.
		 * @returns {Boolean} true if the element name matches any of the names.
		 * @example
		 * var element = new CKEDITOR.element( 'span' );
		 * alert( <b>element.is( 'span' )</b> );  "true"
		 * alert( <b>element.is( 'p', 'span' )</b> );  "true"
		 * alert( <b>element.is( 'p' )</b> );  "false"
		 * alert( <b>element.is( 'p', 'div' )</b> );  "false"
		 */
		is : function()
		{
			var name = this.getName();
			for ( var i = 0 ; i < arguments.length ; i++ )
			{
				if ( arguments[ i ] == name )
					return true;
			}
			return false;
		},

		isEditable : function()
		{
			// Get the element name.
			var name = this.getName();

			// Get the element DTD (defaults to span for unknown elements).
			var dtd = !CKEDITOR.dtd.$nonEditable[ name ]
						&& ( CKEDITOR.dtd[ name ] || CKEDITOR.dtd.span );

			// In the DTD # == text node.
			return ( dtd && dtd['#'] );
		},

		isIdentical : function( otherElement )
		{
			if ( this.getName() != otherElement.getName() )
				return false;

			var thisAttribs = this.$.attributes,
				otherAttribs = otherElement.$.attributes;

			var thisLength = thisAttribs.length,
				otherLength = otherAttribs.length;

			if ( !CKEDITOR.env.ie && thisLength != otherLength )
				return false;

			for ( var i = 0 ; i < thisLength ; i++ )
			{
				var attribute = thisAttribs[ i ];

				if ( ( !CKEDITOR.env.ie || ( attribute.specified && attribute.nodeName != '_cke_expando' ) ) && attribute.nodeValue != otherElement.getAttribute( attribute.nodeName ) )
					return false;
			}

			// For IE, we have to for both elements, because it's difficult to
			// know how the atttibutes collection is organized in its DOM.
			if ( CKEDITOR.env.ie )
			{
				for ( i = 0 ; i < otherLength ; i++ )
				{
					attribute = otherAttribs[ i ];

					if ( ( !CKEDITOR.env.ie || ( attribute.specified && attribute.nodeName != '_cke_expando' ) ) && attribute.nodeValue != thisAttribs.getAttribute( attribute.nodeName ) )
						return false;
				}
			}

			return true;
		},

		/**
		 * Checks if this element is visible. May not work if the element is
		 * child of an element with visibility set to "hidden", but works well
		 * on the great majority of cases.
		 * @return {Boolean} True if the element is visible.
		 */
		isVisible : function()
		{
			return this.$.offsetWidth && ( this.$.style.visibility != 'hidden' );
		},

		/**
		 * Indicates that the element has defined attributes.
		 * @returns {Boolean} True if the element has attributes.
		 * @example
		 * var element = CKEDITOR.dom.element.createFromHtml( '<div title="Test">Example</div>' );
		 * alert( <b>element.hasAttributes()</b> );  "true"
		 * @example
		 * var element = CKEDITOR.dom.element.createFromHtml( '<div>Example</div>' );
		 * alert( <b>element.hasAttributes()</b> );  "false"
		 */
		hasAttributes :
			CKEDITOR.env.ie && ( CKEDITOR.env.ie7Compat || CKEDITOR.env.ie6Compat ) ?
				function()
				{
					var attributes = this.$.attributes;

					for ( var i = 0 ; i < attributes.length ; i++ )
					{
						var attribute = attributes[i];

						switch ( attribute.nodeName )
						{
							case 'class' :
								// IE has a strange bug. If calling removeAttribute('className'),
								// the attributes collection will still contain the "class"
								// attribute, which will be marked as "specified", even if the
								// outerHTML of the element is not displaying the class attribute.
								// Note : I was not able to reproduce it outside the editor,
								// but I've faced it while working on the TC of #1391.
								if ( this.getAttribute( 'class' ) )
									return true;

							// Attributes to be ignored.
							case '_cke_expando' :
								continue;

							/*jsl:fallthru*/

							default :
								if ( attribute.specified )
									return true;
						}
					}

					return false;
				}
			:
				function()
				{
					var attributes = this.$.attributes;
					return ( attributes.length > 1 || ( attributes.length == 1 && attributes[0].nodeName != '_cke_expando' ) );
				},

		/**
		 * Indicates whether a specified attribute is defined for this element.
		 * @returns {Boolean} True if the specified attribute is defined.
		 * @param (String) name The attribute name.
		 * @example
		 */
		hasAttribute : function( name )
		{
			var $attr = this.$.attributes.getNamedItem( name );
			return !!( $attr && $attr.specified );
		},

		/**
		 * Hides this element (display:none).
		 * @example
		 * var element = CKEDITOR.dom.element.getById( 'myElement' );
		 * <b>element.hide()</b>;
		 */
		hide : function()
		{
			this.setStyle( 'display', 'none' );
		},

		moveChildren : function( target, toStart )
		{
			var $ = this.$;
			target = target.$;

			if ( $ == target )
				return;

			var child;

			if ( toStart )
			{
				while ( ( child = $.lastChild ) )
					target.insertBefore( $.removeChild( child ), target.firstChild );
			}
			else
			{
				while ( ( child = $.firstChild ) )
					target.appendChild( $.removeChild( child ) );
			}
		},

		/**
		 * Shows this element (display it).
		 * @example
		 * var element = CKEDITOR.dom.element.getById( 'myElement' );
		 * <b>element.show()</b>;
		 */
		show : function()
		{
			this.setStyles(
				{
					display : '',
					visibility : ''
				});
		},

		/**
		 * Sets the value of an element attribute.
		 * @param {String} name The name of the attribute.
		 * @param {String} value The value to be set to the attribute.
		 * @function
		 * @returns {CKEDITOR.dom.element} This element instance.
		 * @example
		 * var element = CKEDITOR.dom.element.getById( 'myElement' );
		 * <b>element.setAttribute( 'class', 'myClass' )</b>;
		 * <b>element.setAttribute( 'title', 'This is an example' )</b>;
		 */
		setAttribute : (function()
		{
			var standard = function( name, value )
			{
				this.$.setAttribute( name, value );
				return this;
			};

			if ( CKEDITOR.env.ie && ( CKEDITOR.env.ie7Compat || CKEDITOR.env.ie6Compat ) )
			{
				return function( name, value )
				{
					if ( name == 'class' )
						this.$.className = value;
					else if ( name == 'style' )
						this.$.style.cssText = value;
					else if ( name == 'tabindex' )	// Case sensitive.
						this.$.tabIndex = value;
					else if ( name == 'checked' )
						this.$.checked = value;
					else
						standard.apply( this, arguments );
					return this;
				};
			}
			else
				return standard;
		})(),

		/**
		 * Sets the value of several element attributes.
		 * @param {Object} attributesPairs An object containing the names and
		 *		values of the attributes.
		 * @returns {CKEDITOR.dom.element} This element instance.
		 * @example
		 * var element = CKEDITOR.dom.element.getById( 'myElement' );
		 * <b>element.setAttributes({
		 *     'class' : 'myClass',
		 *     'title' : 'This is an example' })</b>;
		 */
		setAttributes : function( attributesPairs )
		{
			for ( var name in attributesPairs )
				this.setAttribute( name, attributesPairs[ name ] );
			return this;
		},

		/**
		 * Sets the element value. This function is usually used with form
		 * field element.
		 * @param {String} value The element value.
		 * @returns {CKEDITOR.dom.element} This element instance.
		 */
		setValue : function( value )
		{
			this.$.value = value;
			return this;
		},

		/**
		 * Removes an attribute from the element.
		 * @param {String} name The attribute name.
		 * @function
		 * @example
		 * var element = CKEDITOR.dom.element.createFromHtml( '<div class="classA"></div>' );
		 * element.removeAttribute( 'class' );
		 */
		removeAttribute : (function()
		{
			var standard = function( name )
			{
				this.$.removeAttribute( name );
			};

			if ( CKEDITOR.env.ie && ( CKEDITOR.env.ie7Compat || CKEDITOR.env.ie6Compat ) )
			{
				return function( name )
				{
					if ( name == 'class' )
						name = 'className';
					else if ( name == 'tabindex' )
						name = 'tabIndex';
					standard.call( this, name );
				};
			}
			else
				return standard;
		})(),

		removeAttributes : function ( attributes )
		{
			for ( var i = 0 ; i < attributes.length ; i++ )
				this.removeAttribute( attributes[ i ] );
		},

		/**
		 * Removes a style from the element.
		 * @param {String} name The style name.
		 * @function
		 * @example
		 * var element = CKEDITOR.dom.element.createFromHtml( '<div style="display:none"></div>' );
		 * element.removeStyle( 'display' );
		 */
		removeStyle : function( name )
		{
			if ( this.$.style.removeAttribute )
				this.$.style.removeAttribute( CKEDITOR.tools.cssStyleToDomStyle( name ) );
			else
				this.setStyle( name, '' );

			if ( !this.$.style.cssText )
				this.removeAttribute( 'style' );
		},

		/**
		 * Sets the value of an element style.
		 * @param {String} name The name of the style. The CSS naming notation
		 *		must be used (e.g. "background-color").
		 * @param {String} value The value to be set to the style.
		 * @returns {CKEDITOR.dom.element} This element instance.
		 * @example
		 * var element = CKEDITOR.dom.element.getById( 'myElement' );
		 * <b>element.setStyle( 'background-color', '#ff0000' )</b>;
		 * <b>element.setStyle( 'margin-top', '10px' )</b>;
		 * <b>element.setStyle( 'float', 'right' )</b>;
		 */
		setStyle : function( name, value )
		{
			this.$.style[ CKEDITOR.tools.cssStyleToDomStyle( name ) ] = value;
			return this;
		},

		/**
		 * Sets the value of several element styles.
		 * @param {Object} stylesPairs An object containing the names and
		 *		values of the styles.
		 * @returns {CKEDITOR.dom.element} This element instance.
		 * @example
		 * var element = CKEDITOR.dom.element.getById( 'myElement' );
		 * <b>element.setStyles({
		 *     'position' : 'absolute',
		 *     'float' : 'right' })</b>;
		 */
		setStyles : function( stylesPairs )
		{
			for ( var name in stylesPairs )
				this.setStyle( name, stylesPairs[ name ] );
			return this;
		},

		/**
		 * Sets the opacity of an element.
		 * @param {Number} opacity A number within the range [0.0, 1.0].
		 * @example
		 * var element = CKEDITOR.dom.element.getById( 'myElement' );
		 * <b>element.setOpacity( 0.75 )</b>;
		 */
		setOpacity : function( opacity )
		{
			if ( CKEDITOR.env.ie )
			{
				opacity = Math.round( opacity * 100 );
				this.setStyle( 'filter', opacity >= 100 ? '' : 'progid:DXImageTransform.Microsoft.Alpha(opacity=' + opacity + ')' );
			}
			else
				this.setStyle( 'opacity', opacity );
		},

		/**
		 * Makes the element and its children unselectable.
		 * @function
		 * @example
		 * var element = CKEDITOR.dom.element.getById( 'myElement' );
		 * element.unselectable();
		 */
		unselectable :
			CKEDITOR.env.gecko ?
				function()
				{
					this.$.style.MozUserSelect = 'none';
				}
			: CKEDITOR.env.webkit ?
				function()
				{
					this.$.style.KhtmlUserSelect = 'none';
				}
			:
				function()
				{
					if ( CKEDITOR.env.ie || CKEDITOR.env.opera )
					{
						var element = this.$,
							e,
							i = 0;

						element.unselectable = 'on';

						while ( ( e = element.all[ i++ ] ) )
						{
							switch ( e.tagName.toLowerCase() )
							{
								case 'iframe' :
								case 'textarea' :
								case 'input' :
								case 'select' :
									/* Ignore the above tags */
									break;
								default :
									e.unselectable = 'on';
							}
						}
					}
				},

		getPositionedAncestor : function()
		{
			var current = this;
			while ( current.getName() != 'html' )
			{
				if ( current.getComputedStyle( 'position' ) != 'static' )
					return current;

				current = current.getParent();
			}
			return null;
		},

		getDocumentPosition : function( refDocument )
		{
			var x = 0, y = 0,
				body = this.getDocument().getBody(),
				quirks = this.getDocument().$.compatMode == 'BackCompat';

			var doc = this.getDocument();

			if ( document.documentElement[ "getBoundingClientRect" ] )
			{
				var box  = this.$.getBoundingClientRect(),
					$doc = doc.$,
					$docElem = $doc.documentElement;

				var clientTop = $docElem.clientTop || body.$.clientTop || 0,
					clientLeft = $docElem.clientLeft || body.$.clientLeft || 0,
					needAdjustScrollAndBorders = true;

				/*
				 * #3804: getBoundingClientRect() works differently on IE and non-IE
				 * browsers, regarding scroll positions.
				 *
				 * On IE, the top position of the <html> element is always 0, no matter
				 * how much you scrolled down.
				 *
				 * On other browsers, the top position of the <html> element is negative
				 * scrollTop.
				 */
				if ( CKEDITOR.env.ie )
				{
					var inDocElem = doc.getDocumentElement().contains( this ),
						inBody = doc.getBody().contains( this );

					needAdjustScrollAndBorders = ( quirks && inBody ) || ( !quirks && inDocElem );
				}

				if ( needAdjustScrollAndBorders )
				{
					x = box.left + ( !quirks && $docElem.scrollLeft || body.$.scrollLeft );
					x -= clientLeft;
					y = box.top  + ( !quirks && $docElem.scrollTop || body.$.scrollTop );
					y -= clientTop;
				}
			}
			else
 			{
				var current = this, previous = null, offsetParent;
				while ( current && !( current.getName() == 'body' || current.getName() == 'html' ) )
				{
					x += current.$.offsetLeft - current.$.scrollLeft;
					y += current.$.offsetTop - current.$.scrollTop;

					// Opera includes clientTop|Left into offsetTop|Left.
					if ( !current.equals( this ) )
					{
						x += ( current.$.clientLeft || 0 );
						y += ( current.$.clientTop || 0 );
					}

					var scrollElement = previous;
					while ( scrollElement && !scrollElement.equals( current ) )
					{
						x -= scrollElement.$.scrollLeft;
						y -= scrollElement.$.scrollTop;
						scrollElement = scrollElement.getParent();
					}

					previous = current;
					current = ( offsetParent = current.$.offsetParent ) ?
					          new CKEDITOR.dom.element( offsetParent ) : null;
				}
			}

			if ( refDocument )
			{
				var currentWindow = this.getWindow(),
					refWindow = refDocument.getWindow();

				if ( !currentWindow.equals( refWindow ) && currentWindow.$.frameElement )
				{
					var iframePosition = ( new CKEDITOR.dom.element( currentWindow.$.frameElement ) ).getDocumentPosition( refDocument );

					x += iframePosition.x;
					y += iframePosition.y;
				}
			}

			if ( !document.documentElement[ "getBoundingClientRect" ] )
			{
				// In Firefox, we'll endup one pixel before the element positions,
				// so we must add it here.
				if ( CKEDITOR.env.gecko && !quirks )
				{
					x += this.$.clientLeft ? 1 : 0;
					y += this.$.clientTop ? 1 : 0;
				}
			}

			return { x : x, y : y };
		},

		scrollIntoView : function( alignTop )
		{
			// Get the element window.
			var win = this.getWindow(),
				winHeight = win.getViewPaneSize().height;

			// Starts from the offset that will be scrolled with the negative value of
			// the visible window height.
			var offset = winHeight * -1;

			// Append the view pane's height if align to top.
			// Append element height if we are aligning to the bottom.
			if ( alignTop )
				offset += winHeight;
			else
			{
				offset += this.$.offsetHeight || 0;

				// Consider the margin in the scroll, which is ok for our current needs, but
				// needs investigation if we will be using this function in other places.
				offset += parseInt( this.getComputedStyle( 'marginBottom' ) || 0, 10 ) || 0;
			}

			// Append the offsets for the entire element hierarchy.
			var elementPosition = this.getDocumentPosition();
			offset += elementPosition.y;

			// offset value might be out of range(nagative), fix it(#3692).
			offset = offset < 0 ? 0 : offset;

			// Scroll the window to the desired position, if not already visible(#3795).
			var currentScroll = win.getScrollPosition().y;
			if ( offset > currentScroll || offset < currentScroll - winHeight )
				win.$.scrollTo( 0, offset );
		},

		setState : function( state )
		{
			switch ( state )
			{
				case CKEDITOR.TRISTATE_ON :
					this.addClass( 'cke_on' );
					this.removeClass( 'cke_off' );
					this.removeClass( 'cke_disabled' );
					break;
				case CKEDITOR.TRISTATE_DISABLED :
					this.addClass( 'cke_disabled' );
					this.removeClass( 'cke_off' );
					this.removeClass( 'cke_on' );
					break;
				default :
					this.addClass( 'cke_off' );
					this.removeClass( 'cke_on' );
					this.removeClass( 'cke_disabled' );
					break;
			}
		},

		/**
		 * Returns the inner document of this IFRAME element.
		 * @returns {CKEDITOR.dom.document} The inner document.
		 */
		getFrameDocument : function()
		{
			var $ = this.$;

			try
			{
				// In IE, with custom document.domain, it may happen that
				// the iframe is not yet available, resulting in "Access
				// Denied" for the following property access.
				$.contentWindow.document;
			}
			catch ( e )
			{
				// Trick to solve this issue, forcing the iframe to get ready
				// by simply setting its "src" property.
				$.src = $.src;

				// In IE6 though, the above is not enough, so we must pause the
				// execution for a while, giving it time to think.
				if ( CKEDITOR.env.ie && CKEDITOR.env.version < 7 )
				{
					window.showModalDialog(
						'javascript:document.write("' +
							'<script>' +
								'window.setTimeout(' +
									'function(){window.close();}' +
									',50);' +
							'</script>")' );
				}
			}

			return $ && new CKEDITOR.dom.document( $.contentWindow.document );
		},

		/**
		 * Copy all the attributes from one node to the other, kinda like a clone
		 * skipAttributes is an object with the attributes that must NOT be copied.
		 * @param {CKEDITOR.dom.element} dest The destination element.
		 * @param {Object} skipAttributes A dictionary of attributes to skip.
		 * @example
		 */
		copyAttributes : function( dest, skipAttributes )
		{
			var attributes = this.$.attributes;
			skipAttributes = skipAttributes || {};

			for ( var n = 0 ; n < attributes.length ; n++ )
			{
				var attribute = attributes[n];

				// IE BUG: value attribute is never specified even if it exists.
				if ( attribute.specified ||
				  ( CKEDITOR.env.ie && attribute.nodeValue && attribute.nodeName.toLowerCase() == 'value' ) )
				{
					var attrName = attribute.nodeName;
					// We can set the type only once, so do it with the proper value, not copying it.
					if ( attrName in skipAttributes )
						continue;

					var attrValue = this.getAttribute( attrName );
					if ( attrValue === null )
						attrValue = attribute.nodeValue;

					dest.setAttribute( attrName, attrValue );
				}
			}

			// The style:
			if ( this.$.style.cssText !== '' )
				dest.$.style.cssText = this.$.style.cssText;
		},

		/**
		 * Changes the tag name of the current element.
		 * @param {String} newTag The new tag for the element.
		 */
		renameNode : function( newTag )
		{
			// If it's already correct exit here.
			if ( this.getName() == newTag )
				return;

			var doc = this.getDocument();

			// Create the new node.
			var newNode = new CKEDITOR.dom.element( newTag, doc );

			// Copy all attributes.
			this.copyAttributes( newNode );

			// Move children to the new node.
			this.moveChildren( newNode );

			// Replace the node.
			this.$.parentNode.replaceChild( newNode.$, this.$ );
			newNode.$._cke_expando = this.$._cke_expando;
			this.$ = newNode.$;
		},

		/**
		 * Gets a DOM tree descendant under the current node.
		 * @param {Array|Number} indices The child index or array of child indices under the node.
		 * @returns {CKEDITOR.dom.node} The specified DOM child under the current node. Null if child does not exist.
		 * @example
		 * var strong = p.getChild(0);
		 */
		getChild : function( indices )
		{
			var rawNode = this.$;

			if ( !indices.slice )
				rawNode = rawNode.childNodes[ indices ];
			else
			{
				while ( indices.length > 0 && rawNode )
					rawNode = rawNode.childNodes[ indices.shift() ];
			}

			return rawNode ? new CKEDITOR.dom.node( rawNode ) : null;
		},

		getChildCount : function()
		{
			return this.$.childNodes.length;
 		},

		disableContextMenu : function()
		{
			this.on( 'contextmenu', function( event )
				{
					// Cancel the browser context menu.
					if ( !event.data.getTarget().hasClass( 'cke_enable_context_menu' ) )
						event.data.preventDefault();
				} );
		}
	});
