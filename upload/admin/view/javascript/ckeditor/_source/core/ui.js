/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * Contains UI features related to an editor instance.
 * @constructor
 * @param {CKEDITOR.editor} editor The editor instance.
 * @example
 */
CKEDITOR.ui = function( editor )
{
	if ( editor.ui )
		return editor.ui;

	/**
	 * Object used to hold private stuff.
	 * @private
	 */
	this._ =
	{
		handlers : {},
		items : {},
		editor : editor
	};

	return this;
};

// PACKAGER_RENAME( CKEDITOR.ui )

CKEDITOR.ui.prototype =
{
	/**
	 * Adds a UI item to the items collection. These items can be later used in
	 * the interface.
	 * @param {String} name The UI item name.
	 * @param {Object} type The item type.
	 * @param {Object} definition The item definition. The properties of this
	 *		object depend on the item type.
	 * @example
	 * // Add a new button named "MyBold".
	 * editorInstance.ui.add( 'MyBold', CKEDITOR.UI_BUTTON,
	 *     {
	 *         label : 'My Bold',
	 *         command : 'bold'
	 *     });
	 */
	add : function( name, type, definition )
	{
		this._.items[ name ] =
		{
			type : type,
			// The name of {@link CKEDITOR.command} which associate with this UI.
			command : definition.command || null,
			args : Array.prototype.slice.call( arguments, 2 )
		};
	},

	/**
	 * Gets a UI object.
	 * @param {String} name The UI item hame.
	 * @example
	 */
	create : function( name )
	{
		var item	= this._.items[ name ],
			handler	= item && this._.handlers[ item.type ],
			command = item && item.command && this._.editor.getCommand( item.command );

		var result = handler && handler.create.apply( this, item.args );

		// Add reference inside command object.
		if ( command )
			command.uiItems.push( result );

		return result;
	},

	/**
	 * Adds a handler for a UI item type. The handler is responsible for
	 * transforming UI item definitions in UI objects.
	 * @param {Object} type The item type.
	 * @param {Object} handler The handler definition.
	 * @example
	 */
	addHandler : function( type, handler )
	{
		this._.handlers[ type ] = handler;
	}
};

/**
 * (Virtual Class) Do not call this constructor. This class is not really part
 *		of the API. It just illustrates the features of hanlder objects to be
 *		passed to the {@link CKEDITOR.ui.prototype.addHandler} function.
 * @name CKEDITOR.ui.handlerDefinition
 * @constructor
 * @example
 */

 /**
 * Transforms an item definition into an UI item object.
 * @name CKEDITOR.handlerDefinition.prototype.create
 * @function
 * @param {Object} definition The item definition.
 * @example
 * editorInstance.ui.addHandler( CKEDITOR.UI_BUTTON,
 *     {
 *         create : function( definition )
 *         {
 *             return new CKEDITOR.ui.button( definition );
 *         }
 *     });
 */
