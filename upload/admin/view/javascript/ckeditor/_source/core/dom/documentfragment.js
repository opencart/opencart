/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
/**
 * DocumentFragment is a "lightweight" or "minimal" Document object. It is
 * commonly used to extract a portion of a document's tree or to create a new
 * fragment of a document. Various operations may take DocumentFragment objects
 * as arguments and results in all the child nodes of the DocumentFragment being
 * moved to the child list of this node.
 *
 * @param {Object} ownerDocument
 */
CKEDITOR.dom.documentFragment = function( ownerDocument )
{
	ownerDocument = ownerDocument || CKEDITOR.document;
	this.$ = ownerDocument.$.createDocumentFragment();
};

CKEDITOR.tools.extend( CKEDITOR.dom.documentFragment.prototype,
	CKEDITOR.dom.element.prototype,
	{
		type : CKEDITOR.NODE_DOCUMENT_FRAGMENT,
		insertAfterNode : function( node )
		{
			node = node.$;
			node.parentNode.insertBefore( this.$, node.nextSibling );
		}
	},
	true,
	{
		'append' : 1,
		'appendBogus' : 1,
		'getFirst' : 1,
		'getLast' : 1,
		'appendTo' : 1,
		'moveChildren' : 1,
		'insertBefore' : 1,
		'insertAfterNode' : 1,
		'replace' : 1,
		'trim' : 1,
		'type' : 1,
		'ltrim' : 1,
		'rtrim' : 1,
		'getDocument' : 1,
		'getChildCount' : 1,
		'getChild' : 1,
		'getChildren' : 1
	} );
