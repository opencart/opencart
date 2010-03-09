/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.dialog.add( 'anchor', function( editor )
{
	// Function called in onShow to load selected element.
	var loadElements = function( editor, selection, element )
	{
		this.editMode = true;
		this.editObj = element;

		var attributeValue = this.editObj.getAttribute( 'name' );
		if ( attributeValue )
			this.setValueOf( 'info','txtName', attributeValue );
		else
			this.setValueOf( 'info','txtName', "" );
	};

	return {
		title : editor.lang.anchor.title,
		minWidth : 300,
		minHeight : 60,
		onOk : function()
		{
			// Always create a new anchor, because of IE BUG.
			var name = this.getValueOf( 'info', 'txtName' ),
				element = CKEDITOR.env.ie ?
				editor.document.createElement( '<a name="' + CKEDITOR.tools.htmlEncode( name ) + '">' ) :
				editor.document.createElement( 'a' );

			// Move contents and attributes of old anchor to new anchor.
			if ( this.editMode )
			{
				this.editObj.copyAttributes( element, { name : 1 } );
				this.editObj.moveChildren( element );
			}

			// Set name.
			element.removeAttribute( '_cke_saved_name' );
			element.setAttribute( 'name', name );

			// Insert a new anchor.
			var fakeElement = editor.createFakeElement( element, 'cke_anchor', 'anchor' );
			if ( !this.editMode )
				editor.insertElement( fakeElement );
			else
			{
				fakeElement.replace( this.fakeObj );
				editor.getSelection().selectElement( fakeElement );
			}

			return true;
		},
		onShow : function()
		{
			this.editObj = false;
			this.fakeObj = false;
			this.editMode = false;

			var selection = editor.getSelection();
			var element = selection.getSelectedElement();
			if ( element && element.getAttribute( '_cke_real_element_type' ) && element.getAttribute( '_cke_real_element_type' ) == 'anchor' )
			{
				this.fakeObj = element;
				element = editor.restoreRealElement( this.fakeObj );
				loadElements.apply( this, [ editor, selection, element ] );
				selection.selectElement( this.fakeObj );
			}
			this.getContentElement( 'info', 'txtName' ).focus();
		},
		contents : [
			{
				id : 'info',
				label : editor.lang.anchor.title,
				accessKey : 'I',
				elements :
				[
					{
						type : 'text',
						id : 'txtName',
						label : editor.lang.anchor.name,
						validate : function()
						{
							if ( !this.getValue() )
							{
								alert( editor.lang.anchor.errorName );
								return false;
							}
							return true;
						}
					}
				]
			}
		]
	};
} );
