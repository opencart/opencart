/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the "virtual" dialog, dialog content and dialog button
 * definition classes.
 */

/**
 * This class is not really part of the API. It just illustrates the properties
 * that developers can use to define and create dialogs.
 * @name CKEDITOR.dialog.dialogDefinition
 * @constructor
 * @example
 * // There is no constructor for this class, the user just has to define an
 * // object with the appropriate properties.
 *
 * CKEDITOR.dialog.add( 'testOnly', function( editor )
 *       {
 *           return {
 *               title : 'Test Dialog',
 *               resizable : CKEDITOR.DIALOG_RESIZE_BOTH,
 *               minWidth : 500,
 *               minHeight : 400,
 *               contents : [
 *                   {
 *                       id : 'tab1',
 *                       label : 'First Tab',
 *                       title : 'First Tab Title',
 *                       accessKey : 'Q',
 *                       elements : [
 *                           {
 *                               type : 'text',
 *                               label : 'Test Text 1',
 *                               id : 'testText1',
 *                               'default' : 'hello world!'
 *                           }
 *                       ]
 *                    }
 *               ]
 *           };
 *       });
 */

/**
 * The dialog title, displayed in the dialog's header. Required.
 * @name CKEDITOR.dialog.dialogDefinition.prototype.title
 * @field
 * @type String
 * @example
 */

/**
 * How the dialog can be resized, must be one of the four contents defined below.
 * <br /><br />
 * <strong>CKEDITOR.DIALOG_RESIZE_NONE</strong><br />
 * <strong>CKEDITOR.DIALOG_RESIZE_WIDTH</strong><br />
 * <strong>CKEDITOR.DIALOG_RESIZE_HEIGHT</strong><br />
 * <strong>CKEDITOR.DIALOG_RESIZE_BOTH</strong><br />
 * @name CKEDITOR.dialog.dialogDefinition.prototype.resizable
 * @field
 * @type Number
 * @default CKEDITOR.DIALOG_RESIZE_NONE
 * @example
 */

/**
 * The minimum width of the dialog, in pixels.
 * @name CKEDITOR.dialog.dialogDefinition.prototype.minWidth
 * @field
 * @type Number
 * @default 600
 * @example
 */

/**
 * The minimum height of the dialog, in pixels.
 * @name CKEDITOR.dialog.dialogDefinition.prototype.minHeight
 * @field
 * @type Number
 * @default 400
 * @example
 */

/**
 * The buttons in the dialog, defined as an array of
 * {@link CKEDITOR.dialog.buttonDefinition} objects.
 * @name CKEDITOR.dialog.dialogDefinition.prototype.buttons
 * @field
 * @type Array
 * @default [ CKEDITOR.dialog.okButton, CKEDITOR.dialog.cancelButton ]
 * @example
 */

/**
 * The contents in the dialog, defined as an array of
 * {@link CKEDITOR.dialog.contentDefinition} objects. Required.
 * @name CKEDITOR.dialog.dialogDefinition.prototype.contents
 * @field
 * @type Array
 * @example
 */

/**
 * The function to execute when OK is pressed.
 * @name CKEDITOR.dialog.dialogDefinition.prototype.onOk
 * @field
 * @type Function
 * @example
 */

/**
 * The function to execute when Cancel is pressed.
 * @name CKEDITOR.dialog.dialogDefinition.prototype.onCancel
 * @field
 * @type Function
 * @example
 */

/**
 * The function to execute when the dialog is displayed for the first time.
 * @name CKEDITOR.dialog.dialogDefinition.prototype.onLoad
 * @field
 * @type Function
 * @example
 */

/**
 * This class is not really part of the API. It just illustrates the properties
 * that developers can use to define and create dialog content pages.
 * @name CKEDITOR.dialog.contentDefinition
 * @constructor
 * @example
 * // There is no constructor for this class, the user just has to define an
 * // object with the appropriate properties.
 */

/**
 * The id of the content page.
 * @name CKEDITOR.dialog.contentDefinition.prototype.id
 * @field
 * @type String
 * @example
 */

