/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.dialog.add( 'cellProperties', function( editor )
	{
		var langTable = editor.lang.table;
		var langCell = langTable.cell;
		var langCommon = editor.lang.common;
		var validate = CKEDITOR.dialog.validate;
		var widthPattern = /^(\d+(?:\.\d+)?)(px|%)$/,
			heightPattern = /^(\d+(?:\.\d+)?)px$/;
		var bind = CKEDITOR.tools.bind;

		function spacer()
		{
			return { type : 'html', html : '&nbsp;' };
		}

		/**
		 *
		 * @param dialogName
		 * @param callback [ childDialog ]
		 */
		function getDialogValue( dialogName, callback )
		{
			var onOk = function()
			{
				releaseHandlers( this );
				callback( this );
			};
			var onCancel = function()
			{
				releaseHandlers( this );
			};
			var bindToDialog = function( dialog )
			{
				dialog.on( 'ok', onOk );
				dialog.on( 'cancel', onCancel );
			};
			var releaseHandlers = function( dialog )
			{
				dialog.removeListener( 'ok', onOk );
				dialog.removeListener( 'cancel', onCancel );
			};
			editor.execCommand( dialogName );
			if ( editor._.storedDialogs.colordialog )
				bindToDialog( editor._.storedDialogs.colordialog );
			else
			{
				CKEDITOR.on( 'dialogDefinition', function( e )
				{
					if ( e.data.name != dialogName )
						return;

					var definition = e.data.definition;

					e.removeListener();
					definition.onLoad = CKEDITOR.tools.override( definition.onLoad, function( orginal )
					{
						return function()
						{
							bindToDialog( this );
							definition.onLoad = orginal;
							if ( typeof orginal == 'function' )
								orginal.call( this );
						};
					} );
				});
			}
		}

		return {
			title : langCell.title,
			minWidth : CKEDITOR.env.ie && CKEDITOR.env.quirks ? 550 : 480,
			minHeight : CKEDITOR.env.ie ? ( CKEDITOR.env.quirks ? 180 : 150 ) : 140,
			contents : [
				{
					id : 'info',
					label : langCell.title,
					accessKey : 'I',
					elements :
					[
						{
							type : 'hbox',
							widths : [ '40%', '5%', '40%' ],
							children :
							[
								{
									type : 'vbox',
									padding : 0,
									children :
									[
										{
											type : 'hbox',
											widths : [ '70%', '30%' ],
											children :
											[
												{
													type : 'text',
													id : 'width',
													label : langTable.width,
													widths : [ '71%', '29%' ],
													labelLayout : 'horizontal',
													validate : validate[ 'number' ]( langCell.invalidWidth ),
													setup : function( selectedCell )
													{
														var widthMatch = widthPattern.exec( selectedCell.$.style.width );
														if ( widthMatch )
															this.setValue( widthMatch[1] );
													},
													commit : function( selectedCell )
													{
														var unit = this.getDialog().getValueOf( 'info', 'widthType' );
														if ( this.getValue() !== '' )
															selectedCell.$.style.width = this.getValue() + unit;
														else
															selectedCell.$.style.width = '';
													},
													'default' : ''
												},
												{
													type : 'select',
													id : 'widthType',
													labelLayout : 'horizontal',
													widths : [ '0%', '100%' ],
													label : '',
													'default' : 'px',
													items :
													[
														[ langTable.widthPx, 'px' ],
														[ langTable.widthPc, '%' ]
													],
													setup : function( selectedCell )
													{
														var widthMatch = widthPattern.exec( selectedCell.$.style.width );
														if ( widthMatch )
															this.setValue( widthMatch[2] );
													}
												}
											]
										},
										{
											type : 'hbox',
											widths : [ '70%', '30%' ],
											children :
											[
												{
													type : 'text',
													id : 'height',
													label : langTable.height,
													'default' : '',
													widths : [ '71%', '29%' ],
													labelLayout : 'horizontal',
													validate : validate[ 'number' ]( langCell.invalidHeight ),
													setup : function( selectedCell )
													{
														var heightMatch = heightPattern.exec( selectedCell.$.style.height );
														if ( heightMatch )
															this.setValue( heightMatch[1] );
													},
													commit : function( selectedCell )
													{
														if ( this.getValue() !== '' )
															selectedCell.$.style.height = this.getValue() + 'px';
														else
															selectedCell.$.style.height = '';
													}
												},
												{
													type : 'html',
													html : langTable.widthPx
												}
											]
										},
										spacer(),
										{
											type : 'select',
											id : 'wordWrap',
											labelLayout : 'horizontal',
											label : langCell.wordWrap,
											widths : [ '50%', '50%' ],
											'default' : 'yes',
											items :
											[
												[ langCell.yes, 'yes' ],
												[ langCell.no, 'no' ]
											],
											commit : function( selectedCell )
											{
												if ( this.getValue() == 'no' )
													selectedCell.setAttribute( 'noWrap', 'nowrap' );
												else
													selectedCell.removeAttribute( 'noWrap' );
											}
										},
										spacer(),
										{
											type : 'select',
											id : 'hAlign',
											labelLayout : 'horizontal',
											label : langCell.hAlign,
											widths : [ '50%', '50%' ],
											'default' : '',
											items :
											[
												[ langCommon.notSet, '' ],
												[ langTable.alignLeft, 'left' ],
												[ langTable.alignCenter, 'center' ],
												[ langTable.alignRight, 'right' ]
											],
											setup : function( selectedCell )
											{
												this.setValue( selectedCell.getAttribute( 'align' ) || '' );
											},
											commit : function( selectedCell )
											{
												if ( this.getValue() )
													selectedCell.setAttribute( 'align', this.getValue() );
												else
													selectedCell.removeAttribute( 'align' );
											}
										},
										{
											type : 'select',
											id : 'vAlign',
											labelLayout : 'horizontal',
											label : langCell.vAlign,
											widths : [ '50%', '50%' ],
											'default' : '',
											items :
											[
												[ langCommon.notSet, '' ],
												[ langCell.alignTop, 'top' ],
												[ langCell.alignMiddle, 'middle' ],
												[ langCell.alignBottom, 'bottom' ],
												[ langCell.alignBaseline, 'baseline' ]
											],
											setup : function( selectedCell )
											{
												this.setValue( selectedCell.getAttribute( 'vAlign' ) || '' );
											},
											commit : function( selectedCell )
											{
												if ( this.getValue() )
													selectedCell.setAttribute( 'vAlign', this.getValue() );
												else
													selectedCell.removeAttribute( 'vAlign' );
											}
										}
									]
								},
								spacer(),
								{
									type : 'vbox',
									padding : 0,
									children :
									[
										{
											type : 'select',
											id : 'cellType',
											label : langCell.cellType,
											labelLayout : 'horizontal',
											widths : [ '50%', '50%' ],
											'default' : 'td',
											items :
											[
												[ langCell.data, 'td' ],
												[ langCell.header, 'th' ]
											],
											setup : function( selectedCell )
											{
												this.setValue( selectedCell.getName() );
											},
											commit : function( selectedCell )
											{
												selectedCell.renameNode( this.getValue() );
											}
										},
										spacer(),
										{
											type : 'text',
											id : 'rowSpan',
											label : langCell.rowSpan,
											labelLayout : 'horizontal',
											widths : [ '50%', '50%' ],
											'default' : '',
											validate : validate.integer( langCell.invalidRowSpan ),
											setup : function( selectedCell )
											{
												this.setValue( selectedCell.getAttribute( 'rowSpan' ) || '' );
											},
											commit : function( selectedCell )
											{
												if ( this.getValue() )
													selectedCell.setAttribute( 'rowSpan', this.getValue() );
												else
													selectedCell.removeAttribute( 'rowSpan' );
											}
										},
										{
											type : 'text',
											id : 'colSpan',
											label : langCell.colSpan,
											labelLayout : 'horizontal',
											widths : [ '50%', '50%' ],
											'default' : '',
											validate : validate.integer( langCell.invalidColSpan ),
											setup : function( selectedCell )
											{
												this.setValue( selectedCell.getAttribute( 'colSpan' ) || '' );
											},
											commit : function( selectedCell )
											{
												if ( this.getValue() )
													selectedCell.setAttribute( 'colSpan', this.getValue() );
												else
													selectedCell.removeAttribute( 'colSpan' );
											}
										},
										spacer(),
										{
											type : 'hbox',
											padding : 0,
											widths : [ '80%', '20%' ],
											children :
											[
												{
													type : 'text',
													id : 'bgColor',
													label : langCell.bgColor,
													labelLayout : 'horizontal',
													widths : [ '70%', '30%' ],
													'default' : '',
													setup : function( selectedCell )
													{
														this.setValue( selectedCell.getAttribute( 'bgColor' ) || '' );
													},
													commit : function( selectedCell )
													{
														if ( this.getValue() )
															selectedCell.setAttribute( 'bgColor', this.getValue() );
														else
															selectedCell.removeAttribute( 'bgColor' );
													}
												},
												{
													type : 'button',
													id : 'bgColorChoose',
													label : langCell.chooseColor,
													style : 'margin-left: 10px',
													onClick : function()
													{
														var self = this;
														getDialogValue( 'colordialog', function( colorDialog )
														{
															self.getDialog().getContentElement( 'info', 'bgColor' ).setValue(
																colorDialog.getContentElement( 'picker', 'selectedColor' ).getValue()
															);
														} );
													}
												}
											]
										},
										spacer(),
										{
											type : 'hbox',
											padding : 0,
											widths : [ '80%', '20%' ],
											children :
											[
												{
													type : 'text',
													id : 'borderColor',
													label : langCell.borderColor,
													labelLayout : 'horizontal',
													widths : [ '70%', '30%' ],
													'default' : '',
													setup : function( selectedCell )
													{
														this.setValue( selectedCell.getStyle( 'border-color' ) || '' );
													},
													commit : function( selectedCell )
													{
														if ( this.getValue() )
															selectedCell.setStyle( 'border-color', this.getValue() );
														else
															selectedCell.removeStyle( 'border-color' );
													}
												},
												{
													type : 'button',
													id : 'borderColorChoose',
													label : langCell.chooseColor,
													style : 'margin-left: 10px',
													onClick : function()
													{
														var self = this;
														getDialogValue( 'colordialog', function( colorDialog )
														{
															self.getDialog().getContentElement( 'info', 'borderColor' ).setValue(
																colorDialog.getContentElement( 'picker', 'selectedColor' ).getValue()
															);
														} );
													}
												}
											]
										}
									]
								}
							]
						}
					]
				}
			],
			onShow : function()
			{
				this.cells = CKEDITOR.plugins.tabletools.getSelectedCells(
					this._.editor.getSelection() );
				this.setupContent( this.cells[ 0 ] );
			},
			onOk : function()
			{
				var cells = this.cells;
				for ( var i = 0 ; i < cells.length ; i++ )
					this.commitContent( cells[ i ] );
			}
		};
	} );
