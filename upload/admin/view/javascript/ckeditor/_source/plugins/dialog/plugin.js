/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview The floating dialog plugin.
 */

CKEDITOR.plugins.add( 'dialog',
	{
		requires : [ 'dialogui' ]
	});

/**
 * No resize for this dialog.
 * @constant
 */
CKEDITOR.DIALOG_RESIZE_NONE = 0;

/**
 * Only allow horizontal resizing for this dialog, disable vertical resizing.
 * @constant
 */
CKEDITOR.DIALOG_RESIZE_WIDTH = 1;

/**
 * Only allow vertical resizing for this dialog, disable horizontal resizing.
 * @constant
 */
CKEDITOR.DIALOG_RESIZE_HEIGHT = 2;

/*
 * Allow the dialog to be resized in both directions.
 * @constant
 */
CKEDITOR.DIALOG_RESIZE_BOTH = 3;

(function()
{
	function isTabVisible( tabId )
	{
		return !!this._.tabs[ tabId ][ 0 ].$.offsetHeight;
	}

	function getPreviousVisibleTab()
	{
		var tabId = this._.currentTabId,
			length = this._.tabIdList.length,
			tabIndex = CKEDITOR.tools.indexOf( this._.tabIdList, tabId ) + length;

		for ( var i = tabIndex - 1 ; i > tabIndex - length ; i-- )
		{
			if ( isTabVisible.call( this, this._.tabIdList[ i % length ] ) )
				return this._.tabIdList[ i % length ];
		}

		return null;
	}

	function getNextVisibleTab()
	{
		var tabId = this._.currentTabId,
			length = this._.tabIdList.length,
			tabIndex = CKEDITOR.tools.indexOf( this._.tabIdList, tabId );

		for ( var i = tabIndex + 1 ; i < tabIndex + length ; i++ )
		{
			if ( isTabVisible.call( this, this._.tabIdList[ i % length ] ) )
				return this._.tabIdList[ i % length ];
		}

		return null;
	}

	// Stores dialog related data from skin definitions. e.g. margin sizes.
	var skinData = {};

	/**
	 * This is the base class for runtime dialog objects. An instance of this
	 * class represents a single named dialog for a single editor instance.
	 * @param {Object} editor The editor which created the dialog.
	 * @param {String} dialogName The dialog's registered name.
	 * @constructor
	 * @example
	 * var dialogObj = new CKEDITOR.dialog( editor, 'smiley' );
	 */
	CKEDITOR.dialog = function( editor, dialogName )
	{
		// Load the dialog definition.
		var definition = CKEDITOR.dialog._.dialogDefinitions[ dialogName ];
		if ( !definition )
		{
			console.log( 'Error: The dialog "' + dialogName + '" is not defined.' );
			return;
		}

		// Completes the definition with the default values.
		definition = CKEDITOR.tools.extend( definition( editor ), defaultDialogDefinition );

		// Clone a functionally independent copy for this dialog.
		definition = CKEDITOR.tools.clone( definition );

		// Create a complex definition object, extending it with the API
		// functions.
		definition = new definitionObject( this, definition );

		// Fire the "dialogDefinition" event, making it possible to customize
		// the dialog definition.
		this.definition = definition = CKEDITOR.fire( 'dialogDefinition',
			{
				name : dialogName,
				definition : definition
			}
			, editor ).definition;

		var doc = CKEDITOR.document;

		var themeBuilt = editor.theme.buildDialog( editor );

		// Initialize some basic parameters.
		this._ =
		{
			editor : editor,
			element : themeBuilt.element,
			name : dialogName,
			contentSize : { width : 0, height : 0 },
			size : { width : 0, height : 0 },
			updateSize : false,
			contents : {},
			buttons : {},
			accessKeyMap : {},

			// Initialize the tab and page map.
			tabs : {},
			tabIdList : [],
			currentTabId : null,
			currentTabIndex : null,
			pageCount : 0,
			lastTab : null,
			tabBarMode : false,

			// Initialize the tab order array for input widgets.
			focusList : [],
			currentFocusIndex : 0,
			hasFocus : false
		};

		this.parts = themeBuilt.parts;

		// Set the startup styles for the dialog, avoiding it enlarging the
		// page size on the dialog creation.
		this.parts.dialog.setStyles(
			{
				position : CKEDITOR.env.ie6Compat ? 'absolute' : 'fixed',
				top : 0,
				left: 0,
				visibility : 'hidden'
			});

		// Call the CKEDITOR.event constructor to initialize this instance.
		CKEDITOR.event.call( this );

		// Initialize load, show, hide, ok and cancel events.
		if ( definition.onLoad )
			this.on( 'load', definition.onLoad );

		if ( definition.onShow )
			this.on( 'show', definition.onShow );

		if ( definition.onHide )
			this.on( 'hide', definition.onHide );

		if ( definition.onOk )
		{
			this.on( 'ok', function( evt )
				{
					if ( definition.onOk.call( this, evt ) === false )
						evt.data.hide = false;
				});
		}

		if ( definition.onCancel )
		{
			this.on( 'cancel', function( evt )
				{
					if ( definition.onCancel.call( this, evt ) === false )
						evt.data.hide = false;
				});
		}

		var me = this;

		// Iterates over all items inside all content in the dialog, calling a
		// function for each of them.
		var iterContents = function( func )
		{
			var contents = me._.contents,
				stop = false;

			for ( var i in contents )
			{
				for ( var j in contents[i] )
				{
					stop = func.call( this, contents[i][j] );
					if ( stop )
						return;
				}
			}
		};

		this.on( 'ok', function( evt )
			{
				iterContents( function( item )
					{
						if ( item.validate )
						{
							var isValid = item.validate( this );

							if ( typeof isValid == 'string' )
							{
								alert( isValid );
								isValid = false;
							}

							if ( isValid === false )
							{
								if ( item.select )
									item.select();
								else
									item.focus();

								evt.data.hide = false;
								evt.stop();
								return true;
							}
						}
					});
			}, this, null, 0 );

		this.on( 'cancel', function( evt )
			{
				iterContents( function( item )
					{
						if ( item.isChanged() )
						{
							if ( !confirm( editor.lang.common.confirmCancel ) )
								evt.data.hide = false;
							return true;
						}
					});
			}, this, null, 0 );

		this.parts.close.on( 'click', function( evt )
				{
					if ( this.fire( 'cancel', { hide : true } ).hide !== false )
						this.hide();
				}, this );

		function changeFocus( forward )
		{
			var focusList = me._.focusList,
				offset = forward ? 1 : -1;
			if ( focusList.length < 1 )
				return;

			var currentIndex = ( me._.currentFocusIndex + offset + focusList.length ) % focusList.length;
			while ( !focusList[ currentIndex ].isFocusable() )
			{
				currentIndex = ( currentIndex + offset + focusList.length ) % focusList.length;
				if ( currentIndex == me._.currentFocusIndex )
					break;
			}
			focusList[ currentIndex ].focus();

			// Select whole field content.
			if ( focusList[ currentIndex ].type == 'text' )
				focusList[ currentIndex ].select();
		}

		function focusKeydownHandler( evt )
		{
			// If I'm not the top dialog, ignore.
			if ( me != CKEDITOR.dialog._.currentTop )
				return;

			var keystroke = evt.data.getKeystroke(),
				processed = false;
			if ( keystroke == 9 || keystroke == CKEDITOR.SHIFT + 9 )
			{
				var shiftPressed = ( keystroke == CKEDITOR.SHIFT + 9 );

				// Handling Tab and Shift-Tab.
				if ( me._.tabBarMode )
				{
					// Change tabs.
					var nextId = shiftPressed ? getPreviousVisibleTab.call( me ) : getNextVisibleTab.call( me );
					me.selectPage( nextId );
					me._.tabs[ nextId ][ 0 ].focus();
				}
				else
				{
					// Change the focus of inputs.
					changeFocus( !shiftPressed );
				}

				processed = true;
			}
			else if ( keystroke == CKEDITOR.ALT + 121 && !me._.tabBarMode )
			{
				// Alt-F10 puts focus into the current tab item in the tab bar.
				me._.tabBarMode = true;
				me._.tabs[ me._.currentTabId ][ 0 ].focus();
				processed = true;
			}
			else if ( ( keystroke == 37 || keystroke == 39 ) && me._.tabBarMode )
			{
				// Arrow keys - used for changing tabs.
				nextId = ( keystroke == 37 ? getPreviousVisibleTab.call( me ) : getNextVisibleTab.call( me ) );
				me.selectPage( nextId );
				me._.tabs[ nextId ][ 0 ].focus();
				processed = true;
			}

			if ( processed )
			{
				evt.stop();
				evt.data.preventDefault();
			}
		}

		// Add the dialog keyboard handlers.
		this.on( 'show', function()
			{
				CKEDITOR.document.on( 'keydown', focusKeydownHandler, this, null, 0 );

				if ( CKEDITOR.env.ie6Compat )
				{
					var coverDoc = coverElement.getChild( 0 ).getFrameDocument();
					coverDoc.on( 'keydown', focusKeydownHandler, this, null, 0 );
				}
			} );
		this.on( 'hide', function()
			{
				CKEDITOR.document.removeListener( 'keydown', focusKeydownHandler );
			} );
		this.on( 'iframeAdded', function( evt )
			{
				var doc = new CKEDITOR.dom.document( evt.data.iframe.$.contentWindow.document );
				doc.on( 'keydown', focusKeydownHandler, this, null, 0 );
			} );

		// Auto-focus logic in dialog.
		this.on( 'show', function()
			{
				if ( !this._.hasFocus )
				{
					this._.currentFocusIndex = -1;
					changeFocus( true );

					/*
					 * IE BUG: If the initial focus went into a non-text element (e.g. button),
					 * then IE would still leave the caret inside the editing area.
					 */
					if ( this._.editor.mode == 'wysiwyg' && CKEDITOR.env.ie )
					{
						var $selection = editor.document.$.selection,
							$range = $selection.createRange();

						if ( $range )
						{
							if ( $range.parentElement && $range.parentElement().ownerDocument == editor.document.$
							  || $range.item && $range.item( 0 ).ownerDocument == editor.document.$ )
							{
								var $myRange = document.body.createTextRange();
								$myRange.moveToElementText( this.getElement().getFirst().$ );
								$myRange.collapse( true );
								$myRange.select();
							}
						}
					}
				}
			}, this, null, 0xffffffff );

		// IE6 BUG: Text fields and text areas are only half-rendered the first time the dialog appears in IE6 (#2661).
		// This is still needed after [2708] and [2709] because text fields in hidden TR tags are still broken.
		if ( CKEDITOR.env.ie6Compat )
		{
			this.on( 'load', function( evt )
					{
						var outer = this.getElement(),
							inner = outer.getFirst();
						inner.remove();
						inner.appendTo( outer );
					}, this );
		}

		initDragAndDrop( this );
		initResizeHandles( this );

		// Insert the title.
		( new CKEDITOR.dom.text( definition.title, CKEDITOR.document ) ).appendTo( this.parts.title );

		// Insert the tabs and contents.
		for ( var i = 0 ; i < definition.contents.length ; i++ )
			this.addPage( definition.contents[i] );

		var tabRegex = /cke_dialog_tab(\s|$|_)/,
			tabOuterRegex = /cke_dialog_tab(\s|$)/;
		this.parts['tabs'].on( 'click', function( evt )
				{
					var target = evt.data.getTarget(), firstNode = target, id, page;

					// If we aren't inside a tab, bail out.
					if ( !( tabRegex.test( target.$.className ) || target.getName() == 'a' ) )
						return;

					// Find the outer <td> container of the tab.
					id = target.$.id.substr( 0, target.$.id.lastIndexOf( '_' ) );
					this.selectPage( id );

					if ( this._.tabBarMode )
					{
						this._.tabBarMode = false;
						this._.currentFocusIndex = -1;
						changeFocus( true );
					}

					evt.data.preventDefault();
				}, this );

		// Insert buttons.
		var buttonsHtml = [],
			buttons = CKEDITOR.dialog._.uiElementBuilders.hbox.build( this,
				{
					type : 'hbox',
					className : 'cke_dialog_footer_buttons',
					widths : [],
					children : definition.buttons
				}, buttonsHtml ).getChild();
		this.parts.footer.setHtml( buttonsHtml.join( '' ) );

		for ( i = 0 ; i < buttons.length ; i++ )
			this._.buttons[ buttons[i].id ] = buttons[i];

		CKEDITOR.skins.load( editor, 'dialog' );
	};

	// Focusable interface. Use it via dialog.addFocusable.
	function Focusable( dialog, element, index ) {
		this.element = element;
		this.focusIndex = index;
		this.isFocusable = function()
		{
			return true;
		};
		this.focus = function()
		{
			dialog._.currentFocusIndex = this.focusIndex;
			this.element.focus();
		};
		// Bind events
		element.on( 'keydown', function( e )
			{
				if ( e.data.getKeystroke() in { 32:1, 13:1 }  )
					this.fire( 'click' );
			} );
		element.on( 'focus', function()
			{
				this.fire( 'mouseover' );
			} );
		element.on( 'blur', function()
			{
				this.fire( 'mouseout' );
			} );
	}

	CKEDITOR.dialog.prototype =
	{
		/**
		 * Resizes the dialog.
		 * @param {Number} width The width of the dialog in pixels.
		 * @param {Number} height The height of the dialog in pixels.
		 * @function
		 * @example
		 * dialogObj.resize( 800, 640 );
		 */
		resize : (function()
		{
			return function( width, height )
			{
				if ( this._.contentSize && this._.contentSize.width == width && this._.contentSize.height == height )
					return;

				CKEDITOR.dialog.fire( 'resize',
					{
						dialog : this,
						skin : this._.editor.skinName,
						width : width,
						height : height
					}, this._.editor );

				this._.contentSize = { width : width, height : height };
				this._.updateSize = true;
			};
		})(),

		/**
		 * Gets the current size of the dialog in pixels.
		 * @returns {Object} An object with "width" and "height" properties.
		 * @example
		 * var width = dialogObj.getSize().width;
		 */
		getSize : function()
		{
			if ( !this._.updateSize )
				return this._.size;
			var element = this._.element.getFirst();
			var size = this._.size = { width : element.$.offsetWidth || 0, height : element.$.offsetHeight || 0};

			// If either the offsetWidth or offsetHeight is 0, the element isn't visible.
			this._.updateSize = !size.width || !size.height;

			return size;
		},

		/**
		 * Moves the dialog to an (x, y) coordinate relative to the window.
		 * @function
		 * @param {Number} x The target x-coordinate.
		 * @param {Number} y The target y-coordinate.
		 * @example
		 * dialogObj.move( 10, 40 );
		 */
		move : (function()
		{
			var isFixed;
			return function( x, y )
			{
				// The dialog may be fixed positioned or absolute positioned. Ask the
				// browser what is the current situation first.
				var element = this._.element.getFirst();
				if ( isFixed === undefined )
					isFixed = element.getComputedStyle( 'position' ) == 'fixed';

				if ( isFixed && this._.position && this._.position.x == x && this._.position.y == y )
					return;

				// Save the current position.
				this._.position = { x : x, y : y };

				// If not fixed positioned, add scroll position to the coordinates.
				if ( !isFixed )
				{
					var scrollPosition = CKEDITOR.document.getWindow().getScrollPosition();
					x += scrollPosition.x;
					y += scrollPosition.y;
				}

				element.setStyles(
						{
							'left'	: ( x > 0 ? x : 0 ) + 'px',
							'top'	: ( y > 0 ? y : 0 ) + 'px'
						});
			};
		})(),

		/**
		 * Gets the dialog's position in the window.
		 * @returns {Object} An object with "x" and "y" properties.
		 * @example
		 * var dialogX = dialogObj.getPosition().x;
		 */
		getPosition : function(){ return CKEDITOR.tools.extend( {}, this._.position ); },

		/**
		 * Shows the dialog box.
		 * @example
		 * dialogObj.show();
		 */
		show : function()
		{
			if ( this._.editor.mode == 'wysiwyg' && CKEDITOR.env.ie )
				this._.editor.getSelection().lock();

			// Insert the dialog's element to the root document.
			var element = this._.element;
			var definition = this.definition;
			if ( !( element.getParent() && element.getParent().equals( CKEDITOR.document.getBody() ) ) )
				element.appendTo( CKEDITOR.document.getBody() );
			else
				return;

			// FIREFOX BUG: Fix vanishing caret for Firefox 2 or Gecko 1.8.
			if ( CKEDITOR.env.gecko && CKEDITOR.env.version < 10900 )
			{
				var dialogElement = this.parts.dialog;
				dialogElement.setStyle( 'position', 'absolute' );
				setTimeout( function()
					{
						dialogElement.setStyle( 'position', 'fixed' );
					}, 0 );
			}


			// First, set the dialog to an appropriate size.
			this.resize( definition.minWidth, definition.minHeight );

			// Select the first tab by default.
			this.selectPage( this.definition.contents[0].id );

			// Reset all inputs back to their default value.
			this.reset();

			// Set z-index.
			if ( CKEDITOR.dialog._.currentZIndex === null )
				CKEDITOR.dialog._.currentZIndex = this._.editor.config.baseFloatZIndex;
			this._.element.getFirst().setStyle( 'z-index', CKEDITOR.dialog._.currentZIndex += 10 );

			// Maintain the dialog ordering and dialog cover.
			// Also register key handlers if first dialog.
			if ( CKEDITOR.dialog._.currentTop === null )
			{
				CKEDITOR.dialog._.currentTop = this;
				this._.parentDialog = null;
				addCover( this._.editor );

				CKEDITOR.document.on( 'keydown', accessKeyDownHandler );
				CKEDITOR.document.on( 'keyup', accessKeyUpHandler );

				// Prevent some keys from bubbling up. (#4269)
				for ( var event in { keyup :1, keydown :1, keypress :1 } )
					CKEDITOR.document.on( event, preventKeyBubbling );
			}
			else
			{
				this._.parentDialog = CKEDITOR.dialog._.currentTop;
				var parentElement = this._.parentDialog.getElement().getFirst();
				parentElement.$.style.zIndex  -= Math.floor( this._.editor.config.baseFloatZIndex / 2 );
				CKEDITOR.dialog._.currentTop = this;
			}

			// Register the Esc hotkeys.
			registerAccessKey( this, this, '\x1b', null, function()
					{
						this.getButton( 'cancel' ) && this.getButton( 'cancel' ).click();
					} );

			// Reset the hasFocus state.
			this._.hasFocus = false;

			// Rearrange the dialog to the middle of the window.
			CKEDITOR.tools.setTimeout( function()
				{
					var viewSize = CKEDITOR.document.getWindow().getViewPaneSize();
					var dialogSize = this.getSize();

					// We're using definition size for initial position because of
					// offten corrupted data in offsetWidth at this point. (#4084)
					this.move( ( viewSize.width - definition.minWidth ) / 2, ( viewSize.height - dialogSize.height ) / 2 );

					this.parts.dialog.setStyle( 'visibility', '' );

					// Execute onLoad for the first show.
					this.fireOnce( 'load', {} );
					this.fire( 'show', {} );

					// Save the initial values of the dialog.
					this.foreach( function( contentObj ) { contentObj.setInitValue && contentObj.setInitValue(); } );

				},
				100, this );
		},

		/**
		 * Executes a function for each UI element.
		 * @param {Function} fn Function to execute for each UI element.
		 * @returns {CKEDITOR.dialog} The current dialog object.
		 */
		foreach : function( fn )
		{
			for ( var i in this._.contents )
			{
				for ( var j in this._.contents[i] )
					fn( this._.contents[i][j]);
			}
			return this;
		},

		/**
		 * Resets all input values in the dialog.
		 * @example
		 * dialogObj.reset();
		 * @returns {CKEDITOR.dialog} The current dialog object.
		 */
		reset : (function()
		{
			var fn = function( widget ){ if ( widget.reset ) widget.reset(); };
			return function(){ this.foreach( fn ); return this; };
		})(),

		setupContent : function()
		{
			var args = arguments;
			this.foreach( function( widget )
				{
					if ( widget.setup )
						widget.setup.apply( widget, args );
				});
		},

		commitContent : function()
		{
			var args = arguments;
			this.foreach( function( widget )
				{
					if ( widget.commit )
						widget.commit.apply( widget, args );
				});
		},

		/**
		 * Hides the dialog box.
		 * @example
		 * dialogObj.hide();
		 */
		hide : function()
		{
			this.fire( 'hide', {} );

			// Remove the dialog's element from the root document.
			var element = this._.element;
			if ( !element.getParent() )
				return;

			element.remove();
			this.parts.dialog.setStyle( 'visibility', 'hidden' );

			// Unregister all access keys associated with this dialog.
			unregisterAccessKey( this );

			// Maintain dialog ordering and remove cover if needed.
			if ( !this._.parentDialog )
				removeCover();
			else
			{
				var parentElement = this._.parentDialog.getElement().getFirst();
				parentElement.setStyle( 'z-index', parseInt( parentElement.$.style.zIndex, 10 ) + Math.floor( this._.editor.config.baseFloatZIndex / 2 ) );
			}
			CKEDITOR.dialog._.currentTop = this._.parentDialog;

			// Deduct or clear the z-index.
			if ( !this._.parentDialog )
			{
				CKEDITOR.dialog._.currentZIndex = null;

				// Remove access key handlers.
				CKEDITOR.document.removeListener( 'keydown', accessKeyDownHandler );
				CKEDITOR.document.removeListener( 'keyup', accessKeyUpHandler );
				CKEDITOR.document.removeListener( 'keypress', accessKeyUpHandler );

				// Remove bubbling-prevention handler. (#4269)
				for ( var event in { keyup :1, keydown :1, keypress :1 } )
					CKEDITOR.document.removeListener( event, preventKeyBubbling );

				var editor = this._.editor;
				editor.focus();

				if ( editor.mode == 'wysiwyg' && CKEDITOR.env.ie )
					editor.getSelection().unlock( true );
			}
			else
				CKEDITOR.dialog._.currentZIndex -= 10;


			// Reset the initial values of the dialog.
			this.foreach( function( contentObj ) { contentObj.resetInitValue && contentObj.resetInitValue(); } );
		},

		/**
		 * Adds a tabbed page into the dialog.
		 * @param {Object} contents Content definition.
		 * @example
		 */
		addPage : function( contents )
		{
			var pageHtml = [],
				titleHtml = contents.label ? ' title="' + CKEDITOR.tools.htmlEncode( contents.label ) + '"' : '',
				elements = contents.elements,
				vbox = CKEDITOR.dialog._.uiElementBuilders.vbox.build( this,
						{
							type : 'vbox',
							className : 'cke_dialog_page_contents',
							children : contents.elements,
							expand : !!contents.expand,
							padding : contents.padding,
							style : contents.style || 'width: 100%; height: 100%;'
						}, pageHtml );

			// Create the HTML for the tab and the content block.
			var page = CKEDITOR.dom.element.createFromHtml( pageHtml.join( '' ) );
			var tab = CKEDITOR.dom.element.createFromHtml( [
					'<a class="cke_dialog_tab"',
						( this._.pageCount > 0 ? ' cke_last' : 'cke_first' ),
						titleHtml,
						( !!contents.hidden ? ' style="display:none"' : '' ),
						' id="', contents.id + '_', CKEDITOR.tools.getNextNumber(), '"' +
						' href="javascript:void(0)"',
						' hidefocus="true">',
							contents.label,
					'</a>'
				].join( '' ) );

			// If only a single page exist, a different style is used in the central pane.
			if ( this._.pageCount === 0 )
				this.parts.dialog.addClass( 'cke_single_page' );
			else
				this.parts.dialog.removeClass( 'cke_single_page' );

			// Take records for the tabs and elements created.
			this._.tabs[ contents.id ] = [ tab, page ];
			this._.tabIdList.push( contents.id );
			this._.pageCount++;
			this._.lastTab = tab;

			var contentMap = this._.contents[ contents.id ] = {},
				cursor,
				children = vbox.getChild();

			while ( ( cursor = children.shift() ) )
			{
				contentMap[ cursor.id ] = cursor;
				if ( typeof( cursor.getChild ) == 'function' )
					children.push.apply( children, cursor.getChild() );
			}

			// Attach the DOM nodes.

			page.setAttribute( 'name', contents.id );
			page.appendTo( this.parts.contents );

			tab.unselectable();
			this.parts.tabs.append( tab );

			// Add access key handlers if access key is defined.
			if ( contents.accessKey )
			{
				registerAccessKey( this, this, 'CTRL+' + contents.accessKey,
					tabAccessKeyDown, tabAccessKeyUp );
				this._.accessKeyMap[ 'CTRL+' + contents.accessKey ] = contents.id;
			}
		},

		/**
		 * Activates a tab page in the dialog by its id.
		 * @param {String} id The id of the dialog tab to be activated.
		 * @example
		 * dialogObj.selectPage( 'tab_1' );
		 */
		selectPage : function( id )
		{
			// Hide the non-selected tabs and pages.
			for ( var i in this._.tabs )
			{
				var tab = this._.tabs[i][0],
					page = this._.tabs[i][1];
				if ( i != id )
				{
					tab.removeClass( 'cke_dialog_tab_selected' );
					page.hide();
				}
			}

			var selected = this._.tabs[id];
			selected[0].addClass( 'cke_dialog_tab_selected' );
			selected[1].show();
			this._.currentTabId = id;
			this._.currentTabIndex = CKEDITOR.tools.indexOf( this._.tabIdList, id );
		},

		/**
		 * Hides a page's tab away from the dialog.
		 * @param {String} id The page's Id.
		 * @example
		 * dialog.hidePage( 'tab_3' );
		 */
		hidePage : function( id )
		{
			var tab = this._.tabs[id] && this._.tabs[id][0];
			if ( !tab )
				return;
			tab.hide();
		},

		/**
		 * Unhides a page's tab.
		 * @param {String} id The page's Id.
		 * @example
		 * dialog.showPage( 'tab_2' );
		 */
		showPage : function( id )
		{
			var tab = this._.tabs[id] && this._.tabs[id][0];
			if ( !tab )
				return;
			tab.show();
		},

		/**
		 * Gets the root DOM element of the dialog.
		 * @returns {CKEDITOR.dom.element} The &lt;span&gt; element containing this dialog.
		 * @example
		 * var dialogElement = dialogObj.getElement().getFirst();
		 * dialogElement.setStyle( 'padding', '5px' );
		 */
		getElement : function()
		{
			return this._.element;
		},

		/**
		 * Gets the name of the dialog.
		 * @returns {String} The name of this dialog.
		 * @example
		 * var dialogName = dialogObj.getName();
		 */
		getName : function()
		{
			return this._.name;
		},

		/**
		 * Gets a dialog UI element object from a dialog page.
		 * @param {String} pageId id of dialog page.
		 * @param {String} elementId id of UI element.
		 * @example
		 * @returns {CKEDITOR.ui.dialog.uiElement} The dialog UI element.
		 */
		getContentElement : function( pageId, elementId )
		{
			return this._.contents[pageId][elementId];
		},

		/**
		 * Gets the value of a dialog UI element.
		 * @param {String} pageId id of dialog page.
		 * @param {String} elementId id of UI element.
		 * @example
		 * @returns {Object} The value of the UI element.
		 */
		getValueOf : function( pageId, elementId )
		{
			return this.getContentElement( pageId, elementId ).getValue();
		},

		/**
		 * Sets the value of a dialog UI element.
		 * @param {String} pageId id of the dialog page.
		 * @param {String} elementId id of the UI element.
		 * @param {Object} value The new value of the UI element.
		 * @example
		 */
		setValueOf : function( pageId, elementId, value )
		{
			return this.getContentElement( pageId, elementId ).setValue( value );
		},

		/**
		 * Gets the UI element of a button in the dialog's button row.
		 * @param {String} id The id of the button.
		 * @example
		 * @returns {CKEDITOR.ui.dialog.button} The button object.
		 */
		getButton : function( id )
		{
			return this._.buttons[ id ];
		},

		/**
		 * Simulates a click to a dialog button in the dialog's button row.
		 * @param {String} id The id of the button.
		 * @example
		 * @returns The return value of the dialog's "click" event.
		 */
		click : function( id )
		{
			return this._.buttons[ id ].click();
		},

		/**
		 * Disables a dialog button.
		 * @param {String} id The id of the button.
		 * @example
		 */
		disableButton : function( id )
		{
			return this._.buttons[ id ].disable();
		},

		/**
		 * Enables a dialog button.
		 * @param {String} id The id of the button.
		 * @example
		 */
		enableButton : function( id )
		{
			return this._.buttons[ id ].enable();
		},

		/**
		 * Gets the number of pages in the dialog.
		 * @returns {Number} Page count.
		 */
		getPageCount : function()
		{
			return this._.pageCount;
		},

		/**
		 * Gets the editor instance which opened this dialog.
		 * @returns {CKEDITOR.editor} Parent editor instances.
		 */
		getParentEditor : function()
		{
			return this._.editor;
		},

		/**
		 * Gets the element that was selected when opening the dialog, if any.
		 * @returns {CKEDITOR.dom.element} The element that was selected, or null.
		 */
		getSelectedElement : function()
		{
			return this.getParentEditor().getSelection().getSelectedElement();
		},

		/**
		 * Adds element to dialog's focusable list.
		 *
		 * @param {CKEDITOR.dom.element} element
		 * @param {Number} [index]
		 */
		addFocusable: function( element, index ) {
			if ( typeof index == 'undefined' )
			{
				index = this._.focusList.length;
				this._.focusList.push( new Focusable( this, element, index ) );
			}
			else
			{
				this._.focusList.splice( index, 0, new Focusable( this, element, index ) );
				for ( var i = index + 1 ; i < this._.focusList.length ; i++ )
					this._.focusList[ i ].focusIndex++;
			}
		}
	};

	CKEDITOR.tools.extend( CKEDITOR.dialog,
		/**
		 * @lends CKEDITOR.dialog
		 */
		{
			/**
			 * Registers a dialog.
			 * @param {String} name The dialog's name.
			 * @param {Function|String} dialogDefinition
			 * A function returning the dialog's definition, or the URL to the .js file holding the function.
			 * The function should accept an argument "editor" which is the current editor instance, and
			 * return an object conforming to {@link CKEDITOR.dialog.dialogDefinition}.
			 * @example
			 * @see CKEDITOR.dialog.dialogDefinition
			 */
			add : function( name, dialogDefinition )
			{
				// Avoid path registration from multiple instances override definition.
				if ( !this._.dialogDefinitions[name]
					|| typeof  dialogDefinition == 'function' )
					this._.dialogDefinitions[name] = dialogDefinition;
			},

			exists : function( name )
			{
				return !!this._.dialogDefinitions[ name ];
			},

			getCurrent : function()
			{
				return CKEDITOR.dialog._.currentTop;
			},

			/**
			 * The default OK button for dialogs. Fires the "ok" event and closes the dialog if the event succeeds.
			 * @static
			 * @field
			 * @example
			 * @type Function
			 */
			okButton : (function()
			{
				var retval = function( editor, override )
				{
					override = override || {};
					return CKEDITOR.tools.extend( {
						id : 'ok',
						type : 'button',
						label : editor.lang.common.ok,
						'class' : 'cke_dialog_ui_button_ok',
						onClick : function( evt )
						{
							var dialog = evt.data.dialog;
							if ( dialog.fire( 'ok', { hide : true } ).hide !== false )
								dialog.hide();
						}
					}, override, true );
				};
				retval.type = 'button';
				retval.override = function( override )
				{
					return CKEDITOR.tools.extend( function( editor ){ return retval( editor, override ); },
							{ type : 'button' }, true );
				};
				return retval;
			})(),

			/**
			 * The default cancel button for dialogs. Fires the "cancel" event and closes the dialog if no UI element value changed.
			 * @static
			 * @field
			 * @example
			 * @type Function
			 */
			cancelButton : (function()
			{
				var retval = function( editor, override )
				{
					override = override || {};
					return CKEDITOR.tools.extend( {
						id : 'cancel',
						type : 'button',
						label : editor.lang.common.cancel,
						'class' : 'cke_dialog_ui_button_cancel',
						onClick : function( evt )
						{
							var dialog = evt.data.dialog;
							if ( dialog.fire( 'cancel', { hide : true } ).hide !== false )
								dialog.hide();
						}
					}, override, true );
				};
				retval.type = 'button';
				retval.override = function( override )
				{
					return CKEDITOR.tools.extend( function( editor ){ return retval( editor, override ); },
							{ type : 'button' }, true );
				};
				return retval;
			})(),

			/**
			 * Registers a dialog UI element.
			 * @param {String} typeName The name of the UI element.
			 * @param {Function} builder The function to build the UI element.
			 * @example
			 */
			addUIElement : function( typeName, builder )
			{
				this._.uiElementBuilders[ typeName ] = builder;
			}
		});

	CKEDITOR.dialog._ =
	{
		uiElementBuilders : {},

		dialogDefinitions : {},

		currentTop : null,

		currentZIndex : null
	};

	// "Inherit" (copy actually) from CKEDITOR.event.
	CKEDITOR.event.implementOn( CKEDITOR.dialog );
	CKEDITOR.event.implementOn( CKEDITOR.dialog.prototype, true );

	var defaultDialogDefinition =
	{
		resizable : CKEDITOR.DIALOG_RESIZE_NONE,
		minWidth : 600,
		minHeight : 400,
		buttons : [ CKEDITOR.dialog.okButton, CKEDITOR.dialog.cancelButton ]
	};

	// Tool function used to return an item from an array based on its id
	// property.
	var getById = function( array, id, recurse )
	{
		for ( var i = 0, item ; ( item = array[ i ] ) ; i++ )
		{
			if ( item.id == id )
				return item;
			if ( recurse && item[ recurse ] )
			{
				var retval = getById( item[ recurse ], id, recurse ) ;
				if ( retval )
					return retval;
			}
		}
		return null;
	};

	// Tool function used to add an item into an array.
	var addById = function( array, newItem, nextSiblingId, recurse, nullIfNotFound )
	{
		if ( nextSiblingId )
		{
			for ( var i = 0, item ; ( item = array[ i ] ) ; i++ )
			{
				if ( item.id == nextSiblingId )
				{
					array.splice( i, 0, newItem );
					return newItem;
				}

				if ( recurse && item[ recurse ] )
				{
					var retval = addById( item[ recurse ], newItem, nextSiblingId, recurse, true );
					if ( retval )
						return retval;
				}
			}

			if ( nullIfNotFound )
				return null;
		}

		array.push( newItem );
		return newItem;
	};

	// Tool function used to remove an item from an array based on its id.
	var removeById = function( array, id, recurse )
	{
		for ( var i = 0, item ; ( item = array[ i ] ) ; i++ )
		{
			if ( item.id == id )
				return array.splice( i, 1 );
			if ( recurse && item[ recurse ] )
			{
				var retval = removeById( item[ recurse ], id, recurse );
				if ( retval )
					return retval;
			}
		}
		return null;
	};

	/**
	 * This class is not really part of the API. It is the "definition" property value
	 * passed to "dialogDefinition" event handlers.
	 * @constructor
	 * @name CKEDITOR.dialog.dialogDefinitionObject
	 * @extends CKEDITOR.dialog.dialogDefinition
	 * @example
	 * CKEDITOR.on( 'dialogDefinition', function( evt )
	 * 	{
	 * 		var definition = evt.data.definition;
	 * 		var content = definition.getContents( 'page1' );
	 * 		...
	 * 	} );
	 */
	var definitionObject = function( dialog, dialogDefinition )
	{
		// TODO : Check if needed.
		this.dialog = dialog;

		// Transform the contents entries in contentObjects.
		var contents = dialogDefinition.contents;
		for ( var i = 0, content ; ( content = contents[i] ) ; i++ )
			contents[ i ] = new contentObject( dialog, content );

		CKEDITOR.tools.extend( this, dialogDefinition );
	};

	definitionObject.prototype =
	/** @lends CKEDITOR.dialog.dialogDefinitionObject.prototype */
	{
		/**
		 * Gets a content definition.
		 * @param {String} id The id of the content definition.
		 * @returns {CKEDITOR.dialog.contentDefinition} The content definition
		 *		matching id.
		 */
		getContents : function( id )
		{
			return getById( this.contents, id );
		},

		/**
		 * Gets a button definition.
		 * @param {String} id The id of the button definition.
		 * @returns {CKEDITOR.dialog.buttonDefinition} The button definition
		 *		matching id.
		 */
		getButton : function( id )
		{
			return getById( this.buttons, id );
		},

		/**
		 * Adds a content definition object under this dialog definition.
		 * @param {CKEDITOR.dialog.contentDefinition} contentDefinition The
		 *		content definition.
		 * @param {String} [nextSiblingId] The id of an existing content
		 *		definition which the new content definition will be inserted
		 *		before. Omit if the new content definition is to be inserted as
		 *		the last item.
		 * @returns {CKEDITOR.dialog.contentDefinition} The inserted content
		 *		definition.
		 */
		addContents : function( contentDefinition, nextSiblingId )
		{
			return addById( this.contents, contentDefinition, nextSiblingId );
		},

		/**
		 * Adds a button definition object under this dialog definition.
		 * @param {CKEDITOR.dialog.buttonDefinition} buttonDefinition The
		 *		button definition.
		 * @param {String} [nextSiblingId] The id of an existing button
		 *		definition which the new button definition will be inserted
		 *		before. Omit if the new button definition is to be inserted as
		 *		the last item.
		 * @returns {CKEDITOR.dialog.buttonDefinition} The inserted button
		 *		definition.
		 */
		addButton : function( buttonDefinition, nextSiblingId )
		{
			return addById( this.buttons, buttonDefinition, nextSiblingId );
		},

		/**
		 * Removes a content definition from this dialog definition.
		 * @param {String} id The id of the content definition to be removed.
		 * @returns {CKEDITOR.dialog.contentDefinition} The removed content
		 *		definition.
		 */
		removeContents : function( id )
		{
			removeById( this.contents, id );
		},

		/**
		 * Removes a button definition from the dialog definition.
		 * @param {String} id The id of the button definition to be removed.
		 * @returns {CKEDITOR.dialog.buttonDefinition} The removed button
		 *		definition.
		 */
		removeButton : function( id )
		{
			removeById( this.buttons, id );
		}
	};

	/**
	 * This class is not really part of the API. It is the template of the
	 * objects representing content pages inside the
	 * CKEDITOR.dialog.dialogDefinitionObject.
	 * @constructor
	 * @name CKEDITOR.dialog.contentDefinitionObject
	 * @example
	 * CKEDITOR.on( 'dialogDefinition', function( evt )
	 * 	{
	 * 		var definition = evt.data.definition;
	 * 		var content = definition.getContents( 'page1' );
	 *		content.remove( 'textInput1' );
	 * 		...
	 * 	} );
	 */
	function contentObject( dialog, contentDefinition )
	{
		this._ =
		{
			dialog : dialog
		};

		CKEDITOR.tools.extend( this, contentDefinition );
	}

	contentObject.prototype =
	/** @lends CKEDITOR.dialog.contentDefinitionObject.prototype */
	{
		/**
		 * Gets a UI element definition under the content definition.
		 * @param {String} id The id of the UI element definition.
		 * @returns {CKEDITOR.dialog.uiElementDefinition}
		 */
		get : function( id )
		{
			return getById( this.elements, id, 'children' );
		},

		/**
		 * Adds a UI element definition to the content definition.
		 * @param {CKEDITOR.dialog.uiElementDefinition} elementDefinition The
		 *		UI elemnet definition to be added.
		 * @param {String} nextSiblingId The id of an existing UI element
		 *		definition which the new UI element definition will be inserted
		 *		before. Omit if the new button definition is to be inserted as
		 *		the last item.
		 * @returns {CKEDITOR.dialog.uiElementDefinition} The element
		 *		definition inserted.
		 */
		add : function( elementDefinition, nextSiblingId )
		{
			return addById( this.elements, elementDefinition, nextSiblingId, 'children' );
		},

		/**
		 * Removes a UI element definition from the content definition.
		 * @param {String} id The id of the UI element definition to be
		 *		removed.
		 * @returns {CKEDITOR.dialog.uiElementDefinition} The element
		 *		definition removed.
		 * @example
		 */
		remove : function( id )
		{
			removeById( this.elements, id, 'children' );
		}
	};

	function initDragAndDrop( dialog )
	{
		var lastCoords = null,
			abstractDialogCoords = null,
			element = dialog.getElement().getFirst(),
			editor = dialog.getParentEditor(),
			magnetDistance = editor.config.dialog_magnetDistance,
			margins = skinData[ editor.skinName ].margins || [ 0, 0, 0, 0 ];

		if ( typeof magnetDistance == 'undefined' )
			magnetDistance = 20;

		function mouseMoveHandler( evt )
		{
			var dialogSize = dialog.getSize(),
				viewPaneSize = CKEDITOR.document.getWindow().getViewPaneSize(),
				x = evt.data.$.screenX,
				y = evt.data.$.screenY,
				dx = x - lastCoords.x,
				dy = y - lastCoords.y,
				realX, realY;

			lastCoords = { x : x, y : y };
			abstractDialogCoords.x += dx;
			abstractDialogCoords.y += dy;

			if ( abstractDialogCoords.x + margins[3] < magnetDistance )
				realX = - margins[3];
			else if ( abstractDialogCoords.x - margins[1] > viewPaneSize.width - dialogSize.width - magnetDistance )
				realX = viewPaneSize.width - dialogSize.width + margins[1];
			else
				realX = abstractDialogCoords.x;

			if ( abstractDialogCoords.y + margins[0] < magnetDistance )
				realY = - margins[0];
			else if ( abstractDialogCoords.y - margins[2] > viewPaneSize.height - dialogSize.height - magnetDistance )
				realY = viewPaneSize.height - dialogSize.height + margins[2];
			else
				realY = abstractDialogCoords.y;

			dialog.move( realX, realY );

			evt.data.preventDefault();
		}

		function mouseUpHandler( evt )
		{
			CKEDITOR.document.removeListener( 'mousemove', mouseMoveHandler );
			CKEDITOR.document.removeListener( 'mouseup', mouseUpHandler );

			if ( CKEDITOR.env.ie6Compat )
			{
				var coverDoc = coverElement.getChild( 0 ).getFrameDocument();
				coverDoc.removeListener( 'mousemove', mouseMoveHandler );
				coverDoc.removeListener( 'mouseup', mouseUpHandler );
			}
		}

		dialog.parts.title.on( 'mousedown', function( evt )
			{
				dialog._.updateSize = true;

				lastCoords = { x : evt.data.$.screenX, y : evt.data.$.screenY };

				CKEDITOR.document.on( 'mousemove', mouseMoveHandler );
				CKEDITOR.document.on( 'mouseup', mouseUpHandler );
				abstractDialogCoords = dialog.getPosition();

				if ( CKEDITOR.env.ie6Compat )
				{
					var coverDoc = coverElement.getChild( 0 ).getFrameDocument();
					coverDoc.on( 'mousemove', mouseMoveHandler );
					coverDoc.on( 'mouseup', mouseUpHandler );
				}

				evt.data.preventDefault();
			}, dialog );
	}

	function initResizeHandles( dialog )
	{
		var definition = dialog.definition,
			minWidth = definition.minWidth || 0,
			minHeight = definition.minHeight || 0,
			resizable = definition.resizable,
			margins = skinData[ dialog.getParentEditor().skinName ].margins || [ 0, 0, 0, 0 ];

		function topSizer( coords, dy )
		{
			coords.y += dy;
		}

		function rightSizer( coords, dx )
		{
			coords.x2 += dx;
		}

		function bottomSizer( coords, dy )
		{
			coords.y2 += dy;
		}

		function leftSizer( coords, dx )
		{
			coords.x += dx;
		}

		var lastCoords = null,
			abstractDialogCoords = null,
			magnetDistance = dialog._.editor.config.magnetDistance,
			parts = [ 'tl', 't', 'tr', 'l', 'r', 'bl', 'b', 'br' ];

		function mouseDownHandler( evt )
		{
			var partName = evt.listenerData.part, size = dialog.getSize();
			abstractDialogCoords = dialog.getPosition();
			CKEDITOR.tools.extend( abstractDialogCoords,
				{
					x2 : abstractDialogCoords.x + size.width,
					y2 : abstractDialogCoords.y + size.height
				} );
			lastCoords = { x : evt.data.$.screenX, y : evt.data.$.screenY };

			CKEDITOR.document.on( 'mousemove', mouseMoveHandler, dialog, { part : partName } );
			CKEDITOR.document.on( 'mouseup', mouseUpHandler, dialog, { part : partName } );

			if ( CKEDITOR.env.ie6Compat )
			{
				var coverDoc = coverElement.getChild( 0 ).getFrameDocument();
				coverDoc.on( 'mousemove', mouseMoveHandler, dialog, { part : partName } );
				coverDoc.on( 'mouseup', mouseUpHandler, dialog, { part : partName } );
			}

			evt.data.preventDefault();
		}

		function mouseMoveHandler( evt )
		{
			var x = evt.data.$.screenX,
				y = evt.data.$.screenY,
				dx = x - lastCoords.x,
				dy = y - lastCoords.y,
				viewPaneSize = CKEDITOR.document.getWindow().getViewPaneSize(),
				partName = evt.listenerData.part;

			if ( partName.search( 't' ) != -1 )
				topSizer( abstractDialogCoords, dy );
			if ( partName.search( 'l' ) != -1 )
				leftSizer( abstractDialogCoords, dx );
			if ( partName.search( 'b' ) != -1 )
				bottomSizer( abstractDialogCoords, dy );
			if ( partName.search( 'r' ) != -1 )
				rightSizer( abstractDialogCoords, dx );

			lastCoords = { x : x, y : y };

			var realX, realY, realX2, realY2;

			if ( abstractDialogCoords.x + margins[3] < magnetDistance )
				realX = - margins[3];
			else if ( partName.search( 'l' ) != -1 && abstractDialogCoords.x2 - abstractDialogCoords.x < minWidth + magnetDistance )
				realX = abstractDialogCoords.x2 - minWidth;
			else
				realX = abstractDialogCoords.x;

			if ( abstractDialogCoords.y + margins[0] < magnetDistance )
				realY = - margins[0];
			else if ( partName.search( 't' ) != -1 && abstractDialogCoords.y2 - abstractDialogCoords.y < minHeight + magnetDistance )
				realY = abstractDialogCoords.y2 - minHeight;
			else
				realY = abstractDialogCoords.y;

			if ( abstractDialogCoords.x2 - margins[1] > viewPaneSize.width - magnetDistance )
				realX2 = viewPaneSize.width + margins[1] ;
			else if ( partName.search( 'r' ) != -1 && abstractDialogCoords.x2 - abstractDialogCoords.x < minWidth + magnetDistance )
				realX2 = abstractDialogCoords.x + minWidth;
			else
				realX2 = abstractDialogCoords.x2;

			if ( abstractDialogCoords.y2 - margins[2] > viewPaneSize.height - magnetDistance )
				realY2= viewPaneSize.height + margins[2] ;
			else if ( partName.search( 'b' ) != -1 && abstractDialogCoords.y2 - abstractDialogCoords.y < minHeight + magnetDistance )
				realY2 = abstractDialogCoords.y + minHeight;
			else
				realY2 = abstractDialogCoords.y2 ;

			dialog.move( realX, realY );
			dialog.resize( realX2 - realX, realY2 - realY );

			evt.data.preventDefault();
		}

		function mouseUpHandler( evt )
		{
			CKEDITOR.document.removeListener( 'mouseup', mouseUpHandler );
			CKEDITOR.document.removeListener( 'mousemove', mouseMoveHandler );

			if ( CKEDITOR.env.ie6Compat )
			{
				var coverDoc = coverElement.getChild( 0 ).getFrameDocument();
				coverDoc.removeListener( 'mouseup', mouseUpHandler );
				coverDoc.removeListener( 'mousemove', mouseMoveHandler );
			}
		}

// TODO : Simplify the resize logic, having just a single resize grip <div>.
//		var widthTest = /[lr]/,
//			heightTest = /[tb]/;
//		for ( var i = 0 ; i < parts.length ; i++ )
//		{
//			var element = dialog.parts[ parts[i] + '_resize' ];
//			if ( resizable == CKEDITOR.DIALOG_RESIZE_NONE ||
//					resizable == CKEDITOR.DIALOG_RESIZE_HEIGHT && widthTest.test( parts[i] ) ||
//			  		resizable == CKEDITOR.DIALOG_RESIZE_WIDTH && heightTest.test( parts[i] ) )
//			{
//				element.hide();
//				continue;
//			}
//			element.on( 'mousedown', mouseDownHandler, dialog, { part : parts[i] } );
//		}
	}

	var resizeCover;
	var coverElement;

	var addCover = function( editor )
	{
		var win = CKEDITOR.document.getWindow();

		if ( !coverElement )
		{
			var html = [
					'<div style="position: ', ( CKEDITOR.env.ie6Compat ? 'absolute' : 'fixed' ),
					'; z-index: ', editor.config.baseFloatZIndex,
					'; top: 0px; left: 0px; ',
					'background-color: ', editor.config.dialog_backgroundCoverColor || 'white',
					'" id="cke_dialog_background_cover">'
				];


			if ( CKEDITOR.env.ie6Compat )
			{
				// Support for custom document.domain in IE.
				var isCustomDomain = CKEDITOR.env.isCustomDomain();

				html.push(
					'<iframe' +
						' hidefocus="true"' +
						' frameborder="0"' +
						' id="cke_dialog_background_iframe"' +
						' src="javascript:' );

				html.push(
						isCustomDomain ?
							'void((function(){' +
								'document.open();' +
								'document.domain=\'' + document.domain + '\';' +
								'document.close();' +
							'})())'
						:
							'\'\'' );

				html.push(
						'"' +
						' style="' +
							'position:absolute;' +
							'left:0;' +
							'top:0;' +
							'width:100%;' +
							'height: 100%;' +
							'progid:DXImageTransform.Microsoft.Alpha(opacity=0)">' +
					'</iframe>' );
			}

			html.push( '</div>' );

			coverElement = CKEDITOR.dom.element.createFromHtml( html.join( '' ) );
		}

		var element = coverElement;

		var resizeFunc = function()
		{
			var size = win.getViewPaneSize();
			element.setStyles(
				{
					width : size.width + 'px',
					height : size.height + 'px'
				} );
		};

		var scrollFunc = function()
		{
			var pos = win.getScrollPosition(),
				cursor = CKEDITOR.dialog._.currentTop;
			element.setStyles(
					{
						left : pos.x + 'px',
						top : pos.y + 'px'
					});

			do
			{
				var dialogPos = cursor.getPosition();
				cursor.move( dialogPos.x, dialogPos.y );
			} while( ( cursor = cursor._.parentDialog ) );
		};

		resizeCover = resizeFunc;
		win.on( 'resize', resizeFunc );
		resizeFunc();
		if ( CKEDITOR.env.ie6Compat )
		{
			// IE BUG: win.$.onscroll assignment doesn't work.. it must be window.onscroll.
			// So we need to invent a really funny way to make it work.
			var myScrollHandler = function()
				{
					scrollFunc();
					arguments.callee.prevScrollHandler.apply( this, arguments );
				};
			win.$.setTimeout( function()
				{
					myScrollHandler.prevScrollHandler = window.onscroll || function(){};
					window.onscroll = myScrollHandler;
				}, 0 );
			scrollFunc();
		}

		var opacity = editor.config.dialog_backgroundCoverOpacity;
		element.setOpacity( typeof opacity != 'undefined' ? opacity : 0.5 );

		element.appendTo( CKEDITOR.document.getBody() );
	};

	var removeCover = function()
	{
		if ( !coverElement )
			return;

		var win = CKEDITOR.document.getWindow();
		coverElement.remove();
		win.removeListener( 'resize', resizeCover );

		if ( CKEDITOR.env.ie6Compat )
		{
			win.$.setTimeout( function()
				{
					var prevScrollHandler = window.onscroll && window.onscroll.prevScrollHandler;
					window.onscroll = prevScrollHandler || null;
				}, 0 );
		}
		resizeCover = null;
	};

	var accessKeyProcessors = {};

	var accessKeyDownHandler = function( evt )
	{
		var ctrl = evt.data.$.ctrlKey || evt.data.$.metaKey,
			alt = evt.data.$.altKey,
			shift = evt.data.$.shiftKey,
			key = String.fromCharCode( evt.data.$.keyCode ),
			keyProcessor = accessKeyProcessors[( ctrl ? 'CTRL+' : '' ) + ( alt ? 'ALT+' : '') + ( shift ? 'SHIFT+' : '' ) + key];

		if ( !keyProcessor || !keyProcessor.length )
			return;

		keyProcessor = keyProcessor[keyProcessor.length - 1];
		keyProcessor.keydown && keyProcessor.keydown.call( keyProcessor.uiElement, keyProcessor.dialog, keyProcessor.key );
		evt.data.preventDefault();
	};

	var accessKeyUpHandler = function( evt )
	{
		var ctrl = evt.data.$.ctrlKey || evt.data.$.metaKey,
			alt = evt.data.$.altKey,
			shift = evt.data.$.shiftKey,
			key = String.fromCharCode( evt.data.$.keyCode ),
			keyProcessor = accessKeyProcessors[( ctrl ? 'CTRL+' : '' ) + ( alt ? 'ALT+' : '') + ( shift ? 'SHIFT+' : '' ) + key];

		if ( !keyProcessor || !keyProcessor.length )
			return;

		keyProcessor = keyProcessor[keyProcessor.length - 1];
		keyProcessor.keyup && keyProcessor.keyup.call( keyProcessor.uiElement, keyProcessor.dialog, keyProcessor.key );
		evt.data.preventDefault();
	};

	var registerAccessKey = function( uiElement, dialog, key, downFunc, upFunc )
	{
		var procList = accessKeyProcessors[key] || ( accessKeyProcessors[key] = [] );
		procList.push( {
				uiElement : uiElement,
				dialog : dialog,
				key : key,
				keyup : upFunc || uiElement.accessKeyUp,
				keydown : downFunc || uiElement.accessKeyDown
			} );
	};

	var unregisterAccessKey = function( obj )
	{
		for ( var i in accessKeyProcessors )
		{
			var list = accessKeyProcessors[i];
			for ( var j = list.length - 1 ; j >= 0 ; j-- )
			{
				if ( list[j].dialog == obj || list[j].uiElement == obj )
					list.splice( j, 1 );
			}
			if ( list.length === 0 )
				delete accessKeyProcessors[i];
		}
	};

	var tabAccessKeyUp = function( dialog, key )
	{
		if ( dialog._.accessKeyMap[key] )
			dialog.selectPage( dialog._.accessKeyMap[key] );
	};

	var tabAccessKeyDown = function( dialog, key )
	{
	};

	// ESC, ENTER
	var preventKeyBubblingKeys = { 27 :1, 13 :1 };
	var preventKeyBubbling = function( e )
	{
		if ( e.data.getKeystroke() in preventKeyBubblingKeys )
			e.data.stopPropagation();
	};

	(function()
	{
		CKEDITOR.ui.dialog =
		{
			/**
			 * The base class of all dialog UI elements.
			 * @constructor
			 * @param {CKEDITOR.dialog} dialog Parent dialog object.
			 * @param {CKEDITOR.dialog.uiElementDefinition} elementDefinition Element
			 * definition. Accepted fields:
			 * <ul>
			 * 	<li><strong>id</strong> (Required) The id of the UI element. See {@link
			 * 	CKEDITOR.dialog#getContentElement}</li>
			 * 	<li><strong>type</strong> (Required) The type of the UI element. The
			 * 	value to this field specifies which UI element class will be used to
			 * 	generate the final widget.</li>
			 * 	<li><strong>title</strong> (Optional) The popup tooltip for the UI
			 * 	element.</li>
			 * 	<li><strong>hidden</strong> (Optional) A flag that tells if the element
			 * 	should be initially visible.</li>
			 * 	<li><strong>className</strong> (Optional) Additional CSS class names
			 * 	to add to the UI element. Separated by space.</li>
			 * 	<li><strong>style</strong> (Optional) Additional CSS inline styles
			 * 	to add to the UI element. A semicolon (;) is required after the last
			 * 	style declaration.</li>
			 * 	<li><strong>accessKey</strong> (Optional) The alphanumeric access key
			 * 	for this element. Access keys are automatically prefixed by CTRL.</li>
			 * 	<li><strong>on*</strong> (Optional) Any UI element definition field that
			 * 	starts with <em>on</em> followed immediately by a capital letter and
			 * 	probably more letters is an event handler. Event handlers may be further
			 * 	divided into registered event handlers and DOM event handlers. Please
			 * 	refer to {@link CKEDITOR.ui.dialog.uiElement#registerEvents} and
			 * 	{@link CKEDITOR.ui.dialog.uiElement#eventProcessors} for more
			 * 	information.</li>
			 * </ul>
			 * @param {Array} htmlList
			 * List of HTML code to be added to the dialog's content area.
			 * @param {Function|String} nodeNameArg
			 * A function returning a string, or a simple string for the node name for
			 * the root DOM node. Default is 'div'.
			 * @param {Function|Object} stylesArg
			 * A function returning an object, or a simple object for CSS styles applied
			 * to the DOM node. Default is empty object.
			 * @param {Function|Object} attributesArg
			 * A fucntion returning an object, or a simple object for attributes applied
			 * to the DOM node. Default is empty object.
			 * @param {Function|String} contentsArg
			 * A function returning a string, or a simple string for the HTML code inside
			 * the root DOM node. Default is empty string.
			 * @example
			 */
			uiElement : function( dialog, elementDefinition, htmlList, nodeNameArg, stylesArg, attributesArg, contentsArg )
			{
				if ( arguments.length < 4 )
					return;

				var nodeName = ( nodeNameArg.call ? nodeNameArg( elementDefinition ) : nodeNameArg ) || 'div',
					html = [ '<', nodeName, ' ' ],
					styles = ( stylesArg && stylesArg.call ? stylesArg( elementDefinition ) : stylesArg ) || {},
					attributes = ( attributesArg && attributesArg.call ? attributesArg( elementDefinition ) : attributesArg ) || {},
					innerHTML = ( contentsArg && contentsArg.call ? contentsArg( dialog, elementDefinition ) : contentsArg ) || '',
					domId = this.domId = attributes.id || CKEDITOR.tools.getNextNumber() + '_uiElement',
					id = this.id = elementDefinition.id,
					i;

				// Set the id, a unique id is required for getElement() to work.
				attributes.id = domId;

				// Set the type and definition CSS class names.
				var classes = {};
				if ( elementDefinition.type )
					classes[ 'cke_dialog_ui_' + elementDefinition.type ] = 1;
				if ( elementDefinition.className )
					classes[ elementDefinition.className ] = 1;
				var attributeClasses = ( attributes['class'] && attributes['class'].split ) ? attributes['class'].split( ' ' ) : [];
				for ( i = 0 ; i < attributeClasses.length ; i++ )
				{
					if ( attributeClasses[i] )
						classes[ attributeClasses[i] ] = 1;
				}
				var finalClasses = [];
				for ( i in classes )
					finalClasses.push( i );
				attributes['class'] = finalClasses.join( ' ' );

				// Set the popup tooltop.
				if ( elementDefinition.title )
					attributes.title = elementDefinition.title;

				// Write the inline CSS styles.
				var styleStr = ( elementDefinition.style || '' ).split( ';' );
				for ( i in styles )
					styleStr.push( i + ':' + styles[i] );
				if ( elementDefinition.hidden )
					styleStr.push( 'display:none' );
				for ( i = styleStr.length - 1 ; i >= 0 ; i-- )
				{
					if ( styleStr[i] === '' )
						styleStr.splice( i, 1 );
				}
				if ( styleStr.length > 0 )
					attributes.style = ( attributes.style ? ( attributes.style + '; ' ) : '' ) + styleStr.join( '; ' );

				// Write the attributes.
				for ( i in attributes )
					html.push( i + '="' + CKEDITOR.tools.htmlEncode( attributes[i] ) + '" ');

				// Write the content HTML.
				html.push( '>', innerHTML, '</', nodeName, '>' );

				// Add contents to the parent HTML array.
				htmlList.push( html.join( '' ) );

				( this._ || ( this._ = {} ) ).dialog = dialog;

				// Override isChanged if it is defined in element definition.
				if ( typeof( elementDefinition.isChanged ) == 'boolean' )
					this.isChanged = function(){ return elementDefinition.isChanged; };
				if ( typeof( elementDefinition.isChanged ) == 'function' )
					this.isChanged = elementDefinition.isChanged;

				// Add events.
				CKEDITOR.event.implementOn( this );

				this.registerEvents( elementDefinition );
				if ( this.accessKeyUp && this.accessKeyDown && elementDefinition.accessKey )
					registerAccessKey( this, dialog, 'CTRL+' + elementDefinition.accessKey );

				var me = this;
				dialog.on( 'load', function()
					{
						if ( me.getInputElement() )
						{
							me.getInputElement().on( 'focus', function()
								{
									dialog._.tabBarMode = false;
									dialog._.hasFocus = true;
									me.fire( 'focus' );
								}, me );
						}
					} );

				// Register the object as a tab focus if it can be included.
				if ( this.keyboardFocusable )
				{
					this.focusIndex = dialog._.focusList.push( this ) - 1;
					this.on( 'focus', function()
						{
							dialog._.currentFocusIndex = me.focusIndex;
						} );
				}

				// Completes this object with everything we have in the
				// definition.
				CKEDITOR.tools.extend( this, elementDefinition );
			},

			/**
			 * Horizontal layout box for dialog UI elements, auto-expends to available width of container.
			 * @constructor
			 * @extends CKEDITOR.ui.dialog.uiElement
			 * @param {CKEDITOR.dialog} dialog
			 * Parent dialog object.
			 * @param {Array} childObjList
			 * Array of {@link CKEDITOR.ui.dialog.uiElement} objects inside this
			 * container.
			 * @param {Array} childHtmlList
			 * Array of HTML code that correspond to the HTML output of all the
			 * objects in childObjList.
			 * @param {Array} htmlList
			 * Array of HTML code that this element will output to.
			 * @param {CKEDITOR.dialog.uiElementDefinition} elementDefinition
			 * The element definition. Accepted fields:
			 * <ul>
			 * 	<li><strong>widths</strong> (Optional) The widths of child cells.</li>
			 * 	<li><strong>height</strong> (Optional) The height of the layout.</li>
			 * 	<li><strong>padding</strong> (Optional) The padding width inside child
			 * 	 cells.</li>
			 * 	<li><strong>align</strong> (Optional) The alignment of the whole layout
			 * 	</li>
			 * </ul>
			 * @example
			 */
			hbox : function( dialog, childObjList, childHtmlList, htmlList, elementDefinition )
			{
				if ( arguments.length < 4 )
					return;

				this._ || ( this._ = {} );

				var children = this._.children = childObjList,
					widths = elementDefinition && elementDefinition.widths || null,
					height = elementDefinition && elementDefinition.height || null,
					styles = {},
					i;
				/** @ignore */
				var innerHTML = function()
				{
					var html = [ '<tbody><tr class="cke_dialog_ui_hbox">' ];
					for ( i = 0 ; i < childHtmlList.length ; i++ )
					{
						var className = 'cke_dialog_ui_hbox_child',
							styles = [];
						if ( i === 0 )
							className = 'cke_dialog_ui_hbox_first';
						if ( i == childHtmlList.length - 1 )
							className = 'cke_dialog_ui_hbox_last';
						html.push( '<td class="', className, '" ' );
						if ( widths )
						{
							if ( widths[i] )
								styles.push( 'width:' + CKEDITOR.tools.cssLength( widths[i] ) );
						}
						else
							styles.push( 'width:' + Math.floor( 100 / childHtmlList.length ) + '%' );
						if ( height )
							styles.push( 'height:' + CKEDITOR.tools.cssLength( height ) );
						if ( elementDefinition && elementDefinition.padding != undefined )
							styles.push( 'padding:' + CKEDITOR.tools.cssLength( elementDefinition.padding ) );
						if ( styles.length > 0 )
							html.push( 'style="' + styles.join('; ') + '" ' );
						html.push( '>', childHtmlList[i], '</td>' );
					}
					html.push( '</tr></tbody>' );
					return html.join( '' );
				};

				CKEDITOR.ui.dialog.uiElement.call(
					this,
					dialog,
					elementDefinition || { type : 'hbox' },
					htmlList,
					'table',
					styles,
					elementDefinition && elementDefinition.align && { align : elementDefinition.align } || null,
					innerHTML );
			},

			/**
			 * Vertical layout box for dialog UI elements.
			 * @constructor
			 * @extends CKEDITOR.ui.dialog.hbox
			 * @param {CKEDITOR.dialog} dialog
			 * Parent dialog object.
			 * @param {Array} childObjList
			 * Array of {@link CKEDITOR.ui.dialog.uiElement} objects inside this
			 * container.
			 * @param {Array} childHtmlList
			 * Array of HTML code that correspond to the HTML output of all the
			 * objects in childObjList.
			 * @param {Array} htmlList
			 * Array of HTML code that this element will output to.
			 * @param {CKEDITOR.dialog.uiElementDefinition} elementDefinition
			 * The element definition. Accepted fields:
			 * <ul>
			 * 	<li><strong>width</strong> (Optional) The width of the layout.</li>
			 * 	<li><strong>heights</strong> (Optional) The heights of individual cells.
			 * 	</li>
			 * 	<li><strong>align</strong> (Optional) The alignment of the layout.</li>
			 * 	<li><strong>padding</strong> (Optional) The padding width inside child
			 * 	cells.</li>
			 * 	<li><strong>expand</strong> (Optional) Whether the layout should expand
			 * 	vertically to fill its container.</li>
			 * </ul>
			 * @example
			 */
			vbox : function( dialog, childObjList, childHtmlList, htmlList, elementDefinition )
			{
				if (arguments.length < 3 )
					return;

				this._ || ( this._ = {} );

				var children = this._.children = childObjList,
					width = elementDefinition && elementDefinition.width || null,
					heights = elementDefinition && elementDefinition.heights || null;
				/** @ignore */
				var innerHTML = function()
				{
					var html = [ '<table cellspacing="0" border="0" ' ];
					html.push( 'style="' );
					if ( elementDefinition && elementDefinition.expand )
						html.push( 'height:100%;' );
					html.push( 'width:' + CKEDITOR.tools.cssLength( width || '100%' ), ';' );
					html.push( '"' );
					html.push( 'align="', CKEDITOR.tools.htmlEncode(
						( elementDefinition && elementDefinition.align ) || ( dialog.getParentEditor().lang.dir == 'ltr' ? 'left' : 'right' ) ), '" ' );

					html.push( '><tbody>' );
					for ( var i = 0 ; i < childHtmlList.length ; i++ )
					{
						var styles = [];
						html.push( '<tr><td ' );
						if ( width )
							styles.push( 'width:' + CKEDITOR.tools.cssLength( width || '100%' ) );
						if ( heights )
							styles.push( 'height:' + CKEDITOR.tools.cssLength( heights[i] ) );
						else if ( elementDefinition && elementDefinition.expand )
							styles.push( 'height:' + Math.floor( 100 / childHtmlList.length ) + '%' );
						if ( elementDefinition && elementDefinition.padding != undefined )
							styles.push( 'padding:' + CKEDITOR.tools.cssLength( elementDefinition.padding ) );
						if ( styles.length > 0 )
							html.push( 'style="', styles.join( '; ' ), '" ' );
						html.push( ' class="cke_dialog_ui_vbox_child">', childHtmlList[i], '</td></tr>' );
					}
					html.push( '</tbody></table>' );
					return html.join( '' );
				};
				CKEDITOR.ui.dialog.uiElement.call( this, dialog, elementDefinition || { type : 'vbox' }, htmlList, 'div', null, null, innerHTML );
			}
		};
	})();

	CKEDITOR.ui.dialog.uiElement.prototype =
	{
		/**
		 * Gets the root DOM element of this dialog UI object.
		 * @returns {CKEDITOR.dom.element} Root DOM element of UI object.
		 * @example
		 * uiElement.getElement().hide();
		 */
		getElement : function()
		{
			return CKEDITOR.document.getById( this.domId );
		},

		/**
		 * Gets the DOM element that the user inputs values.
		 * This function is used by setValue(), getValue() and focus(). It should
		 * be overrided in child classes where the input element isn't the root
		 * element.
		 * @returns {CKEDITOR.dom.element} The element where the user input values.
		 * @example
		 * var rawValue = textInput.getInputElement().$.value;
		 */
		getInputElement : function()
		{
			return this.getElement();
		},

		/**
		 * Gets the parent dialog object containing this UI element.
		 * @returns {CKEDITOR.dialog} Parent dialog object.
		 * @example
		 * var dialog = uiElement.getDialog();
		 */
		getDialog : function()
		{
			return this._.dialog;
		},

		/**
		 * Sets the value of this dialog UI object.
		 * @param {Object} value The new value.
		 * @returns {CKEDITOR.dialog.uiElement} The current UI element.
		 * @example
		 * uiElement.setValue( 'Dingo' );
		 */
		setValue : function( value )
		{
			this.getInputElement().setValue( value );
			this.fire( 'change', { value : value } );
			return this;
		},

		/**
		 * Gets the current value of this dialog UI object.
		 * @returns {Object} The current value.
		 * @example
		 * var myValue = uiElement.getValue();
		 */
		getValue : function()
		{
			return this.getInputElement().getValue();
		},

		/**
		 * Tells whether the UI object's value has changed.
		 * @returns {Boolean} true if changed, false if not changed.
		 * @example
		 * if ( uiElement.isChanged() )
		 * &nbsp;&nbsp;confirm( 'Value changed! Continue?' );
		 */
		isChanged : function()
		{
			// Override in input classes.
			return false;
		},

		/**
		 * Selects the parent tab of this element. Usually called by focus() or overridden focus() methods.
		 * @returns {CKEDITOR.dialog.uiElement} The current UI element.
		 * @example
		 * focus : function()
		 * {
		 * 		this.selectParentTab();
		 * 		// do something else.
		 * }
		 */
		selectParentTab : function()
		{
			var element = this.getInputElement(),
				cursor = element,
				tabId;
			while ( ( cursor = cursor.getParent() ) && cursor.$.className.search( 'cke_dialog_page_contents' ) == -1 )
			{ /*jsl:pass*/ }

			// Some widgets don't have parent tabs (e.g. OK and Cancel buttons).
			if ( !cursor )
				return this;

			tabId = cursor.getAttribute( 'name' );
			// Avoid duplicate select.
			if ( this._.dialog._.currentTabId != tabId )
				this._.dialog.selectPage( tabId );
			return this;
		},

		/**
		 * Puts the focus to the UI object. Switches tabs if the UI object isn't in the active tab page.
		 * @returns {CKEDITOR.dialog.uiElement} The current UI element.
		 * @example
		 * uiElement.focus();
		 */
		focus : function()
		{
			this.selectParentTab().getInputElement().focus();
			return this;
		},

		/**
		 * Registers the on* event handlers defined in the element definition.
		 * The default behavior of this function is:
		 * <ol>
		 *  <li>
		 *  	If the on* event is defined in the class's eventProcesors list,
		 *  	then the registration is delegated to the corresponding function
		 *  	in the eventProcessors list.
		 *  </li>
		 *  <li>
		 *  	If the on* event is not defined in the eventProcessors list, then
		 *  	register the event handler under the corresponding DOM event of
		 *  	the UI element's input DOM element (as defined by the return value
		 *  	of {@link CKEDITOR.ui.dialog.uiElement#getInputElement}).
		 *  </li>
		 * </ol>
		 * This function is only called at UI element instantiation, but can
		 * be overridded in child classes if they require more flexibility.
		 * @param {CKEDITOR.dialog.uiElementDefinition} definition The UI element
		 * definition.
		 * @returns {CKEDITOR.dialog.uiElement} The current UI element.
		 * @example
		 */
		registerEvents : function( definition )
		{
			var regex = /^on([A-Z]\w+)/,
				match;

			var registerDomEvent = function( uiElement, dialog, eventName, func )
			{
				dialog.on( 'load', function()
				{
					uiElement.getInputElement().on( eventName, func, uiElement );
				});
			};

			for ( var i in definition )
			{
				if ( !( match = i.match( regex ) ) )
					continue;
				if ( this.eventProcessors[i] )
					this.eventProcessors[i].call( this, this._.dialog, definition[i] );
				else
					registerDomEvent( this, this._.dialog, match[1].toLowerCase(), definition[i] );
			}

			return this;
		},

		/**
		 * The event processor list used by
		 * {@link CKEDITOR.ui.dialog.uiElement#getInputElement} at UI element
		 * instantiation. The default list defines three on* events:
		 * <ol>
		 *  <li>onLoad - Called when the element's parent dialog opens for the
		 *  first time</li>
		 *  <li>onShow - Called whenever the element's parent dialog opens.</li>
		 *  <li>onHide - Called whenever the element's parent dialog closes.</li>
		 * </ol>
		 * @field
		 * @type Object
		 * @example
		 * // This connects the 'click' event in CKEDITOR.ui.dialog.button to onClick
		 * // handlers in the UI element's definitions.
		 * CKEDITOR.ui.dialog.button.eventProcessors = CKEDITOR.tools.extend( {},
		 * &nbsp;&nbsp;CKEDITOR.ui.dialog.uiElement.prototype.eventProcessors,
		 * &nbsp;&nbsp;{ onClick : function( dialog, func ) { this.on( 'click', func ); } },
		 * &nbsp;&nbsp;true );
		 */
		eventProcessors :
		{
			onLoad : function( dialog, func )
			{
				dialog.on( 'load', func, this );
			},

			onShow : function( dialog, func )
			{
				dialog.on( 'show', func, this );
			},

			onHide : function( dialog, func )
			{
				dialog.on( 'hide', func, this );
			}
		},

		/**
		 * The default handler for a UI element's access key down event, which
		 * tries to put focus to the UI element.<br />
		 * Can be overridded in child classes for more sophisticaed behavior.
		 * @param {CKEDITOR.dialog} dialog The parent dialog object.
		 * @param {String} key The key combination pressed. Since access keys
		 * are defined to always include the CTRL key, its value should always
		 * include a 'CTRL+' prefix.
		 * @example
		 */
		accessKeyDown : function( dialog, key )
		{
			this.focus();
		},

		/**
		 * The default handler for a UI element's access key up event, which
		 * does nothing.<br />
		 * Can be overridded in child classes for more sophisticated behavior.
		 * @param {CKEDITOR.dialog} dialog The parent dialog object.
		 * @param {String} key The key combination pressed. Since access keys
		 * are defined to always include the CTRL key, its value should always
		 * include a 'CTRL+' prefix.
		 * @example
		 */
		accessKeyUp : function( dialog, key )
		{
		},

		/**
		 * Disables a UI element.
		 * @example
		 */
		disable : function()
		{
			var element = this.getInputElement();
			element.setAttribute( 'disabled', 'true' );
			element.addClass( 'cke_disabled' );
		},

		/**
		 * Enables a UI element.
		 * @example
		 */
		enable : function()
		{
			var element = this.getInputElement();
			element.removeAttribute( 'disabled' );
			element.removeClass( 'cke_disabled' );
		},

		/**
		 * Determines whether an UI element is enabled or not.
		 * @returns {Boolean} Whether the UI element is enabled.
		 * @example
		 */
		isEnabled : function()
		{
			return !this.getInputElement().getAttribute( 'disabled' );
		},

		/**
		 * Determines whether an UI element is visible or not.
		 * @returns {Boolean} Whether the UI element is visible.
		 * @example
		 */
		isVisible : function()
		{
			return !!this.getInputElement().$.offsetHeight;
		},

		/**
		 * Determines whether an UI element is focus-able or not.
		 * Focus-able is defined as being both visible and enabled.
		 * @returns {Boolean} Whether the UI element can be focused.
		 * @example
		 */
		isFocusable : function()
		{
			if ( !this.isEnabled() || !this.isVisible() )
				return false;
			return true;
		}
	};

	CKEDITOR.ui.dialog.hbox.prototype = CKEDITOR.tools.extend( new CKEDITOR.ui.dialog.uiElement,
		/**
		 * @lends CKEDITOR.ui.dialog.hbox.prototype
		 */
		{
			/**
			 * Gets a child UI element inside this container.
			 * @param {Array|Number} indices An array or a single number to indicate the child's
			 * position in the container's descendant tree. Omit to get all the children in an array.
			 * @returns {Array|CKEDITOR.ui.dialog.uiElement} Array of all UI elements in the container
			 * if no argument given, or the specified UI element if indices is given.
			 * @example
			 * var checkbox = hbox.getChild( [0,1] );
			 * checkbox.setValue( true );
			 */
			getChild : function( indices )
			{
				// If no arguments, return a clone of the children array.
				if ( arguments.length < 1 )
					return this._.children.concat();

				// If indices isn't array, make it one.
				if ( !indices.splice )
					indices = [ indices ];

				// Retrieve the child element according to tree position.
				if ( indices.length < 2 )
					return this._.children[ indices[0] ];
				else
					return ( this._.children[ indices[0] ] && this._.children[ indices[0] ].getChild ) ?
						this._.children[ indices[0] ].getChild( indices.slice( 1, indices.length ) ) :
						null;
			}
		}, true );

	CKEDITOR.ui.dialog.vbox.prototype = new CKEDITOR.ui.dialog.hbox();



	(function()
	{
		var commonBuilder = {
			build : function( dialog, elementDefinition, output )
			{
				var children = elementDefinition.children,
					child,
					childHtmlList = [],
					childObjList = [];
				for ( var i = 0 ; ( i < children.length && ( child = children[i] ) ) ; i++ )
				{
					var childHtml = [];
					childHtmlList.push( childHtml );
					childObjList.push( CKEDITOR.dialog._.uiElementBuilders[ child.type ].build( dialog, child, childHtml ) );
				}
				return new CKEDITOR.ui.dialog[elementDefinition.type]( dialog, childObjList, childHtmlList, output, elementDefinition );
			}
		};

		CKEDITOR.dialog.addUIElement( 'hbox', commonBuilder );
		CKEDITOR.dialog.addUIElement( 'vbox', commonBuilder );
	})();

	/**
	 * Generic dialog command. It opens a specific dialog when executed.
	 * @constructor
	 * @augments CKEDITOR.commandDefinition
	 * @param {string} dialogName The name of the dialog to open when executing
	 *		this command.
	 * @example
	 * // Register the "link" command, which opens the "link" dialog.
	 * editor.addCommand( 'link', <b>new CKEDITOR.dialogCommand( 'link' )</b> );
	 */
	CKEDITOR.dialogCommand = function( dialogName )
	{
		this.dialogName = dialogName;
	};

	CKEDITOR.dialogCommand.prototype =
	{
		/** @ignore */
		exec : function( editor )
		{
			editor.openDialog( this.dialogName );
		},
		// Dialog commands just open a dialog ui, thus require no undo logic,
		// undo support should dedicate to specific dialog implementation.
		canUndo: false
	};

	(function()
	{
		var notEmptyRegex = /^([a]|[^a])+$/,
			integerRegex = /^\d*$/,
			numberRegex = /^\d*(?:\.\d+)?$/;

		CKEDITOR.VALIDATE_OR = 1;
		CKEDITOR.VALIDATE_AND = 2;

		CKEDITOR.dialog.validate =
		{
			functions : function()
			{
				return function()
				{
					/**
					 * It's important for validate functions to be able to accept the value
					 * as argument in addition to this.getValue(), so that it is possible to
					 * combine validate functions together to make more sophisticated
					 * validators.
					 */
					var value = this && this.getValue ? this.getValue() : arguments[0];

					var msg = undefined,
						relation = CKEDITOR.VALIDATE_AND,
						functions = [], i;

					for ( i = 0 ; i < arguments.length ; i++ )
					{
						if ( typeof( arguments[i] ) == 'function' )
							functions.push( arguments[i] );
						else
							break;
					}

					if ( i < arguments.length && typeof( arguments[i] ) == 'string' )
					{
						msg = arguments[i];
						i++;
					}

					if ( i < arguments.length && typeof( arguments[i]) == 'number' )
						relation = arguments[i];

					var passed = ( relation == CKEDITOR.VALIDATE_AND ? true : false );
					for ( i = 0 ; i < functions.length ; i++ )
					{
						if ( relation == CKEDITOR.VALIDATE_AND )
							passed = passed && functions[i]( value );
						else
							passed = passed || functions[i]( value );
					}

					if ( !passed )
					{
						if ( msg !== undefined )
							alert( msg );
						if ( this && ( this.select || this.focus ) )
							( this.select || this.focus )();
						return false;
					}

					return true;
				};
			},

			regex : function( regex, msg )
			{
				/*
				 * Can be greatly shortened by deriving from functions validator if code size
				 * turns out to be more important than performance.
				 */
				return function()
				{
					var value = this && this.getValue ? this.getValue() : arguments[0];
					if ( !regex.test( value ) )
					{
						if ( msg !== undefined )
							alert( msg );
						if ( this && ( this.select || this.focus ) )
						{
							if ( this.select )
								this.select();
							else
								this.focus();
						}
						return false;
					}
					return true;
				};
			},

			notEmpty : function( msg )
			{
				return this.regex( notEmptyRegex, msg );
			},

			integer : function( msg )
			{
				return this.regex( integerRegex, msg );
			},

			'number' : function( msg )
			{
				return this.regex( numberRegex, msg );
			},

			equals : function( value, msg )
			{
				return this.functions( function( val ){ return val == value; }, msg );
			},

			notEqual : function( value, msg )
			{
				return this.functions( function( val ){ return val != value; }, msg );
			}
		};
	})();

	// Grab the margin data from skin definition and store it away.
	CKEDITOR.skins.add = ( function()
	{
		var original = CKEDITOR.skins.add;
		return function( skinName, skinDefinition )
		{
			skinData[ skinName ] = { margins : skinDefinition.margins };
			return original.apply( this, arguments );
		};
	} )();
})();

