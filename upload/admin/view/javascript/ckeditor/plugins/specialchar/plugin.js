/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add('specialchar',{init:function(a){var b='specialchar';CKEDITOR.dialog.add(b,this.path+'dialogs/specialchar.js');a.addCommand(b,new CKEDITOR.dialogCommand(b));a.ui.addButton('SpecialChar',{label:a.lang.specialChar.toolbar,command:b});}});
