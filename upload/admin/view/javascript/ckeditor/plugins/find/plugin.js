/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add('find',{init:function(a){var b=CKEDITOR.plugins.find;a.ui.addButton('Find',{label:a.lang.findAndReplace.find,command:'find'});var c=a.addCommand('find',new CKEDITOR.dialogCommand('find'));c.canUndo=false;a.ui.addButton('Replace',{label:a.lang.findAndReplace.replace,command:'replace'});var d=a.addCommand('replace',new CKEDITOR.dialogCommand('replace'));d.canUndo=false;CKEDITOR.dialog.add('find',this.path+'dialogs/find.js');CKEDITOR.dialog.add('replace',this.path+'dialogs/find.js');},requires:['styles']});CKEDITOR.config.find_highlight={element:'span',styles:{'background-color':'#004',color:'#fff'}};