// Extend the CKEDITOR.editor class with dialog specific functions.
CKEDITOR.tools.extend( CKEDITOR.editor.prototype,
	/** @lends CKEDITOR.editor.prototype */
	{
		/**
		 * Loads and opens a registered dialog.
		 * @param {String} dialogName The registered name of the dialog.
		 * @see CKEDITOR.dialog.add
		 * @example
		 * CKEDITOR.instances.editor1.openDialog( 'smiley' );
		 * @returns {CKEDITOR.dialog} The dialog object corresponding to the dialog displayed. null if the dialog name is not registered.
		 */
		openDialog : function( dialogName )
		{
			var dialogDefinitions = CKEDITOR.dialog._.dialogDefinitions[ dialogName ];

			// If the dialogDefinition is already loaded, open it immediately.
			if ( typeof dialogDefinitions == 'function' )
			{
				var storedDialogs = this._.storedDialogs ||
					( this._.storedDialogs = {} );

				var dialog = storedDialogs[ dialogName ] ||
					( storedDialogs[ dialogName ] = new CKEDITOR.dialog( this, dialogName ) );

				dialog.show();

				return dialog;
			}
			else if ( dialogDefinitions == 'failed' )
				throw new Error( '[CKEDITOR.dialog.openDialog] Dialog "' + dialogName + '" failed when loading definition.' );

			// Not loaded? Load the .js file first.
			var body = CKEDITOR.document.getBody(),
				cursor = body.$.style.cursor,
				me = this;

			body.setStyle( 'cursor', 'wait' );
			CKEDITOR.scriptLoader.load( CKEDITOR.getUrl( dialogDefinitions ), function()
				{
					// In case of plugin error, mark it as loading failed.
					if ( typeof CKEDITOR.dialog._.dialogDefinitions[ dialogName ] != 'function' )
							CKEDITOR.dialog._.dialogDefinitions[ dialogName ] =  'failed';
					me.openDialog( dialogName );
					body.setStyle( 'cursor', cursor );
				} );

			return null;
		}
	});

// Dialog related configurations.

/**
 * The color of the dialog background cover. It should be a valid CSS color
 * string.
 * @name CKEDITOR.config.dialog_backgroundCoverColor
 * @type String
 * @default 'white'
 * @example
 * config.dialog_backgroundCoverColor = 'rgb(255, 254, 253)';
 */

/**
 * The opacity of the dialog background cover. It should be a number within the
 * range [0.0, 1.0].
 * @name CKEDITOR.config.dialog_backgroundCoverOpacity
 * @type Number
 * @default 0.5
 * @example
 * config.dialog_backgroundCoverOpacity = 0.7;
 */

/**
 * The distance of magnetic borders used in moving and resizing dialogs,
 * measured in pixels.
 * @name CKEDITOR.config.dialog_magnetDistance
 * @type Number
 * @default 20
 * @example
 * config.dialog_magnetDistance = 30;
 */
