/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add('menubutton',{requires:['button','contextmenu'],beforeInit:function(a){a.ui.addHandler(CKEDITOR.UI_MENUBUTTON,CKEDITOR.ui.menuButton.handler);}});CKEDITOR.UI_MENUBUTTON=5;(function(){var a=function(b){var c=this._;if(c.state===CKEDITOR.TRISTATE_DISABLED)return;c.previousState=c.state;var d=c.menu;if(!d){d=c.menu=new CKEDITOR.plugins.contextMenu(b);d.onHide=CKEDITOR.tools.bind(function(){this.setState(c.previousState);},this);if(this.onMenu)d.addListener(this.onMenu);}if(c.on){d.hide();return;}this.setState(CKEDITOR.TRISTATE_ON);d.show(CKEDITOR.document.getById(this._.id),4);};CKEDITOR.ui.menuButton=CKEDITOR.tools.createClass({base:CKEDITOR.ui.button,$:function(b){var c=b.panel;delete b.panel;this.base(b);this.hasArrow=true;this.click=a;},statics:{handler:{create:function(b){return new CKEDITOR.ui.menuButton(b);}}}});})();
