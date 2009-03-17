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
 * Component that creates floating panels. It is used by many
 * other components, like the toolbar items, context menu, etc...
 */

var FCKPanel = function( parentWindow )
{
	this.IsRTL			= ( FCKLang.Dir == 'rtl' ) ;
	this.IsContextMenu	= false ;
	this._LockCounter	= 0 ;

	this._Window = parentWindow || window ;

	var oDocument ;

	if ( FCKBrowserInfo.IsIE )
	{
		// Create the Popup that will hold the panel.
		// The popup has to be created before playing with domain hacks, see #1666.
		this._Popup	= this._Window.createPopup() ;

		// this._Window cannot be accessed while playing with domain hacks, but local variable is ok.
		// See #1666.
		var pDoc = this._Window.document ;

		// This is a trick to IE6 (not IE7). The original domain must be set
		// before creating the popup, so we are able to take a refence to the
		// document inside of it, and the set the proper domain for it. (#123)
		if ( FCK_IS_CUSTOM_DOMAIN && !FCKBrowserInfo.IsIE7 )
		{
			pDoc.domain = FCK_ORIGINAL_DOMAIN ;
			document.domain = FCK_ORIGINAL_DOMAIN ;
		}

		oDocument = this.Document = this._Popup.document ;

		// Set the proper domain inside the popup.
		if ( FCK_IS_CUSTOM_DOMAIN )
		{
			oDocument.domain = FCK_RUNTIME_DOMAIN ;
			pDoc.domain = FCK_RUNTIME_DOMAIN ;
			document.domain = FCK_RUNTIME_DOMAIN ;
		}

		FCK.IECleanup.AddItem( this, FCKPanel_Cleanup ) ;
	}
	else
	{
		var oIFrame = this._IFrame = this._Window.document.createElement('iframe') ;
		oIFrame.src					= 'javascript:void(0)' ;
		oIFrame.allowTransparency	= true ;
		oIFrame.frameBorder			= '0' ;
		oIFrame.scrolling			= 'no' ;
		oIFrame.width = oIFrame.height = 0 ;
		FCKDomTools.SetElementStyles( oIFrame,
			{
				position	: 'absolute',
				zIndex		: FCKConfig.FloatingPanelsZIndex
			} ) ;

		this._Window.document.body.appendChild( oIFrame ) ;

		var oIFrameWindow = oIFrame.contentWindow ;

		oDocument = this.Document = oIFrameWindow.document ;

		// Workaround for Safari 12256. Ticket #63
		var sBase = '' ;
		if ( FCKBrowserInfo.IsSafari )
			sBase = '<base href="' + window.document.location + '">' ;

		// Initialize the IFRAME document body.
		oDocument.open() ;
		oDocument.write( '<html><head>' + sBase + '<\/head><body style="margin:0px;padding:0px;"><\/body><\/html>' ) ;
		oDocument.close() ;

		if( FCKBrowserInfo.IsAIR )
			FCKAdobeAIR.Panel_Contructor( oDocument, window.document.location ) ;

		FCKTools.AddEventListenerEx( oIFrameWindow, 'focus', FCKPanel_Window_OnFocus, this ) ;
		FCKTools.AddEventListenerEx( oIFrameWindow, 'blur', FCKPanel_Window_OnBlur, this ) ;
	}

	oDocument.dir = FCKLang.Dir ;

	FCKTools.AddEventListener( oDocument, 'contextmenu', FCKTools.CancelEvent ) ;


	// Create the main DIV that is used as the panel base.
	this.MainNode = oDocument.body.appendChild( oDocument.createElement('DIV') ) ;

	// The "float" property must be set so Firefox calculates the size correctly.
	this.MainNode.style.cssFloat = this.IsRTL ? 'right' : 'left' ;
}


FCKPanel.prototype.AppendStyleSheet = function( styleSheet )
{
	FCKTools.AppendStyleSheet( this.Document, styleSheet ) ;
}

FCKPanel.prototype.Preload = function( x, y, relElement )
{
	// The offsetWidth and offsetHeight properties are not available if the
	// element is not visible. So we must "show" the popup with no size to
	// be able to use that values in the second call (IE only).
	if ( this._Popup )
		this._Popup.show( x, y, 0, 0, relElement ) ;
}

