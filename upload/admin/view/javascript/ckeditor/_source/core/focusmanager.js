/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.focusManager} class, which is used
 *		to handle the focus on editor instances..
 */

/**
 * Manages the focus activity in an editor instance. This class is to be used
 * mainly by UI elements coders when adding interface elements to CKEditor.
 * @constructor
 * @param {CKEDITOR.editor} editor The editor instance.
 * @example
 */
CKEDITOR.focusManager = function( editor )
{
	if ( editor.focusManager )
		return editor.focusManager;

	/**
	 * Indicates that the editor instance has focus.
	 * @type Boolean
	 * @example
	 * alert( CKEDITOR.instances.editor1.focusManager.hasFocus );  // e.g "true"
	 */
	this.hasFocus = false;

	/**
	 * Object used to hold private stuff.
	 * @private
	 */
	this._ =
	{
		editor : editor
	};

	return this;
};

CKEDITOR.focusManager.prototype =
{
	/**
	 * Indicates that the editor instance has the focus.
	 *
	 * This function is not used to set the focus in the editor. Use
	 * {@link CKEDITOR.editor#focus} for it instead.
	 * @example
	 * var editor = CKEDITOR.instances.editor1;
	 * <b>editor.focusManager.focus()</b>;
	 */
	focus : function()
	{
		if ( this._.timer )
			clearTimeout( this._.timer );

		if ( !this.hasFocus )
		{
			// If another editor has the current focus, we first "blur" it. In
			// this way the events happen in a more logical sequence, like:
			//		"focus 1" > "blur 1" > "focus 2"
			// ... instead of:
			//		"focus 1" > "focus 2" > "blur 1"
			if ( CKEDITOR.currentInstance )
				CKEDITOR.currentInstance.focusManager.forceBlur();

			var editor = this._.editor;

			editor.container.getFirst().addClass( 'cke_focus' );

			this.hasFocus = true;
			editor.fire( 'focus' );
		}
	},

	/**
	 * Indicates that the editor instance has lost the focus. Note that this
	 * functions acts asynchronously with a delay of 100ms to avoid subsequent
	 * blur/focus effects. If you want the "blur" to happen immediately, use
	 * the {@link #forceBlur} function instead.
	 * @example
	 * var editor = CKEDITOR.instances.editor1;
	 * <b>editor.focusManager.blur()</b>;
	 */
	blur : function()
	{
		var focusManager = this;

		if ( focusManager._.timer )
			clearTimeout( focusManager._.timer );

		focusManager._.timer = setTimeout(
			function()
			{
				delete focusManager._.timer;
				focusManager.forceBlur();
			}
			, 100 );
	},

	/**
	 * Indicates that the editor instance has lost the focus. Unlike
	 * {@link #blur}, this function is synchronous, marking the instance as
	 * "blured" immediately.
	 * @example
	 * var editor = CKEDITOR.instances.editor1;
	 * <b>editor.focusManager.forceBlur()</b>;
	 */
	forceBlur : function()
	{
		if ( this.hasFocus )
		{
			var editor = this._.editor;

			editor.container.getFirst().removeClass( 'cke_focus' );

			this.hasFocus = false;
			editor.fire( 'blur' );
		}
	}
};
