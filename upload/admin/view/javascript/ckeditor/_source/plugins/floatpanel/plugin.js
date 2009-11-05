/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'floatpanel',
{
	requires : [ 'panel' ]
});

(function()
{
	var panels = {};
	var isShowing = false;

	function getPanel( editor, doc, parentElement, definition, level )
	{
		// Generates the panel key: docId-eleId-skinName-langDir[-uiColor][-CSSs][-level]
		var key =
			doc.getUniqueId() +
			'-' + parentElement.getUniqueId() +
			'-' + editor.skinName +
			'-' + editor.lang.dir +
			( ( editor.uiColor && ( '-' + editor.uiColor ) ) || '' ) +
			( ( definition.css && ( '-' + definition.css ) ) || '' ) +
			( ( level && ( '-' + level ) ) || '' );

		var panel = panels[ key ];

		if ( !panel )
		{
			panel = panels[ key ] = new CKEDITOR.ui.panel( doc, definition );
			panel.element = parentElement.append( CKEDITOR.dom.element.createFromHtml( panel.renderHtml( editor ), doc ) );

			panel.element.setStyles(
				{
					display : 'none',
					position : 'absolute'
				});
		}

		return panel;
	}

	CKEDITOR.ui.floatPanel = CKEDITOR.tools.createClass(
	{
		$ : function( editor, parentElement, definition, level )
		{
			definition.forceIFrame = true;

			var doc = parentElement.getDocument(),
				panel = getPanel( editor, doc, parentElement, definition, level || 0 ),
				element = panel.element,
				iframe = element.getFirst().getFirst();

			this.element = element;

			// Register panels to editor for easy destroying ( #4241 ).
			editor.panels ? editor.panels.push( element ) : editor.panels = [ element ];


			this._ =
			{
				// The panel that will be floating.
				panel : panel,
				parentElement : parentElement,
				definition : definition,
				document : doc,
				iframe : iframe,
				children : [],
				dir : editor.lang.dir
			};
		},

		proto :
		{
			addBlock : function( name, block )
			{
				return this._.panel.addBlock( name, block );
			},

			addListBlock : function( name, multiSelect )
			{
				return this._.panel.addListBlock( name, multiSelect );
			},

			getBlock : function( name )
			{
				return this._.panel.getBlock( name );
			},

			/*
				corner (LTR):
					1 = top-left
					2 = top-right
					3 = bottom-right
					4 = bottom-left

				corner (RTL):
					1 = top-right
					2 = top-left
					3 = bottom-left
					4 = bottom-right
			 */
			showBlock : function( name, offsetParent, corner, offsetX, offsetY )
			{
				var panel = this._.panel,
					block = panel.showBlock( name );

				this.allowBlur( false );
				isShowing = true;

				var element = this.element,
					iframe = this._.iframe,
					definition = this._.definition,
					position = offsetParent.getDocumentPosition( element.getDocument() ),
					rtl = this._.dir == 'rtl';

				var left	= position.x + ( offsetX || 0 ),
					top		= position.y + ( offsetY || 0 );

				// Floating panels are off by (-1px, 0px) in RTL mode. (#3438)
				if ( rtl && ( corner == 1 || corner == 4 ) )
					left += offsetParent.$.offsetWidth;
				else if ( !rtl && ( corner == 2 || corner == 3 ) )
					left += offsetParent.$.offsetWidth - 1;

				if ( corner == 3 || corner == 4 )
					top += offsetParent.$.offsetHeight - 1;

				// Memorize offsetParent by it's ID.
				this._.panel._.offsetParentId = offsetParent.getId();

				element.setStyles(
					{
						top : top + 'px',
						left : '-3000px',
						visibility : 'hidden',
						opacity : '0',	// FF3 is ignoring "visibility"
						display	: ''
					});

				// Configure the IFrame blur event. Do that only once.
				if ( !this._.blurSet )
				{
					// Non IE prefer the event into a window object.
					var focused = CKEDITOR.env.ie ? iframe : new CKEDITOR.dom.window( iframe.$.contentWindow );

					// With addEventListener compatible browsers, we must
					// useCapture when registering the focus/blur events to
					// guarantee they will be firing in all situations. (#3068, #3222 )
					CKEDITOR.event.useCapture = true;

					focused.on( 'blur', function( ev )
						{
							if ( CKEDITOR.env.ie && !this.allowBlur() )
								return;

							// As we are using capture to register the listener,
							// the blur event may get fired even when focusing
							// inside the window itself, so we must ensure the
							// target is out of it.
							var target = ev.data.getTarget(),
								targetWindow = target.getWindow && target.getWindow();

							if ( targetWindow && targetWindow.equals( focused ) )
								return;

							if ( this.visible && !this._.activeChild && !isShowing )
								this.hide();
						},
						this );

					focused.on( 'focus', function()
						{
							this._.focused = true;
							this.hideChild();
							this.allowBlur( true );
						},
						this );

					CKEDITOR.event.useCapture = false;

					this._.blurSet = 1;
				}

				panel.onEscape = CKEDITOR.tools.bind( function()
					{
						this.onEscape && this.onEscape();
					},
					this );

				CKEDITOR.tools.setTimeout( function()
					{
						if ( rtl )
							left -= element.$.offsetWidth;

						element.setStyles(
							{
								left : left + 'px',
								visibility	: '',
								opacity : '1'	// FF3 is ignoring "visibility"
							});

						if ( block.autoSize )
						{
							function setHeight()
							{
								var target = element.getFirst();
								var height = block.element.$.scrollHeight;

								// Account for extra height needed due to IE quirks box model bug:
								// http://en.wikipedia.org/wiki/Internet_Explorer_box_model_bug
								// (#3426)
								if ( CKEDITOR.env.ie && CKEDITOR.env.quirks && height > 0 )
									height += ( target.$.offsetHeight || 0 ) - ( target.$.clientHeight || 0 );

								target.setStyle( 'height', height + 'px' );

								// Fix IE < 8 visibility.
								panel._.currentBlock.element.setStyle( 'display', 'none' ).removeStyle( 'display' );
							}

							if ( panel.isLoaded )
								setHeight();
							else
								panel.onLoad = setHeight;
						}
						else
							element.getFirst().removeStyle( 'height' );

						// Set the IFrame focus, so the blur event gets fired.
						CKEDITOR.tools.setTimeout( function()
							{
								if ( definition.voiceLabel )
								{
									if ( CKEDITOR.env.gecko )
									{
										var container = iframe.getParent();
										container.setAttribute( 'role', 'region' );
										container.setAttribute( 'title', definition.voiceLabel );
										iframe.setAttribute( 'role', 'region' );
										iframe.setAttribute( 'title', ' ' );
									}
								}
								if ( CKEDITOR.env.ie && CKEDITOR.env.quirks )
									iframe.focus();
								else
									iframe.$.contentWindow.focus();

								// We need this get fired manually because of unfired focus() function.
								if ( CKEDITOR.env.ie && !CKEDITOR.env.quirks )
									this.allowBlur( true );
							}, 0, this);
					}, 0, this);
				this.visible = 1;

				if ( this.onShow )
					this.onShow.call( this );

				isShowing = false;
			},

			hide : function()
			{
				if ( this.visible && ( !this.onHide || this.onHide.call( this ) !== true ) )
				{
					this.hideChild();
					this.element.setStyle( 'display', 'none' );
					this.visible = 0;
				}
			},

			allowBlur : function( allow )	// Prevent editor from hiding the panel. #3222.
			{
				var panel = this._.panel;
				if ( allow != undefined )
					panel.allowBlur = allow;

				return panel.allowBlur;
			},

			showAsChild : function( panel, blockName, offsetParent, corner, offsetX, offsetY )
			{
				// Skip reshowing of child which is already visible.
				if ( this._.activeChild == panel && panel._.panel._.offsetParentId == offsetParent.getId() )
					return;

				this.hideChild();

				panel.onHide = CKEDITOR.tools.bind( function()
					{
						// Use a timeout, so we give time for this menu to get
						// potentially focused.
						CKEDITOR.tools.setTimeout( function()
							{
								if ( !this._.focused )
									this.hide();
							},
							0, this );
					},
					this );

				this._.activeChild = panel;
				this._.focused = false;

				panel.showBlock( blockName, offsetParent, corner, offsetX, offsetY );

				/* #3767 IE: Second level menu may not have borders */
				if ( CKEDITOR.env.ie7Compat || ( CKEDITOR.env.ie8 && CKEDITOR.env.ie6Compat ) )
				{
					setTimeout(function()
						{
							panel.element.getChild( 0 ).$.style.cssText += '';
						}, 100);
				}
			},

			hideChild : function()
			{
				var activeChild = this._.activeChild;

				if ( activeChild )
				{
					delete activeChild.onHide;
					delete this._.activeChild;
					activeChild.hide();
				}
			}
		}
	});
})();
