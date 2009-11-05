/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'button',
{
	beforeInit : function( editor )
	{
		editor.ui.addHandler( CKEDITOR.UI_BUTTON, CKEDITOR.ui.button.handler );
	}
});

/**
 * Button UI element.
 * @constant
 * @example
 */
CKEDITOR.UI_BUTTON = 1;

/**
 * Represents a button UI element. This class should not be called directly. To
 * create new buttons use {@link CKEDITOR.ui.prototype.addButton} instead.
 * @constructor
 * @param {Object} definition The button definition.
 * @example
 */
CKEDITOR.ui.button = function( definition )
{
	// Copy all definition properties to this object.
	CKEDITOR.tools.extend( this, definition,
		// Set defaults.
		{
			title		: definition.label,
			className	: definition.className || ( definition.command && 'cke_button_' + definition.command ) || '',
			click		: definition.click || function( editor )
				{
					editor.execCommand( definition.command );
				}
		});

	this._ = {};
};

/**
 * Transforms a button definition in a {@link CKEDITOR.ui.button} instance.
 * @type Object
 * @example
 */
CKEDITOR.ui.button.handler =
{
	create : function( definition )
	{
		return new CKEDITOR.ui.button( definition );
	}
};

CKEDITOR.ui.button.prototype =
{
	canGroup : true,

	/**
	 * Renders the button.
	 * @param {CKEDITOR.editor} editor The editor instance which this button is
	 *		to be used by.
	 * @param {Array} output The output array to which append the HTML relative
	 *		to this button.
	 * @example
	 */
	render : function( editor, output )
	{
		var env = CKEDITOR.env;

		var id = this._.id = 'cke_' + CKEDITOR.tools.getNextNumber();
		this._.editor = editor;

		var instance =
		{
			id : id,
			button : this,
			editor : editor,
			focus : function()
			{
				var element = CKEDITOR.document.getById( id );
				element.focus();
			},
			execute : function()
			{
				this.button.click( editor );
			}
		};

		var clickFn = CKEDITOR.tools.addFunction( instance.execute, instance );

		var index = CKEDITOR.ui.button._.instances.push( instance ) - 1;

		var classes = '';

		// Get the command name.
		var command = this.command;

		if ( this.modes )
		{
			editor.on( 'mode', function()
				{
					this.setState( this.modes[ editor.mode ] ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED );
				}, this);
		}
		else if ( command )
		{
			// Get the command instance.
			command = editor.getCommand( command );

			if ( command )
			{
				command.on( 'state', function()
					{
						this.setState( command.state );
					}, this);

				classes += 'cke_' + (
					command.state == CKEDITOR.TRISTATE_ON ? 'on' :
					command.state == CKEDITOR.TRISTATE_DISABLED ? 'disabled' :
					'off' );
			}
		}

		if ( !command )
			classes	+= 'cke_off';

		if ( this.className )
			classes += ' ' + this.className;

		output.push(
			'<span class="cke_button">',
			'<a id="', id, '"' +
				' class="', classes, '" href="javascript:void(\'', ( this.title || '' ).replace( "'", '' ), '\')"' +
				' title="', this.title, '"' +
				' tabindex="-1"' +
				' hidefocus="true"' );

		// Some browsers don't cancel key events in the keydown but in the
		// keypress.
		// TODO: Check if really needed for Gecko+Mac.
		if ( env.opera || ( env.gecko && env.mac ) )
		{
			output.push(
				' onkeypress="return false;"' );
		}

		// With Firefox, we need to force the button to redraw, otherwise it
		// will remain in the focus state.
		if ( env.gecko )
		{
			output.push(
				' onblur="this.style.cssText = this.style.cssText;"' );
		}

		output.push(
				' onkeydown="return CKEDITOR.ui.button._.keydown(', index, ', event);"' +
				' onfocus="return CKEDITOR.ui.button._.focus(', index, ', event);"' +
				' onclick="CKEDITOR.tools.callFunction(', clickFn, ', this); return false;">' +
					'<span class="cke_icon"' );

		if ( this.icon )
		{
			var offset = ( this.iconOffset || 0 ) * -16;
			output.push( ' style="background-image:url(', CKEDITOR.getUrl( this.icon ), ');background-position:0 ' + offset + 'px;"' );
		}

		output.push(
					'></span>' +
					'<span class="cke_label">', this.label, '</span>' );

		if ( this.hasArrow )
		{
			output.push(
					'<span class="cke_buttonarrow"></span>' );
		}

		output.push(
			'</a>',
			'</span>' );

		if ( this.onRender )
			this.onRender();

		return instance;
	},

	setState : function( state )
	{
		if ( this._.state == state )
			return;

		var element = CKEDITOR.document.getById( this._.id );

		if ( element )
		{
			element.setState( state );

			var htmlTitle = this.title,
				unavailable = this._.editor.lang.common.unavailable,
				labelElement = element.getChild( 1 );

			if ( state == CKEDITOR.TRISTATE_DISABLED )
				htmlTitle = unavailable.replace( '%1', this.title );

			labelElement.setHtml( htmlTitle );
		}

		this._.state = state;
	}
};

/**
 * Handles a button click.
 * @private
 */
CKEDITOR.ui.button._ =
{
	instances : [],

	keydown : function( index, ev )
	{
		var instance = CKEDITOR.ui.button._.instances[ index ];

		if ( instance.onkey )
		{
			ev = new CKEDITOR.dom.event( ev );
			return ( instance.onkey( instance, ev.getKeystroke() ) !== false );
		}
	},

	focus : function( index, ev )
	{
		var instance = CKEDITOR.ui.button._.instances[ index ],
			retVal;

		if ( instance.onfocus )
			retVal = ( instance.onfocus( instance, new CKEDITOR.dom.event( ev ) ) !== false );

		// FF2: prevent focus event been bubbled up to editor container, which caused unexpected editor focus.
		if ( CKEDITOR.env.gecko && CKEDITOR.env.version < 10900 )
			ev.preventBubble();
		return retVal;
	}
};

/**
 * Adds a button definition to the UI elements list.
 * @param {String} The button name.
 * @param {Object} The button definition.
 * @example
 * editorInstance.ui.addButton( 'MyBold',
 *     {
 *         label : 'My Bold',
 *         command : 'bold'
 *     });
 */
CKEDITOR.ui.prototype.addButton = function( name, definition )
{
	this.add( name, CKEDITOR.UI_BUTTON, definition );
};
