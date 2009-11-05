/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add('image',{init:function(a){var b='image';CKEDITOR.dialog.add(b,this.path+'dialogs/image.js');a.addCommand(b,new CKEDITOR.dialogCommand(b));a.ui.addButton('Image',{label:a.lang.common.image,command:b});if(a.addMenuItems)a.addMenuItems({image:{label:a.lang.image.menu,command:'image',group:'image'}});if(a.contextMenu)a.contextMenu.addListener(function(c,d){if(!c||!c.is('img')||c.getAttribute('_cke_realelement'))return null;return{image:CKEDITOR.TRISTATE_OFF};});}});CKEDITOR.config.image_removeLinkByEmptyURL=true;
