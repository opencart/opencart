/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
CKEDITOR.dialog.add( 'textfield', function( editor )
{
	var autoAttributes =
	{
		value : 1,
		size : 1,
		maxLength : 1
	};

	var acceptedTypes =
	{
		text : 1,
		password : 1
	};

	return {
		title : editor.lang.textfield.title,
		minWidth : 350,
		minHeight : 150,
		onShow : function()
		{
			delete this.textField;

			var element = this.getParentEditor().getSelection().getSelectedElement();
			if ( element && element.getName() == "input" &&
					( acceptedTypes[ element.getAttribute( 'type' ) ] || !element.getAttribute( 'type' ) ) )
			{
				this.textField = element;
				this.setupContent( element );
			}
		},
		onOk : function()
		{
			var editor,
				element = this.textField,
				isInsertMode = !element;

			if ( isInsertMode )
			{
				editor = this.getParentEditor();
				element = editor.document.createElement( 'input' );
				element.setAttribute( 'type', 'text' );
			}

			if ( isInsertMode )
				editor.insertElement( element );
			this.commitContent( { element : element } );
		},
		onLoad : function()
		{
			var autoSetup = function( element )
			{
				var value = element.hasAttribute( this.id ) && element.getAttribute( this.id );
				this.setValue( value || '' );
			};

			var autoCommit = function( data )
			{
				var element = data.element;
				var value = this.getValue();

				if ( value )
					element.setAttribute( this.id, value );
				else
					element.removeAttribute( this.id );
			};

			this.foreach( function( contentObj )
				{
					if ( autoAttributes[ contentObj.id ] )
					{
						contentObj.setup = autoSetup;
						contentObj.commit = autoCommit;
					}
				} );
		},
		contents : [
			{
				id : 'info',
				label : editor.lang.textfield.title,
				title : editor.lang.textfield.title,
				elements : [
					{
						type : 'hbox',
						widths : [ '50%', '50%' ],
						children :
						[
							{
								id : '_cke_saved_name',
								type : 'text',
								label : editor.lang.textfield.name,
								'default' : '',
								accessKey : 'N',
								setup : function( element )
								{
									this.setValue(
											element.getAttribute( '_cke_saved_name' ) ||
											element.getAttribute( 'name' ) ||
											'' );
								},
								commit : function( data )
								{
									var element = data.element;

									if ( this.getValue() )
										element.setAttribute( '_cke_saved_name', this.getValue() );
									else
									{
										element.removeAttribute( '_cke_saved_name' );
										element.removeAttribute( 'name' );
									}
								}
							},
							{
								id : 'value',
								type : 'text',
								label : editor.lang.textfield.value,
								'default' : '',
								accessKey : 'V'
							}
						]
					},
					{
						type : 'hbox',
						widths : [ '50%', '50%' ],
						children :
						[
							{
								id : 'size',
								type : 'text',
								label : editor.lang.textfield.charWidth,
								'default' : '',
								accessKey : 'C',
								style : 'width:50px',
								validate : CKEDITOR.dialog.validate.integer( editor.lang.common.validateNumberFailed )
							},
							{
								id : 'maxLength',
								type : 'text',
								label : editor.lang.textfield.maxChars,
								'default' : '',
								accessKey : 'M',
								style : 'width:50px',
								validate : CKEDITOR.dialog.validate.integer( editor.lang.common.validateNumberFailed )
							}
						]
					},
					{
						id : 'type',
						type : 'select',
						label : editor.lang.textfield.type,
						'default' : 'text',
						accessKey : 'M',
						items :
						[
							[ editor.lang.textfield.typeText, 'text' ],
							[ editor.lang.textfield.typePass, 'password' ]
						],
						setup : function( element )
						{
							this.setValue( element.getAttribute( 'type' ) );
						},
						commit : function( data )
						{
							var element = data.element;

							if ( CKEDITOR.env.ie )
							{
								var elementType = element.getAttribute( 'type' );
								var myType = this.getValue();

								if ( elementType != myType )
								{
									var replace = CKEDITOR.dom.element.createFromHtml( '<input type="' + myType + '"></input>', editor.document );
									element.copyAttributes( replace, { type : 1 } );
									replace.replace( element );
									editor.getSelection().selectElement( replace );
									data.element = element;
								}
							}
							else
								element.setAttribute( 'type', this.getValue() );
						}
					}
				]
			}
		]
	};
});
