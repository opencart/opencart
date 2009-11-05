/*
 * CKFinder
 * ========
 * http://ckfinder.com
 * Copyright (C) 2007-2009, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 *
 * $Revision: 565 $
 */

var CKFinder = function( basePath, width, height, selectFunction )
{
	// The URL path for the installation folder of CKFinder (default = "/ckfinder/").
	this.BasePath = basePath || CKFinder.DEFAULT_BASEPATH ;

	// The CKFinder width (ex: 600, '80%') (default = "100%").
	this.Width	= width || '100%' ;

	// The CKFinder height (ex: 500, '100%') (default = 400).
	this.Height	= height || 400 ;

	// An optional function to be called when the user selects a file in CKFinder.
	this.SelectFunction = selectFunction || null ;
	
	// (Optional) argument of type string that will be passed to the "SelectFunction".
	this.SelectFunctionData = null ;

	// An optional function to be called when the user selects a thumbnail in CKFinder.
	this.SelectThumbnailFunction = selectFunction || null ;

	// (Optional) argument of type string that will be passed to the "SelectThumbnailFunction".
	this.SelectThumbnailFunctionData = null ;

	// If set to true, "Select thumbnail" item will not appear in the context menu.
	this.DisableThumbnailSelection = false ;

	// The name of the CSS class rule assigned to the CKFinder frame (default = "CKFinderFrame").
	this.ClassName = null || 'CKFinderFrame' ;

	// Resource Type and the name of the startup folder, separated with a colon (i.e. "Files:/", "Images:/cars/").
	this.StartupPath = null ;
	
	// Used only when StartupPath is set. If set to true, the initial folder will be opened automatically on startup.
	this.StartupFolderExpanded = false ;
	
	// If set to true, the last opened folder will be opened automatically on startup (if StartupPath is not set).
	this.RememberLastFolder = true ;
	
	// Used to identify the CKFinder object, optional.
	// If set, the Id variable will be passed to the server connector on each request.
	// When RememberLastFolder is enabled and the "Id" is set, CKFinder will remember the last directory in a separate cookie.
	this.Id = null ;
	
	// The server language of the connector
	this.ConnectorLanguage = 'php' ;
}

CKFinder.DEFAULT_BASEPATH = '/ckfinder/' ;

