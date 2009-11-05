/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	// Load image preview.
	var IMAGE = 1,
		LINK = 2,
		PREVIEW = 4,
		CLEANUP = 8,
		regexGetSize = /^\s*(\d+)((px)|\%)?\s*$/i,
		regexGetSizeOrEmpty = /(^\s*(\d+)((px)|\%)?\s*$)|^$/i;

	var onSizeChange = function()
	{
		var value = this.getValue(),	// This = input element.
			dialog = this.getDialog(),
			aMatch  =  value.match( regexGetSize );	// Check value
		if ( aMatch )
		{
			if ( aMatch[2] == '%' )			// % is allowed - > unlock ratio.
				switchLockRatio( dialog, false );	// Unlock.
			value = aMatch[1];
		}

		// Only if ratio is locked
		if ( dialog.lockRatio )
		{
			var oImageOriginal = dialog.originalElement;
			if ( oImageOriginal.getCustomData( 'isReady' ) == 'true' )
			{
				if ( this.id == 'txtHeight' )
				{
					if ( value && value != '0' )
						value = Math.round( oImageOriginal.$.width * ( value  / oImageOriginal.$.height ) );
					if ( !isNaN( value ) )
						dialog.setValueOf( 'info', 'txtWidth', value );
				}
				else		//this.id = txtWidth.
				{
					if ( value && value != '0' )
						value = Math.round( oImageOriginal.$.height * ( value  / oImageOriginal.$.width ) );
					if ( !isNaN( value ) )
						dialog.setValueOf( 'info', 'txtHeight', value );
				}
			}
		}
		updatePreview( dialog );
	};

	var updatePreview = function( dialog )
	{
		//Don't load before onShow.
		if ( !dialog.originalElement || !dialog.preview )
			return 1;

		// Read attributes and update imagePreview;
		dialog.commitContent( PREVIEW, dialog.preview );
		return 0;
	};

	var switchLockRatio = function( dialog, value )
	{
		var oImageOriginal = dialog.originalElement,
			ratioButton = CKEDITOR.document.getById( 'btnLockSizes' );

		if ( oImageOriginal.getCustomData( 'isReady' ) == 'true' )
		{
			if ( value == 'check' )			// Check image ratio and original image ratio.
			{
				var width = dialog.getValueOf( 'info', 'txtWidth' ),
					height = dialog.getValueOf( 'info', 'txtHeight' ),
					originalRatio = oImageOriginal.$.width * 1000 / oImageOriginal.$.height,
					thisRatio = width * 1000 / height;
				dialog.lockRatio  = false;		// Default: unlock ratio

				if ( !width && !height )
					dialog.lockRatio = true;
				else if ( !isNaN( originalRatio ) && !isNaN( thisRatio ) )
				{
					if ( Math.round( originalRatio ) == Math.round( thisRatio ) )
						dialog.lockRatio = true;
				}
			}
			else if ( value != undefined )
				dialog.lockRatio = value;
			else
				dialog.lockRatio = !dialog.lockRatio;
		}
		else if ( value != 'check' )		// I can't lock ratio if ratio is unknown.
			dialog.lockRatio = false;

		if ( dialog.lockRatio )
			ratioButton.removeClass( 'cke_btn_unlocked' );
		else
			ratioButton.addClass( 'cke_btn_unlocked' );

		return dialog.lockRatio;
	};

	var resetSize = function( dialog )
	{
		var oImageOriginal = dialog.originalElement;
		if ( oImageOriginal.getCustomData( 'isReady' ) == 'true' )
		{
			dialog.setValueOf( 'info', 'txtWidth', oImageOriginal.$.width );
			dialog.setValueOf( 'info', 'txtHeight', oImageOriginal.$.height );
		}
		updatePreview( dialog );
	};

	var setupDimension = function( type, element )
	{
		if ( type != IMAGE )
			return;

		function checkDimension( size, defaultValue )
		{
			var aMatch  =  size.match( regexGetSize );
			if ( aMatch )
			{
				if ( aMatch[2] == '%' )				// % is allowed.
				{
					aMatch[1] += '%';
					switchLockRatio( dialog, false );	// Unlock ratio
				}
				return aMatch[1];
			}
			return defaultValue;
		}

		var dialog = this.getDialog(),
			value = '',
			dimension = (( this.id == 'txtWidth' )? 'width' : 'height' ),
			size = element.getAttribute( dimension );

		if ( size )
			value = checkDimension( size, value );
		value = checkDimension( element.$.style[ dimension ], value );

		this.setValue( value );
	};

	var imageDialog = function( editor, dialogType )
	{
		var onImgLoadEvent = function()
		{
			// Image is ready.
			var original = this.originalElement;
			original.setCustomData( 'isReady', 'true' );
			original.removeListener( 'load', onImgLoadEvent );
			original.removeListener( 'error', onImgLoadErrorEvent );
			original.removeListener( 'abort', onImgLoadErrorEvent );

			// Hide loader
			CKEDITOR.document.getById( 'ImagePreviewLoader' ).setStyle( 'display', 'none' );

			// New image -> new domensions
			if ( !this.dontResetSize )
				resetSize( this );

			if ( this.firstLoad )
				switchLockRatio( this, 'check' );
			this.firstLoad = false;
			this.dontResetSize = false;
		};

		var onImgLoadErrorEvent = function()
		{
			// Error. Image is not loaded.
			var original = this.originalElement;
			original.removeListener( 'load', onImgLoadEvent );
			original.removeListener( 'error', onImgLoadErrorEvent );
			original.removeListener( 'abort', onImgLoadErrorEvent );

			// Set Error image.
			var noimage = CKEDITOR.getUrl( editor.skinPath + 'images/noimage.png' );

			if ( this.preview )
				this.preview.setAttribute( 'src', noimage );

			// Hide loader
			CKEDITOR.document.getById( 'ImagePreviewLoader' ).setStyle( 'display', 'none' );
			switchLockRatio( this, false );	// Unlock.
		};
		return {
			title : ( dialogType == 'image' ) ? editor.lang.image.title : editor.lang.image.titleButton,
			minWidth : 420,
			minHeight : 310,
			onShow : function()
			{
				this.imageElement = false;
				this.linkElement = false;

				// Default: create a new element.
				this.imageEditMode = false;
				this.linkEditMode = false;

				this.lockRatio = true;
				this.dontResetSize = false;
				this.firstLoad = true;
				this.addLink = false;

				//Hide loader.
				CKEDITOR.document.getById( 'ImagePreviewLoader' ).setStyle( 'display', 'none' );
				// Preview
				this.preview = CKEDITOR.document.getById( 'previewImage' );

				var editor = this.getParentEditor(),
					sel = this.getParentEditor().getSelection(),
					element = sel.getSelectedElement(),
					link = element && element.getAscendant( 'a' );

				// Copy of the image
				this.originalElement = editor.document.createElement( 'img' );
				this.originalElement.setAttribute( 'alt', '' );
				this.originalElement.setCustomData( 'isReady', 'false' );

				if ( link )
				{
					this.linkElement = link;
					this.linkEditMode = true;

					// Look for Image element.
					var linkChildren = link.getChildren();
					if ( linkChildren.count() == 1 )			// 1 child.
					{
						var childTagName = linkChildren.getItem( 0 ).getName();
						if ( childTagName == 'img' || childTagName == 'input' )
						{
							this.imageElement = linkChildren.getItem( 0 );
							if ( this.imageElement.getName() == 'img' )
								this.imageEditMode = 'img';
							else if ( this.imageElement.getName() == 'input' )
								this.imageEditMode = 'input';
						}
					}
					// Fill out all fields.
					if ( dialogType == 'image' )
						this.setupContent( LINK, link );
				}

				if ( element && element.getName() == 'img' && !element.getAttribute( '_cke_protected_html' ) )
					this.imageEditMode = 'img';
				else if ( element && element.getName() == 'input' && element.getAttribute( 'type' ) && element.getAttribute( 'type' ) == 'image' )
					this.imageEditMode = 'input';

				if ( this.imageEditMode || this.imageElement )
				{
					if ( !this.imageElement )
						this.imageElement = element;

					// Fill out all fields.
					this.setupContent( IMAGE, this.imageElement );

					// Refresh LockRatio button
					switchLockRatio ( this, true );
				}

				// Dont show preview if no URL given.
				if ( !CKEDITOR.tools.trim( this.getValueOf( 'info', 'txtUrl' ) ) )
				{
					this.preview.removeAttribute( 'src' );
					this.preview.setStyle( 'display', 'none' );
				}
			},
			onOk : function()
			{
				// Edit existing Image.
				if ( this.imageEditMode )
				{
					var imgTagName = this.imageEditMode;

					// Image dialog and Input element.
					if ( dialogType == 'image' && imgTagName == 'input' && confirm( editor.lang.image.button2Img ) )
					{
						// Replace INPUT-> IMG
						imgTagName = 'img';
						this.imageElement = editor.document.createElement( 'img' );
						this.imageElement.setAttribute( 'alt', '' );
						editor.insertElement( this.imageElement );
					}
					// ImageButton dialog and Image element.
					else if ( dialogType != 'image' && imgTagName == 'img' && confirm( editor.lang.image.img2Button ))
					{
						// Replace IMG -> INPUT
						imgTagName = 'input';
						this.imageElement = editor.document.createElement( 'input' );
						this.imageElement.setAttributes(
							{
								type : 'image',
								alt : ''
							}
						);
						editor.insertElement( this.imageElement );
					}
				}
				else	// Create a new image.
				{
					// Image dialog -> create IMG element.
					if ( dialogType == 'image' )
						this.imageElement = editor.document.createElement( 'img' );
					else
					{
						this.imageElement = editor.document.createElement( 'input' );
						this.imageElement.setAttribute ( 'type' ,'image' );
					}
					this.imageElement.setAttribute( 'alt', '' );
				}

				// Create a new link.
				if ( !this.linkEditMode )
					this.linkElement = editor.document.createElement( 'a' );

				// Set attributes.
				this.commitContent( IMAGE, this.imageElement );
				this.commitContent( LINK, this.linkElement );

				// Insert a new Image.
				if ( !this.imageEditMode )
				{
					if ( this.addLink )
					{
						//Insert a new Link.
						if ( !this.linkEditMode )
						{
							editor.insertElement(this.linkElement);
							this.linkElement.append(this.imageElement, false);
						}
						else	 //Link already exists, image not.
							editor.insertElement(this.imageElement );
					}
					else
						editor.insertElement( this.imageElement );
				}
				else		// Image already exists.
				{
					//Add a new link element.
					if ( !this.linkEditMode && this.addLink )
					{
						editor.insertElement( this.linkElement );
						this.imageElement.appendTo( this.linkElement );
					}
					//Remove Link, Image exists.
					else if ( this.linkEditMode && !this.addLink )
					{
						editor.getSelection().selectElement( this.linkElement );
						editor.insertElement( this.imageElement );
					}
				}
			},
			onLoad : function()
			{
				if ( dialogType != 'image' )
					this.hidePage( 'Link' );		//Hide Link tab.
				var doc = this._.element.getDocument();
				this.addFocusable( doc.getById( 'btnResetSize' ), 5 );
				this.addFocusable( doc.getById( 'btnLockSizes' ), 5 );
			},
			onHide : function()
			{
				if ( this.preview )
					this.commitContent( CLEANUP, this.preview );

				if ( this.originalElement )
				{
					this.originalElement.removeListener( 'load', onImgLoadEvent );
					this.originalElement.removeListener( 'error', onImgLoadErrorEvent );
					this.originalElement.removeListener( 'abort', onImgLoadErrorEvent );
					this.originalElement.remove();
					this.originalElement = false;		// Dialog is closed.
				}
			},
			contents : [
				{
					id : 'info',
					label : editor.lang.image.infoTab,
					accessKey : 'I',
					elements :
					[
						{
							type : 'vbox',
							padding : 0,
							children :
							[
								{
									type : 'html',
									html : '<span>' + CKEDITOR.tools.htmlEncode( editor.lang.image.url ) + '</span>'
								},
								{
									type : 'hbox',
									widths : [ '280px', '110px' ],
									align : 'right',
									children :
									[
										{
											id : 'txtUrl',
											type : 'text',
											label : '',
											onChange : function()
											{
												var dialog = this.getDialog(),
													newUrl = this.getValue();

												//Update original image
												if ( newUrl.length > 0 )	//Prevent from load before onShow
												{
													dialog = this.getDialog();
													var original = dialog.originalElement;

													dialog.preview.removeStyle( 'display' );

													original.setCustomData( 'isReady', 'false' );
													// Show loader
													var loader = CKEDITOR.document.getById( 'ImagePreviewLoader' );
													if ( loader )
														loader.setStyle( 'display', '' );

													original.on( 'load', onImgLoadEvent, dialog );
													original.on( 'error', onImgLoadErrorEvent, dialog );
													original.on( 'abort', onImgLoadErrorEvent, dialog );
													original.setAttribute( 'src', newUrl );
													dialog.preview.setAttribute( 'src', newUrl );

													updatePreview( dialog );
												}
												// Dont show preview if no URL given.
												else if ( dialog.preview )
												{
													dialog.preview.removeAttribute( 'src' );
													dialog.preview.setStyle( 'display', 'none' );
												}
											},
											setup : function( type, element )
											{
												if ( type == IMAGE )
												{
													var url = element.getAttribute( '_cke_saved_src' ) || element.getAttribute( 'src' );
													var field = this;

													this.getDialog().dontResetSize = true;

													// In IE7 the dialog is being rendered improperly when loading
													// an image with a long URL. So we need to delay it a bit. (#4122)
													setTimeout( function()
														{
															field.setValue( url );		// And call this.onChange()
															// Manually set the initial value.(#4191)
															field.setInitValue();
															field.focus();
														}, 0 );
												}
											},
											commit : function( type, element )
											{
												if ( type == IMAGE && ( this.getValue() || this.isChanged() ) )
												{
													element.setAttribute( '_cke_saved_src', decodeURI( this.getValue() ) );
													element.setAttribute( 'src', decodeURI( this.getValue() ) );
												}
												else if ( type == CLEANUP )
												{
													element.setAttribute( 'src', '' );	// If removeAttribute doesn't work.
													element.removeAttribute( 'src' );
												}
											},
											validate : CKEDITOR.dialog.validate.notEmpty( editor.lang.image.urlMissing )
										},
										{
											type : 'button',
											id : 'browse',
											align : 'center',
											label : editor.lang.common.browseServer,
											hidden : true,
											filebrowser : 'info:txtUrl'
										}
									]
								}
							]
						},
						{
							id : 'txtAlt',
							type : 'text',
							label : editor.lang.image.alt,
							accessKey : 'A',
							'default' : '',
							onChange : function()
							{
								updatePreview( this.getDialog() );
							},
							setup : function( type, element )
							{
								if ( type == IMAGE )
									this.setValue( element.getAttribute( 'alt' ) );
							},
							commit : function( type, element )
							{
								if ( type == IMAGE )
								{
									if ( this.getValue() || this.isChanged() )
										element.setAttribute( 'alt', this.getValue() );
								}
								else if ( type == PREVIEW )
								{
									element.setAttribute( 'alt', this.getValue() );
								}
								else if ( type == CLEANUP )
								{
									element.removeAttribute( 'alt' );
								}
							}
						},
						{
							type : 'hbox',
							widths : [ '140px', '240px' ],
							children :
							[
								{
									type : 'vbox',
									padding : 10,
									children :
									[
										{
											type : 'hbox',
											widths : [ '70%', '30%' ],
											children :
											[
												{
													type : 'vbox',
													padding : 1,
													children :
													[
														{
															type : 'text',
															width: '40px',
															id : 'txtWidth',
															labelLayout : 'horizontal',
															label : editor.lang.image.width,
															onKeyUp : onSizeChange,
															validate: function()
															{
																var aMatch  =  this.getValue().match( regexGetSizeOrEmpty );
																if ( !aMatch )
																	alert( editor.lang.common.validateNumberFailed );
																return !!aMatch;
															},
															setup : setupDimension,
															commit : function( type, element )
															{
																if ( type == IMAGE )
																{
																	var value = this.getValue();
																	if ( value )
																		element.setAttribute( 'width', value );
																	else if ( !value && this.isChanged() )
																		element.removeAttribute( 'width' );
																}
																else if ( type == PREVIEW )
																{
																	value = this.getValue();
																	var aMatch = value.match( regexGetSize );
																	if ( !aMatch )
																	{
																		var oImageOriginal = this.getDialog().originalElement;
																		if ( oImageOriginal.getCustomData( 'isReady' ) == 'true' )
																			element.setStyle( 'width',  oImageOriginal.$.width + 'px');
																	}
																	else
																		element.setStyle( 'width', value + 'px');
																}
																else if ( type == CLEANUP )
																{
																	element.setStyle( 'width', '0px' );	// If removeAttribute doesn't work.
																	element.removeAttribute( 'width' );
																	element.removeStyle( 'width' );
																}
															}
														},
														{
															type : 'text',
															id : 'txtHeight',
															width: '40px',
															labelLayout : 'horizontal',
															label : editor.lang.image.height,
															onKeyUp : onSizeChange,
															validate: function()
															{
																var aMatch = this.getValue().match( regexGetSizeOrEmpty );
																if ( !aMatch )
																	alert( editor.lang.common.validateNumberFailed );
																return !!aMatch;
															},
															setup : setupDimension,
															commit : function( type, element )
															{
																if ( type == IMAGE )
																{
																	var value = this.getValue();
																	if ( value )
																		element.setAttribute( 'height', value );
																	else if ( !value && this.isChanged() )
																		element.removeAttribute( 'height' );
																}
																else if ( type == PREVIEW )
																{
																	value = this.getValue();
																	var aMatch = value.match( regexGetSize );
																	if ( !aMatch )
																	{
																		var oImageOriginal = this.getDialog().originalElement;
																		if ( oImageOriginal.getCustomData( 'isReady' ) == 'true' )
																			element.setStyle( 'height',  oImageOriginal.$.height + 'px');
																	}
																	else
																		element.setStyle( 'height', value + 'px');
																}
																else if ( type == CLEANUP )
																{
																	element.setStyle( 'height', '0px' );	// If removeAttribute doesn't work.
																	element.removeAttribute( 'height' );
																	element.removeStyle( 'height' );
																}
															}
														}
													]
												},
												{
													type : 'html',
													style : 'margin-top:10px;width:40px;height:40px;',
													onLoad : function()
													{
														// Activate Reset button
														var	resetButton = CKEDITOR.document.getById( 'btnResetSize' ),
															ratioButton = CKEDITOR.document.getById( 'btnLockSizes' );
														if ( resetButton )
														{
															resetButton.on( 'click', function()
																{
																	resetSize( this );
																}, this.getDialog() );
															resetButton.on( 'mouseover', function()
																{
																	this.addClass( 'cke_btn_over' );
																}, resetButton );
															resetButton.on( 'mouseout', function()
																{
																	this.removeClass( 'cke_btn_over' );
																}, resetButton );
														}
														// Activate (Un)LockRatio button
														if ( ratioButton )
														{
															ratioButton.on( 'click', function()
																{
																	var locked = switchLockRatio( this ),
																		oImageOriginal = this.originalElement,
																		width = this.getValueOf( 'info', 'txtWidth' );

																	if ( oImageOriginal.getCustomData( 'isReady' ) == 'true' && width )
																	{
																		var height = oImageOriginal.$.height / oImageOriginal.$.width * width;
																		if ( !isNaN( height ) )
																		{
																			this.setValueOf( 'info', 'txtHeight', Math.round( height ) );
																			updatePreview( this );
																		}
																	}
																}, this.getDialog() );
															ratioButton.on( 'mouseover', function()
																{
																	this.addClass( 'cke_btn_over' );
																}, ratioButton );
															ratioButton.on( 'mouseout', function()
																{
																	this.removeClass( 'cke_btn_over' );
																}, ratioButton );
														}
													},
													html : '<div>'+
														'<a href="javascript:void(0)" tabindex="-1" title="' + editor.lang.image.lockRatio +
														'" class="cke_btn_locked" id="btnLockSizes"></a>' +
														'<a href="javascript:void(0)" tabindex="-1" title="' + editor.lang.image.resetSize +
														'" class="cke_btn_reset" id="btnResetSize"></a>'+
														'</div>'
												}
											]
										},
										{
											type : 'vbox',
											padding : 1,
											children :
											[
												{
													type : 'text',
													id : 'txtBorder',
													width: '60px',
													labelLayout : 'horizontal',
													label : editor.lang.image.border,
													'default' : '',
													onKeyUp : function()
													{
														updatePreview( this.getDialog() );
													},
													validate: function()
													{
														var func = CKEDITOR.dialog.validate.integer( editor.lang.common.validateNumberFailed );
														return func.apply( this );
													},
													setup : function( type, element )
													{
														if ( type == IMAGE )
															this.setValue( element.getAttribute( 'border' ) );
													},
													commit : function( type, element )
													{
														if ( type == IMAGE )
														{
															if ( this.getValue() || this.isChanged() )
																element.setAttribute( 'border', this.getValue() );
														}
														else if ( type == PREVIEW )
														{
															var value = parseInt( this.getValue(), 10 );
															value = isNaN( value ) ? 0 : value;
															element.setAttribute( 'border', value );
															element.setStyle( 'border', value + 'px solid black' );
														}
														else if ( type == CLEANUP )
														{
															element.removeAttribute( 'border' );
															element.removeStyle( 'border' );
														}
													}
												},
												{
													type : 'text',
													id : 'txtHSpace',
													width: '60px',
													labelLayout : 'horizontal',
													label : editor.lang.image.hSpace,
													'default' : '',
													onKeyUp : function()
													{
														updatePreview( this.getDialog() );
													},
													validate: function()
													{
														var func = CKEDITOR.dialog.validate.integer( editor.lang.common.validateNumberFailed );
														return func.apply( this );
													},
													setup : function( type, element )
													{
														if ( type == IMAGE )
														{
															var value = element.getAttribute( 'hspace' );
															if ( value != -1 )				// In IE empty = -1.
																this.setValue( value );
														}
													},
													commit : function( type, element )
													{
														if ( type == IMAGE )
														{
															if ( this.getValue() || this.isChanged() )
																element.setAttribute( 'hspace', this.getValue() );
														}
														else if ( type == PREVIEW )
														{
															var value = parseInt( this.getValue(), 10 );
															value = isNaN( value ) ? 0 : value;
															element.setAttribute( 'hspace', value );
															element.setStyle( 'margin-left', value + 'px' );
															element.setStyle( 'margin-right', value + 'px' );
														}
														else if ( type == CLEANUP )
														{
															element.removeAttribute( 'hspace' );
															element.removeStyle( 'margin-left' );
															element.removeStyle( 'margin-right' );
														}
													}
												},
												{
													type : 'text',
													id : 'txtVSpace',
													width : '60px',
													labelLayout : 'horizontal',
													label : editor.lang.image.vSpace,
													'default' : '',
													onKeyUp : function()
													{
														updatePreview( this.getDialog() );
													},
													validate: function()
													{
														var func = CKEDITOR.dialog.validate.integer( editor.lang.common.validateNumberFailed );
														return func.apply( this );
													},
													setup : function( type, element )
													{
														if ( type == IMAGE )
															this.setValue( element.getAttribute( 'vspace' ) );
													},
													commit : function( type, element )
													{
														if ( type == IMAGE )
														{
															if ( this.getValue() || this.isChanged() )
																element.setAttribute( 'vspace', this.getValue() );
														}
														else if ( type == PREVIEW )
														{
															var value = parseInt( this.getValue(), 10 );
															value = isNaN( value ) ? 0 : value;
															element.setAttribute( 'vspace', this.getValue() );
															element.setStyle( 'margin-top', value + 'px' );
															element.setStyle( 'margin-bottom', value + 'px' );
														}
														else if ( type == CLEANUP )
														{
															element.removeAttribute( 'vspace' );
															element.removeStyle( 'margin-top' );
															element.removeStyle( 'margin-bottom' );
														}
													}
												},
												{
													id : 'cmbAlign',
													type : 'select',
													labelLayout : 'horizontal',
													widths : [ '35%','65%' ],
													style : 'width:90px',
													label : editor.lang.image.align,
													'default' : '',
													items :
													[
														[ editor.lang.common.notSet , ''],
														[ editor.lang.image.alignLeft , 'left'],
														[ editor.lang.image.alignAbsBottom , 'absBottom'],
														[ editor.lang.image.alignAbsMiddle , 'absMiddle'],
														[ editor.lang.image.alignBaseline , 'baseline'],
														[ editor.lang.image.alignBottom , 'bottom'],
														[ editor.lang.image.alignMiddle , 'middle'],
														[ editor.lang.image.alignRight , 'right'],
														[ editor.lang.image.alignTextTop , 'textTop'],
														[ editor.lang.image.alignTop , 'top']
													],
													onChange : function()
													{
														updatePreview( this.getDialog() );
													},
													setup : function( type, element )
													{
														if ( type == IMAGE )
															this.setValue( element.getAttribute( 'align' ) );
													},
													commit : function( type, element )
													{
														var value = this.getValue();
														if ( type == IMAGE )
														{
															if ( value || this.isChanged() )
																element.setAttribute( 'align', value );
														}
														else if ( type == PREVIEW )
														{
															element.setAttribute( 'align', this.getValue() );

															if ( value == 'absMiddle' || value == 'middle' )
																element.setStyle( 'vertical-align', 'middle' );
															else if ( value == 'top' || value == 'textTop' )
																element.setStyle( 'vertical-align', 'top' );
															else
																element.removeStyle( 'vertical-align' );

															if ( value == 'right' || value == 'left' )
																element.setStyle( 'styleFloat', value );
															else
																element.removeStyle( 'styleFloat' );

														}
														else if ( type == CLEANUP )
														{
															element.removeAttribute( 'align' );
														}
													}
												}
											]
										}
									]
								},
								{
									type : 'vbox',
									height : '250px',
									children :
									[
										{
											type : 'html',
											style : 'width:95%;',
											html : '<div>' + CKEDITOR.tools.htmlEncode( editor.lang.image.preview ) +'<br>'+
											'<div id="ImagePreviewLoader" style="display:none"><div class="loading">&nbsp;</div></div>'+
											'<div id="ImagePreviewBox">'+
											'<a href="javascript:void(0)" target="_blank" onclick="return false;" id="previewLink">'+
											'<img id="previewImage" src="" alt="" /></a>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. '+
											'Maecenas feugiat consequat diam. Maecenas metus. Vivamus diam purus, cursus a, commodo non, facilisis vitae, '+
											'nulla. Aenean dictum lacinia tortor. Nunc iaculis, nibh non iaculis aliquam, orci felis euismod neque, sed ornare massa mauris sed velit. Nulla pretium mi et risus. Fusce mi pede, tempor id, cursus ac, ullamcorper nec, enim. Sed tortor. Curabitur molestie. Duis velit augue, condimentum at, ultrices a, luctus ut, orci. Donec pellentesque egestas eros. Integer cursus, augue in cursus faucibus, eros pede bibendum sem, in tempus tellus justo quis ligula. Etiam eget tortor. Vestibulum rutrum, est ut placerat elementum, lectus nisl aliquam velit, tempor aliquam eros nunc nonummy metus. In eros metus, gravida a, gravida sed, lobortis id, turpis. Ut ultrices, ipsum at venenatis fringilla, sem nulla lacinia tellus, eget aliquet turpis mauris non enim. Nam turpis. Suspendisse lacinia. Curabitur ac tortor ut ipsum egestas elementum. Nunc imperdiet gravida mauris.' +
											'</div>'+'</div>'
										}
									]
								}
							]
						}
					]
				},
				{
					id : 'Link',
					label : editor.lang.link.title,
					padding : 0,
					elements :
					[
						{
							id : 'txtUrl',
							type : 'text',
							label : editor.lang.image.url,
							style : 'width: 100%',
							'default' : '',
							setup : function( type, element )
							{
								if ( type == LINK )
								{
									var href = element.getAttribute( '_cke_saved_href' );
									if ( !href )
										href = element.getAttribute( 'href' );
									this.setValue( href );
								}
							},
							commit : function( type, element )
							{
								if ( type == LINK )
								{
									if ( this.getValue() || this.isChanged() )
									{
										element.setAttribute( '_cke_saved_href', decodeURI( this.getValue() ) );
										element.setAttribute( 'href', 'javascript:void(0)/*' +
											CKEDITOR.tools.getNextNumber() + '*/' );

										if ( this.getValue() || !editor.config.image_removeLinkByEmptyURL )
											this.getDialog().addLink = true;
									}
								}
							}
						},
						{
							type : 'button',
							id : 'browse',
							filebrowser : 'Link:txtUrl',
							style : 'float:right',
							hidden : true,
							label : editor.lang.common.browseServer
						},
						{
							id : 'cmbTarget',
							type : 'select',
							label : editor.lang.link.target,
							'default' : '',
							items :
							[
								[ editor.lang.link.targetNotSet , ''],
								[ editor.lang.link.targetNew , '_blank'],
								[ editor.lang.link.targetTop , '_top'],
								[ editor.lang.link.targetSelf , '_self'],
								[ editor.lang.link.targetParent , '_parent']
							],
							setup : function( type, element )
							{
								if ( type == LINK )
									this.setValue( element.getAttribute( 'target' ) );
							},
							commit : function( type, element )
							{
								if ( type == LINK )
								{
									if ( this.getValue() || this.isChanged() )
										element.setAttribute( 'target', this.getValue() );
								}
							}
						}
					]
				},
				{
					id : 'Upload',
					hidden : true,
					filebrowser : 'uploadButton',
					label : editor.lang.image.upload,
					elements :
					[
						{
							type : 'file',
							id : 'upload',
							label : editor.lang.image.btnUpload,
							style: 'height:40px',
							size : 38
						},
						{
							type : 'fileButton',
							id : 'uploadButton',
							filebrowser : 'info:txtUrl',
							label : editor.lang.image.btnUpload,
							'for' : [ 'Upload', 'upload' ]
						}
					]
				},
				{
					id : 'advanced',
					label : editor.lang.common.advancedTab,
					elements :
					[
						{
							type : 'hbox',
							widths : [ '50%', '25%', '25%' ],
							children :
							[
								{
									type : 'text',
									id : 'linkId',
									label : editor.lang.common.id,
									setup : function( type, element )
									{
										if ( type == IMAGE )
											this.setValue( element.getAttribute( 'id' ) );
									},
									commit : function( type, element )
									{
										if ( type == IMAGE )
										{
											if ( this.getValue() || this.isChanged() )
												element.setAttribute( 'id', this.getValue() );
										}
									}
								},
								{
									id : 'cmbLangDir',
									type : 'select',
									style : 'width : 100px;',
									label : editor.lang.common.langDir,
									'default' : '',
									items :
									[
										[ editor.lang.common.notSet, '' ],
										[ editor.lang.common.langDirLtr, 'ltr' ],
										[ editor.lang.common.langDirRtl, 'rtl' ]
									],
									setup : function( type, element )
									{
										if ( type == IMAGE )
											this.setValue( element.getAttribute( 'dir' ) );
									},
									commit : function( type, element )
									{
										if ( type == IMAGE )
										{
											if ( this.getValue() || this.isChanged() )
												element.setAttribute( 'dir', this.getValue() );
										}
									}
								},
								{
									type : 'text',
									id : 'txtLangCode',
									label : editor.lang.common.langCode,
									'default' : '',
									setup : function( type, element )
									{
										if ( type == IMAGE )
											this.setValue( element.getAttribute( 'lang' ) );
									},
									commit : function( type, element )
									{
										if ( type == IMAGE )
										{
											if ( this.getValue() || this.isChanged() )
												element.setAttribute( 'lang', this.getValue() );
										}
									}
								}
							]
						},
						{
							type : 'text',
							id : 'txtGenLongDescr',
							label : editor.lang.common.longDescr,
							setup : function( type, element )
							{
								if ( type == IMAGE )
									this.setValue( element.getAttribute( 'longDesc' ) );
							},
							commit : function( type, element )
							{
								if ( type == IMAGE )
								{
									if ( this.getValue() || this.isChanged() )
										element.setAttribute( 'longDesc', this.getValue() );
								}
							}
						},
						{
							type : 'hbox',
							widths : [ '50%', '50%' ],
							children :
							[
								{
									type : 'text',
									id : 'txtGenClass',
									label : editor.lang.common.cssClass,
									'default' : '',
									setup : function( type, element )
									{
										if ( type == IMAGE )
											this.setValue( element.getAttribute( 'class' ) );
									},
									commit : function( type, element )
									{
										if ( type == IMAGE )
										{
											if ( this.getValue() || this.isChanged() )
												element.setAttribute( 'class', this.getValue() );
										}
									}
								},
								{
									type : 'text',
									id : 'txtGenTitle',
									label : editor.lang.common.advisoryTitle,
									'default' : '',
									onChange : function()
									{
										updatePreview( this.getDialog() );
									},
									setup : function( type, element )
									{
										if ( type == IMAGE )
											this.setValue( element.getAttribute( 'title' ) );
									},
									commit : function( type, element )
									{
										if ( type == IMAGE )
										{
											if ( this.getValue() || this.isChanged() )
												element.setAttribute( 'title', this.getValue() );
										}
										else if ( type == PREVIEW )
										{
											element.setAttribute( 'title', this.getValue() );
										}
										else if ( type == CLEANUP )
										{
											element.removeAttribute( 'title' );
										}
									}
								}
							]
						},
						{
							type : 'text',
							id : 'txtdlgGenStyle',
							label : editor.lang.common.cssStyle,
							'default' : '',
							setup : function( type, element )
							{
								if ( type == IMAGE )
								{
									var genStyle = element.getAttribute( 'style' );
									if ( !genStyle && element.$.style.cssText )
										genStyle = element.$.style.cssText;
									this.setValue( genStyle );

									var height = element.$.style.height,
										width = element.$.style.width,
										aMatchH  = ( height ? height : '' ).match( regexGetSize ),
										aMatchW  = ( width ? width : '').match( regexGetSize );

									this.attributesInStyle =
									{
										height : !!aMatchH,
										width : !!aMatchW
									};
								}
							},
							commit : function( type, element )
							{
								if ( type == IMAGE && ( this.getValue() || this.isChanged() ) )
								{
									element.setAttribute( 'style', this.getValue() );

									// Set STYLE dimensions.
									var height = element.getAttribute( 'height' ),
										width = element.getAttribute( 'width' );

									if ( this.attributesInStyle && this.attributesInStyle.height )
									{
										if ( height )
										{
											if ( height.match( regexGetSize )[2] == '%' )			// % is allowed
												element.setStyle( 'height', height + '%' );
											else
												element.setStyle( 'height', height + 'px' );
										}
										else
											element.removeStyle( 'height' );
									}
									if ( this.attributesInStyle && this.attributesInStyle.width )
									{
										if ( width )
										{
											if ( width.match( regexGetSize )[2] == '%' )			// % is allowed
												element.setStyle( 'width', width + '%' );
											else
												element.setStyle( 'width', width + 'px' );
										}
										else
											element.removeStyle( 'width' );
									}
								}
							}
						}
					]
				}
			]
		};
	};

	CKEDITOR.dialog.add( 'image', function( editor )
		{
			return imageDialog( editor, 'image' );
		});

	CKEDITOR.dialog.add( 'imagebutton', function( editor )
		{
			return imageDialog( editor, 'imagebutton' );
		});
})();
