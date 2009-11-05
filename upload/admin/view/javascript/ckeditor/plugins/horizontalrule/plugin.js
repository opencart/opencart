/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function(){var a={exec:function(c){c.insertElement(c.document.createElement('hr'));}},b='horizontalrule';CKEDITOR.plugins.add(b,{init:function(c){c.addCommand(b,a);c.ui.addButton('HorizontalRule',{label:c.lang.horizontalrule,command:b});}});})();
