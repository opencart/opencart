/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
CKEDITOR.dialog.add( 'button', function( editor )
{
	return {
		title : editor.lang.button.title,
		minWidth : 350,
		minHeight : 150,
		onShow : function()
		{
			delete this.button;
			var element = this.getParentEditor().getSelection().getSelectedElement();
			if ( element && element.getName() == "input" )
			{
				var type = element.getAttribute( 'type' );
				if ( type == "button" || type == "reset" || type == "submit" )
				{
					this.button = element;
					this.setupContent( element );
				}
			}
		},
		onOk : function()
		{
			var editor,
				element = this.button,
				isInsertMode = !element;

			if ( isInsertMode )
			{
				editor = this.getParentEditor();
				element = editor.document.createElement( 'input' );
			}

			if ( isInsertMode )
				editor.insertElement( element );
			this.commitContent( { element : element } );
		},
		contents : [
			{
				id : 'info',
				label : editor.lang.button.title,
				title : editor.lang.button.title,
				elements : [
					{
						id : '_cke_saved_name',
						type : 'text',
						label : editor.lang.common.name,
						'default' : '',
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
						label : editor.lang.button.text,
						accessKey : 'V',
						'default' : '',
						setup : function( element )
						{
							this.setValue( element.getAttribute( 'value' ) || '' );
						},
						commit : function( data )
						{
							var element = data.element;

							if ( this.getValue() )
								element.setAttribute( 'value', this.getValue() );
							else
								element.removeAttribute( 'value' );
						}
					},
					{
						id : 'type',
						type : 'select',
						label : editor.lang.button.type,
						'default' : 'button',
						accessKey : 'T',
						items :
						[
							[ editor.lang.button.typeBtn, 'button' ],
							[ editor.lang.button.typeSbm, 'submit' ],
							[ editor.lang.button.typeRst, 'reset' ]
						],
						setup : function( element )
						{
							this.setValue( element.getAttribute( 'type' ) || '' );
						},
						commit : function( data )
						{
							var element = data.element;

							if ( CKEDITOR.env.ie )
							{
								var elementType = element.getAttribute( 'type' );
								var currentType = this.getValue();

								if ( currentType != elementType )
								{
									var replace = CKEDITOR.dom.element.createFromHtml( '<input type="' + currentType +
										'"></input>', editor.document );
									element.copyAttributes( replace, { type : 1 } );
									replace.replace( element );
									editor.getSelection().selectElement( replace );
									data.element = replace;
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
