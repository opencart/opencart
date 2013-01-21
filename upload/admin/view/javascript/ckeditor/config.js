/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config
	config.filebrowserWindowWidth = '800';
	config.filebrowserWindowHeight = '500';
	config.resize_enabled = false;
	config.htmlEncodeOutput = false;
	config.entities = false;
	config.extraPlugins = 'codemirror';
	config.codemirror_theme = 'rubyblue';
	config.toolbar = 'Custom';

	config.toolbar_Custom = [
		['Source'],
		['Maximize'],
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
		['SpecialChar'],
		'/',
		['Undo','Redo'],
		['Font','FontSize'],
		['TextColor','BGColor'],
		['Link','Unlink','Anchor'],
		['Image','Table','HorizontalRule']
	];
};