/**
 * The tab label of the content page.
 * @name CKEDITOR.dialog.contentDefinition.prototype.label
 * @field
 * @type String
 * @example
 */

/**
 * The popup message of the tab label.
 * @name CKEDITOR.dialog.contentDefinition.prototype.title
 * @field
 * @type String
 * @example
 */

/**
 * The CTRL hotkey for switching to the tab.
 * @name CKEDITOR.dialog.contentDefinition.prototype.accessKey
 * @field
 * @type String
 * @example
 * contentDefinition.accessKey = 'Q';	// Switch to this page when CTRL-Q is pressed.
 */

/**
 * The UI elements contained in this content page, defined as an array of
 * {@link CKEDITOR.dialog.uiElementDefinition} objects.
 * @name CKEDITOR.dialog.contentDefinition.prototype.elements
 * @field
 * @type Array
 * @example
 */

/**
 * This class is not really part of the API. It just illustrates the properties
 * that developers can use to define and create dialog buttons.
 * @name CKEDITOR.dialog.buttonDefinition
 * @constructor
 * @example
 * // There is no constructor for this class, the user just has to define an
 * // object with the appropriate properties.
 */

/**
 * The id of the dialog button. Required.
 * @name CKEDITOR.dialog.buttonDefinition.prototype.id
 * @type String
 * @field
 * @example
 */

/**
 * The label of the dialog button. Required.
 * @name CKEDITOR.dialog.buttonDefinition.prototype.label
 * @type String
 * @field
 * @example
 */

/**
 * The popup message of the dialog button.
 * @name CKEDITOR.dialog.buttonDefinition.prototype.title
 * @type String
 * @field
 * @example
 */

/**
 * The CTRL hotkey for the button.
 * @name CKEDITOR.dialog.buttonDefinition.prototype.accessKey
 * @type String
 * @field
 * @example
 * exitButton.accessKey = 'X';		// Button will be pressed when user presses CTRL-X
 */

/**
 * Whether the button is disabled.
 * @name CKEDITOR.dialog.buttonDefinition.prototype.disabled
 * @type Boolean
 * @field
 * @default false
 * @example
 */

/**
 * The function to execute when the button is clicked.
 * @name CKEDITOR.dialog.buttonDefinition.prototype.onClick
 * @type Function
 * @field
 * @example
 */

/**
 * This class is not really part of the API. It just illustrates the properties
 * that developers can use to define and create dialog UI elements.
 * @name CKEDITOR.dialog.uiElementDefinition
 * @constructor
 * @see CKEDITOR.ui.dialog.uiElement
 * @example
 * // There is no constructor for this class, the user just has to define an
 * // object with the appropriate properties.
 */

/**
 * The id of the UI element.
 * @name CKEDITOR.dialog.uiElementDefinition.prototype.id
 * @field
 * @type String
 * @example
 */

/**
 * The type of the UI element. Required.
 * @name CKEDITOR.dialog.uiElementDefinition.prototype.type
 * @field
 * @type String
 * @example
 */

/**
 * The popup label of the UI element.
 * @name CKEDITOR.dialog.uiElementDefinition.prototype.title
 * @field
 * @type String
 * @example
 */

/**
 * CSS class names to append to the UI element.
 * @name CKEDITOR.dialog.uiElementDefinition.prototype.className
 * @field
 * @type String
 * @example
 */

/**
 * Inline CSS classes to append to the UI element.
 * @name CKEDITOR.dialog.uiElementDefinition.prototype.style
 * @field
 * @type String
 * @example
 */

/**
 * Function to execute the first time the UI element is displayed.
 * @name CKEDITOR.dialog.uiElementDefinition.prototype.onLoad
 * @field
 * @type Function
 * @example
 */

/**
 * Function to execute whenever the UI element's parent dialog is displayed.
 * @name CKEDITOR.dialog.uiElementDefinition.prototype.onShow
 * @field
 * @type Function
 * @example
 */

/**
 * Function to execute whenever the UI element's parent dialog is closed.
 * @name CKEDITOR.dialog.uiElementDefinition.prototype.onHide
 * @field
 * @type Function
 * @example
 */
