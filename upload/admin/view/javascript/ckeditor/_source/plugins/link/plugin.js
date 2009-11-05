/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'link',
{
	init : function( editor )
	{
		// Add the link and unlink buttons.
		editor.addCommand( 'link', new CKEDITOR.dialogCommand( 'link' ) );
		editor.addCommand( 'anchor', new CKEDITOR.dialogCommand( 'anchor' ) );
		editor.addCommand( 'unlink', new CKEDITOR.unlinkCommand() );
		editor.ui.addButton( 'Link',
			{
				label : editor.lang.link.toolbar,
				command : 'link'
			} );
		editor.ui.addButton( 'Unlink',
			{
				label : editor.lang.unlink,
				command : 'unlink'
			} );
		editor.ui.addButton( 'Anchor',
			{
				label : editor.lang.anchor.toolbar,
				command : 'anchor'
			} );
		CKEDITOR.dialog.add( 'link', this.path + 'dialogs/link.js' );
		CKEDITOR.dialog.add( 'anchor', this.path + 'dialogs/anchor.js' );

		// Add the CSS styles for anchor placeholders.
		editor.addCss(
			'img.cke_anchor' +
			'{' +
				'background-image: url(' + CKEDITOR.getUrl( this.path + 'images/anchor.gif' ) + ');' +
				'background-position: center center;' +
				'background-repeat: no-repeat;' +
				'border: 1px solid #a9a9a9;' +
				'width: 18px;' +
				'height: 18px;' +
			'}\n' +
			'a.cke_anchor' +
			'{' +
				'background-image: url(' + CKEDITOR.getUrl( this.path + 'images/anchor.gif' ) + ');' +
				'background-position: 0 center;' +
				'background-repeat: no-repeat;' +
				'border: 1px solid #a9a9a9;' +
				'padding-left: 18px;' +
			'}'
		   	);

		// Register selection change handler for the unlink button.
		 editor.on( 'selectionChange', function( evt )
			{
				/*
				 * Despite our initial hope, document.queryCommandEnabled() does not work
				 * for this in Firefox. So we must detect the state by element paths.
				 */
				var command = editor.getCommand( 'unlink' ),
					element = evt.data.path.lastElement.getAscendant( 'a', true );
				if ( element && element.getName() == 'a' && element.getAttribute( 'href' ) )
					command.setState( CKEDITOR.TRISTATE_OFF );
				else
					command.setState( CKEDITOR.TRISTATE_DISABLED );
			} );

		// If the "menu" plugin is loaded, register the menu items.
		if ( editor.addMenuItems )
		{
			editor.addMenuItems(
				{
					anchor :
					{
						label : editor.lang.anchor.menu,
						command : 'anchor',
						group : 'anchor'
					},

					link :
					{
						label : editor.lang.link.menu,
						command : 'link',
						group : 'link',
						order : 1
					},

					unlink :
					{
						label : editor.lang.unlink,
						command : 'unlink',
						group : 'link',
						order : 5
					}
				});
		}

		// If the "contextmenu" plugin is loaded, register the listeners.
		if ( editor.contextMenu )
		{
			editor.contextMenu.addListener( function( element, selection )
				{
					if ( !element )
						return null;

					var isAnchor = ( element.is( 'img' ) && element.getAttribute( '_cke_real_element_type' ) == 'anchor' );

					if ( !isAnchor )
					{
						if ( !( element = element.getAscendant( 'a', true ) ) )
							return null;

						isAnchor = ( element.getAttribute( 'name' ) && !element.getAttribute( 'href' ) );
					}

					return isAnchor ?
							{ anchor : CKEDITOR.TRISTATE_OFF } :
							{ link : CKEDITOR.TRISTATE_OFF, unlink : CKEDITOR.TRISTATE_OFF };
				});
		}
	},

	afterInit : function( editor )
	{
		// Register a filter to displaying placeholders after mode change.

		var dataProcessor = editor.dataProcessor,
			dataFilter = dataProcessor && dataProcessor.dataFilter;

		if ( dataFilter )
		{
			dataFilter.addRules(
				{
					elements :
					{
						a : function( element )
						{
							var attributes = element.attributes;
							if ( attributes.name && !attributes.href )
								return editor.createFakeParserElement( element, 'cke_anchor', 'anchor' );
						}
					}
				});
		}
	},

	requires : [ 'fakeobjects' ]
} );

CKEDITOR.unlinkCommand = function(){};
CKEDITOR.unlinkCommand.prototype =
{
	/** @ignore */
	exec : function( editor )
	{
		/*
		 * execCommand( 'unlink', ... ) in Firefox leaves behind <span> tags at where
		 * the <a> was, so again we have to remove the link ourselves. (See #430)
		 *
		 * TODO: Use the style system when it's complete. Let's use execCommand()
		 * as a stopgap solution for now.
		 */
		var selection = editor.getSelection(),
			bookmarks = selection.createBookmarks(),
			ranges = selection.getRanges(),
			rangeRoot,
			element;

		for ( var i = 0 ; i < ranges.length ; i++ )
		{
			rangeRoot = ranges[i].getCommonAncestor( true );
			element = rangeRoot.getAscendant( 'a', true );
			if ( !element )
				continue;
			ranges[i].selectNodeContents( element );
		}

		selection.selectRanges( ranges );
		editor.document.$.execCommand( 'unlink', false, null );
		selection.selectBookmarks( bookmarks );
	}
};

CKEDITOR.tools.extend( CKEDITOR.config,
{
	linkShowAdvancedTab : true,
	linkShowTargetTab : true
} );
