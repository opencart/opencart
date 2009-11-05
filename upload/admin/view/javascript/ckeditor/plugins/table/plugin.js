/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add('table',{init:function(a){var b=CKEDITOR.plugins.table,c=a.lang.table;a.addCommand('table',new CKEDITOR.dialogCommand('table'));a.addCommand('tableProperties',new CKEDITOR.dialogCommand('tableProperties'));a.ui.addButton('Table',{label:c.toolbar,command:'table'});CKEDITOR.dialog.add('table',this.path+'dialogs/table.js');CKEDITOR.dialog.add('tableProperties',this.path+'dialogs/table.js');if(a.addMenuItems)a.addMenuItems({table:{label:c.menu,command:'tableProperties',group:'table',order:5},tabledelete:{label:c.deleteTable,command:'tableDelete',group:'table',order:1}});if(a.contextMenu)a.contextMenu.addListener(function(d,e){if(!d)return null;var f=d.is('table')||d.hasAscendant('table');if(f)return{tabledelete:CKEDITOR.TRISTATE_OFF,table:CKEDITOR.TRISTATE_OFF};return null;});}});
