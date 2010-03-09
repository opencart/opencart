/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'contextmenu',
{
	requires : [ 'menu' ],

	beforeInit : function( editor )
	{
		editor.contextMenu = new CKEDITOR.plugins.contextMenu( editor );

		editor.addCommand( 'contextMenu',
			{
				exec : function()
					{
						editor.contextMenu.show( editor.document.getBody() );
					}
			});
	}
});

CKEDITOR.plugins.contextMenu = CKEDITOR.tools.createClass(
{
	$ : function( editor )
	{
		this.id = 'cke_' + CKEDITOR.tools.getNextNumber();
		this.editor = editor;
		this._.listeners = [];
		this._.functionId = CKEDITOR.tools.addFunction( function( commandName )
			{
				this._.panel.hide();
				editor.focus();
				editor.execCommand( commandName );
			},
			this);
	},

	_ :
	{
		onMenu : function( offsetParent, corner, offsetX, offsetY )
		{
			var menu = this._.menu,
				editor = this.editor;

			if ( menu )
			{
				menu.hide();
				menu.removeAll();
			}
			else
			{
				menu = this._.menu = new CKEDITOR.menu( editor );
				menu.onClick = CKEDITOR.tools.bind( function( item )
				{
					var noUnlock = true;
					menu.hide();

					if ( CKEDITOR.env.ie )
						menu.onEscape();

					if ( item.onClick )
						item.onClick();
					else if ( item.command )
						editor.execCommand( item.command );

					noUnlock = false;
				}, this );

				menu.onEscape = function()
				{
					editor.focus();

					if ( CKEDITOR.env.ie )
						editor.getSelection().unlock( true );
				};
			}

			var listeners = this._.listeners,
				includedItems = [];

			var selection = this.editor.getSelection(),
				element = selection && selection.getStartElement();

			// Lock the selection in IE, so it can be restored when closing the
			// menu.
			if ( CKEDITOR.env.ie )
				selection.lock();

			menu.onHide = CKEDITOR.tools.bind( function()
				{
					menu.onHide = null;

					if ( CKEDITOR.env.ie )
						editor.getSelection().unlock();

					this.onHide && this.onHide();
				},
				this );

			// Call all listeners, filling the list of items to be displayed.
			for ( var i = 0 ; i < listeners.length ; i++ )
			{
				var listenerItems = listeners[ i ]( element, selection );

				if ( listenerItems )
				{
					for ( var itemName in listenerItems )
					{
						var item = this.editor.getMenuItem( itemName );

						if ( item )
						{
							item.state = listenerItems[ itemName ];
							menu.add( item );
						}
					}
				}
			}

			menu.show( offsetParent, corner || ( editor.lang.dir == 'rtl' ? 2 : 1 ), offsetX, offsetY );
		}
	},

	proto :
	{
		addTarget : function( element )
		{
			element.on( 'contextmenu', function( event )
				{
					var domEvent = event.data;

					// Cancel the browser context menu.
					domEvent.preventDefault();

					var offsetParent = domEvent.getTarget().getDocument().getDocumentElement(),
						offsetX = domEvent.$.clientX,
						offsetY = domEvent.$.clientY;

					CKEDITOR.tools.setTimeout( function()
						{
							this._.onMenu( offsetParent, null, offsetX, offsetY );
						},
						0, this );
				},
				this );
		},

		addListener : function( listenerFn )
		{
			this._.listeners.push( listenerFn );
		},

		show : function( offsetParent, corner, offsetX, offsetY )
		{
			this.editor.focus();
			this._.onMenu( offsetParent || CKEDITOR.document.getDocumentElement(), corner, offsetX || 0, offsetY || 0 );
		}
	}
});

// Fix the "contextmenu" event for DOM elements.
// We may do this if we identify browsers that don't support the context meny
// event on element directly. Leaving here for reference.
//if ( <specific browsers> )
//{
//	CKEDITOR.dom.element.prototype.on = CKEDITOR.tools.override( CKEDITOR.dom.element.prototype.on, function( originalOn )
//		{
//			return function( eventName )
//				{
//					if ( eventName != 'contextmenu' )
//						return originalOn.apply( this, arguments );
//
//					// TODO : Implement the fix.
//				};
//		});
//}