FCKPanel.prototype.Show = function( x, y, relElement, width, height )
{
	var iMainWidth ;
	var eMainNode = this.MainNode ;

	if ( this._Popup )
	{
		// The offsetWidth and offsetHeight properties are not available if the
		// element is not visible. So we must "show" the popup with no size to
		// be able to use that values in the second call.
		this._Popup.show( x, y, 0, 0, relElement ) ;

		// The following lines must be place after the above "show", otherwise it
		// doesn't has the desired effect.
		FCKDomTools.SetElementStyles( eMainNode,
			{
				width	: width ? width + 'px' : '',
				height	: height ? height + 'px' : ''
			} ) ;

		iMainWidth = eMainNode.offsetWidth ;

		if ( this.IsRTL )
		{
			if ( this.IsContextMenu )
				x  = x - iMainWidth + 1 ;
			else if ( relElement )
				x  = ( x * -1 ) + relElement.offsetWidth - iMainWidth ;
		}

		// Second call: Show the Popup at the specified location, with the correct size.
		this._Popup.show( x, y, iMainWidth, eMainNode.offsetHeight, relElement ) ;

		if ( this.OnHide )
		{
			if ( this._Timer )
				CheckPopupOnHide.call( this, true ) ;

			this._Timer = FCKTools.SetInterval( CheckPopupOnHide, 100, this ) ;
		}
	}
	else
	{
		// Do not fire OnBlur while the panel is opened.
		if ( typeof( FCK.ToolbarSet.CurrentInstance.FocusManager ) != 'undefined' )
			FCK.ToolbarSet.CurrentInstance.FocusManager.Lock() ;

		if ( this.ParentPanel )
		{
			this.ParentPanel.Lock() ;

			// Due to a bug on FF3, we must ensure that the parent panel will
			// blur (#1584).
			FCKPanel_Window_OnBlur( null, this.ParentPanel ) ;
		}

		// Toggle the iframe scrolling attribute to prevent the panel
		// scrollbars from disappearing in FF Mac. (#191)
		if ( FCKBrowserInfo.IsGecko && FCKBrowserInfo.IsMac )
		{
			this._IFrame.scrolling = '' ;
			FCKTools.RunFunction( function(){ this._IFrame.scrolling = 'no'; }, this ) ;
		}

		// Be sure we'll not have more than one Panel opened at the same time.
		// Do not unlock focus manager here because we're displaying another floating panel
		// instead of returning the editor to a "no panel" state (Bug #1514).
		if ( FCK.ToolbarSet.CurrentInstance.GetInstanceObject( 'FCKPanel' )._OpenedPanel &&
				FCK.ToolbarSet.CurrentInstance.GetInstanceObject( 'FCKPanel' )._OpenedPanel != this )
			FCK.ToolbarSet.CurrentInstance.GetInstanceObject( 'FCKPanel' )._OpenedPanel.Hide( false, true ) ;

		FCKDomTools.SetElementStyles( eMainNode,
			{
				width	: width ? width + 'px' : '',
				height	: height ? height + 'px' : ''
			} ) ;

		iMainWidth = eMainNode.offsetWidth ;

		if ( !width )	this._IFrame.width	= 1 ;
		if ( !height )	this._IFrame.height	= 1 ;

		// This is weird... but with Firefox, we must get the offsetWidth before
		// setting the _IFrame size (which returns "0"), and then after that,
		// to return the correct width. Remove the first step and it will not
		// work when the editor is in RTL.
		//
		// The "|| eMainNode.firstChild.offsetWidth" part has been added
		// for Opera compatibility (see #570).
		iMainWidth = eMainNode.offsetWidth || eMainNode.firstChild.offsetWidth ;

		// Base the popup coordinates upon the coordinates of relElement.
		var oPos = FCKTools.GetDocumentPosition( this._Window,
			relElement.nodeType == 9 ?
				( FCKTools.IsStrictMode( relElement ) ? relElement.documentElement : relElement.body ) :
				relElement ) ;

		// Minus the offsets provided by any positioned parent element of the panel iframe.
		var positionedAncestor = FCKDomTools.GetPositionedAncestor( this._IFrame.parentNode ) ;
		if ( positionedAncestor )
		{
			var nPos = FCKTools.GetDocumentPosition( FCKTools.GetElementWindow( positionedAncestor ), positionedAncestor ) ;
			oPos.x -= nPos.x ;
			oPos.y -= nPos.y ;
		}

		if ( this.IsRTL && !this.IsContextMenu )
			x = ( x * -1 ) ;

		x += oPos.x ;
		y += oPos.y ;

		if ( this.IsRTL )
		{
			if ( this.IsContextMenu )
				x  = x - iMainWidth + 1 ;
			else if ( relElement )
				x  = x + relElement.offsetWidth - iMainWidth ;
		}
		else
		{
			var oViewPaneSize = FCKTools.GetViewPaneSize( this._Window ) ;
			var oScrollPosition = FCKTools.GetScrollPosition( this._Window ) ;

			var iViewPaneHeight	= oViewPaneSize.Height + oScrollPosition.Y ;
			var iViewPaneWidth	= oViewPaneSize.Width + oScrollPosition.X ;

			if ( ( x + iMainWidth ) > iViewPaneWidth )
				x -= x + iMainWidth - iViewPaneWidth ;

			if ( ( y + eMainNode.offsetHeight ) > iViewPaneHeight )
				y -= y + eMainNode.offsetHeight - iViewPaneHeight ;
		}

		// Set the context menu DIV in the specified location.
		FCKDomTools.SetElementStyles( this._IFrame,
			{
				left	: x + 'px',
				top		: y + 'px'
			} ) ;

		// Move the focus to the IFRAME so we catch the "onblur".
		this._IFrame.contentWindow.focus() ;
		this._IsOpened = true ;

		var me = this ;
		this._resizeTimer = setTimeout( function()
			{
				var iWidth = eMainNode.offsetWidth || eMainNode.firstChild.offsetWidth ;
				var iHeight = eMainNode.offsetHeight ;
				me._IFrame.width = iWidth ;
				me._IFrame.height = iHeight ;

			}, 0 ) ;

		FCK.ToolbarSet.CurrentInstance.GetInstanceObject( 'FCKPanel' )._OpenedPanel = this ;
	}

	FCKTools.RunFunction( this.OnShow, this ) ;
}

