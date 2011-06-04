/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.skins.add('office2003',(function(){return{editor:{css:['editor.css']},dialog:{css:['dialog.css']},separator:{canGroup:false},templates:{css:['templates.css']},margins:[0,14,18,14]};})());(function(){CKEDITOR.dialog?a():CKEDITOR.on('dialogPluginReady',a);function a(){CKEDITOR.dialog.on('resize',function(b){var c=b.data,d=c.width,e=c.height,f=c.dialog,g=f.parts.contents;if(c.skin!='office2003')return;g.setStyles({width:d+'px',height:e+'px'});if(!CKEDITOR.env.ie||CKEDITOR.env.ie9Compat)return;var h=function(){var i=f.parts.dialog.getChild([0,0,0]),j=i.getChild(0),k=j.getSize('width');e+=j.getChild(0).getSize('height')+1;var l=i.getChild(2);l.setSize('width',k);l=i.getChild(7);l.setSize('width',k-28);l=i.getChild(4);l.setSize('height',e);l=i.getChild(5);l.setSize('height',e);};setTimeout(h,100);if(b.editor.lang.dir=='rtl')setTimeout(h,1000);});};})();