CKFinder.prototype = {

	// Renders CKFinder in the current document.
	Create : function()
	{
		document.write( this.CreateHtml() ) ;
	},

	// Gets the HTML needed to create a CKFinder instance.
	CreateHtml : function()
	{
		var className = this.ClassName ;
		if ( className && className.length > 0 )
			className = ' class="' + className + '"' ;

		var id = this.Id ;
		if ( id && id.length > 0 )
			id = ' id="' + id + '"' ;
			
		return '<iframe src="' + this._BuildUrl() + '" width="' + this.Width + '" ' +
			'height="' + this.Height + '"' + className + id + ' frameborder="0" scrolling="no"></iframe>' ;
	},

	// Opens CKFinder in a popup. The "width" and "height" parameters accept
	// numbers (pixels) or percent (of screen size) values.
	Popup : function( width, height )
	{
		width = width || '80%' ;
		height = height || '70%' ;

		if ( typeof width == 'string' && width.length > 1 && width.substr( width.length - 1, 1 ) == '%' )
			width = parseInt( window.screen.width * parseInt( width ) / 100 ) ;

		if ( typeof height == 'string' && height.length > 1 && height.substr( height.length - 1, 1 ) == '%' )
			height = parseInt( window.screen.height * parseInt( height ) / 100 ) ;

		if ( width < 200 )
			width = 200 ;

		if ( height < 200 )
			height = 200 ;

		var top = parseInt( ( window.screen.height - height ) / 2 ) ;
		var left = parseInt( ( window.screen.width  - width ) / 2 ) ;

		var options = 'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes' +
			',width='  + width +
			',height=' + height +
			',top='  + top +
			',left=' + left ;

		var popupWindow = window.open( '', 'CKFinderPopup', options, true ) ;

		// Blocked by a popup blocker.
		if ( !popupWindow )
			return false ;

		var url = this._BuildUrl().replace(/&amp;/g, '&');
		try
		{
			popupWindow.moveTo( left, top ) ;
			popupWindow.resizeTo( width, height ) ;
			popupWindow.focus() ;
			popupWindow.location.href = url ;
		}
		catch (e)
		{
			popupWindow = window.open( url, 'CKFinderPopup', options, true ) ;
		}

		return true ;
	},

	_BuildUrl : function( url )
	{
		var url = url || this.BasePath ;
		var qs = "" ;

		if ( !url || url.length == 0 )
			url = CKFinder.DEFAULT_BASEPATH ;

		if ( url.substr( url.length - 1, 1 ) != '/' )
			url = url + '/' ;

		url += 'ckfinder.html' ;

		if ( this.SelectFunction )
		{
			var functionName = this.SelectFunction ;
			if ( typeof functionName == 'function' )
				functionName = functionName.toString().match( /function ([^(]+)/ )[1] ;

			qs += '?action=js&amp;func=' + functionName ;
		}

		if ( this.SelectFunctionData )
		{
			qs += qs ? '&amp;' : '?' ;
			qs += 'data=' + encodeURIComponent( this.SelectFunctionData ) ;
		}

		if ( this.DisableThumbnailSelection )
		{
			qs += qs ? "&amp;" : "?" ;
			qs += 'dts=1' ;
		}
		else if ( this.SelectThumbnailFunction || this.SelectFunction )
		{
			var functionName = this.SelectThumbnailFunction || this.SelectFunction ;
			if ( typeof functionName == 'function' )
				functionName = functionName.toString().match( /function ([^(]+)/ )[1] ;

			qs += qs ? "&amp;" : "?" ;
			qs += 'thumbFunc=' + functionName ;
			
			if ( this.SelectThumbnailFunctionData )
				qs += '&amp;tdata=' + encodeURIComponent( this.SelectThumbnailFunctionData ) ;
			else if ( !this.SelectThumbnailFunction && this.SelectFunctionData )
				qs += '&amp;tdata=' + encodeURIComponent( this.SelectFunctionData ) ;
		}

		if ( this.StartupPath )
		{
			qs += qs ? "&amp;" : "?" ;
			qs += "start=" + encodeURIComponent( this.StartupPath + ( this.StartupFolderExpanded ? ':1' : ':0' ) ) ;
		}

		if ( !this.RememberLastFolder )
		{
			qs += qs ? "&amp;" : "?" ;
			qs += "rlf=0" ;
		}

		if ( this.Id )
		{
			qs += qs ? "&amp;" : "?" ;
			qs += "id=" + encodeURIComponent( this.Id ) ;
		}
		
		return url + qs ;
	}

} ;

// Static "Create".
// Accepts four arguments to set the most basic properties of CKFinder.
// Example:	
// 		CKFinder.Create( '/ckfinder/', '100%', 400 ) ;
// It is possible to pass an object with selected properties as the only argument.
// Example:
//		CKFinder.Create( { BasePath : '/ckfinder/', Height : '400' } ) ;
CKFinder.Create = function( basePath, width, height, selectFunction )
{
	var ckfinder ;
	
	if ( basePath != null && typeof( basePath ) == 'object' )
	{
		ckfinder = new CKFinder( ) ;
		for ( var _property in basePath )
			ckfinder[_property] = basePath[_property] ;
	}
	else
		ckfinder = new CKFinder( basePath, width, height, selectFunction ) ;

	ckfinder.Create() ;
}

// Static "Popup".
// Accepts four arguments to set the most basic properties of CKFinder.
// Example:	
//		CKFinder.Popup( '/ckfinder/', '100%', 400 ) ;
// It is possible to pass an object with selected properties as the only argument.
// Example:
//		CKFinder.Popup( { BasePath : '/ckfinder/', Height : '400' } ) ;
CKFinder.Popup = function( basePath, width, height, selectFunction )
{
	var ckfinder ;
	
	if ( basePath != null && typeof( basePath ) == 'object' )
	{
		ckfinder = new CKFinder( ) ;
		for ( var _property in basePath )
			ckfinder[_property] = basePath[_property] ;
	}
	else
		ckfinder = new CKFinder( basePath, width, height, selectFunction ) ;

	ckfinder.Popup( width, height ) ;
}

// Static "SetupFCKeditor".
// It is possible to pass an object with selected properties as a second argument.
CKFinder.SetupFCKeditor = function( editorObj, basePath, imageType, flashType )
{
	var ckfinder ;

	if ( basePath != null && typeof( basePath ) == 'object' )
	{
		ckfinder = new CKFinder( ) ;
		for ( var _property in basePath )
		{
			ckfinder[_property] = basePath[_property] ;
			
			if ( _property == 'Width' )
			{
				var width = ckfinder[_property] || 800 ;
				if ( typeof width == 'string' && width.length > 1 && width.substr( width.length - 1, 1 ) == '%' )
					width = parseInt( window.screen.width * parseInt( width ) / 100 ) ;

				editorObj.Config['LinkBrowserWindowWidth'] = width ;
				editorObj.Config['ImageBrowserWindowWidth'] = width ;
				editorObj.Config['FlashBrowserWindowWidth'] = width ;
			}
			else if ( _property == 'Height' )
			{
				var height = ckfinder[_property] || 600 ;
				if ( typeof height == 'string' && height.length > 1 && height.substr( height.length - 1, 1 ) == '%' )
					height = parseInt( window.screen.height * parseInt( height ) / 100 ) ;

				editorObj.Config['LinkBrowserWindowHeight'] = height ;
				editorObj.Config['ImageBrowserWindowHeight'] = height ;
				editorObj.Config['FlashBrowserWindowHeight'] = height ;
			}
		}
	}
	else
		ckfinder = new CKFinder( basePath ) ;

	var url = ckfinder.BasePath ;

	// If it is a path relative to the current page.
	if ( url.substr( 0, 1 ) != '/' )
		url = document.location.pathname.substring( 0, document.location.pathname.lastIndexOf('/') + 1 ) + url ;

	url = ckfinder._BuildUrl( url ) ;
	var qs = ( url.indexOf( "?" ) !== -1 ) ? "&amp;" : "?" ;

	editorObj.Config['LinkBrowserURL'] = url ;
	editorObj.Config['ImageBrowserURL'] = url + qs + 'type=' + ( imageType || 'Images' ) ;
	editorObj.Config['FlashBrowserURL'] = url + qs + 'type=' + ( flashType || 'Flash' ) ;

	var dir = url.substring(0, 1 + url.lastIndexOf("/"));
	editorObj.Config['LinkUploadURL'] = dir + "core/connector/" + ckfinder.ConnectorLanguage + "/connector." 
		+ ckfinder.ConnectorLanguage + "?command=QuickUpload&type=Files" ;
	editorObj.Config['ImageUploadURL'] = dir + "core/connector/" + ckfinder.ConnectorLanguage + "/connector." 
		+ ckfinder.ConnectorLanguage + "?command=QuickUpload&type=" + ( imageType || 'Images' ) ;
	editorObj.Config['FlashUploadURL'] = dir + "core/connector/" + ckfinder.ConnectorLanguage + "/connector." 
		+ ckfinder.ConnectorLanguage + "?command=QuickUpload&type=" + ( flashType || 'Flash' ) ;
}

//Static "SetupCKEditor".
//It is possible to pass an object with selected properties as a second argument.
//If the editorObj is null, then CKFinder will integrate with all CKEditor instances.
CKFinder.SetupCKEditor = function( editorObj, basePath, imageType, flashType )
{
	if ( editorObj === null )
	{
		// Setup current instances
		for ( var editorName in CKEDITOR.instances )
			CKFinder.SetupCKEditor( CKEDITOR.instances[editorName], basePath, imageType, flashType ) ;

		// Future instances
		CKEDITOR.on( 'instanceCreated', function(e) {
			CKFinder.SetupCKEditor( e.editor, basePath, imageType, flashType ) ;
		});

		return;
	}

	var ckfinder ;

	if ( basePath != null && typeof( basePath ) == 'object' )
	{
		ckfinder = new CKFinder( ) ;
		for ( var _property in basePath )
		{
			ckfinder[_property] = basePath[_property] ;	

			if ( _property == 'Width' )
			{
				var width = ckfinder[_property] || 800 ;
				if ( typeof width == 'string' && width.length > 1 && width.substr( width.length - 1, 1 ) == '%' )
					width = parseInt( window.screen.width * parseInt( width ) / 100 ) ;

				editorObj.config.filebrowserWindowWidth = width ;
			}
			else if ( _property == 'Height' )
			{
				var height = ckfinder[_property] || 600 ;
				if ( typeof height == 'string' && height.length > 1 && height.substr( height.length - 1, 1 ) == '%' )
					height = parseInt( window.screen.height * parseInt( height ) / 100 ) ;

				editorObj.config.filebrowserWindowHeight = width ;
			}
		}
	}
	else
		ckfinder = new CKFinder( basePath ) ;

	var url = ckfinder.BasePath ;

	// If it is a path relative to the current page.
	if ( url.substr( 0, 1 ) != '/' )
		url = document.location.pathname.substring( 0, document.location.pathname.lastIndexOf('/') + 1 ) + url ;

	url = ckfinder._BuildUrl( url ) ;
	var qs = ( url.indexOf( "?" ) !== -1 ) ? "&amp;" : "?" ;

	editorObj.config.filebrowserBrowseUrl = url ;
	editorObj.config.filebrowserImageBrowseUrl = url + qs + 'type=' + ( imageType || 'Images' ) ;
	editorObj.config.filebrowserFlashBrowseUrl = url + qs + 'type=' + ( flashType || 'Flash' ) ;

	var dir = url.substring(0, 1 + url.lastIndexOf("/"));
	editorObj.config.filebrowserUploadUrl = dir + "core/connector/" + ckfinder.ConnectorLanguage + "/connector." 
		+ ckfinder.ConnectorLanguage + "?command=QuickUpload&type=Files" ;
	editorObj.config.filebrowserImageUploadUrl = dir + "core/connector/" + ckfinder.ConnectorLanguage + "/connector." 
		+ ckfinder.ConnectorLanguage + "?command=QuickUpload&type=" + ( imageType || 'Images' ) ;
	editorObj.config.filebrowserFlashUploadUrl = dir + "core/connector/" + ckfinder.ConnectorLanguage + "/connector." 
		+ ckfinder.ConnectorLanguage + "?command=QuickUpload&type=" + ( flashType || 'Flash' ) ;
}
