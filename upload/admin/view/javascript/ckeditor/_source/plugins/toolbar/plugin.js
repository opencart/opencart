/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview The "toolbar" plugin. Renders the default toolbar interface in
 * the editor.
 */

(function()
{
	var toolbox = function()
	{
		this.toolbars = [];
		this.focusCommandExecuted = false;
	};

	toolbox.prototype.focus = function()
	{
		for ( var t = 0, toolbar ; toolbar = this.toolbars[ t++ ] ; )
		{
			for ( var i = 0, item ; item = toolbar.items[ i++ ] ; )
			{
				if ( item.focus )
				{
					item.focus();
					return;
				}
			}
		}
	};

	var commands =
	{
		toolbarFocus :
		{
			modes : { wysiwyg : 1, source : 1 },

			exec : function( editor )
			{
				if ( editor.toolbox )
				{
					editor.toolbox.focusCommandExecuted = true;

					// Make the first button focus accessible. (#3417)
					if ( CKEDITOR.env.ie )
						setTimeout( function(){ editor.toolbox.focus(); }, 100 );
					else
						editor.toolbox.focus();
				}
			}
		}
	};

	CKEDITOR.plugins.add( 'toolbar',
	{
		init : function( editor )
		{
			var itemKeystroke = function( item, keystroke )
			{
				switch ( keystroke )
				{
					case 39 :					// RIGHT-ARROW
					case 9 :					// TAB
						// Look for the next item in the toolbar.
						while ( ( item = item.next || ( item.toolbar.next && item.toolbar.next.items[ 0 ] ) ) && !item.focus )
						{ /*jsl:pass*/ }

						// If available, just focus it, otherwise focus the
						// first one.
						if ( item )
							item.focus();
						else
							editor.toolbox.focus();

						return false;

					case 37 :					// LEFT-ARROW
					case CKEDITOR.SHIFT + 9 :	// SHIFT + TAB
						// Look for the previous item in the toolbar.
						while ( ( item = item.previous || ( item.toolbar.previous && item.toolbar.previous.items[ item.toolbar.previous.items.length - 1 ] ) ) && !item.focus )
						{ /*jsl:pass*/ }

						// If available, just focus it, otherwise focus the
						// last one.
						if ( item )
							item.focus();
						else
						{
							var lastToolbarItems = editor.toolbox.toolbars[ editor.toolbox.toolbars.length - 1 ].items;
							lastToolbarItems[ lastToolbarItems.length - 1 ].focus();
						}

						return false;

					case 27 :					// ESC
						editor.focus();
						return false;

					case 13 :					// ENTER
					case 32 :					// SPACE
						item.execute();
						return false;
				}
				return true;
			};

			editor.on( 'themeSpace', function( event )
				{
					if ( event.data.space == editor.config.toolbarLocation )
					{
						editor.toolbox = new toolbox();

						var output = [ '<div class="cke_toolbox"' ],
							expanded =  editor.config.toolbarStartupExpanded,
							groupStarted;

						output.push( expanded ? '>' : ' style="display:none">' );

						var toolbars = editor.toolbox.toolbars,
							toolbar =
									( editor.config.toolbar instanceof Array ) ?
										editor.config.toolbar
									:
										editor.config[ 'toolbar_' + editor.config.toolbar ];

						for ( var r = 0 ; r < toolbar.length ; r++ )
						{
							var row = toolbar[ r ];

							// It's better to check if the row object is really
							// available because it's a common mistake to leave
							// an extra comma in the toolbar definition
							// settings, which leads on the editor not loading
							// at all in IE. (#3983)
							if ( !row )
								continue;

							var toolbarId = 'cke_' + CKEDITOR.tools.getNextNumber(),
								toolbarObj = { id : toolbarId, items : [] };

							if ( groupStarted )
							{
								output.push( '</div>' );
								groupStarted = 0;
							}

							if ( row === '/' )
							{
								output.push( '<div class="cke_break"></div>' );
								continue;
							}

							output.push( '<span id="', toolbarId, '" class="cke_toolbar"><span class="cke_toolbar_start"></span>' );

							// Add the toolbar to the "editor.toolbox.toolbars"
							// array.
							var index = toolbars.push( toolbarObj ) - 1;

							// Create the next/previous reference.
							if ( index > 0 )
							{
								toolbarObj.previous = toolbars[ index - 1 ];
								toolbarObj.previous.next = toolbarObj;
							}

							// Create all items defined for this toolbar.
							for ( var i = 0 ; i < row.length ; i++ )
							{
								var item,
									itemName = row[ i ];

								if ( itemName == '-' )
									item = CKEDITOR.ui.separator;
								else
									item = editor.ui.create( itemName );

								if ( item )
								{
									if ( item.canGroup )
									{
										if ( !groupStarted )
										{
											output.push( '<span class="cke_toolgroup">' );
											groupStarted = 1;
										}
									}
									else if ( groupStarted )
									{
										output.push( '</span>' );
										groupStarted = 0;
									}

									var itemObj = item.render( editor, output );
									index = toolbarObj.items.push( itemObj ) - 1;

									if ( index > 0 )
									{
										itemObj.previous = toolbarObj.items[ index - 1 ];
										itemObj.previous.next = itemObj;
									}

									itemObj.toolbar = toolbarObj;
									itemObj.onkey = itemKeystroke;

									/*
									 * Fix for #3052:
									 * Prevent JAWS from focusing the toolbar after document load.
									 */
									itemObj.onfocus = function()
									{
										if ( !editor.toolbox.focusCommandExecuted )
											editor.focus();
									};
								}
							}

							if ( groupStarted )
							{
								output.push( '</span>' );
								groupStarted = 0;
							}

							output.push( '<span class="cke_toolbar_end"></span></span>' );
						}

						output.push( '</div>' );

						if ( editor.config.toolbarCanCollapse )
						{
							var collapserFn = CKEDITOR.tools.addFunction(
								function()
								{
									editor.execCommand( 'toolbarCollapse' );
								} );

							var collapserId = 'cke_' + CKEDITOR.tools.getNextNumber();

							editor.addCommand( 'toolbarCollapse',
								{
									exec : function( editor )
									{
										var collapser = CKEDITOR.document.getById( collapserId );
										var toolbox = collapser.getPrevious();
										var contents = editor.getThemeSpace( 'contents' );
										var toolboxContainer = toolbox.getParent();
										var contentHeight = parseInt( contents.$.style.height, 10 );
										var previousHeight = toolboxContainer.$.offsetHeight;

										if ( toolbox.isVisible() )
										{
											toolbox.hide();
											collapser.addClass( 'cke_toolbox_collapser_min' );
										}
										else
										{
											toolbox.show();
											collapser.removeClass( 'cke_toolbox_collapser_min' );
										}

										var dy = toolboxContainer.$.offsetHeight - previousHeight;
										contents.setStyle( 'height', ( contentHeight - dy ) + 'px' );
									},

									modes : { wysiwyg : 1, source : 1 }
								} );

							output.push( '<a id="' + collapserId + '" class="cke_toolbox_collapser' );

							if ( !expanded )
								output.push( ' cke_toolbox_collapser_min' );

							output.push( '" onclick="CKEDITOR.tools.callFunction(' + collapserFn + ')"></a>' );
						}

						event.data.html += output.join( '' );
					}
				});

			editor.addCommand( 'toolbarFocus', commands.toolbarFocus );
		}
	});
})();

