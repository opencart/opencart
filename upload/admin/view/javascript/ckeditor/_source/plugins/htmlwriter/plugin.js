/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'htmlwriter' );

/**
 * Class used to write HTML data.
 * @constructor
 * @example
 * var writer = new CKEDITOR.htmlWriter();
 * writer.openTag( 'p' );
 * writer.attribute( 'class', 'MyClass' );
 * writer.openTagClose( 'p' );
 * writer.text( 'Hello' );
 * writer.closeTag( 'p' );
 * alert( writer.getHtml() );  "&lt;p class="MyClass"&gt;Hello&lt;/p&gt;"
 */
CKEDITOR.htmlWriter = CKEDITOR.tools.createClass(
{
	base : CKEDITOR.htmlParser.basicWriter,

	$ : function()
	{
		// Call the base contructor.
		this.base();

		/**
		 * The characters to be used for each identation step.
		 * @type String
		 * @default "\t" (tab)
		 * @example
		 * // Use two spaces for indentation.
		 * editorInstance.dataProcessor.writer.indentationChars = '  ';
		 */
		this.indentationChars = '\t';

		/**
		 * The characters to be used to close "self-closing" elements, like "br" or
		 * "img".
		 * @type String
		 * @default " /&gt;"
		 * @example
		 * // Use HTML4 notation for self-closing elements.
		 * editorInstance.dataProcessor.writer.selfClosingEnd = '>';
		 */
		this.selfClosingEnd = ' />';

		/**
		 * The characters to be used for line breaks.
		 * @type String
		 * @default "\n" (LF)
		 * @example
		 * // Use CRLF for line breaks.
		 * editorInstance.dataProcessor.writer.lineBreakChars = '\r\n';
		 */
		this.lineBreakChars = '\n';

		this.forceSimpleAmpersand = false;

		this.sortAttributes = true;

		this._.indent = false;
		this._.indentation = '';
		this._.rules = {};

		var dtd = CKEDITOR.dtd;

		for ( var e in CKEDITOR.tools.extend( {}, dtd.$block, dtd.$listItem, dtd.$tableContent ) )
		{
			this.setRules( e,
				{
					indent : true,
					breakBeforeOpen : true,
					breakAfterOpen : true,
					breakBeforeClose : !dtd[ e ][ '#' ],
					breakAfterClose : true
				});
		}
		this.setRules( 'br',
			{
				breakAfterOpen : true
			});
		// Disable indentation on <pre>.
		this.setRules( 'pre',
		{
		  indent: false
		} );
	},

	proto :
	{
		/**
		 * Writes the tag opening part for a opener tag.
		 * @param {String} tagName The element name for this tag.
		 * @param {Object} attributes The attributes defined for this tag. The
		 *		attributes could be used to inspect the tag.
		 * @example
		 * // Writes "&lt;p".
		 * writer.openTag( 'p', { class : 'MyClass', id : 'MyId' } );
		 */
		openTag : function( tagName, attributes )
		{
			var rules = this._.rules[ tagName ];

			if ( this._.indent )
				this.indentation();
			// Do not break if indenting.
			else if ( rules && rules.breakBeforeOpen )
			{
				this.lineBreak();
				this.indentation();
			}

			this._.output.push( '<', tagName );
		},

		/**
		 * Writes the tag closing part for a opener tag.
		 * @param {String} tagName The element name for this tag.
		 * @param {Boolean} isSelfClose Indicates that this is a self-closing tag,
		 *		like "br" or "img".
		 * @example
		 * // Writes "&gt;".
		 * writer.openTagClose( 'p', false );
		 * @example
		 * // Writes " /&gt;".
		 * writer.openTagClose( 'br', true );
		 */
		openTagClose : function( tagName, isSelfClose )
		{
			var rules = this._.rules[ tagName ];

			if ( isSelfClose )
				this._.output.push( this.selfClosingEnd );
			else
			{
				this._.output.push( '>' );

				if ( rules && rules.indent )
					this._.indentation += this.indentationChars;
			}

			if ( rules && rules.breakAfterOpen )
				this.lineBreak();
		},

		/**
		 * Writes an attribute. This function should be called after opening the
		 * tag with {@link #openTagClose}.
		 * @param {String} attName The attribute name.
		 * @param {String} attValue The attribute value.
		 * @example
		 * // Writes ' class="MyClass"'.
		 * writer.attribute( 'class', 'MyClass' );
		 */
		attribute : function( attName, attValue )
		{
			if ( this.forceSimpleAmpersand )
				attValue = attValue.replace( /&amp;/, '&' );

			this._.output.push( ' ', attName, '="', attValue, '"' );
		},

		/**
		 * Writes a closer tag.
		 * @param {String} tagName The element name for this tag.
		 * @example
		 * // Writes "&lt;/p&gt;".
		 * writer.closeTag( 'p' );
		 */
		closeTag : function( tagName )
		{
			var rules = this._.rules[ tagName ];

			if ( rules && rules.indent )
				this._.indentation = this._.indentation.substr( this.indentationChars.length );

			if ( this._.indent )
				this.indentation();
			// Do not break if indenting.
			else if ( rules && rules.breakBeforeClose )
			{
				this.lineBreak();
				this.indentation();
			}

			this._.output.push( '</', tagName, '>' );

			if ( rules && rules.breakAfterClose )
				this.lineBreak();
		},

		/**
		 * Writes text.
		 * @param {String} text The text value
		 * @example
		 * // Writes "Hello Word".
		 * writer.text( 'Hello Word' );
		 */
		text : function( text )
		{
			if ( this._.indent )
			{
				this.indentation();
				text = CKEDITOR.tools.ltrim( text );
			}

			this._.output.push( text );
		},

		/**
		 * Writes a comment.
		 * @param {String} comment The comment text.
		 * @example
		 * // Writes "&lt;!-- My comment --&gt;".
		 * writer.comment( ' My comment ' );
		 */
		comment : function( comment )
		{
			if ( this._.indent )
				this.indentation();

			this._.output.push( '<!--', comment, '-->' );
		},

		/**
		 * Writes a line break. It uses the {@link #lineBreakChars} property for it.
		 * @example
		 * // Writes "\n" (e.g.).
		 * writer.lineBreak();
		 */
		lineBreak : function()
		{
			if ( this._.output.length > 0 )
				this._.output.push( this.lineBreakChars );
			this._.indent = true;
		},

		/**
		 * Writes the current indentation chars. It uses the
		 * {@link #indentationChars} property, repeating it for the current
		 * indentation steps.
		 * @example
		 * // Writes "\t" (e.g.).
		 * writer.indentation();
		 */
		indentation : function()
		{
			this._.output.push( this._.indentation );
			this._.indent = false;
		},

		/**
		 * Sets formatting rules for a give element. The possible rules are:
		 * <ul>
		 *	<li><b>indent</b>: indent the element contents.</li>
		 *	<li><b>breakBeforeOpen</b>: break line before the opener tag for this element.</li>
		 *	<li><b>breakAfterOpen</b>: break line after the opener tag for this element.</li>
		 *	<li><b>breakBeforeClose</b>: break line before the closer tag for this element.</li>
		 *	<li><b>breakAfterClose</b>: break line after the closer tag for this element.</li>
		 * </ul>
		 *
		 * All rules default to "false".
		 *
		 * By default, all elements available in the {@link CKEDITOR.dtd.$block),
		 * {@link CKEDITOR.dtd.$listItem} and {@link CKEDITOR.dtd.$tableContent}
		 * lists have all the above rules set to "true". Additionaly, the "br"
		 * element has the "breakAfterOpen" set to "true".
		 * @param {String} tagName The element name to which set the rules.
		 * @param {Object} rules An object containing the element rules.
		 * @example
		 * // Break line before and after "img" tags.
		 * writer.setRules( 'img',
		 *     {
		 *         breakBeforeOpen : true
		 *         breakAfterOpen : true
		 *     });
		 * @example
		 * // Reset the rules for the "h1" tag.
		 * writer.setRules( 'h1', {} );
		 */
		setRules : function( tagName, rules )
		{
			this._.rules[ tagName ] = rules;
		}
	}
});
