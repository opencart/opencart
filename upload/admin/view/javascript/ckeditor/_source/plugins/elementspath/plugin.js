/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview The "elementspath" plugin. It shows all elements in the DOM
 *		parent tree relative to the current selection in the editing area.
 */

(function()
{
	var commands =
	{
		toolbarFocus :
		{
			exec : function( editor )
			{
				var idBase = editor._.elementsPath.idBase;
				var element = CKEDITOR.document.getById( idBase + '0' );

				if ( element )
					element.focus();
			}
		}
	};

	var emptyHtml = '<span class="cke_empty">&nbsp;</span>';

	CKEDITOR.plugins.add( 'elementspath',
	{
		requires : [ 'selection' ],

		init : function( editor )
		{
			var spaceId = 'cke_path_' + editor.name;
			var spaceElement;
			var getSpaceElement = function()
			{
				if ( !spaceElement )
					spaceElement = CKEDITOR.document.getById( spaceId );
				return spaceElement;
			};

			var idBase = 'cke_elementspath_' + CKEDITOR.tools.getNextNumber() + '_';

			editor._.elementsPath = { idBase : idBase };

			editor.on( 'themeSpace', function( event )
				{
					if ( event.data.space == 'bottom' )
						event.data.html += '<div id="' + spaceId + '" class="cke_path">' + emptyHtml + '</div>';
				});

			editor.on( 'selectionChange', function( ev )
				{
					var env = CKEDITOR.env;

					var selection = ev.data.selection;

					var element = selection.getStartElement(),
						html = [],
						elementsList = this._.elementsPath.list = [];

					while ( element )
					{
						var index = elementsList.push( element ) - 1;
						var name;
						if ( element.getAttribute( '_cke_real_element_type' ) )
							name = element.getAttribute( '_cke_real_element_type' );
						else
							name = element.getName();

						// Use this variable to add conditional stuff to the
						// HTML (because we are doing it in reverse order... unshift).
						var extra = '';

						// Some browsers don't cancel key events in the keydown but in the
						// keypress.
						// TODO: Check if really needed for Gecko+Mac.
						if ( env.opera || ( env.gecko && env.mac ) )
							extra += ' onkeypress="return false;"';

						// With Firefox, we need to force the button to redraw, otherwise it
						// will remain in the focus state.
						if ( env.gecko )
							extra += ' onblur="this.style.cssText = this.style.cssText;"';

						html.unshift(
							'<a' +
								' id="', idBase, index, '"' +
								' href="javascript:void(\'', name, '\')"' +
								' tabindex="-1"' +
								' title="', editor.lang.elementsPath.eleTitle.replace( /%1/, name ), '"' +
								( ( CKEDITOR.env.gecko && CKEDITOR.env.version < 10900 ) ?
								' onfocus="event.preventBubble();"' : '' ) +
								' hidefocus="true" ' +
								' onkeydown="return CKEDITOR._.elementsPath.keydown(\'', this.name, '\',', index, ', event);"' +
								extra ,
								' onclick="return CKEDITOR._.elementsPath.click(\'', this.name, '\',', index, ');">',
									name,
							'</a>' );

						if ( name == 'body' )
							break;

						element = element.getParent();
					}

					getSpaceElement().setHtml( html.join('') + emptyHtml );
				});

			editor.on( 'contentDomUnload', function()
				{
					getSpaceElement().setHtml( emptyHtml );
				});

			editor.addCommand( 'elementsPathFocus', commands.toolbarFocus );
		}
	});
})();

/**
 * Handles the click on an element in the element path.
 * @private
 */
CKEDITOR._.elementsPath =
{
	click : function( instanceName, elementIndex )
	{
		var editor = CKEDITOR.instances[ instanceName ];
		editor.focus();

		var element = editor._.elementsPath.list[ elementIndex ];
		editor.getSelection().selectElement( element );

		return false;
	},

	keydown : function( instanceName, elementIndex, ev )
	{
		var instance = CKEDITOR.ui.button._.instances[ elementIndex ];
		var editor = CKEDITOR.instances[ instanceName ];
		var idBase = editor._.elementsPath.idBase;

		var element;

		ev = new CKEDITOR.dom.event( ev );

		switch ( ev.getKeystroke() )
		{
			case 37 :					// LEFT-ARROW
			case 9 :					// TAB
				element = CKEDITOR.document.getById( idBase + ( elementIndex + 1 ) );
				if ( !element )
					element = CKEDITOR.document.getById( idBase + '0' );
				element.focus();
				return false;

			case 39 :					// RIGHT-ARROW
			case CKEDITOR.SHIFT + 9 :	// SHIFT + TAB
				element = CKEDITOR.document.getById( idBase + ( elementIndex - 1 ) );
				if ( !element )
					element = CKEDITOR.document.getById( idBase + ( editor._.elementsPath.list.length - 1 ) );
				element.focus();
				return false;

			case 27 :					// ESC
				editor.focus();
				return false;

			case 13 :					// ENTER	// Opera
			case 32 :					// SPACE
				this.click( instanceName, elementIndex );
				return false;

			//default :
			//	alert( ev.getKeystroke() );
		}
		return true;
	}
};