/**
 * The UI element that renders a toolbar separator.
 * @type Object
 * @example
 */
CKEDITOR.ui.separator =
{
	render : function( editor, output )
	{
		output.push( '<span class="cke_separator"></span>' );
		return {};
	}
};

/**
 * The "theme space" to which rendering the toolbar. For the default theme,
 * the recommended options are "top" and "bottom".
 * @type String
 * @default 'top'
 * @see CKEDITOR.config.theme
 * @example
 * config.toolbarLocation = 'bottom';
 */
CKEDITOR.config.toolbarLocation = 'top';

/**
 * The toolbar definition. It is an array of toolbars (strips),
 * each one being also an array, containing a list of UI items.
 * Note that this setting is composed by "toolbar_" added by the toolbar name,
 * which in this case is called "Basic". This second part of the setting name
 * can be anything. You must use this name in the
 * {@link CKEDITOR.config.toolbar} setting, so you instruct the editor which
 * toolbar_(name) setting to you.
 * @type Array
 * @example
 * // Defines a toolbar with only one strip containing the "Source" button, a
 * // separator and the "Bold" and "Italic" buttons.
 * <b>config.toolbar_Basic =
 * [
 *     [ 'Source', '-', 'Bold', 'Italic' ]
 * ]</b>;
 * config.toolbar = 'Basic';
 */
CKEDITOR.config.toolbar_Basic =
[
	['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-','About']
];

/**
 * This is the default toolbar definition used by the editor. It contains all
 * editor features.
 * @type Array
 * @default (see example)
 * @example
 * // This is actually the default value.
 * config.toolbar_Full =
 * [
 *     ['Source','-','Save','NewPage','Preview','-','Templates'],
 *     ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
 *     ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
 *     ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
 *     '/',
 *     ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
 *     ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
 *     ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
 *     ['Link','Unlink','Anchor'],
 *     ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
 *     '/',
 *     ['Styles','Format','Font','FontSize'],
 *     ['TextColor','BGColor'],
 *     ['Maximize', 'ShowBlocks','-','About']
 * ];
 */
CKEDITOR.config.toolbar_Full =
[
	['Source','-','Save','NewPage','Preview','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
	'/',
	['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
	['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	['Link','Unlink','Anchor'],
	['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
	'/',
	['Styles','Format','Font','FontSize'],
	['TextColor','BGColor'],
	['Maximize', 'ShowBlocks','-','About']
];

/**
 * The toolbox (alias toolbar) definition. It is a toolbar name or an array of
 * toolbars (strips), each one being also an array, containing a list of UI items.
 * @type Array|String
 * @default 'Full'
 * @example
 * // Defines a toolbar with only one strip containing the "Source" button, a
 * // separator and the "Bold" and "Italic" buttons.
 * config.toolbar =
 * [
 *     [ 'Source', '-', 'Bold', 'Italic' ]
 * ];
 * @example
 * // Load toolbar_Name where Name = Basic.
 * config.toolbar = 'Basic';
 */
CKEDITOR.config.toolbar = 'Full';

/**
 * Whether the toolbar can be collapsed by the user. If disabled, the collapser
 * button will not be displayed.
 * @type Boolean
 * @default true
 * @example
 * config.toolbarCanCollapse = false;
 */
CKEDITOR.config.toolbarCanCollapse = true;

/**
 * Whether the toolbar must start expanded when the editor is loaded.
 * @type Boolean
 * @default true
 * @example
 * config.toolbarStartupExpanded = false;
 */
CKEDITOR.config.toolbarStartupExpanded = true;
