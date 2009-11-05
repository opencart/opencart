/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'panel',
{
	beforeInit : function( editor )
	{
		editor.ui.addHandler( CKEDITOR.UI_PANEL, CKEDITOR.ui.panel.handler );
	}
});

/**
 * Panel UI element.
 * @constant
 * @example
 */
CKEDITOR.UI_PANEL = 2;

CKEDITOR.ui.panel = function( document, definition )
{
	// Copy all definition properties to this object.
	if ( definition )
		CKEDITOR.tools.extend( this, definition );

	// Set defaults.
	CKEDITOR.tools.extend( this,
		{
			className : '',
			css : []
		});

	this.id = CKEDITOR.tools.getNextNumber();
	this.document = document;

	this._ =
	{
		blocks : {}
	};
};

/**
 * Transforms a rich combo definition in a {@link CKEDITOR.ui.richCombo}
 * instance.
 * @type Object
 * @example
 */
CKEDITOR.ui.panel.handler =
{
	create : function( definition )
	{
		return new CKEDITOR.ui.panel( definition );
	}
};

CKEDITOR.ui.panel.prototype =
{
	renderHtml : function( editor )
	{
		var output = [];
		this.render( editor, output );
		return output.join( '' );
	},

	/**
	 * Renders the combo.
	 * @param {CKEDITOR.editor} editor The editor instance which this button is
	 *		to be used by.
	 * @param {Array} output The output array to which append the HTML relative
	 *		to this button.
	 * @example
	 */
	render : function( editor, output )
	{
		var id = 'cke_' + this.id;

		output.push(
			'<div class="', editor.skinClass ,'"' +
				' lang="', editor.langCode, '"' +
				// iframe loading need sometime, keep the panel hidden(#4186).
				' style="display:none;z-index:' + ( editor.config.baseFloatZIndex + 1 ) + '">' +
				'<div' +
					' id=', id,
					' dir=', editor.lang.dir,
					' class="cke_panel cke_', editor.lang.dir );

		if ( this.className )
			output.push( ' ', this.className );

		output.push(
				'">' );

		if ( this.forceIFrame || this.css.length )
		{
			output.push(
						'<iframe id="', id, '_frame"' +
							' frameborder="0"' +
							' src="javascript:void(' );

			output.push(
							// Support for custom document.domain in IE.
							CKEDITOR.env.isCustomDomain() ?
								'(function(){' +
									'document.open();' +
									'document.domain=\'' + document.domain + '\';' +
									'document.close();' +
								'})()'
							:
								'0' );

			output.push(
						')"></iframe>' );
		}

		output.push(
				'</div>' +
			'</div>' );

		return id;
	},

	getHolderElement : function()
	{
		var holder = this._.holder;

		if ( !holder )
		{
			if ( this.forceIFrame || this.css.length )
			{
				var iframe = this.document.getById( 'cke_' + this.id + '_frame' ),
					parentDiv = iframe.getParent(),
					dir = parentDiv.getAttribute( 'dir' ),
					className = parentDiv.getParent().getAttribute( 'class' ),
					langCode = parentDiv.getParent().getAttribute( 'lang' ),
					doc = iframe.getFrameDocument();
				// Initialize the IFRAME document body.
				doc.$.open();

				// Support for custom document.domain in IE.
				if ( CKEDITOR.env.isCustomDomain() )
					doc.$.domain = document.domain;

				var onLoad = CKEDITOR.tools.addFunction( CKEDITOR.tools.bind( function( ev )
					{
						this.isLoaded = true;
						if ( this.onLoad )
							this.onLoad();
					}, this ) );

				doc.$.write(
					'<!DOCTYPE html>' +
					'<html dir="' + dir + '" class="' + className + '_container" lang="' + langCode + '">' +
						'<head>' +
							'<style>.' + className + '_container{visibility:hidden}</style>' +
						'</head>' +
						'<body class="cke_' + dir + ' cke_panel_frame ' + CKEDITOR.env.cssClass + '" style="margin:0;padding:0"' +
						' onload="( window.CKEDITOR || window.parent.CKEDITOR ).tools.callFunction(' + onLoad + ');">' +
						'</body>' +
						// It looks strange, but for FF2, the styles must go
						// after <body>, so it (body) becames immediatelly
						// available. (#3031)
						'<link type="text/css" rel=stylesheet href="' + this.css.join( '"><link type="text/css" rel="stylesheet" href="' ) + '">' +
					'<\/html>' );
				doc.$.close();

				var win = doc.getWindow();

				// Register the CKEDITOR global.
				win.$.CKEDITOR = CKEDITOR;

				doc.on( 'keydown', function( evt )
					{
						var keystroke = evt.data.getKeystroke();

						// Delegate key processing to block.
						if ( this._.onKeyDown && this._.onKeyDown( keystroke ) === false )
						{
							evt.data.preventDefault();
							return;
						}

						if ( keystroke == 27 )		// ESC
							this.onEscape && this.onEscape();
					},
					this );

				holder = doc.getBody();
			}
			else
				holder = this.document.getById( 'cke_' + this.id );

			this._.holder = holder;
		}

		return holder;
	},

	addBlock : function( name, block )
	{
		block = this._.blocks[ name ] = block || new CKEDITOR.ui.panel.block( this.getHolderElement() );

		if ( !this._.currentBlock )
			this.showBlock( name );

		return block;
	},

	getBlock : function( name )
	{
		return this._.blocks[ name ];
	},

	showBlock : function( name )
	{
		var blocks = this._.blocks,
			block = blocks[ name ],
			current = this._.currentBlock;

		if ( current )
			current.hide();

		this._.currentBlock = block;

		// Reset the focus index, so it will always go into the first one.
		block._.focusIndex = -1;

		this._.onKeyDown = block.onKeyDown && CKEDITOR.tools.bind( block.onKeyDown, block );

		block.show();

		return block;
	}
};

