/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function(){CKEDITOR.plugins.colordialog={init:function(a){a.addCommand('colordialog',new CKEDITOR.dialogCommand('colordialog'));CKEDITOR.dialog.add('colordialog',this.path+'dialogs/colordialog.js');}};CKEDITOR.plugins.add('colordialog',CKEDITOR.plugins.colordialog);})();
