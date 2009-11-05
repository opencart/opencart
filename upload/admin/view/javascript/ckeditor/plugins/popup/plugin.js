/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add('popup');CKEDITOR.tools.extend(CKEDITOR.editor.prototype,{popup:function(a,b,c){b=b||'80%';c=c||'70%';if(typeof b=='string'&&b.length>1&&b.substr(b.length-1,1)=='%')b=parseInt(window.screen.width*parseInt(b,10)/100,10);if(typeof c=='string'&&c.length>1&&c.substr(c.length-1,1)=='%')c=parseInt(window.screen.height*parseInt(c,10)/100,10);if(b<640)b=640;if(c<420)c=420;var d=parseInt((window.screen.height-c)/(2),10),e=parseInt((window.screen.width-b)/(2),10),f='location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,width='+b+',height='+c+',top='+d+',left='+e,g=window.open('',null,f,true);if(!g)return false;try{g.moveTo(e,d);g.resizeTo(b,c);g.focus();g.location.href=a;}catch(h){g=window.open(a,null,f,true);}return true;}});
