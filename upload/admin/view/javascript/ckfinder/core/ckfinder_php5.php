<?php
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
 */

define( 'CKFINDER_DEFAULT_BASEPATH', '/ckfinder/' ) ;

class CKFinder
{
	public $BasePath ;
	public $Width ;
	public $Height ;
	public $SelectFunction ;
	public $SelectFunctionData ;
	public $SelectThumbnailFunction ;
	public $SelectThumbnailFunctionData ;
	public $DisableThumbnailSelection = false ;
	public $ClassName = '' ;
	public $Id = '' ;
	public $StartupPath ;
	public $RememberLastFolder = true ;
	public $StartupFolderExpanded = false ;

	// PHP 5 Constructor
	function __construct( $basePath = CKFINDER_DEFAULT_BASEPATH, $width = '100%', $height = 400, $selectFunction = null )
	{
		$this->BasePath			= $basePath ;
		$this->Width			= $width ;
		$this->Height			= $height ;
		$this->SelectFunction	= $selectFunction ;
		$this->SelectThumbnailFunction	= $selectFunction ;
	}

	// Renders CKFinder in the current page.
	public function Create()
	{
		echo $this->CreateHtml() ;
	}

	// Gets the HTML needed to create a CKFinder instance.
	public function CreateHtml()
	{
		$className = $this->ClassName ;
		if ( !empty( $className ) )
			$className = ' class="' . $className . '"' ;

		$id = $this->Id ;
		if ( !empty( $id ) )
			$id = ' id="' . $id . '"' ;
			
		return '<iframe src="' . $this->_BuildUrl() . '" width="' . $this->Width . '" ' .
			'height="' . $this->Height . '"' . $className . $id . ' frameborder="0" scrolling="no"></iframe>' ;
	}

	private function _BuildUrl( $url = "" )
	{
		if ( !$url )
			$url = $this->BasePath ;

		$qs = "" ;

		if ( empty( $url ) )
			$url = CKFINDER_DEFAULT_BASEPATH ;

		if ( $url[ strlen( $url ) - 1 ] != '/' )
			$url = $url . '/' ;

		$url .= 'ckfinder.html' ;

		if ( !empty( $this->SelectFunction ) )
			$qs .= '?action=js&amp;func=' . $this->SelectFunction ;

		if ( !empty( $this->SelectFunctionData ) ) 
		{
			$qs .= $qs ? "&amp;" : "?" ;
			$qs .= 'data=' . rawurlencode($this->SelectFunctionData) ;
		}

		if ( $this->DisableThumbnailSelection )
		{
			$qs .= $qs ? "&amp;" : "?" ;
			$qs .= "dts=1" ;
		}
		else if ( !empty( $this->SelectThumbnailFunction ) || !empty( $this->SelectFunction ) )
		{
			$qs .= $qs ? "&amp;" : "?" ;
			$qs .= 'thumbFunc=' . ( !empty( $this->SelectThumbnailFunction ) ? $this->SelectThumbnailFunction : $this->SelectFunction ) ;
			
			if ( !empty( $this->SelectThumbnailFunctionData ) )
				$qs .= '&amp;tdata=' . rawurlencode( $this->SelectThumbnailFunctionData ) ;
			else if ( empty( $this->SelectThumbnailFunction ) && !empty( $this->SelectFunctionData ) )
				$qs .= '&amp;tdata=' . rawurlencode( $this->SelectFunctionData ) ;
		}

		if ( !empty( $this->StartupPath ) )
		{
			$qs .= ( $qs ? "&amp;" : "?" ) ;
			$qs .= "start=" . urlencode( $this->StartupPath . ( $this->StartupFolderExpanded ? ':1' : ':0' ) ) ;
		}

		if ( !$this->RememberLastFolder )
		{
			$qs .= ( $qs ? "&amp;" : "?" ) ;
			$qs .= "rlf=0" ;
		}

		if ( !empty( $this->Id ) )
		{
			$qs .= ( $qs ? "&amp;" : "?" ) ;
			$qs .= "id=" . urlencode( $this->Id ) ;
		}
		
		return $url . $qs ;
	}

	// Static "Create".
	public static function CreateStatic( $basePath = CKFINDER_DEFAULT_BASEPATH, $width = '100%', $height = 400, $selectFunction = null )
	{
		$finder = new CKFinder( $basePath, $width, $height, $selectFunction ) ;
		$finder->Create() ;
	}

	// Static "SetupFCKeditor".
	public static function SetupFCKeditor( &$editorObj, $basePath = CKFINDER_DEFAULT_BASEPATH, $imageType = null, $flashType = null )
	{
		if ( empty( $basePath ) )
			$basePath = CKFINDER_DEFAULT_BASEPATH ;

		$ckfinder = new CKFinder( $basePath ) ;
		$ckfinder->SetupFCKeditorObject( $editorObj, $imageType, $flashType );
	}
	
	// Non-static method of attaching CKFinder to FCKeditor
	public function SetupFCKeditorObject( &$editorObj, $imageType = null, $flashType = null )
	{
		$url = $this->BasePath ;
		
		// If it is a path relative to the current page.
		if ( isset($url[0]) && $url[0] != '/' )
		{
			$url = substr( $_SERVER[ 'REQUEST_URI' ], 0, strrpos( $_SERVER[ 'REQUEST_URI' ], '/' ) + 1 ) . $url ;
		}
		
		$url = $this->_BuildUrl( $url ) ;
		$qs = ( strpos($url, "?") !== false ) ? "&" : "?" ;

		if ( $this->Width !== '100%' && is_numeric( str_ireplace( "px", "", $this->Width ) ) )
		{
			$width = intval( $this->Width );
			$editorObj->Config['LinkBrowserWindowWidth'] = $width ;
			$editorObj->Config['ImageBrowserWindowWidth'] = $width ;
			$editorObj->Config['FlashBrowserWindowWidth'] = $width ;
		}
		if ( $this->Height !== 400 && is_numeric( str_ireplace( "px", "", $this->Height ) ) )
		{
			$height = intval( $this->Height );
			$editorObj->Config['LinkBrowserWindowHeight'] = $height ;
			$editorObj->Config['ImageBrowserWindowHeight'] = $height ;
			$editorObj->Config['FlashBrowserWindowHeight'] = $height ;
		}

		$editorObj->Config['LinkBrowserURL'] = $url ;
		$editorObj->Config['ImageBrowserURL'] = $url . $qs . 'type=' . ( empty( $imageType ) ? 'Images' : $imageType ) ;
		$editorObj->Config['FlashBrowserURL'] = $url . $qs . 'type=' . ( empty( $flashType ) ? 'Flash' : $flashType ) ;
		
		$dir = substr( $url, 0, strrpos( $url, "/" ) + 1 ) ;
		$editorObj->Config['LinkUploadURL'] = $dir . urlencode( 'core/connector/php/connector.php?command=QuickUpload&type=Files' ) ;
		$editorObj->Config['ImageUploadURL'] = $dir . urlencode( 'core/connector/php/connector.php?command=QuickUpload&type=') . ( empty( $imageType ) ? 'Images' : $imageType ) ;
		$editorObj->Config['FlashUploadURL'] = $dir . urlencode( 'core/connector/php/connector.php?command=QuickUpload&type=') . ( empty( $flashType ) ? 'Flash' : $flashType ) ;
	}
}
