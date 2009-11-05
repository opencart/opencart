/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.dom.event} class, which
 *		represents the a native DOM event object.
 */

/**
 * Represents a native DOM event object.
 * @constructor
 * @param {Object} domEvent A native DOM event object.
 * @example
 */
CKEDITOR.dom.event = function( domEvent )
{
	/**
	 * The native DOM event object represented by this class instance.
	 * @type Object
	 * @example
	 */
	this.$ = domEvent;
};

CKEDITOR.dom.event.prototype =
{
	/**
	 * Gets the key code associated to the event.
	 * @returns {Number} The key code.
	 * @example
	 * alert( event.getKey() );  "65" is "a" has been pressed
	 */
	getKey : function()
	{
		return this.$.keyCode || this.$.which;
	},

	/**
	 * Gets a number represeting the combination of the keys pressed during the
	 * event. It is the sum with the current key code and the {@link CKEDITOR.CTRL},
	 * {@link CKEDITOR.SHIFT} and {@link CKEDITOR.ALT} constants.
	 * @returns {Number} The number representing the keys combination.
	 * @example
	 * alert( event.getKeystroke() == 65 );                                   // "a" key
	 * alert( event.getKeystroke() == CKEDITOR.CTRL + 65 );                   // CTRL + "a" key
	 * alert( event.getKeystroke() == CKEDITOR.CTRL + CKEDITOR.SHIFT + 65 );  // CTRL + SHIFT + "a" key
	 */
	getKeystroke : function()
	{
		var keystroke = this.getKey();

		if ( this.$.ctrlKey || this.$.metaKey )
			keystroke += CKEDITOR.CTRL;

		if ( this.$.shiftKey )
			keystroke += CKEDITOR.SHIFT;

		if ( this.$.altKey )
			keystroke += CKEDITOR.ALT;

		return keystroke;
	},

	/**
	 * Prevents the original behavior of the event to happen. It can optionally
	 * stop propagating the event in the event chain.
	 * @param {Boolean} [stopPropagation] Stop propagating this event in the
	 *		event chain.
	 * @example
	 * var element = CKEDITOR.document.getById( 'myElement' );
	 * element.on( 'click', function( ev )
	 *     {
	 *         // The DOM event object is passed by the "data" property.
	 *         var domEvent = ev.data;
	 *         // Prevent the click to chave any effect in the element.
	 *         domEvent.preventDefault();
	 *     });
	 */
	preventDefault : function( stopPropagation )
	{
		var $ = this.$;
		if ( $.preventDefault )
			$.preventDefault();
		else
			$.returnValue = false;

		if ( stopPropagation )
			this.stopPropagation();
	},

	stopPropagation : function()
	{
		var $ = this.$;
		if ( $.stopPropagation )
			$.stopPropagation();
		else
			$.cancelBubble = true;
	},

	/**
	 * Returns the DOM node where the event was targeted to.
	 * @returns {CKEDITOR.dom.node} The target DOM node.
	 * @example
	 * var element = CKEDITOR.document.getById( 'myElement' );
	 * element.on( 'click', function( ev )
	 *     {
	 *         // The DOM event object is passed by the "data" property.
	 *         var domEvent = ev.data;
	 *         // Add a CSS class to the event target.
	 *         domEvent.getTarget().addClass( 'clicked' );
	 *     });
	 */

	getTarget : function()
	{
		var rawNode = this.$.target || this.$.srcElement;
		return rawNode ? new CKEDITOR.dom.node( rawNode ) : null;
	}
};

/**
 * CTRL key (1000).
 * @constant
 * @example
 */
CKEDITOR.CTRL = 1000;

/**
 * SHIFT key (2000).
 * @constant
 * @example
 */
CKEDITOR.SHIFT = 2000;

/**
 * ALT key (4000).
 * @constant
 * @example
 */
CKEDITOR.ALT = 4000;
