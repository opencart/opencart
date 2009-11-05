/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Plugin for making iframe based dialogs.
 */

CKEDITOR.plugins.add( 'iframedialog',
{
	requires : [ 'dialog' ],
	onLoad : function()
	{
		CKEDITOR.dialog.addIframe = function( name, title, src, width, height, onContentLoad )
		{
			var element =
			{
				type : 'iframe',
				src : src,
				width : '100%',
				height : '100%'
			};

			if ( typeof( onContentLoad ) == 'function' )
				element.onContentLoad = onContentLoad;

			var definition =
			{
				title : title,
				minWidth : width,
				minHeight : height,
				contents :
				[
					{
						id : 'iframe',
						label : title,
						expand : true,
						elements : [ element ]
					}
				]
			};

			return this.add( name, function(){ return definition; } );
		};

		(function()
		{
			/**
			 * An iframe element.
			 * @extends CKEDITOR.ui.dialog.uiElement
			 * @example
			 * @constructor
			 * @param {CKEDITOR.dialog} dialog
			 * Parent dialog object.
			 * @param {CKEDITOR.dialog.uiElementDefinition} elementDefinition
			 * The element definition. Accepted fields:
			 * <ul>
			 * 	<li><strong>src</strong> (Required) The src field of the iframe. </li>
			 * 	<li><strong>width</strong> (Required) The iframe's width.</li>
			 * 	<li><strong>height</strong> (Required) The iframe's height.</li>
			 * 	<li><strong>onContentLoad</strong> (Optional) A function to be executed
			 * 	after the iframe's contents has finished loading.</li>
			 * </ul>
			 * @param {Array} htmlList
			 * List of HTML code to output to.
			 */
			var iframeElement = function( dialog, elementDefinition, htmlList )
			{
				if ( arguments.length < 3 )
					return;

				var _ = ( this._ || ( this._ = {} ) ),
					contentLoad = elementDefinition.onContentLoad && CKEDITOR.tools.bind( elementDefinition.onContentLoad, this ),
					cssWidth = CKEDITOR.tools.cssLength( elementDefinition.width ),
					cssHeight = CKEDITOR.tools.cssLength( elementDefinition.height );
				_.frameId = CKEDITOR.tools.getNextNumber() + '_iframe';

				// IE BUG: Parent container does not resize to contain the iframe automatically.
				dialog.on( 'load', function()
					{
						var iframe = CKEDITOR.document.getById( _.frameId ),
							parentContainer = iframe.getParent();

						parentContainer.setStyles(
							{
								width : cssWidth,
								height : cssHeight
							} );
					} );

				var attributes =
				{
					src : '%2',
					id : _.frameId,
					frameborder : 0,
					allowtransparency : true
				};
				var myHtml = [];

				if ( typeof( elementDefinition.onContentLoad ) == 'function' )
					attributes.onload = 'CKEDITOR.tools.callFunction(%1);';

				CKEDITOR.ui.dialog.uiElement.call( this, dialog, elementDefinition, myHtml, 'iframe',
						{
							width : cssWidth,
							height : cssHeight
						}, attributes, '' );

				// Put a placeholder for the first time.
				htmlList.push( '<div style="width:' + cssWidth + ';height:' + cssHeight + ';" id="' + this.domId + '"></div>' );

				// Iframe elements should be refreshed whenever it is shown.
				myHtml = myHtml.join( '' );
				dialog.on( 'show', function()
					{
						var iframe = CKEDITOR.document.getById( _.frameId ),
							parentContainer = iframe.getParent(),
							callIndex = CKEDITOR.tools.addFunction( contentLoad ),
							html = myHtml.replace( '%1', callIndex ).replace( '%2', CKEDITOR.tools.htmlEncode( elementDefinition.src ) );
						parentContainer.setHtml( html );
					} );
			};

			iframeElement.prototype = new CKEDITOR.ui.dialog.uiElement;

			CKEDITOR.dialog.addUIElement( 'iframe',
				{
					build : function( dialog, elementDefinition, output )
					{
						return new iframeElement( dialog, elementDefinition, output );
					}
				} );
		})();
	}
} );
