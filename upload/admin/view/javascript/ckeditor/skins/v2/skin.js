/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.skins.add('v2',(function(){var a=[];if(CKEDITOR.env.ie&&CKEDITOR.env.version<7)a.push('icons.png','images/sprites_ie6.png','images/dialog_sides.gif');return{preload:a,editor:{css:['editor.css']},dialog:{css:['dialog.css']},templates:{css:['templates.css']},margins:[0,14,18,14]};})());if(CKEDITOR.dialog)CKEDITOR.dialog.on('resize',function(a){var b=a.data,c=b.width,d=b.height,e=b.dialog,f=!CKEDITOR.env.quirk;if(b.skin!='v2')return;e.parts.contents.setStyles({width:c+'px',height:d+'px'});if(!CKEDITOR.env.ie)return;setTimeout(function(){var g=e.parts.contents,h=g.getParent(),i=h.getParent(),j=i.getChild(2);j.setStyle('width',h.$.offsetWidth+'px');j=i.getChild(7);j.setStyle('width',h.$.offsetWidth-28+'px');j=i.getChild(4);j.setStyle('height',h.$.offsetHeight-31-14+'px');j=i.getChild(5);j.setStyle('height',h.$.offsetHeight-31-14+'px');},100);});
