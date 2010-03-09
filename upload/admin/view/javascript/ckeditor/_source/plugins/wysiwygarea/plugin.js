/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview The "wysiwygarea" plugin. It registers the "wysiwyg" editing
 *		mode, which handles the main editing area space.
 */

(function()
{
	/**
	 * List of elements in which has no way to move editing focus outside.
	 */
	var nonExitableElementNames = { table:1,pre:1 };
	// Matching an empty paragraph at the end of document.
	var emptyParagraphRegexp = /\s*<(p|div|address|h\d|center)[^>]*>\s*(?:<br[^>]*>|&nbsp;|&#160;)\s*(:?<\/\1>)?\s*$/gi;

	function onInsertHtml( evt )
	{
		if ( this.mode == 'wysiwyg' )
		{
			this.focus();

			var selection = this.getSelection(),
				data = evt.data;

			if ( this.dataProcessor )
				data = this.dataProcessor.toHtml( data );

			if ( CKEDITOR.env.ie )
			{
				var selIsLocked = selection.isLocked;

				if ( selIsLocked )
					selection.unlock();

				var $sel = selection.getNative();
				if ( $sel.type == 'Control' )
					$sel.clear();
				$sel.createRange().pasteHTML( data );

				if ( selIsLocked )
					this.getSelection().lock();
			}
			else
				this.document.$.execCommand( 'inserthtml', false, data );
		}
	}

	function onInsertElement( evt )
	{
		if ( this.mode == 'wysiwyg' )
		{
			this.focus();
			this.fire( 'saveSnapshot' );

			var element = evt.data,
				elementName = element.getName(),
				isBlock = CKEDITOR.dtd.$block[ elementName ];

			var selection = this.getSelection(),
				ranges = selection.getRanges();

			var selIsLocked = selection.isLocked;

			if ( selIsLocked )
				selection.unlock();

			var range, clone, lastElement, bookmark;

			for ( var i = ranges.length - 1 ; i >= 0 ; i-- )
			{
				range = ranges[ i ];

				// Remove the original contents.
				range.deleteContents();

				clone = !i && element || element.clone( true );

				// If we're inserting a block at dtd-violated position, split
				// the parent blocks until we reach blockLimit.
				var current, dtd;
				if ( isBlock )
				{
					while( ( current = range.getCommonAncestor( false, true ) )
							&& ( dtd = CKEDITOR.dtd[ current.getName() ] )
							&& !( dtd && dtd [ elementName ] ) )
					{
						// If we're in an empty block which indicate a new paragraph,
						// simply replace it with the inserting block.(#3664)
						if ( range.checkStartOfBlock()
							 && range.checkEndOfBlock() )
						{
							range.setStartBefore( current );
							range.collapse( true );
							current.remove();
						}
						else
							range.splitBlock();
					}
				}

				// Insert the new node.
				range.insertNode( clone );

				// Save the last element reference so we can make the
				// selection later.
				if ( !lastElement )
					lastElement = clone;
			}

			range.moveToPosition( lastElement, CKEDITOR.POSITION_AFTER_END );

			var next = lastElement.getNextSourceNode( true );
			if ( next && next.type == CKEDITOR.NODE_ELEMENT )
				range.moveToElementEditStart( next );

			selection.selectRanges( [ range ] );

			if ( selIsLocked )
				this.getSelection().lock();

			// Save snaps after the whole execution completed.
			// This's a workaround for make DOM modification's happened after
			// 'insertElement' to be included either, e.g. Form-based dialogs' 'commitContents'
			// call.
			CKEDITOR.tools.setTimeout( function(){
				this.fire( 'saveSnapshot' );
			}, 0, this );
		}
	}

	// DOM modification here should not bother dirty flag.(#4385)
	function restoreDirty( editor )
	{
		if( !editor.checkDirty() )
			setTimeout( function(){ editor.resetDirty(); } );
	}

	/**
	 *  Auto-fixing block-less content by wrapping paragraph (#3190), prevent
	 *  non-exitable-block by padding extra br.(#3189)
	 */
	function onSelectionChangeFixBody( evt )
	{
		var editor = evt.editor,
			path = evt.data.path,
			blockLimit = path.blockLimit,
			selection = evt.data.selection,
			range = selection.getRanges()[0],
			body = editor.document.getBody(),
			enterMode = editor.config.enterMode;

		// When enterMode set to block, we'll establing new paragraph only if we're
		// selecting inline contents right under body. (#3657)
		if ( enterMode != CKEDITOR.ENTER_BR
		     && range.collapsed
			 && blockLimit.getName() == 'body'
			 && !path.block )
		{
			restoreDirty( editor );
			var bms = selection.createBookmarks(),
				fixedBlock = range.fixBlock( true,
					editor.config.enterMode == CKEDITOR.ENTER_DIV ? 'div' : 'p'  );

			// For IE, we'll be removing any bogus br ( introduce by fixing body )
			// right now to prevent it introducing visual line break.
			if ( CKEDITOR.env.ie )
			{
				var brNodeList = fixedBlock.getElementsByTag( 'br' ), brNode;
				for ( var i = 0 ; i < brNodeList.count() ; i++ )
				{
					if( ( brNode = brNodeList.getItem( i ) ) && brNode.hasAttribute( '_cke_bogus' ) )
						brNode.remove();
				}
			}

			selection.selectBookmarks( bms );

			// If the fixed block is blank and is already followed by a exitable
			// block, we should drop it and move to the exist block(#3684).
			var children = fixedBlock.getChildren(),
				count = children.count(),
				firstChild,
				whitespaceGuard = CKEDITOR.dom.walker.whitespaces( true ),
				previousElement = fixedBlock.getPrevious( whitespaceGuard ),
				nextElement = fixedBlock.getNext( whitespaceGuard ),
				enterBlock;
			if ( previousElement && previousElement.getName
				 && !( previousElement.getName() in nonExitableElementNames ) )
				enterBlock = previousElement;
			else if ( nextElement && nextElement.getName
					  && !( nextElement.getName() in nonExitableElementNames ) )
				enterBlock = nextElement;

			// Not all blocks are editable, e.g. <hr />, further checking it.(#3994)
			if( ( !count
				  || ( firstChild = children.getItem( 0 ) ) && firstChild.is && firstChild.is( 'br' ) )
				&& enterBlock
				&& range.moveToElementEditStart( enterBlock ) )
			{
				fixedBlock.remove();
				range.select();
			}
		}

		// Inserting the padding-br before body if it's preceded by an
		// unexitable block.
		var lastNode = body.getLast( CKEDITOR.dom.walker.whitespaces( true ) );
		if ( lastNode && lastNode.getName && ( lastNode.getName() in nonExitableElementNames ) )
		{
			restoreDirty( editor );
			var paddingBlock = editor.document.createElement(
					( CKEDITOR.env.ie && enterMode != CKEDITOR.ENTER_BR ) ?
						'<br _cke_bogus="true" />' : 'br' );
			body.append( paddingBlock );
		}
	}

	CKEDITOR.plugins.add( 'wysiwygarea',
	{
		requires : [ 'editingblock' ],

		init : function( editor )
		{
			var fixForBody = ( editor.config.enterMode != CKEDITOR.ENTER_BR )
				? editor.config.enterMode == CKEDITOR.ENTER_DIV ? 'div' : 'p' : false;

			editor.on( 'editingBlockReady', function()
				{
					var mainElement,
						fieldset,
						iframe,
						isLoadingData,
						isPendingFocus,
						frameLoaded,
						fireMode;

					// Support for custom document.domain in IE.
					var isCustomDomain = CKEDITOR.env.isCustomDomain();

					// Creates the iframe that holds the editable document.
					var createIFrame = function()
					{
						if ( iframe )
							iframe.remove();
						if ( fieldset )
							fieldset.remove();

						frameLoaded = 0;
						// The document domain must be set within the src
						// attribute;
						// Defer the script execution until iframe
						// has been added to main window, this is needed for some
						// browsers which will begin to load the frame content
						// prior to it's presentation in DOM.(#3894)
						var src = 'void( '
								+ ( CKEDITOR.env.gecko ? 'setTimeout' : '' ) + '( function(){' +
								'document.open();' +
								( CKEDITOR.env.ie && isCustomDomain ? 'document.domain="' + document.domain + '";' : '' ) +
								'document.write( window.parent[ "_cke_htmlToLoad_' + editor.name + '" ] );' +
								'document.close();' +
								'window.parent[ "_cke_htmlToLoad_' + editor.name + '" ] = null;' +
								'}'
								+ ( CKEDITOR.env.gecko ? ', 0 )' : ')()' )
								+ ' )';

						// Loading via src attribute does not work in Opera.
						if ( CKEDITOR.env.opera )
							src = 'void(0);';

						iframe = CKEDITOR.dom.element.createFromHtml( '<iframe' +
								' style="width:100%;height:100%"' +
								' frameBorder="0"' +
								' tabIndex="-1"' +
								' allowTransparency="true"' +
								' src="javascript:' + encodeURIComponent( src ) + '"' +
								'></iframe>' );

						var accTitle = editor.lang.editorTitle.replace( '%1', editor.name );

						if ( CKEDITOR.env.gecko )
						{
							// Double checking the iframe will be loaded properly(#4058).
							iframe.on( 'load', function( ev )
							{
								ev.removeListener();
								contentDomReady( iframe.$.contentWindow );
							} );

							// Accessibility attributes for Firefox.
							mainElement.setAttributes(
								{
									role : 'region',
									title : accTitle
								} );
							iframe.setAttributes(
								{
									role : 'region',
									title : ' '
								} );
						}
						else if ( CKEDITOR.env.webkit )
						{
							iframe.setAttribute( 'title', accTitle );	// Safari 4
							iframe.setAttribute( 'name', accTitle );	// Safari 3
						}
						else if ( CKEDITOR.env.ie )
						{
							// Accessibility label for IE.
							fieldset = CKEDITOR.dom.element.createFromHtml(
								'<fieldset style="height:100%' +
								( CKEDITOR.env.ie && CKEDITOR.env.quirks ? ';position:relative' : '' ) +
								'">' +
									'<legend style="display:block;width:0;height:0;overflow:hidden;' +
									( CKEDITOR.env.ie && CKEDITOR.env.quirks ? 'position:absolute' : '' ) +
									'">' +
										CKEDITOR.tools.htmlEncode( accTitle ) +
									'</legend>' +
								'</fieldset>'
								, CKEDITOR.document );
							iframe.appendTo( fieldset );
							fieldset.appendTo( mainElement );
						}

						if ( !CKEDITOR.env.ie )
							mainElement.append( iframe );
					};

					// The script that is appended to the data being loaded. It
					// enables editing, and makes some
					var activationScript =
						'<script id="cke_actscrpt" type="text/javascript">' +
							'window.onload = function()' +
							'{' +
								// Call the temporary function for the editing
								// boostrap.
								'window.parent.CKEDITOR._["contentDomReady' + editor.name + '"]( window );' +
							'}' +
						'</script>';

					// Editing area bootstrap code.
					var contentDomReady = function( domWindow )
					{
						if ( frameLoaded )
							return;

						frameLoaded = 1;

						var domDocument = domWindow.document,
							body = domDocument.body;

						// Remove this script from the DOM.
						var script = domDocument.getElementById( "cke_actscrpt" );
						script.parentNode.removeChild( script );

						delete CKEDITOR._[ 'contentDomReady' + editor.name ];

						body.spellcheck = !editor.config.disableNativeSpellChecker;

						if ( CKEDITOR.env.ie )
						{
							// Don't display the focus border.
							body.hideFocus = true;

							// Disable and re-enable the body to avoid IE from
							// taking the editing focus at startup. (#141 / #523)
							body.disabled = true;
							body.contentEditable = true;
							body.removeAttribute( 'disabled' );
						}
						else
							domDocument.designMode = 'on';

						// IE, Opera and Safari may not support it and throw
						// errors.
						try { domDocument.execCommand( 'enableObjectResizing', false, !editor.config.disableObjectResizing ) ; } catch(e) {}
						try { domDocument.execCommand( 'enableInlineTableEditing', false, !editor.config.disableNativeTableHandles ) ; } catch(e) {}

						domWindow	= editor.window		= new CKEDITOR.dom.window( domWindow );
						domDocument	= editor.document	= new CKEDITOR.dom.document( domDocument );

						// Gecko/Webkit need some help when selecting control type elements. (#3448)
						if ( !( CKEDITOR.env.ie || CKEDITOR.env.opera) )
						{
							domDocument.on( 'mousedown', function( ev )
							{
								var control = ev.data.getTarget();
								if ( control.is( 'img', 'hr', 'input', 'textarea', 'select' ) )
									editor.getSelection().selectElement( control );
							} );
						}

						// Webkit: avoid from editing form control elements content.
						if ( CKEDITOR.env.webkit )
						{
							// Prevent from tick checkbox/radiobox/select
							domDocument.on( 'click', function( ev )
							{
								if ( ev.data.getTarget().is( 'input', 'select' ) )
									ev.data.preventDefault();
							} );

							// Prevent from editig textfield/textarea value.
							domDocument.on( 'mouseup', function( ev )
							{
								if ( ev.data.getTarget().is( 'input', 'textarea' ) )
									ev.data.preventDefault();
							} );
						}

						var focusTarget = ( CKEDITOR.env.ie || CKEDITOR.env.webkit ) ?
								domWindow : domDocument;

						focusTarget.on( 'blur', function()
							{
								editor.focusManager.blur();
							});

						focusTarget.on( 'focus', function()
							{
								// Gecko need a key event to 'wake up' the editing
								// ability when document is empty.(#3864)
								if ( CKEDITOR.env.gecko )
								{
									var first = body;
									while( first.firstChild )
										first = first.firstChild;

									if( !first.nextSibling
										&& ( 'BR' == first.tagName )
										&& first.hasAttribute( '_moz_editor_bogus_node' ) )
									{
										var keyEventSimulate = domDocument.$.createEvent( "KeyEvents" );
										keyEventSimulate.initKeyEvent( 'keypress', true, true, domWindow.$, false,
											false, false, false, 0, 32 );
										domDocument.$.dispatchEvent( keyEventSimulate );
										var bogusText = domDocument.getBody().getFirst() ;
										// Compensate the line maintaining <br> if enterMode is not block.
										if ( editor.config.enterMode == CKEDITOR.ENTER_BR )
											domDocument.createElement( 'br', { attributes: { '_moz_dirty' : "" } } )
												.replace( bogusText );
										else
											bogusText.remove();
									}
								}

								editor.focusManager.focus();
							});

						var keystrokeHandler = editor.keystrokeHandler;
						if ( keystrokeHandler )
							keystrokeHandler.attach( domDocument );

						// Cancel default action for backspace in IE on control types. (#4047)
						if ( CKEDITOR.env.ie )
						{
							editor.on( 'key', function( event )
							{
								// Backspace.
								var control = event.data.keyCode == 8
											  && editor.getSelection().getSelectedElement();
								if ( control )
								{
									// Make undo snapshot.
									editor.fire( 'saveSnapshot' );
									// Remove manually.
									control.remove();
									editor.fire( 'saveSnapshot' );
									event.cancel();
								}
							} );
						}

						// Adds the document body as a context menu target.
						if ( editor.contextMenu )
							editor.contextMenu.addTarget( domDocument );

						setTimeout( function()
							{
								editor.fire( 'contentDom' );

								if ( fireMode )
								{
									editor.mode = 'wysiwyg';
									editor.fire( 'mode' );
									fireMode = false;
								}

								isLoadingData = false;

								if ( isPendingFocus )
								{
									editor.focus();
									isPendingFocus = false;
								}
								setTimeout( function()
								{
									editor.fire( 'dataReady' );
								}, 0 );

								/*
								 * IE BUG: IE might have rendered the iframe with invisible contents.
								 * (#3623). Push some inconsequential CSS style changes to force IE to
								 * refresh it.
								 *
								 * Also, for some unknown reasons, short timeouts (e.g. 100ms) do not
								 * fix the problem. :(
								 */
								if ( CKEDITOR.env.ie )
								{
									setTimeout( function()
										{
											if ( editor.document )
											{
												var $body = editor.document.$.body;
												$body.runtimeStyle.marginBottom = '0px';
												$body.runtimeStyle.marginBottom = '';
											}
										}, 1000 );
								}
							},
							0 );
					};

					editor.addMode( 'wysiwyg',
						{
							load : function( holderElement, data, isSnapshot )
							{
								mainElement = holderElement;

								if ( CKEDITOR.env.ie && CKEDITOR.env.quirks )
									holderElement.setStyle( 'position', 'relative' );

								// The editor data "may be dirty" after this
								// point.
								editor.mayBeDirty = true;

								fireMode = true;

								if ( isSnapshot )
									this.loadSnapshotData( data );
								else
									this.loadData( data );
							},

							loadData : function( data )
							{
								isLoadingData = true;

								// Get the HTML version of the data.
								if ( editor.dataProcessor )
								{
									data = editor.dataProcessor.toHtml( data, fixForBody );
								}

								data =
									editor.config.docType +
									'<html dir="' + editor.config.contentsLangDirection + '">' +
									'<head>' +
										'<link type="text/css" rel="stylesheet" href="' +
										[].concat( editor.config.contentsCss ).join( '"><link type="text/css" rel="stylesheet" href="' ) +
										'">' +
										'<style type="text/css" _fcktemp="true">' +
											editor._.styles.join( '\n' ) +
										'</style>'+
									'</head>' +
									'<body>' +
										data +
									'</body>' +
									'</html>' +
									activationScript;

								window[ '_cke_htmlToLoad_' + editor.name ] = data;
								CKEDITOR._[ 'contentDomReady' + editor.name ] = contentDomReady;
								createIFrame();

								// Opera must use the old method for loading contents.
								if ( CKEDITOR.env.opera )
								{
									var doc = iframe.$.contentWindow.document;
									doc.open();
									doc.write( data );
									doc.close();
								}
							},

							getData : function()
							{
								var data = iframe.getFrameDocument().getBody().getHtml();

								if ( editor.dataProcessor )
									data = editor.dataProcessor.toDataFormat( data, fixForBody );

								// Strip the last blank paragraph within document.
								if ( editor.config.ignoreEmptyParagraph )
									data = data.replace( emptyParagraphRegexp, '' );

								return data;
							},

							getSnapshotData : function()
							{
								return iframe.getFrameDocument().getBody().getHtml();
							},

							loadSnapshotData : function( data )
							{
								iframe.getFrameDocument().getBody().setHtml( data );
							},

							unload : function( holderElement )
							{
								editor.window = editor.document = iframe = mainElement = isPendingFocus = null;

								editor.fire( 'contentDomUnload' );
							},

							focus : function()
							{
								if ( isLoadingData )
									isPendingFocus = true;
								else if ( editor.window )
								{
									editor.window.focus();
									editor.selectionChange();
								}
							}
						});

					editor.on( 'insertHtml', onInsertHtml, null, null, 20 );
					editor.on( 'insertElement', onInsertElement, null, null, 20 );
					// Auto fixing on some document structure weakness to enhance usabilities. (#3190 and #3189)
					editor.on( 'selectionChange', onSelectionChangeFixBody, null, null, 1 );
				});
		}
	});
})();

/**
 * Disables the ability of resize objects (image and tables) in the editing
 * area.
 * @type Boolean
 * @default false
 * @example
 * config.disableObjectResizing = true;
 */
CKEDITOR.config.disableObjectResizing = false;

/**
 * Disables the "table tools" offered natively by the browser (currently
 * Firefox only) to make quick table editing operations, like adding or
 * deleting rows and columns.
 * @type Boolean
 * @default true
 * @example
 * config.disableNativeTableHandles = false;
 */
CKEDITOR.config.disableNativeTableHandles = true;

/**
 * Disables the built-in spell checker while typing natively available in the
 * browser (currently Firefox and Safari only).<br /><br />
 *
 * Even if word suggestions will not appear in the CKEditor context menu, this
 * feature is useful to help quickly identifying misspelled words.<br /><br />
 *
 * This setting is currently compatible with Firefox only due to limitations in
 * other browsers.
 * @type Boolean
 * @default true
 * @example
 * config.disableNativeSpellChecker = false;
 */
CKEDITOR.config.disableNativeSpellChecker = true;

/**
 * Whether the editor must output an empty value ("") if it's contents is made
 * by an empty paragraph only.
 * @type Boolean
 * @default true
 * @example
 * config.ignoreEmptyParagraph = false;
 */
CKEDITOR.config.ignoreEmptyParagraph = true;
