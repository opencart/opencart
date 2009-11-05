/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function(){var a={modes:{wysiwyg:1,source:1},exec:function(c){var d=c.element.$.form;if(d)try{d.submit();}catch(e){if(d.submit.click)d.submit.click();}}},b='save';CKEDITOR.plugins.add(b,{init:function(c){var d=c.addCommand(b,a);d.modes={wysiwyg:!!c.element.$.form};c.ui.addButton('Save',{label:c.lang.save,command:b});}});})();
