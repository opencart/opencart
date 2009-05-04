/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2008 Frederico Caldeira Knabben
 *
 * == BEGIN LICENSE ==
 *
 * Licensed under the terms of any of the following licenses at your
 * choice:
 *
 *  - GNU General Public License Version 2 or later (the "GPL")
 *    http://www.gnu.org/licenses/gpl.html
 *
 *  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 *    http://www.gnu.org/licenses/lgpl.html
 *
 *  - Mozilla Public License Version 1.1 or later (the "MPL")
 *    http://www.mozilla.org/MPL/MPL-1.1.html
 *
 * == END LICENSE ==
 *
 * Active selection functions. (IE specific implementation)
 */

// Get the selection type.
FCKSelection.GetType = function()
{
	// It is possible that we can still get a text range object even when type=='None' is returned by IE.
	// So we'd better check the object returned by createRange() rather than by looking at the type.
	try
	{
		var ieType = FCKSelection.GetSelection().type ;
		if ( ieType == 'Control' || ieType == 'Text' )
			return ieType ;

		if ( this.GetSelection().createRange().parentElement )
			return 'Text' ;
	}
	catch(e)
	{
		// Nothing to do, it will return None properly.
	}

	return 'None' ;
} ;

// Retrieves the selected element (if any), just in the case that a single
// element (object like and image or a table) is selected.
FCKSelection.GetSelectedElement = function()
{
	if ( this.GetType() == 'Control' )
	{
		var oRange = this.GetSelection().createRange() ;

		if ( oRange && oRange.item )
			return this.GetSelection().createRange().item(0) ;
	}
	return null ;
} ;

FCKSelection.GetParentElement = function()
{
	switch ( this.GetType() )
	{
		case 'Control' :
			var el = FCKSelection.GetSelectedElement() ;
			return el ? el.parentElement : null ;

		case 'None' :
			return null ;

		default :
			return this.GetSelection().createRange().parentElement() ;
	}
} ;

FCKSelection.GetBoundaryParentElement = function( startBoundary )
{
	switch ( this.GetType() )
	{
		case 'Control' :
			var el = FCKSelection.GetSelectedElement() ;
			return el ? el.parentElement : null ;

		case 'None' :
			return null ;

		default :
			var doc = FCK.EditorDocument ;

			var range = doc.selection.createRange() ;
			range.collapse( startBoundary !== false ) ;

			var el = range.parentElement() ;

			// It may happen that range is comming from outside "doc", so we
			// must check it (#1204).
			return FCKTools.GetElementDocument( el ) == doc ? el : null ;
	}
} ;

FCKSelection.SelectNode = function( node )
{
	FCK.Focus() ;
	this.GetSelection().empty() ;
	var oRange ;
	try
	{
		// Try to select the node as a control.
		oRange = FCK.EditorDocument.body.createControlRange() ;
		oRange.addElement( node ) ;
	}
	catch(e)
	{
		// If failed, select it as a text range.
		oRange = FCK.EditorDocument.body.createTextRange() ;
		oRange.moveToElementText( node ) ;
	}

	oRange.select() ;
} ;

FCKSelection.Collapse = function( toStart )
{
	FCK.Focus() ;
	if ( this.GetType() == 'Text' )
	{
		var oRange = this.GetSelection().createRange() ;
		oRange.collapse( toStart == null || toStart === true ) ;
		oRange.select() ;
	}
} ;

// The "nodeTagName" parameter must be Upper Case.
FCKSelection.HasAncestorNode = function( nodeTagName )
{
	var oContainer ;

	if ( this.GetSelection().type == "Control" )
	{
		oContainer = this.GetSelectedElement() ;
	}
	else
	{
		var oRange  = this.GetSelection().createRange() ;
		oContainer = oRange.parentElement() ;
	}

	while ( oContainer )
	{
		if ( oContainer.tagName == nodeTagName ) return true ;
		oContainer = oContainer.parentNode ;
	}

	return false ;
} ;

// The "nodeTagName" parameter must be UPPER CASE.
FCKSelection.MoveToAncestorNode = function( nodeTagName )
{
	var oNode, oRange ;

	if ( ! FCK.EditorDocument )
		return null ;

	if ( this.GetSelection().type == "Control" )
	{
		oRange = this.GetSelection().createRange() ;
		for ( i = 0 ; i < oRange.length ; i++ )
		{
			if (oRange(i).parentNode)
			{
				oNode = oRange(i).parentNode ;
				break ;
			}
		}
	}
	else
	{
		oRange  = this.GetSelection().createRange() ;
		oNode = oRange.parentElement() ;
	}

	while ( oNode && oNode.nodeName != nodeTagName )
		oNode = oNode.parentNode ;

	return oNode ;
} ;

FCKSelection.Delete = function()
{
	// Gets the actual selection.
	var oSel = this.GetSelection() ;

	// Deletes the actual selection contents.
	if ( oSel.type.toLowerCase() != "none" )
	{
		oSel.clear() ;
	}

	return oSel ;
} ;

/**
 * Returns the native selection object.
 */
FCKSelection.GetSelection = function()
{
	this.Restore() ;
	return FCK.EditorDocument.selection ;
}

FCKSelection.Save = function()
{
	// Ensures the editor has the selection focus. (#1801)
	FCK.Focus() ;

	var editorDocument = FCK.EditorDocument ;

	if ( !editorDocument )
		return ;

	var selection = editorDocument.selection ;
	var range ;

	if ( selection )
	{
		range = selection.createRange() ;

		// Ensure that the range comes from the editor document.
		if ( range )
		{
			if ( range.parentElement && FCKTools.GetElementDocument( range.parentElement() ) != editorDocument )
				range = null ;
			else if ( range.item && FCKTools.GetElementDocument( range.item(0) )!= editorDocument )
				range = null ;
		}
	}

	this.SelectionData = range ;
}

FCKSelection._GetSelectionDocument = function( selection )
{
	var range = selection.createRange() ;
	if ( !range )
		return null;
	else if ( range.item )
		return FCKTools.GetElementDocument( range.item( 0 ) ) ;
	else
		return FCKTools.GetElementDocument( range.parentElement() ) ;
}

FCKSelection.Restore = function()
{
	if ( this.SelectionData )
	{
		FCK.IsSelectionChangeLocked = true ;

		try
		{
			// Don't repeat the restore process if the editor document is already selected.
			if ( this._GetSelectionDocument( FCK.EditorDocument.selection ) == FCK.EditorDocument )
				return ;
			this.SelectionData.select() ;
		}
		catch ( e ) {}

		FCK.IsSelectionChangeLocked = false ;
	}
}

FCKSelection.Release = function()
{
	delete this.SelectionData ;
}
