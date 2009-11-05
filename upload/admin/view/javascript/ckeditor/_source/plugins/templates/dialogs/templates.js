/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	var doc = CKEDITOR.document;

	var listId = 'cke' + CKEDITOR.tools.getNextNumber();

	// Constructs the HTML view of the specified templates data.
	function renderTemplatesList( editor, templatesDefinitions )
	{
		var listDiv = doc.getById( listId );

		// clear loading wait text.
		listDiv.setHtml( '' );

		for ( var i = 0 ; i < templatesDefinitions.length ; i++ )
		{
			var definition = CKEDITOR.getTemplates( templatesDefinitions[ i ] ),
				imagesPath = definition.imagesPath,
				templates = definition.templates;

			for ( var j = 0 ; j < templates.length ; j++ )
			{
				var template = templates[ j ];
				listDiv.append( createTemplateItem( editor, template, imagesPath ) );
			}
		}
	}

	function createTemplateItem( editor, template, imagesPath )
	{
		var div = doc.createElement( 'div' );
		div.setAttribute( 'class', 'cke_tpl_item' );

		// Build the inner HTML of our new item DIV.
		var html = '<table style="width:350px;" class="cke_tpl_preview"><tr>';

		if( template.image && imagesPath )
			html += '<td class="cke_tpl_preview_img"><img src="' + CKEDITOR.getUrl( imagesPath + template.image ) + '"></td>';

		html += '<td style="white-space:normal;"><span class="cke_tpl_title">' + template.title + '</span><br/>';

		if( template.description )
			html += '<span>' + template.description + '</span>';

		html += '</td></tr></table>';

		div.setHtml( html );

		div.on( 'mouseover', function()
			{
				div.addClass( 'cke_tpl_hover' );
			});

		div.on( 'mouseout', function()
			{
				div.removeClass( 'cke_tpl_hover' );
			});

		div.on( 'click', function()
			{
				insertTemplate( editor, template.html );
			});

		return div;
	}

	/**
	 * Insert the specified template content
	 * to document.
	 * @param {Number} index
	 */
	function insertTemplate( editor, html )
	{
		var dialog = CKEDITOR.dialog.getCurrent(),
			isInsert = dialog.getValueOf( 'selectTpl', 'chkInsertOpt' );

		if( isInsert )
		{
			editor.setData( html );
		}
		else
		{
			editor.insertHtml( html );
		}

		dialog.hide();
	}

	CKEDITOR.dialog.add( 'templates', function( editor )
		{
			// Load skin at first.
			CKEDITOR.skins.load( editor, 'templates' );

			/**
			 * Load templates once.
			 */
			var isLoaded = false;

			return {
				title :editor.lang.templates.title,

				minWidth : CKEDITOR.env.ie ? 440 : 400,
				minHeight : 340,

				contents :
				[
					{
						id :'selectTpl',
						label : editor.lang.templates.title,
						elements :
						[
							{
								type : 'vbox',
								padding : 5,
								children :
								[
									{
										type : 'html',
										html :
											'<span>'  +
												editor.lang.templates.selectPromptMsg +
											'</span>'
									},
									{
										type : 'html',
										html :
											'<div id="' + listId + '" class="cke_tpl_list">' +
												'<div class="cke_tpl_loading"><span></span></div>' +
											'</div>'
									},
									{
										id : 'chkInsertOpt',
										type : 'checkbox',
										label : editor.lang.templates.insertOption,
										'default' : editor.config.templates_replaceContent
									}
								]
							}
						]
					}
				],

				buttons : [ CKEDITOR.dialog.cancelButton ],

				onShow : function()
				{
					CKEDITOR.loadTemplates( editor.config.templates_files, function()
						{
							var templates = editor.config.templates.split( ',' );

							if ( templates.length )
								renderTemplatesList( editor, templates );
							else
							{
								var listCtEl = doc.getById( listId );
								listCtEl.setHtml(
									'<div class="cke_tpl_empty">' +
										'<span>' + editor.lang.templates.emptyListMsg + '</span>' +
									'</div>' );
							}
						});
				}
			};
		});
})();