CKEDITOR.ui.panel.block = CKEDITOR.tools.createClass(
{
	$ : function( blockHolder )
	{
		this.element = blockHolder.append(
			blockHolder.getDocument().createElement( 'div',
				{
					attributes :
					{
						'class' : 'cke_panel_block'
					},
					styles :
					{
						display : 'none'
					}
				}) );

		this.keys = {};

		this._.focusIndex = -1;

		// Disable context menu for panels.
		this.element.disableContextMenu();
	},

	_ : {},

	proto :
	{
		show : function()
		{
			this.element.setStyle( 'display', '' );
		},

		hide : function()
		{
			if ( !this.onHide || this.onHide.call( this )  !== true )
				this.element.setStyle( 'display', 'none' );
		},

		onKeyDown : function( keystroke )
		{
			var keyAction = this.keys[ keystroke ];
			switch ( keyAction )
			{
				// Move forward.
				case 'next' :
					var index = this._.focusIndex,
						links = this.element.getElementsByTag( 'a' ),
						link;

					while ( ( link = links.getItem( ++index ) ) )
					{
						// Move the focus only if the element is marked with
						// the _cke_focus and it it's visible (check if it has
						// width).
						if ( link.getAttribute( '_cke_focus' ) && link.$.offsetWidth )
						{
							this._.focusIndex = index;
							link.focus();
							break;
						}
					}
					return false;

				// Move backward.
				case 'prev' :
					index = this._.focusIndex;
					links = this.element.getElementsByTag( 'a' );

					while ( index > 0 && ( link = links.getItem( --index ) ) )
					{
						// Move the focus only if the element is marked with
						// the _cke_focus and it it's visible (check if it has
						// width).
						if ( link.getAttribute( '_cke_focus' ) && link.$.offsetWidth )
						{
							this._.focusIndex = index;
							link.focus();
							break;
						}
					}
					return false;

				case 'click' :
					index = this._.focusIndex;
					link = index >= 0 && this.element.getElementsByTag( 'a' ).getItem( index );

					if ( link )
						link.$.click ? link.$.click() : link.$.onclick();

					return false;
			}

			return true;
		}
	}
});
