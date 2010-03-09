/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.dom.document} class, which
 *		represents a DOM document.
 */

/**
 * Represents a DOM window.
 * @constructor
 * @augments CKEDITOR.dom.domObject
 * @param {Object} domWindow A native DOM window.
 * @example
 * var document = new CKEDITOR.dom.window( window );
 */
CKEDITOR.dom.window = function( domWindow )
{
	CKEDITOR.dom.domObject.call( this, domWindow );
};

CKEDITOR.dom.window.prototype = new CKEDITOR.dom.domObject();

CKEDITOR.tools.extend( CKEDITOR.dom.window.prototype,
	/** @lends CKEDITOR.dom.window.prototype */
	{
		/**
		 * Moves the selection focus to this window.
		 * @function
		 * @example
		 * var win = new CKEDITOR.dom.window( window );
		 * <b>win.focus()</b>;
		 */
		focus : function()
		{
			// Webkit is sometimes failed to focus iframe, blur it first(#3835).
			if( CKEDITOR.env.webkit && this.$.parent )
				this.$.parent.focus();
			this.$.focus();
		},

		/**
		 * Gets the width and height of this window's viewable area.
		 * @function
		 * @returns {Object} An object with the "width" and "height"
		 *		properties containing the size.
		 * @example
		 * var win = new CKEDITOR.dom.window( window );
		 * var size = <b>win.getViewPaneSize()</b>;
		 * alert( size.width );
		 * alert( size.height );
		 */
		getViewPaneSize : function()
		{
			var doc = this.$.document,
				stdMode = doc.compatMode == 'CSS1Compat';
			return {
				width : ( stdMode ? doc.documentElement.clientWidth : doc.body.clientWidth ) || 0,
				height : ( stdMode ? doc.documentElement.clientHeight : doc.body.clientHeight ) || 0
			};
		},

		/**
		 * Gets the current position of the window's scroll.
		 * @function
		 * @returns {Object} An object with the "x" and "y" properties
		 *		containing the scroll position.
		 * @example
		 * var win = new CKEDITOR.dom.window( window );
		 * var pos = <b>win.getScrollPosition()</b>;
		 * alert( pos.x );
		 * alert( pos.y );
		 */
		getScrollPosition : function()
		{
			var $ = this.$;

			if ( 'pageXOffset' in $ )
			{
				return {
					x : $.pageXOffset || 0,
					y : $.pageYOffset || 0
				};
			}
			else
			{
				var doc = $.document;
				return {
					x : doc.documentElement.scrollLeft || doc.body.scrollLeft || 0,
					y : doc.documentElement.scrollTop || doc.body.scrollTop || 0
				};
			}
		}
	});
