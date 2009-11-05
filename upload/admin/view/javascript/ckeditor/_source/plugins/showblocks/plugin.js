/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview The "showblocks" plugin. Enable it will make all block level
 *               elements being decorated with a border and the element name
 *               displayed on the left-right corner.
 */

(function()
{
	var cssTemplate = '.%2 p,'+
		'.%2 div,'+
		'.%2 pre,'+
		'.%2 address,'+
		'.%2 blockquote,'+
		'.%2 h1,'+
		'.%2 h2,'+
		'.%2 h3,'+
		'.%2 h4,'+
		'.%2 h5,'+
		'.%2 h6'+
		'{'+
			'background-repeat: no-repeat;'+
			'border: 1px dotted gray;'+
			'padding-top: 8px;'+
			'padding-left: 8px;'+
		'}'+

		'.%2 p'+
		'{'+
			'%1p.png);'+
		'}'+

		'.%2 div'+
		'{'+
			'%1div.png);'+
		'}'+

		'.%2 pre'+
		'{'+
			'%1pre.png);'+
		'}'+

		'.%2 address'+
		'{'+
			'%1address.png);'+
		'}'+

		'.%2 blockquote'+
		'{'+
			'%1blockquote.png);'+
		'}'+

		'.%2 h1'+
		'{'+
			'%1h1.png);'+
		'}'+

		'.%2 h2'+
		'{'+
			'%1h2.png);'+
		'}'+

		'.%2 h3'+
		'{'+
			'%1h3.png);'+
		'}'+

		'.%2 h4'+
		'{'+
			'%1h4.png);'+
		'}'+

		'.%2 h5'+
		'{'+
			'%1h5.png);'+
		'}'+

		'.%2 h6'+
		'{'+
			'%1h6.png);'+
		'}';

	var cssTemplateRegex = /%1/g, cssClassRegex = /%2/g;

	var commandDefinition =
	{
		preserveState : true,
		editorFocus : false,

		exec : function ( editor )
		{
			this.toggleState();
			this.refresh( editor );
		},

		refresh : function( editor )
		{
			var funcName = ( this.state == CKEDITOR.TRISTATE_ON ) ? 'addClass' : 'removeClass';
			editor.document.getBody()[ funcName ]( 'cke_show_blocks' );
		}
	};

	CKEDITOR.plugins.add( 'showblocks',
	{
		requires : [ 'wysiwygarea' ],

		init : function( editor )
		{
			var command = editor.addCommand( 'showblocks', commandDefinition );
			command.canUndo = false;

			if ( editor.config.startupOutlineBlocks )
				command.setState( CKEDITOR.TRISTATE_ON );

			editor.addCss( cssTemplate
				.replace( cssTemplateRegex, 'background-image: url(' + CKEDITOR.getUrl( this.path ) + 'images/block_' )
				.replace( cssClassRegex, 'cke_show_blocks ' ) );

			editor.ui.addButton( 'ShowBlocks',
				{
					label : editor.lang.showBlocks,
					command : 'showblocks'
				});

			// Refresh the command on setData.
			editor.on( 'mode', function()
				{
					if ( command.state != CKEDITOR.TRISTATE_DISABLED )
						command.refresh( editor );
				});

			// Refresh the command on setData.
			editor.on( 'contentDom', function()
				{
					if ( command.state != CKEDITOR.TRISTATE_DISABLED )
						command.refresh( editor );
				});
		}
	});
} )();

/**
 * Whether to automaticaly enable the "show block" command when the editor
 * loads.
 * @type Boolean
 * @default false
 * @example
 * config.startupOutlineBlocks = true;
 */
CKEDITOR.config.startupOutlineBlocks = false;
