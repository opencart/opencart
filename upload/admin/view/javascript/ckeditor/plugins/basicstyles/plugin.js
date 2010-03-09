/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add('basicstyles',{requires:['styles','button'],init:function(a){var b=function(e,f,g,h){var i=new CKEDITOR.style(h);a.attachStyleStateChange(i,function(j){a.getCommand(g).setState(j);});a.addCommand(g,new CKEDITOR.styleCommand(i));a.ui.addButton(e,{label:f,command:g});},c=a.config,d=a.lang;b('Bold',d.bold,'bold',c.coreStyles_bold);b('Italic',d.italic,'italic',c.coreStyles_italic);b('Underline',d.underline,'underline',c.coreStyles_underline);b('Strike',d.strike,'strike',c.coreStyles_strike);b('Subscript',d.subscript,'subscript',c.coreStyles_subscript);b('Superscript',d.superscript,'superscript',c.coreStyles_superscript);}});CKEDITOR.config.coreStyles_bold={element:'strong',overrides:'b'};CKEDITOR.config.coreStyles_italic={element:'em',overrides:'i'};CKEDITOR.config.coreStyles_underline={element:'u'};CKEDITOR.config.coreStyles_strike={element:'strike'};CKEDITOR.config.coreStyles_subscript={element:'sub'};CKEDITOR.config.coreStyles_superscript={element:'sup'};
