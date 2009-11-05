/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add('wsc',{init:function(a){var b='checkspell',c=a.addCommand(b,new CKEDITOR.dialogCommand(b));c.modes={wysiwyg:!CKEDITOR.env.opera&&document.domain==window.location.hostname};a.ui.addButton('SpellChecker',{label:a.lang.spellCheck.toolbar,command:b});CKEDITOR.dialog.add(b,this.path+'dialogs/wsc.js');}});CKEDITOR.config.wsc_customerId=CKEDITOR.config.wsc_customerId||'1:ua3xw1-2XyGJ3-GWruD3-6OFNT1-oXcuB1-nR6Bp4-hgQHc-EcYng3-sdRXG3-NOfFk';CKEDITOR.config.wsc_customLoaderScript=CKEDITOR.config.wsc_customLoaderScript||null;
