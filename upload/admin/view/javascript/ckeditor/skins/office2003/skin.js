/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.skins.add('office2003',(function(){var a=[];if(CKEDITOR.env.ie&&CKEDITOR.env.version<7)a.push('icons.png','images/sprites_ie6.png','images/dialog_sides.gif');return{preload:a,editor:{css:['editor.css']},dialog:{css:['dialog.css']},templates:{css:['templates.css']},margins:[0,14,18,14]};})());if(CKEDITOR.dialog)CKEDITOR.dialog.on('resize',function(a){var b=a.data,c=b.width,d=b.height,e=b.dialog,f=!CKEDITOR.env.quirk;if(b.skin!='office2003')return;e.parts.contents.setStyles({width:c+'px',height:d+'px'});if(!CKEDITOR.env.ie)return;var g=function(){var h=e.parts.contents,i=h.getParent(),j=i.getParent(),k=j.getChild(2);k.setStyle('width',i.$.offsetWidth+'px');k=j.getChild(7);k.setStyle('width',i.$.offsetWidth-28+'px');k=j.getChild(4);k.setStyle('height',i.$.offsetHeight-31-14+'px');k=j.getChild(5);k.setStyle('height',i.$.offsetHeight-31-14+'px');};setTimeout(g,100);if(a.editor.lang.dir=='rtl')setTimeout(g,1000);});
