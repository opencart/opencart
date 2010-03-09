/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add('print',{init:function(a){var b='print',c=a.addCommand(b,CKEDITOR.plugins.print);a.ui.addButton('Print',{label:a.lang.print,command:b});}});CKEDITOR.plugins.print={exec:function(a){if(CKEDITOR.env.opera)return;else if(CKEDITOR.env.gecko)a.window.$.print();else a.document.$.execCommand('Print');},canUndo:false,modes:{wysiwyg:!CKEDITOR.env.opera}};
