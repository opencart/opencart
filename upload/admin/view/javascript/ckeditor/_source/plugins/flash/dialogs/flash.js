/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	/*
	 * It is possible to set things in three different places.
	 * 1. As attributes in the object tag.
	 * 2. As param tags under the object tag.
	 * 3. As attributes in the embed tag.
	 * It is possible for a single attribute to be present in more than one place.
	 * So let's define a mapping between a sementic attribute and its syntactic
	 * equivalents.
	 * Then we'll set and retrieve attribute values according to the mapping,
	 * instead of having to check and set each syntactic attribute every time.
	 *
	 * Reference: http://kb.adobe.com/selfservice/viewContent.do?externalId=tn_12701
	 */
	var ATTRTYPE_OBJECT = 1,
		ATTRTYPE_PARAM = 2,
		ATTRTYPE_EMBED = 4;

	var attributesMap =
	{
		id : [ { type : ATTRTYPE_OBJECT, name :  'id' } ],
		classid : [ { type : ATTRTYPE_OBJECT, name : 'classid' } ],
		codebase : [ { type : ATTRTYPE_OBJECT, name : 'codebase'} ],
		pluginspage : [ { type : ATTRTYPE_EMBED, name : 'pluginspage' } ],
		src : [ { type : ATTRTYPE_PARAM, name : 'movie' }, { type : ATTRTYPE_EMBED, name : 'src' } ],
		name : [ { type : ATTRTYPE_EMBED, name : 'name' } ],
		align : [ { type : ATTRTYPE_OBJECT, name : 'align' } ],
		title : [ { type : ATTRTYPE_OBJECT, name : 'title' }, { type : ATTRTYPE_EMBED, name : 'title' } ],
		'class' : [ { type : ATTRTYPE_OBJECT, name : 'class' }, { type : ATTRTYPE_EMBED, name : 'class'} ],
		width : [ { type : ATTRTYPE_OBJECT, name : 'width' }, { type : ATTRTYPE_EMBED, name : 'width' } ],
		height : [ { type : ATTRTYPE_OBJECT, name : 'height' }, { type : ATTRTYPE_EMBED, name : 'height' } ],
		hSpace : [ { type : ATTRTYPE_OBJECT, name : 'hSpace' }, { type : ATTRTYPE_EMBED, name : 'hSpace' } ],
		vSpace : [ { type : ATTRTYPE_OBJECT, name : 'vSpace' }, { type : ATTRTYPE_EMBED, name : 'vSpace' } ],
		style : [ { type : ATTRTYPE_OBJECT, name : 'style' }, { type : ATTRTYPE_EMBED, name : 'style' } ],
		type : [ { type : ATTRTYPE_EMBED, name : 'type' } ]
	};

	var names = [ 'play', 'loop', 'menu', 'quality', 'scale', 'salign', 'wmode', 'bgcolor', 'base', 'flashvars', 'allowScriptAccess',
		'allowFullScreen' ];
	for ( var i = 0 ; i < names.length ; i++ )
		attributesMap[ names[i] ] = [ { type : ATTRTYPE_EMBED, name : names[i] }, { type : ATTRTYPE_PARAM, name : names[i] } ];
	names = [ 'allowFullScreen', 'play', 'loop', 'menu' ];
	for ( i = 0 ; i < names.length ; i++ )
		attributesMap[ names[i] ][0]['default'] = attributesMap[ names[i] ][1]['default'] = true;

	function loadValue( objectNode, embedNode, paramMap )
	{
		var attributes = attributesMap[ this.id ];
		if ( !attributes )
			return;

		var isCheckbox = ( this instanceof CKEDITOR.ui.dialog.checkbox );
		for ( var i = 0 ; i < attributes.length ; i++ )
		{
			var attrDef = attributes[ i ];
			switch ( attrDef.type )
			{
				case ATTRTYPE_OBJECT:
					if ( !objectNode )
						continue;
					if ( objectNode.getAttribute( attrDef.name ) !== null )
					{
						var value = objectNode.getAttribute( attrDef.name );
						if ( isCheckbox )
							this.setValue( value.toLowerCase() == 'true' );
						else
							this.setValue( value );
						return;
					}
					else if ( isCheckbox )
						this.setValue( !!attrDef[ 'default' ] );
					break;
				case ATTRTYPE_PARAM:
					if ( !objectNode )
						continue;
					if ( attrDef.name in paramMap )
					{
						value = paramMap[ attrDef.name ];
						if ( isCheckbox )
							this.setValue( value.toLowerCase() == 'true' );
						else
							this.setValue( value );
						return;
					}
					else if ( isCheckbox )
						this.setValue( !!attrDef[ 'default' ] );
					break;
				case ATTRTYPE_EMBED:
					if ( !embedNode )
						continue;
					if ( embedNode.getAttribute( attrDef.name ) )
					{
						value = embedNode.getAttribute( attrDef.name );
						if ( isCheckbox )
							this.setValue( value.toLowerCase() == 'true' );
						else
							this.setValue( value );
						return;
					}
					else if ( isCheckbox )
						this.setValue( !!attrDef[ 'default' ] );
			}
		}
	}

	function commitValue( objectNode, embedNode, paramMap )
	{
		var attributes = attributesMap[ this.id ];
		if ( !attributes )
			return;

		var isRemove = ( this.getValue() === '' ),
			isCheckbox = ( this instanceof CKEDITOR.ui.dialog.checkbox );

		for ( var i = 0 ; i < attributes.length ; i++ )
		{
			var attrDef = attributes[i];
			switch ( attrDef.type )
			{
				case ATTRTYPE_OBJECT:
					if ( !objectNode )
						continue;
					var value = this.getValue();
					if ( isRemove || isCheckbox && value === attrDef[ 'default' ] )
						objectNode.removeAttribute( attrDef.name );
					else
						objectNode.setAttribute( attrDef.name, value );
					break;
				case ATTRTYPE_PARAM:
					if ( !objectNode )
						continue;
					value = this.getValue();
					if ( isRemove || isCheckbox && value === attrDef[ 'default' ] )
					{
						if ( attrDef.name in paramMap )
							paramMap[ attrDef.name ].remove();
					}
					else
					{
						if ( attrDef.name in paramMap )
							paramMap[ attrDef.name ].setAttribute( 'value', value );
						else
						{
							var param = CKEDITOR.dom.element.createFromHtml( '<cke:param></cke:param>', objectNode.getDocument() );
							param.setAttributes( { name : attrDef.name, value : value } );
							if ( objectNode.getChildCount() < 1 )
								param.appendTo( objectNode );
							else
								param.insertBefore( objectNode.getFirst() );
						}
					}
					break;
				case ATTRTYPE_EMBED:
					if ( !embedNode )
						continue;
					value = this.getValue();
					if ( isRemove || isCheckbox && value === attrDef[ 'default' ])
						embedNode.removeAttribute( attrDef.name );
					else
						embedNode.setAttribute( attrDef.name, value );
			}
		}
	}

	CKEDITOR.dialog.add( 'flash', function( editor )
	{
		var makeObjectTag = !editor.config.flashEmbedTagOnly,
			makeEmbedTag = editor.config.flashAddEmbedTag || editor.config.flashEmbedTagOnly;

		var previewAreaHtml = '<div>' + CKEDITOR.tools.htmlEncode( editor.lang.image.preview ) +'<br>' +
			'<div id="FlashPreviewLoader" style="display:none"><div class="loading">&nbsp;</div></div>' +
			'<div id="FlashPreviewBox"></div></div>';

		return {
			title : editor.lang.flash.title,
			minWidth : 420,
			minHeight : 310,
			onShow : function()
			{
				// Clear previously saved elements.
				this.fakeImage = this.objectNode = this.embedNode = null;

				// Try to detect any embed or object tag that has Flash parameters.
				var fakeImage = this.getSelectedElement();
				if ( fakeImage && fakeImage.getAttribute( '_cke_real_element_type' ) && fakeImage.getAttribute( '_cke_real_element_type' ) == 'flash' )
				{
					this.fakeImage = fakeImage;

					var realElement = editor.restoreRealElement( fakeImage ),
						objectNode = null, embedNode = null, paramMap = {};
					if ( realElement.getName() == 'cke:object' )
					{
						objectNode = realElement;
						var embedList = objectNode.getElementsByTag( 'embed', 'cke' );
						if ( embedList.count() > 0 )
							embedNode = embedList.getItem( 0 );
						var paramList = objectNode.getElementsByTag( 'param', 'cke' );
						for ( var i = 0, length = paramList.count() ; i < length ; i++ )
						{
							var item = paramList.getItem( i ),
								name = item.getAttribute( 'name' ),
								value = item.getAttribute( 'value' );
							paramMap[ name ] = value;
						}
					}
					else if ( realElement.getName() == 'cke:embed' )
						embedNode = realElement;

					this.objectNode = objectNode;
					this.embedNode = embedNode;

					this.setupContent( objectNode, embedNode, paramMap, fakeImage );
				}
			},
			onOk : function()
			{
				// If there's no selected object or embed, create one. Otherwise, reuse the
				// selected object and embed nodes.
				var objectNode = null,
					embedNode = null,
					paramMap = null;
				if ( !this.fakeImage )
				{
					if ( makeObjectTag )
					{
						objectNode = CKEDITOR.dom.element.createFromHtml( '<cke:object></cke:object>', editor.document );
						var attributes = {
							classid : 'clsid:d27cdb6e-ae6d-11cf-96b8-444553540000',
							codebase : 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0'
						};
						objectNode.setAttributes( attributes );
					}
					if ( makeEmbedTag )
					{
						embedNode = CKEDITOR.dom.element.createFromHtml( '<cke:embed></cke:embed>', editor.document );
						embedNode.setAttributes(
							{
								type : 'application/x-shockwave-flash',
								pluginspage : 'http://www.macromedia.com/go/getflashplayer'
							} );
						if ( objectNode )
							embedNode.appendTo( objectNode );
					}
				}
				else
				{
					objectNode = this.objectNode;
					embedNode = this.embedNode;
				}

				// Produce the paramMap if there's an object tag.
				if ( objectNode )
				{
					paramMap = {};
					var paramList = objectNode.getElementsByTag( 'param', 'cke' );
					for ( var i = 0, length = paramList.count() ; i < length ; i++ )
						paramMap[ paramList.getItem( i ).getAttribute( 'name' ) ] = paramList.getItem( i );
				}

				// Apply or remove flash parameters.
				var extraStyles = {};
				this.commitContent( objectNode, embedNode, paramMap, extraStyles );

				// Refresh the fake image.
				var newFakeImage = editor.createFakeElement( objectNode || embedNode, 'cke_flash', 'flash', true );
				newFakeImage.setStyles( extraStyles );
				if ( this.fakeImage )
					newFakeImage.replace( this.fakeImage );
				else
					editor.insertElement( newFakeImage );
			},

			onHide : function()
			{
				if ( this.preview )
					this.preview.setHtml('');
			},

			contents : [
				{
					id : 'info',
					label : editor.lang.common.generalTab,
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
											id : 'src',
											type : 'text',
											label : '',
											validate : CKEDITOR.dialog.validate.notEmpty( editor.lang.flash.validateSrc ),
											setup : loadValue,
											commit : commitValue,
											onLoad : function()
											{
												var dialog = this.getDialog(),
												updatePreview = function( src ){

													dialog.preview.setHtml( '<embed height="100%" width="100%" src="'
														+ CKEDITOR.tools.htmlEncode( src )
														+ '" type="application/x-shockwave-flash"></embed>' );
												};
												// Preview element
												dialog.preview = dialog.getContentElement( 'info', 'preview' ).getElement().getChild( 3 );

												// Sync on inital value loaded.
												this.on( 'change', function( evt ){

														if ( evt.data && evt.data.value )
															updatePreview( evt.data.value );
													} );
												// Sync when input value changed.
												this.getInputElement().on( 'change', function( evt ){

													updatePreview( this.getValue() );
												}, this );
											}
										},
										{
											type : 'button',
											id : 'browse',
											filebrowser : 'info:src',
											hidden : true,
											align : 'center',
											label : editor.lang.common.browseServer
										}
									]
								}
							]
						},
						{
							type : 'hbox',
							widths : [ '25%', '25%', '25%', '25%', '25%' ],
							children :
							[
								{
									type : 'text',
									id : 'width',
									style : 'width:95px',
									label : editor.lang.flash.width,
									validate : CKEDITOR.dialog.validate.integer( editor.lang.flash.validateWidth ),
									setup : function( objectNode, embedNode, paramMap, fakeImage )
									{
										loadValue.apply( this, arguments );
										if ( fakeImage )
										{
											var fakeImageWidth = parseInt( fakeImage.$.style.width, 10 );
											if ( !isNaN( fakeImageWidth ) )
												this.setValue( fakeImageWidth );
										}
									},
									commit : function( objectNode, embedNode, paramMap, extraStyles )
									{
										commitValue.apply( this, arguments );
										if ( this.getValue() )
											extraStyles.width = this.getValue() + 'px';
									}
								},
								{
									type : 'text',
									id : 'height',
									style : 'width:95px',
									label : editor.lang.flash.height,
									validate : CKEDITOR.dialog.validate.integer( editor.lang.flash.validateHeight ),
									setup : function( objectNode, embedNode, paramMap, fakeImage )
									{
										loadValue.apply( this, arguments );
										if ( fakeImage )
										{
											var fakeImageHeight = parseInt( fakeImage.$.style.height, 10 );
											if ( !isNaN( fakeImageHeight ) )
												this.setValue( fakeImageHeight );
										}
									},
									commit : function( objectNode, embedNode, paramMap, extraStyles )
									{
										commitValue.apply( this, arguments );
										if ( this.getValue() )
											extraStyles.height = this.getValue() + 'px';
									}
								},
								{
									type : 'text',
									id : 'hSpace',
									style : 'width:95px',
									label : editor.lang.flash.hSpace,
									validate : CKEDITOR.dialog.validate.integer( editor.lang.flash.validateHSpace ),
									setup : loadValue,
									commit : commitValue
								},
								{
									type : 'text',
									id : 'vSpace',
									style : 'width:95px',
									label : editor.lang.flash.vSpace,
									validate : CKEDITOR.dialog.validate.integer( editor.lang.flash.validateVSpace ),
									setup : loadValue,
									commit : commitValue
								}
							]
						},

						{
							type : 'vbox',
							children :
							[
								{
									type : 'html',
									id : 'preview',
									style : 'width:95%;',
									html : previewAreaHtml
								}
							]
						}
					]
				},
				{
					id : 'Upload',
					hidden : true,
					filebrowser : 'uploadButton',
					label : editor.lang.common.upload,
					elements :
					[
						{
							type : 'file',
							id : 'upload',
							label : editor.lang.common.upload,
							size : 38
						},
						{
							type : 'fileButton',
							id : 'uploadButton',
							label : editor.lang.common.uploadSubmit,
							filebrowser : 'info:src',
							'for' : [ 'Upload', 'upload' ]
						}
					]
				},
				{
					id : 'properties',
					label : editor.lang.flash.propertiesTab,
					elements :
					[
						{
							type : 'hbox',
							widths : [ '50%', '50%' ],
							children :
							[
								{
									id : 'scale',
									type : 'select',
									label : editor.lang.flash.scale,
									'default' : '',
									style : 'width : 100%;',
									items :
									[
										[ editor.lang.common.notSet , ''],
										[ editor.lang.flash.scaleAll, 'showall' ],
										[ editor.lang.flash.scaleNoBorder, 'noborder' ],
										[ editor.lang.flash.scaleFit, 'exactfit' ]
									],
									setup : loadValue,
									commit : commitValue
								},
								{
									id : 'allowScriptAccess',
									type : 'select',
									label : editor.lang.flash.access,
									'default' : '',
									style : 'width : 100%;',
									items :
									[
										[ editor.lang.common.notSet , ''],
										[ editor.lang.flash.accessAlways, 'always' ],
										[ editor.lang.flash.accessSameDomain, 'samedomain' ],
										[ editor.lang.flash.accessNever, 'never' ]
									],
									setup : loadValue,
									commit : commitValue
								}
							]
						},
						{
							type : 'hbox',
							widths : [ '50%', '50%' ],
							children :
							[
								{
									id : 'wmode',
									type : 'select',
									label : editor.lang.flash.windowMode,
									'default' : '',
									style : 'width : 100%;',
									items :
									[
										[ editor.lang.common.notSet , '' ],
										[ editor.lang.flash.windowModeWindow, 'window' ],
										[ editor.lang.flash.windowModeOpaque, 'opaque' ],
										[ editor.lang.flash.windowModeTransparent, 'transparent' ]
									],
									setup : loadValue,
									commit : commitValue
								},
								{
									id : 'quality',
									type : 'select',
									label : editor.lang.flash.quality,
									'default' : 'high',
									style : 'width : 100%;',
									items :
									[
										[ editor.lang.common.notSet , '' ],
										[ editor.lang.flash.qualityBest, 'best' ],
										[ editor.lang.flash.qualityHigh, 'high' ],
										[ editor.lang.flash.qualityAutoHigh, 'autohigh' ],
										[ editor.lang.flash.qualityMedium, 'medium' ],
										[ editor.lang.flash.qualityAutoLow, 'autolow' ],
										[ editor.lang.flash.qualityLow, 'low' ]
									],
									setup : loadValue,
									commit : commitValue
								}
							]
						},
						{
							type : 'hbox',
							widths : [ '50%', '50%' ],
							children :
							[
								{
									id : 'align',
									type : 'select',
									label : editor.lang.flash.align,
									'default' : '',
									style : 'width : 100%;',
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
									setup : loadValue,
									commit : commitValue
								},
								{
									type : 'html',
									html : '<div></div>'
								}
							]
						},
						{
							type : 'vbox',
							padding : 0,
							children :
							[
								{
									type : 'html',
									html : CKEDITOR.tools.htmlEncode( editor.lang.flash.flashvars )
								},
								{
									type : 'checkbox',
									id : 'menu',
									label : editor.lang.flash.chkMenu,
									'default' : true,
									setup : loadValue,
									commit : commitValue
								},
								{
									type : 'checkbox',
									id : 'play',
									label : editor.lang.flash.chkPlay,
									'default' : true,
									setup : loadValue,
									commit : commitValue
								},
								{
									type : 'checkbox',
									id : 'loop',
									label : editor.lang.flash.chkLoop,
									'default' : true,
									setup : loadValue,
									commit : commitValue
								},
								{
									type : 'checkbox',
									id : 'allowFullScreen',
									label : editor.lang.flash.chkFull,
									'default' : true,
									setup : loadValue,
									commit : commitValue
								}
							]
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
							widths : [ '45%', '55%' ],
							children :
							[
								{
									type : 'text',
									id : 'id',
									label : editor.lang.common.id,
									setup : loadValue,
									commit : commitValue
								},
								{
									type : 'text',
									id : 'title',
									label : editor.lang.common.advisoryTitle,
									setup : loadValue,
									commit : commitValue
								}
							]
						},
						{
							type : 'hbox',
							widths : [ '45%', '55%' ],
							children :
							[
								{
									type : 'text',
									id : 'bgcolor',
									label : editor.lang.flash.bgcolor,
									setup : loadValue,
									commit : commitValue
								},
								{
									type : 'text',
									id : 'class',
									label : editor.lang.common.cssClass,
									setup : loadValue,
									commit : commitValue
								}
							]
						},
						{
							type : 'text',
							id : 'style',
							label : editor.lang.common.cssStyle,
							setup : loadValue,
							commit : commitValue
						}
					]
				}
			]
		};
	} );
})();
