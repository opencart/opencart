/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'table',
{
	init : function( editor )
	{
		var table = CKEDITOR.plugins.table,
			lang = editor.lang.table;

		editor.addCommand( 'table', new CKEDITOR.dialogCommand( 'table' ) );
		editor.addCommand( 'tableProperties', new CKEDITOR.dialogCommand( 'tableProperties' ) );

		editor.ui.addButton( 'Table',
			{
				label : lang.toolbar,
				command : 'table'
			});

		CKEDITOR.dialog.add( 'table', this.path + 'dialogs/table.js' );
		CKEDITOR.dialog.add( 'tableProperties', this.path + 'dialogs/table.js' );

		// If the "menu" plugin is loaded, register the menu items.
		if ( editor.addMenuItems )
		{
			editor.addMenuItems(
				{
					table :
					{
						label : lang.menu,
						command : 'tableProperties',
						group : 'table',
						order : 5
					},

					tabledelete :
					{
						label : lang.deleteTable,
						command : 'tableDelete',
						group : 'table',
						order : 1
					}
				} );
		}

		// If the "contextmenu" plugin is loaded, register the listeners.
		if ( editor.contextMenu )
		{
			editor.contextMenu.addListener( function( element, selection )
				{
					if ( !element )
						return null;

					var isTable	= element.is( 'table' ) || element.hasAscendant( 'table' );

					if ( isTable )
					{
						return {
							tabledelete : CKEDITOR.TRISTATE_OFF,
							table : CKEDITOR.TRISTATE_OFF
						};
					}

					return null;
				} );
		}
	}
} );