FCKPanel.prototype.Hide = function( ignoreOnHide, ignoreFocusManagerUnlock )
{
	if ( this._Popup )
		this._Popup.hide() ;
	else
	{
		if ( !this._IsOpened || this._LockCounter > 0 )
			return ;

		// Enable the editor to fire the "OnBlur".
		if ( typeof( FCKFocusManager ) != 'undefined' && !ignoreFocusManagerUnlock )
			FCKFocusManager.Unlock() ;

		// It is better to set the sizes to 0, otherwise Firefox would have
		// rendering problems.
		this._IFrame.width = this._IFrame.height = 0 ;

		this._IsOpened = false ;

		if ( this._resizeTimer )
		{
			clearTimeout( this._resizeTimer ) ;
			this._resizeTimer = null ;
		}

		if ( this.ParentPanel )
			this.ParentPanel.Unlock() ;

		if ( !ignoreOnHide )
			FCKTools.RunFunction( this.OnHide, this ) ;
	}
}

FCKPanel.prototype.CheckIsOpened = function()
{
	if ( this._Popup )
		return this._Popup.isOpen ;
	else
		return this._IsOpened ;
}

FCKPanel.prototype.CreateChildPanel = function()
{
	var oWindow = this._Popup ? FCKTools.GetDocumentWindow( this.Document ) : this._Window ;

	var oChildPanel = new FCKPanel( oWindow ) ;
	oChildPanel.ParentPanel = this ;

	return oChildPanel ;
}

FCKPanel.prototype.Lock = function()
{
	this._LockCounter++ ;
}

FCKPanel.prototype.Unlock = function()
{
	if ( --this._LockCounter == 0 && !this.HasFocus )
		this.Hide() ;
}

/* Events */

function FCKPanel_Window_OnFocus( e, panel )
{
	panel.HasFocus = true ;
}

function FCKPanel_Window_OnBlur( e, panel )
{
	panel.HasFocus = false ;

	if ( panel._LockCounter == 0 )
		FCKTools.RunFunction( panel.Hide, panel ) ;
}

function CheckPopupOnHide( forceHide )
{
	if ( forceHide || !this._Popup.isOpen )
	{
		window.clearInterval( this._Timer ) ;
		this._Timer = null ;

		FCKTools.RunFunction( this.OnHide, this ) ;
	}
}

function FCKPanel_Cleanup()
{
	this._Popup = null ;
	this._Window = null ;
	this.Document = null ;
	this.MainNode = null ;
}
