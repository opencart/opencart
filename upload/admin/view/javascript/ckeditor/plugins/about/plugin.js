/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add('about',{init:function(a){var b=a.addCommand('about',new CKEDITOR.dialogCommand('about'));b.modes={wysiwyg:1,source:1};b.canUndo=false;a.ui.addButton('About',{label:a.lang.about.title,command:'about'});CKEDITOR.dialog.add('about',this.path+'dialogs/about.js');}});
