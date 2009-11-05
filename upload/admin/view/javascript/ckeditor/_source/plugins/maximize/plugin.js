/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	function protectFormStyles( formElement )
	{
		if ( !formElement || formElement.type != CKEDITOR.NODE_ELEMENT || formElement.getName() != 'form' )
			return [];

		var hijackRecord = [];
		var hijackNames = [ 'style', 'className' ];
		for ( var i = 0 ; i < hijackNames.length ; i++ )
		{
			var name = hijackNames[i];
			var $node = formElement.$.elements.namedItem( name );
			if ( $node )
			{
				var hijackNode = new CKEDITOR.dom.element( $node );
				hijackRecord.push( [ hijackNode, hijackNode.nextSibling ] );
				hijackNode.remove();
			}
		}

		return hijackRecord;
	}

	function restoreFormStyles( formElement, hijackRecord )
	{
		if ( !formElement || formElement.type != CKEDITOR.NODE_ELEMENT || formElement.getName() != 'form' )
			return;

		if ( hijackRecord.length > 0 )
		{
			for ( var i = hijackRecord.length - 1 ; i >= 0 ; i-- )
			{
				var node = hijackRecord[i][0];
				var sibling = hijackRecord[i][1];
				if ( sibling )
					node.insertBefore( sibling );
				else
					node.appendTo( formElement );
			}
		}
	}

	function saveStyles( element, isInsideEditor )
	{
		var data = protectFormStyles( element );
		var retval = {};

		var $element = element.$;

		if ( !isInsideEditor )
		{
			retval[ 'class' ] = $element.className || '';
			$element.className = '';
		}

		retval.inline = $element.style.cssText || '';
		if ( !isInsideEditor )		// Reset any external styles that might interfere. (#2474)
			$element.style.cssText = 'position: static; overflow: visible';

		restoreFormStyles( data );
		return retval;
	}

	function restoreStyles( element, savedStyles )
	{
		var data = protectFormStyles( element );
		var $element = element.$;
		if ( 'class' in savedStyles )
			$element.className = savedStyles[ 'class' ];
		if ( 'inline' in savedStyles )
			$element.style.cssText = savedStyles.inline;
		restoreFormStyles( data );
	}

	function getResizeHandler( mainWindow, editor )
	{
		return function()
		{
			var viewPaneSize = mainWindow.getViewPaneSize();
			editor.resize( viewPaneSize.width, viewPaneSize.height, null, true );
		};
	}

	CKEDITOR.plugins.add( 'maximize',
	{
		init : function( editor )
		{
			var lang = editor.lang;
			var mainDocument = CKEDITOR.document;
			var mainWindow = mainDocument.getWindow();

			// Saved selection and scroll position for the editing area.
			var savedSelection;
			var savedScroll;

			// Saved scroll position for the outer window.
			var outerScroll;

			// Saved resize handler function.
			var resizeHandler = getResizeHandler( mainWindow, editor );

			// Retain state after mode switches.
			var savedState = CKEDITOR.TRISTATE_OFF;

			editor.addCommand( 'maximize',
				{
					modes : { wysiwyg : 1, source : 1 },
					editorFocus : false,
					exec : function()
					{
						var container = editor.container.getChild( [ 0, 0 ] );
						var contents = editor.getThemeSpace( 'contents' );

						// Save current selection and scroll position in editing area.
						if ( editor.mode == 'wysiwyg' )
						{
							var selection = editor.getSelection();
							savedSelection = selection && selection.getRanges();
							savedScroll = mainWindow.getScrollPosition();
						}
						else
						{
							var $textarea = editor.textarea.$;
							savedSelection = !CKEDITOR.env.ie && [ $textarea.selectionStart, $textarea.selectionEnd ];
							savedScroll = [ $textarea.scrollLeft, $textarea.scrollTop ];
						}

						if ( this.state == CKEDITOR.TRISTATE_OFF )		// Go fullscreen if the state is off.
						{
							// Add event handler for resizing.
							mainWindow.on( 'resize', resizeHandler );

							// Save the scroll bar position.
							outerScroll = mainWindow.getScrollPosition();

							// Save and reset the styles for the entire node tree.
							var currentNode = editor.container;
							while ( ( currentNode = currentNode.getParent() ) )
							{
								currentNode.setCustomData( 'maximize_saved_styles', saveStyles( currentNode ) );
								currentNode.setStyle( 'z-index', editor.config.baseFloatZIndex - 1 );
							}
							contents.setCustomData( 'maximize_saved_styles', saveStyles( contents, true ) );
							container.setCustomData( 'maximize_saved_styles', saveStyles( container, true ) );

							// Hide scroll bars.
							if ( CKEDITOR.env.ie )
							{
								mainDocument.$.documentElement.style.overflow =
									mainDocument.getBody().$.style.overflow = 'hidden';
							}
							else
							{
								mainDocument.getBody().setStyles(
									{
										overflow : 'hidden',
										width : '0px',
										height : '0px'
									} );
							}

							// Scroll to the top left.
							mainWindow.$.scrollTo( 0, 0 );

							// Resize and move to top left.
							var viewPaneSize = mainWindow.getViewPaneSize();
							container.setStyle( 'position', 'absolute' );
							container.$.offsetLeft;			// SAFARI BUG: See #2066.
							container.setStyles(
								{
									'z-index' : editor.config.baseFloatZIndex - 1,
									left : '0px',
									top : '0px'
								} );
							editor.resize( viewPaneSize.width, viewPaneSize.height, null, true );

							// Still not top left? Fix it. (Bug #174)
							var offset = container.getDocumentPosition();
							container.setStyles(
								{
									left : ( -1 * offset.x ) + 'px',
									top : ( -1 * offset.y ) + 'px'
								} );

							// Add cke_maximized class.
							container.addClass( 'cke_maximized' );
						}
						else if ( this.state == CKEDITOR.TRISTATE_ON )	// Restore from fullscreen if the state is on.
						{
							// Remove event handler for resizing.
							mainWindow.removeListener( 'resize', resizeHandler );

							// Restore CSS styles for the entire node tree.
							var editorElements = [ contents, container ];
							for ( var i = 0 ; i < editorElements.length ; i++ )
							{
								restoreStyles( editorElements[i], editorElements[i].getCustomData( 'maximize_saved_styles' ) );
								editorElements[i].removeCustomData( 'maximize_saved_styles' );
							}

							currentNode = editor.container;
							while ( ( currentNode = currentNode.getParent() ) )
							{
								restoreStyles( currentNode, currentNode.getCustomData( 'maximize_saved_styles' ) );
								currentNode.removeCustomData( 'maximize_saved_styles' );
							}

							// Restore the window scroll position.
							mainWindow.$.scrollTo( outerScroll.x, outerScroll.y );

							// Remove cke_maximized class.
							container.removeClass( 'cke_maximized' );

							// Emit a resize event, because this time the size is modified in
							// restoreStyles.
							editor.fire( 'resize' );
						}

						this.toggleState();

						// Toggle button label.
						var button = this.uiItems[ 0 ];
						var label = ( this.state == CKEDITOR.TRISTATE_OFF )
							? lang.maximize : lang.minimize;
						var buttonNode = editor.element.getDocument().getById( button._.id );
						buttonNode.getChild( 1 ).setHtml( label );
						buttonNode.setAttribute( 'title', label );
						buttonNode.setAttribute( 'href', 'javascript:void("' + label + '");' );

						// Restore selection and scroll position in editing area.
						if ( editor.mode == 'wysiwyg' )
						{
							if ( savedSelection )
							{
								editor.getSelection().selectRanges(savedSelection);
								var element = editor.getSelection().getStartElement();
								element && element.scrollIntoView( true );
							}

							else
								mainWindow.$.scrollTo( savedScroll.x, savedScroll.y );
						}
						else
						{
							if ( savedSelection )
							{
								$textarea.selectionStart = savedSelection[0];
								$textarea.selectionEnd = savedSelection[1];
							}
							$textarea.scrollLeft = savedScroll[0];
							$textarea.scrollTop = savedScroll[1];
						}

						savedSelection = savedScroll = null;
						savedState = this.state;
					},
					canUndo : false
				} );

			editor.ui.addButton( 'Maximize',
				{
					label : lang.maximize,
					command : 'maximize'
				} );

			// Restore the command state after mode change.
			editor.on( 'mode', function()
				{
					editor.getCommand( 'maximize' ).setState( savedState );
				}, null, null, 100 );
		}
	} );
})();
