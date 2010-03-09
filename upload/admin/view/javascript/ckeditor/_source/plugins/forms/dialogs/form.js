/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
CKEDITOR.dialog.add( 'form', function( editor )
{
	var autoAttributes =
	{
		action : 1,
		id : 1,
		method : 1,
		enctype : 1,
		target : 1
	};

	return {
		title : editor.lang.form.title,
		minWidth : 350,
		minHeight : 200,
		onShow : function()
		{
			delete this.form;

			var element = this.getParentEditor().getSelection().getStartElement();
			var form = element && element.getAscendant( 'form', true );
			if ( form )
			{
				this.form = form;
				this.setupContent( form );
			}
		},
		onOk : function()
		{
			var editor,
				element = this.form,
				isInsertMode = !element;

			if ( isInsertMode )
			{
				editor = this.getParentEditor();
				element = editor.document.createElement( 'form' );
				element.append( editor.document.createElement( 'br' ) );
			}

			if ( isInsertMode )
				editor.insertElement( element );
			this.commitContent( element );
		},
		onLoad : function()
		{
			function autoSetup( element )
			{
				this.setValue( element.getAttribute( this.id ) || '' );
			}

			function autoCommit( element )
			{
				if ( this.getValue() )
					element.setAttribute( this.id, this.getValue() );
				else
					element.removeAttribute( this.id );
			}

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
				label : editor.lang.form.title,
				title : editor.lang.form.title,
				elements : [
					{
						id : 'txtName',
						type : 'text',
						label : editor.lang.common.name,
						'default' : '',
						accessKey : 'N',
						setup : function( element )
						{
							this.setValue( element.getAttribute( '_cke_saved_name' ) ||
									element.getAttribute( 'name' ) ||
									'' );
						},
						commit : function( element )
						{
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
						id : 'action',
						type : 'text',
						label : editor.lang.form.action,
						'default' : '',
						accessKey : 'A'
					},
					{
						type : 'hbox',
						widths : [ '45%', '55%' ],
						children :
						[
							{
								id : 'id',
								type : 'text',
								label : editor.lang.common.id,
								'default' : '',
								accessKey : 'I'
							},
							{
								id : 'enctype',
								type : 'select',
								label : editor.lang.form.encoding,
								style : 'width:100%',
								accessKey : 'E',
								'default' : '',
								items :
								[
									[ '' ],
									[ 'text/plain' ],
									[ 'multipart/form-data' ],
									[ 'application/x-www-form-urlencoded' ]
								]
							}
						]
					},
					{
						type : 'hbox',
						widths : [ '45%', '55%' ],
						children :
						[
							{
								id : 'target',
								type : 'select',
								label : editor.lang.form.target,
								style : 'width:100%',
								accessKey : 'M',
								'default' : '',
								items :
								[
									[ editor.lang.form.targetNotSet, '' ],
									[ editor.lang.form.targetNew, '_blank' ],
									[ editor.lang.form.targetTop, '_top' ],
									[ editor.lang.form.targetSelf, '_self' ],
									[ editor.lang.form.targetParent, '_parent' ]
								]
							},
							{
								id : 'method',
								type : 'select',
								label : editor.lang.form.method,
								accessKey : 'M',
								'default' : 'GET',
								items :
								[
									[ 'GET', 'get' ],
									[ 'POST', 'post' ]
								]
							}
						]
					}
				]
			}
		]
	};
});
