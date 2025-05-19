/**
 * @license Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config
	config.language = $(this).attr('data-lang');
	config.filebrowserWindowWidth = '800';
	config.filebrowserWindowHeight = '500';
	config.resize_enabled = true;
	config.resize_dir = 'vertical';
	config.htmlEncodeOutput = false;
	config.entities = false;
	config.extraPlugins = 'opencart,codemirror,youtube';
	config.codemirror_theme = 'monokai';
	config.toolbar = 'Custom';
	config.allowedContent = true;
	config.startupOutlineBlocks = false;
	config.disableNativeSpellChecker = false;
	config.browserContextMenuOnCtrl = true;
	config.resize_enabled = true;
	config.resize_dir = 'vertical';
	config.versionCheck = false;

	config.toolbar_Custom = [
		['Source'],
		['ShowBlocks'],
		['Maximize'],
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['SpecialChar'],
		'/',
		['Undo','Redo'],
		['Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Link','Unlink','Anchor'],
		['Image','OpenCart','Youtube','Table','HorizontalRule']
	];
};
