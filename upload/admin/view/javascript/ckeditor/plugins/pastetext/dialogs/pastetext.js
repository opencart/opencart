/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function(){CKEDITOR.dialog.add('pastetext',function(a){return{title:a.lang.pasteText.title,minWidth:CKEDITOR.env.ie&&CKEDITOR.env.quirks?368:350,minHeight:240,onShow:function(){this.getContentElement('general','content').getInputElement().setValue('');},onOk:function(){var b=this.getContentElement('general','content').getInputElement().getValue();this.getParentEditor().insertText(b);},contents:[{label:a.lang.common.generalTab,id:'general',elements:[{type:'html',id:'pasteMsg',html:'<div style="white-space:normal;width:340px;">'+a.lang.clipboard.pasteMsg+'</div>'},{type:'html',id:'content',style:'width:340px;height:170px',html:'<textarea style="width:346px;height:170px;resize: none;border:1px solid black;background-color:white"></textarea>',focus:function(){this.getElement().focus();}}]}]};});})();
