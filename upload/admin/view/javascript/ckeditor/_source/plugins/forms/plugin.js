/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Forms Plugin
 */

CKEDITOR.plugins.add( 'forms',
{
	init : function( editor )
	{
		var lang = editor.lang;

		editor.addCss(
			'form' +
			'{' +
				'border: 1px dotted #FF0000;' +
				'padding: 2px;' +
			'}' );

		// All buttons use the same code to register. So, to avoid
		// duplications, let's use this tool function.
		var addButtonCommand = function( buttonName, commandName, dialogFile )
		{
			editor.addCommand( commandName, new CKEDITOR.dialogCommand( commandName ) );

			editor.ui.addButton( buttonName,
				{
					label : lang.common[ buttonName.charAt(0).toLowerCase() + buttonName.slice(1) ],
					command : commandName
				});
			CKEDITOR.dialog.add( commandName, dialogFile );
		};

		var dialogPath = this.path + 'dialogs/';
		addButtonCommand( 'Form',			'form',			dialogPath + 'form.js' );
		addButtonCommand( 'Checkbox',		'checkbox',		dialogPath + 'checkbox.js' );
		addButtonCommand( 'Radio',			'radio',		dialogPath + 'radio.js' );
		addButtonCommand( 'TextField',		'textfield',	dialogPath + 'textfield.js' );
		addButtonCommand( 'Textarea',		'textarea',		dialogPath + 'textarea.js' );
		addButtonCommand( 'Select',			'select',		dialogPath + 'select.js' );
		addButtonCommand( 'Button',			'button',		dialogPath + 'button.js' );
		addButtonCommand( 'ImageButton',	'imagebutton',	CKEDITOR.plugins.getPath('image') + 'dialogs/image.js' );
		addButtonCommand( 'HiddenField',	'hiddenfield',	dialogPath + 'hiddenfield.js' );

		// If the "menu" plugin is loaded, register the menu items.
		if ( editor.addMenuItems )
		{
			editor.addMenuItems(
				{
					form :
					{
						label : lang.form.menu,
						command : 'form',
						group : 'form'
					},

					checkbox :
					{
						label : lang.checkboxAndRadio.checkboxTitle,
						command : 'checkbox',
						group : 'checkbox'
					},

					radio :
					{
						label : lang.checkboxAndRadio.radioTitle,
						command : 'radio',
						group : 'radio'
					},

					textfield :
					{
						label : lang.textfield.title,
						command : 'textfield',
						group : 'textfield'
					},

					hiddenfield :
					{
						label : lang.hidden.title,
						command : 'hiddenfield',
						group : 'hiddenfield'
					},

					imagebutton :
					{
						label : lang.image.titleButton,
						command : 'imagebutton',
						group : 'imagebutton'
					},

					button :
					{
						label : lang.button.title,
						command : 'button',
						group : 'button'
					},

					select :
					{
						label : lang.select.title,
						command : 'select',
						group : 'select'
					},

					textarea :
					{
						label : lang.textarea.title,
						command : 'textarea',
						group : 'textarea'
					}
				});
		}

		// If the "contextmenu" plugin is loaded, register the listeners.
		if ( editor.contextMenu )
		{
			editor.contextMenu.addListener( function( element )
				{
					if ( element && element.hasAscendant( 'form' ) )
						return { form : CKEDITOR.TRISTATE_OFF };
				});

			editor.contextMenu.addListener( function( element )
				{
					if ( element )
					{
						var name = element.getName();

						if ( name == 'select' )
							return { select : CKEDITOR.TRISTATE_OFF };

						if ( name == 'textarea' )
							return { textarea : CKEDITOR.TRISTATE_OFF };

						if ( name == 'input' )
						{
							var type = element.getAttribute( 'type' );

							if ( type == 'text' || type == 'password' )
								return { textfield : CKEDITOR.TRISTATE_OFF };

							if ( type == 'button' || type == 'submit' || type == 'reset' )
								return { button : CKEDITOR.TRISTATE_OFF };

							if ( type == 'checkbox' )
								return { checkbox : CKEDITOR.TRISTATE_OFF };

							if ( type == 'radio' )
								return { radio : CKEDITOR.TRISTATE_OFF };

							if ( type == 'image' )
								return { imagebutton : CKEDITOR.TRISTATE_OFF };
						}

						if ( name == 'img' && element.getAttribute( '_cke_real_element_type' ) == 'hiddenfield' )
							return { hiddenfield : CKEDITOR.TRISTATE_OFF };
					}
				});
		}
	},
	requires : [ 'image' ]
} );

if ( CKEDITOR.env.ie )
{
	CKEDITOR.dom.element.prototype.hasAttribute = function( name )
	{
		var $attr = this.$.attributes.getNamedItem( name );

		if ( this.getName() == 'input' )
		{
			switch ( name )
			{
				case 'class' :
					return this.$.className.length > 0;
				case 'checked' :
					return !!this.$.checked;
				case 'value' :
					var type = this.getAttribute( 'type' );
					if ( type == 'checkbox' || type == 'radio' )
						return this.$.value != 'on';
					break;
				default:
			}
		}

		return !!( $attr && $attr.specified );
	};
}
