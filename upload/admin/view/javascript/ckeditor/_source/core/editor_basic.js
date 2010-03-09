/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

if ( !CKEDITOR.editor )
{
	/**
	 * No element is linked to the editor instance.
	 * @constant
	 * @example
	 */
	CKEDITOR.ELEMENT_MODE_NONE = 0;

	/**
	 * The element is to be replaced by the editor instance.
	 * @constant
	 * @example
	 */
	CKEDITOR.ELEMENT_MODE_REPLACE = 1;

	/**
	 * The editor is to be created inside the element.
	 * @constant
	 * @example
	 */
	CKEDITOR.ELEMENT_MODE_APPENDTO = 2;

	/**
	 * Represents an editor instance. This constructor should be rarely used,
	 * being the {@link CKEDITOR} methods preferible.
	 * @constructor
	 * @param {Object} instanceConfig Configuration values for this specific
	 *		instance.
	 * @param {CKEDITOR.dom.element} [element] The element linked to this
	 *		instance.
	 * @param {Number} [mode] The mode in which the element is linked to this
	 *		instance.
	 * @augments CKEDITOR.event
	 * @example
	 */
	CKEDITOR.editor = function( instanceConfig, element, mode )
	{
		this._ =
		{
			// Save the config to be processed later by the full core code.
			instanceConfig : instanceConfig,
			element : element
		};

		/**
		 * The mode in which the {@link #element} is linked to this editor
		 * instance. It can be any of the following values:
		 * <ul>
		 * <li><b>CKEDITOR.ELEMENT_MODE_NONE</b>: No element is linked to the
		 *		editor instance.</li>
		 * <li><b>CKEDITOR.ELEMENT_MODE_REPLACE</b>: The element is to be
		 *		replaced by the editor instance.</li>
		 * <li><b>CKEDITOR.ELEMENT_MODE_APPENDTO</b>: The editor is to be
		 *		created inside the element.</li>
		 * </ul>
		 * @name CKEDITOR.editor.prototype.elementMode
		 * @type Number
		 * @example
		 * var editor = CKEDITOR.replace( 'editor1' );
		 * alert( <b>editor.elementMode</b> );  "1"
		 */
		this.elementMode = mode || CKEDITOR.ELEMENT_MODE_NONE;

		// Call the CKEDITOR.event constructor to initialize this instance.
		CKEDITOR.event.call( this );

		this._init();
	};

	/**
	 * Replaces a &lt;textarea&gt; or a DOM element (DIV) with a CKEditor
	 * instance. For textareas, the initial value in the editor will be the
	 * textarea value. For DOM elements, their innerHTML will be used
	 * instead. We recommend using TEXTAREA and DIV elements only. Do not use
	 * this function directly. Use {@link CKEDITOR.replace} instead.
	 * @param {Object|String} elementOrIdOrName The DOM element (textarea), its
	 *		ID or name.
	 * @param {Object} [config] The specific configurations to apply to this
	 *		editor instance. Configurations set here will override global CKEditor
	 *		settings.
	 * @returns {CKEDITOR.editor} The editor instance created.
	 * @example
	 */
	CKEDITOR.editor.replace = function( elementOrIdOrName, config )
	{
		var element = elementOrIdOrName;

		if ( typeof element != 'object' )
		{
			// Look for the element by id. We accept any kind of element here.
			element = document.getElementById( elementOrIdOrName );

			// If not found, look for elements by name. In this case we accept only
			// textareas.
			if ( !element )
			{
				var i = 0,
					textareasByName	= document.getElementsByName( elementOrIdOrName );

				while ( ( element = textareasByName[ i++ ] ) && element.tagName.toLowerCase() != 'textarea' )
				{ /*jsl:pass*/ }
			}

			if ( !element )
				throw '[CKEDITOR.editor.replace] The element with id or name "' + elementOrIdOrName + '" was not found.';
		}

		// Do not replace the textarea right now, just hide it. The effective
		// replacement will be done by the _init function.
		element.style.visibility = 'hidden';

		// Create the editor instance.
		return new CKEDITOR.editor( config, element, CKEDITOR.ELEMENT_MODE_REPLACE );
	};

	/**
	 * Creates a new editor instance inside a specific DOM element. Do not use
	 * this function directly. Use {@link CKEDITOR.appendTo} instead.
	 * @param {Object|String} elementOrId The DOM element or its ID.
	 * @param {Object} [config] The specific configurations to apply to this
	 *		editor instance. Configurations set here will override global CKEditor
	 *		settings.
	 * @returns {CKEDITOR.editor} The editor instance created.
	 * @example
	 */
	CKEDITOR.editor.appendTo = function( elementOrId, config )
	{
		if ( typeof elementOrId != 'object' )
		{
			elementOrId = document.getElementById( elementOrId );

			if ( !elementOrId )
				throw '[CKEDITOR.editor.appendTo] The element with id "' + elementOrId + '" was not found.';
		}

		// Create the editor instance.
		return new CKEDITOR.editor( config, elementOrId, CKEDITOR.ELEMENT_MODE_APPENDTO );
	};

	CKEDITOR.editor.prototype =
	{
		/**
		 * Initializes the editor instance. This function will be overriden by the
		 * full CKEDITOR.editor implementation (editor.js).
		 * @private
		 */
		_init : function()
		{
			var pending = CKEDITOR.editor._pending || ( CKEDITOR.editor._pending = [] );
			pending.push( this );
		},

		// Both fire and fireOnce will always pass this editor instance as the
		// "editor" param in CKEDITOR.event.fire. So, we override it to do that
		// automaticaly.

		/** @ignore */
		fire : function( eventName, data )
		{
			return CKEDITOR.event.prototype.fire.call( this, eventName, data, this );
		},

		/** @ignore */
		fireOnce : function( eventName, data )
		{
			return CKEDITOR.event.prototype.fireOnce.call( this, eventName, data, this );
		}
	};

	// "Inherit" (copy actually) from CKEDITOR.event.
	CKEDITOR.event.implementOn( CKEDITOR.editor.prototype, true );
}
