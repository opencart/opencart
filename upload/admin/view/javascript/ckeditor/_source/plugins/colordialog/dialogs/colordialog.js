/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.dialog.add( 'colordialog', function( editor )
	{
		// Define some shorthands.
		var $el = CKEDITOR.dom.element,
			$doc = CKEDITOR.document,
			$tools = CKEDITOR.tools,
			lang = editor.lang.colordialog;

		// Reference the dialog.
		var dialog;

		function spacer()
		{
			return {
				type : 'html',
				html : '&nbsp;'
			};
		}

		var table = new $el( 'table' );
		createColorTable();

		var cellMouseover = function( event )
		{
			var color = new $el( event.data.getTarget() ).getAttribute( 'title' );
			$doc.getById( 'hicolor' ).setStyle( 'background-color', color );
			$doc.getById( 'hicolortext' ).setHtml( color );
		};

		var cellClick = function( event )
		{
			var color = new $el( event.data.getTarget() ).getAttribute( 'title' );
			dialog.getContentElement( 'picker', 'selectedColor' ).setValue( color );
		};

		function createColorTable()
		{
			// Create the base colors array.
			var aColors = ['00','33','66','99','cc','ff'];

			// This function combines two ranges of three values from the color array into a row.
			function appendColorRow( rangeA, rangeB )
			{
				for ( var i = rangeA ; i < rangeA + 3 ; i++ )
				{
					var row = table.$.insertRow(-1);

					for ( var j = rangeB ; j < rangeB + 3 ; j++ )
					{
						for ( var n = 0 ; n < 6 ; n++ )
						{
							appendColorCell( row, '#' + aColors[j] + aColors[n] + aColors[i] );
						}
					}
				}
			}

			// This function create a single color cell in the color table.
			function appendColorCell( targetRow, color )
			{
				var cell = new $el( targetRow.insertCell( -1 ) );
				cell.setAttribute( 'class', 'ColorCell' );
				cell.setStyle( 'background-color', color );

				cell.setStyle( 'width', '15px' );
				cell.setStyle( 'height', '15px' );

				// Pass unparsed color value in some markup-degradable form.
				cell.setAttribute( 'title', color );
			}

			appendColorRow( 0, 0 );
			appendColorRow( 3, 0 );
			appendColorRow( 0, 3 );
			appendColorRow( 3, 3 );

			// Create the last row.
			var oRow = table.$.insertRow(-1) ;

			// Create the gray scale colors cells.
			for ( var n = 0 ; n < 6 ; n++ )
			{
				appendColorCell( oRow, '#' + aColors[n] + aColors[n] + aColors[n] ) ;
			}

			// Fill the row with black cells.
			for ( var i = 0 ; i < 12 ; i++ )
			{
				appendColorCell( oRow, '#000000' ) ;
			}
		}

		function clear()
		{
			$doc.getById( 'selhicolor' ).removeStyle( 'background-color' );
			dialog.getContentElement( 'picker', 'selectedColor' ).setValue( '' );
		}

		var clearActual = $tools.addFunction( function()
		{
			$doc.getById( 'hicolor' ).removeStyle( 'background-color' );
			$doc.getById( 'hicolortext' ).setHtml( '&nbsp;' );
		} );

		return {
			title : lang.title,
			minWidth : 360,
			minHeight : 220,
			onLoad : function()
			{
				// Update reference.
				dialog = this;
			},
			contents : [
				{
					id : 'picker',
					label : lang.title,
					accessKey : 'I',
					elements :
					[
						{
							type : 'hbox',
							padding : 0,
							widths : [ '70%', '10%', '30%' ],
							children :
							[
								{
									type : 'html',
									html : '<table onmouseout="CKEDITOR.tools.callFunction( ' + clearActual + ' );">' + table.getHtml() + '</table>',
									onLoad : function()
									{
										var table = CKEDITOR.document.getById( this.domId );
										table.on( 'mouseover', cellMouseover );
										table.on( 'click', cellClick );
									}
								},
								spacer(),
								{
									type : 'vbox',
									padding : 0,
									widths : [ '70%', '5%', '25%' ],
									children :
									[
										{
											type : 'html',
											html : '<span>' + lang.highlight +'</span>\
												<div id="hicolor" style="border: 1px solid; height: 74px; width: 74px;"></div>\
												<div id="hicolortext">&nbsp;</div>\
												<span>' + lang.selected +'</span>\
												<div id="selhicolor" style="border: 1px solid; height: 20px; width: 74px;"></div>'
										},
										{
											type : 'text',
											id : 'selectedColor',
											style : 'width: 74px',
											onChange : function()
											{
												// Try to update color preview with new value. If fails, then set it no none.
												try
												{
													$doc.getById( 'selhicolor' ).setStyle( 'background-color', this.getValue() );
												}
												catch ( e )
												{
													clear();
												}
											}
										},
										spacer(),
										{
											type : 'button',
											id : 'clear',
											style : 'margin-top: 5px',
											label : lang.clear,
											onClick : clear
										}
									]
								}
							]
						}
					]
				}
			]
		};
	}
	);
