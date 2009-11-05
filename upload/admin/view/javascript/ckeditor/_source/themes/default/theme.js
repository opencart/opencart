/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.themes.add( 'default', (function()
{
	return {
		build : function( editor, themePath )
		{
			var name = editor.name,
				element = editor.element,
				elementMode = editor.elementMode;

			if ( !element || elementMode == CKEDITOR.ELEMENT_MODE_NONE )
				return;

			if ( elementMode == CKEDITOR.ELEMENT_MODE_REPLACE )
				element.hide();

			// Get the HTML for the predefined spaces.
			var topHtml			= editor.fire( 'themeSpace', { space : 'top', html : '' } ).html;
			var contentsHtml	= editor.fire( 'themeSpace', { space : 'contents', html : '' } ).html;
			var bottomHtml		= editor.fireOnce( 'themeSpace', { space : 'bottom', html : '' } ).html;

			var height	= contentsHtml && editor.config.height;

			var tabIndex = editor.config.tabIndex || editor.element.getAttribute( 'tabindex' ) || 0;

			// The editor height is considered only if the contents space got filled.
			if ( !contentsHtml )
				height = 'auto';
			else if ( !isNaN( height ) )
				height += 'px';

			var style = '';
			var width	= editor.config.width;

			if ( width )
			{
				if ( !isNaN( width ) )
					width += 'px';

				style += "width: " + width + ";";
			}

			var container = CKEDITOR.dom.element.createFromHtml( [
				'<span' +
					' id="cke_', name, '"' +
					' onmousedown="return false;"' +
					' class="', editor.skinClass, '"' +
					' dir="', editor.lang.dir, '"' +
					' title="', ( CKEDITOR.env.gecko ? ' ' : '' ), '"' +
					' lang="', editor.langCode, '"' +
					' tabindex="' + tabIndex + '"' +
					( style ? ' style="' + style + '"' : '' ) +
					'>' +
					'<span class="' , CKEDITOR.env.cssClass, '">' +
						'<span class="cke_wrapper cke_', editor.lang.dir, '">' +
							'<table class="cke_editor" border="0" cellspacing="0" cellpadding="0"><tbody>' +
								'<tr', topHtml		? '' : ' style="display:none"', '><td id="cke_top_'		, name, '" class="cke_top">'	, topHtml		, '</td></tr>' +
								'<tr', contentsHtml	? '' : ' style="display:none"', '><td id="cke_contents_', name, '" class="cke_contents" style="height:', height, '">', contentsHtml, '</td></tr>' +
								'<tr', bottomHtml	? '' : ' style="display:none"', '><td id="cke_bottom_'	, name, '" class="cke_bottom">'	, bottomHtml	, '</td></tr>' +
							'</tbody></table>' +
							//Hide the container when loading skins, later restored by skin css.
							'<style>.', editor.skinClass, '{visibility:hidden;}</style>' +
						'</span>' +
					'</span>' +
				'</span>' ].join( '' ) );

			container.getChild( [0, 0, 0, 0, 0] ).unselectable();
			container.getChild( [0, 0, 0, 0, 2] ).unselectable();

			if ( elementMode == CKEDITOR.ELEMENT_MODE_REPLACE )
				container.insertAfter( element );
			else
				element.append( container );

			/**
			 * The DOM element that holds the main editor interface.
			 * @name CKEDITOR.editor.prototype.container
			 * @type CKEDITOR.dom.element
			 * @example
			 * var editor = CKEDITOR.instances.editor1;
			 * alert( <b>editor.container</b>.getName() );  "span"
			 */
			editor.container = container;

			// Disable browser context menu for editor's chrome.
			container.disableContextMenu();

			editor.fireOnce( 'themeLoaded' );
			editor.fireOnce( 'uiReady' );
		},

		buildDialog : function( editor )
		{
			var baseIdNumber = CKEDITOR.tools.getNextNumber();

			var element = CKEDITOR.dom.element.createFromHtml( [
					'<div id="cke_' + editor.name.replace('.', '\\.') + '_dialog" class="cke_skin_', editor.skinName,
						'" dir="', editor.lang.dir, '"' +
						' lang="', editor.langCode, '"' +
						'>' +

						'<div class="cke_dialog', ' ' + CKEDITOR.env.cssClass,
							' cke_', editor.lang.dir, '" style="position:absolute">' +
							'<div class="%body">' +
								'<div id="%title#" class="%title"></div>' +
								'<div id="%close_button#" class="%close_button">' +
									'<span>X</span>' +
								'</div>' +
								'<div id="%tabs#" class="%tabs"></div>' +
								'<div id="%contents#" class="%contents"></div>' +
								'<div id="%footer#" class="%footer"></div>' +
							'</div>' +
							'<div id="%tl#" class="%tl"></div>' +
							'<div id="%tc#" class="%tc"></div>' +
							'<div id="%tr#" class="%tr"></div>' +
							'<div id="%ml#" class="%ml"></div>' +
							'<div id="%mr#" class="%mr"></div>' +
							'<div id="%bl#" class="%bl"></div>' +
							'<div id="%bc#" class="%bc"></div>' +
							'<div id="%br#" class="%br"></div>' +
						'</div>',

						//Hide the container when loading skins, later restored by skin css.
						( CKEDITOR.env.ie ? '' : '<style>.cke_dialog{visibility:hidden;}</style>' ),

					'</div>'
				].join( '' )
					.replace( /#/g, '_' + baseIdNumber )
					.replace( /%/g, 'cke_dialog_' ) );

			var body = element.getChild( [ 0, 0 ] );

			// Make the Title and Close Button unselectable.
			body.getChild( 0 ).unselectable();
			body.getChild( 1 ).unselectable();


			return {
				element : element,
				parts :
				{
					dialog		: element.getChild( 0 ),
					title		: body.getChild( 0 ),
					close		: body.getChild( 1 ),
					tabs		: body.getChild( 2 ),
					contents	: body.getChild( 3 ),
					footer		: body.getChild( 4 )
				}
			};
		},

		destroy : function( editor )
		{
			var container = editor.container,
				panels = editor.panels;

			/*
			 * IE BUG: Removing the editor DOM elements while the selection is inside
			 * the editing area would break IE7/8's selection system. So we need to put
			 * the selection back to the parent document without scrolling the window.
			 * (#3812)
			 */
			if ( CKEDITOR.env.ie )
			{
				container.setStyle( 'display', 'none' );

				var $range = document.body.createTextRange();
				$range.moveToElementText( container.$ );
				try
				{
					// Putting the selection to a display:none element - this will certainly
					// fail. But! We've just put the selection document back to the parent
					// document without scrolling the window!
					$range.select();
				}
				catch ( e ) {}
			}

			if ( container )
				container.remove();

			for( var i = 0 ; panels && i < panels.length ; i++ )
					panels[ i ].remove();

			if ( editor.elementMode == CKEDITOR.ELEMENT_MODE_REPLACE )
			{
				editor.element.show();
				delete editor.element;
			}
		}
	};
})() );

CKEDITOR.editor.prototype.getThemeSpace = function( spaceName )
{
	var spacePrefix = 'cke_' + spaceName;
	var space = this._[ spacePrefix ] ||
		( this._[ spacePrefix ] = CKEDITOR.document.getById( spacePrefix + '_' + this.name ) );
	return space;
};

CKEDITOR.editor.prototype.resize = function( width, height, isContentHeight, resizeInner )
{
	var numberRegex = /^\d+$/;
	if ( numberRegex.test( width ) )
		width += 'px';

	var contents = CKEDITOR.document.getById( 'cke_contents_' + this.name );
	var outer = resizeInner ? contents.getAscendant( 'table' ).getParent()
		: contents.getAscendant( 'table' ).getParent().getParent().getParent();

	// Resize the width first.
	// WEBKIT BUG: Webkit requires that we put the editor off from display when we
	// resize it. If we don't, the browser crashes!
	CKEDITOR.env.webkit && outer.setStyle( 'display', 'none' );
	outer.setStyle( 'width', width );
	if ( CKEDITOR.env.webkit )
	{
		outer.$.offsetWidth;
		outer.setStyle( 'display', '' );
	}

	// Get the height delta between the outer table and the content area.
	// If we're setting the content area's height, then we don't need the delta.
	var delta = isContentHeight ? 0 : ( outer.$.offsetHeight || 0 ) - ( contents.$.clientHeight || 0 );
	contents.setStyle( 'height', Math.max( height - delta, 0 ) + 'px' );

	// Emit a resize event.
	this.fire( 'resize' );
};

CKEDITOR.editor.prototype.getResizable = function()
{
	return this.container.getChild( [ 0, 0 ] );
};
