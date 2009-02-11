<?php
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
 * This is the File Manager Connector for PHP.
 */
function CombinePaths( $sBasePath, $sFolder )
{
	return RemoveFromEnd( $sBasePath, '/' ) . '/' . RemoveFromStart( $sFolder, '/' ) ;
}
function GetResourceTypePath( $resourceType, $sCommand )
{
	global $Config ;

	if ( $sCommand == "QuickUpload")
		return $Config['QuickUploadPath'][$resourceType] ;
	else
		return $Config['FileTypesPath'][$resourceType] ;
}

function GetResourceTypeDirectory( $resourceType, $sCommand )
{
	global $Config ;
	if ( $sCommand == "QuickUpload")
	{
		if ( strlen( $Config['QuickUploadAbsolutePath'][$resourceType] ) > 0 )
			return $Config['QuickUploadAbsolutePath'][$resourceType] ;

		// Map the "UserFiles" path to a local directory.
		return Server_MapPath( $Config['QuickUploadPath'][$resourceType] ) ;
	}
	else
	{
		if ( strlen( $Config['FileTypesAbsolutePath'][$resourceType] ) > 0 )
			return $Config['FileTypesAbsolutePath'][$resourceType] ;

		// Map the "UserFiles" path to a local directory.
		return Server_MapPath( $Config['FileTypesPath'][$resourceType] ) ;
	}
}

function GetUrlFromPath( $resourceType, $folderPath, $sCommand )
{
	return CombinePaths( GetResourceTypePath( $resourceType, $sCommand ), $folderPath ) ;
}

function RemoveExtension( $fileName )
{
	return substr( $fileName, 0, strrpos( $fileName, '.' ) ) ;
}

function ServerMapFolder( $resourceType, $folderPath, $sCommand )
{
	// Get the resource type directory.
	$sResourceTypePath = GetResourceTypeDirectory( $resourceType, $sCommand ) ;

	// Ensure that the directory exists.
	$sErrorMsg = CreateServerFolder( $sResourceTypePath ) ;
	if ( $sErrorMsg != '' )
		SendError( 1, "Error creating folder \"{$sResourceTypePath}\" ({$sErrorMsg})" ) ;

	// Return the resource type directory combined with the required path.
	return CombinePaths( $sResourceTypePath , $folderPath ) ;
}

function GetParentFolder( $folderPath )
{
	$sPattern = "-[/\\\\][^/\\\\]+[/\\\\]?$-" ;
	return preg_replace( $sPattern, '', $folderPath ) ;
}

function CreateServerFolder( $folderPath, $lastFolder = null )
{
	global $Config ;
	$sParent = GetParentFolder( $folderPath ) ;

	// Ensure the folder path has no double-slashes, or mkdir may fail on certain platforms
	while ( strpos($folderPath, '//') !== false )
	{
		$folderPath = str_replace( '//', '/', $folderPath ) ;
	}

	// Check if the parent exists, or create it.
	if ( !file_exists( $sParent ) )
	{
		//prevents agains infinite loop when we can't create root folder
		if ( !is_null( $lastFolder ) && $lastFolder === $sParent) {
			return "Can't create $folderPath directory" ;
		}

		$sErrorMsg = CreateServerFolder( $sParent, $folderPath ) ;
		if ( $sErrorMsg != '' )
			return $sErrorMsg ;
	}

	if ( !file_exists( $folderPath ) )
	{
		// Turn off all error reporting.
		error_reporting( 0 ) ;

		$php_errormsg = '' ;
		// Enable error tracking to catch the error.
		ini_set( 'track_errors', '1' ) ;

		if ( isset( $Config['ChmodOnFolderCreate'] ) && !$Config['ChmodOnFolderCreate'] )
		{
			mkdir( $folderPath ) ;
		}
		else
		{
			$permissions = 0777 ;
			if ( isset( $Config['ChmodOnFolderCreate'] ) )
			{
				$permissions = $Config['ChmodOnFolderCreate'] ;
			}
			// To create the folder with 0777 permissions, we need to set umask to zero.
			$oldumask = umask(0) ;
			mkdir( $folderPath, $permissions ) ;
			umask( $oldumask ) ;
		}

		$sErrorMsg = $php_errormsg ;

		// Restore the configurations.
		ini_restore( 'track_errors' ) ;
		ini_restore( 'error_reporting' ) ;

		return $sErrorMsg ;
	}
	else
		return '' ;
}

function GetRootPath()
{
	if (!isset($_SERVER)) {
		global $_SERVER;
	}
	$sRealPath = realpath( './' ) ;

	$sSelfPath = $_SERVER['PHP_SELF'] ;
	$sSelfPath = substr( $sSelfPath, 0, strrpos( $sSelfPath, '/' ) ) ;

	$sSelfPath = str_replace( '/', DIRECTORY_SEPARATOR, $sSelfPath ) ;

	$position = strpos( $sRealPath, $sSelfPath ) ;

	// This can check only that this script isn't run from a virtual dir
	// But it avoids the problems that arise if it isn't checked
	if ( $position === false || $position <> strlen( $sRealPath ) - strlen( $sSelfPath ) )
		SendError( 1, 'Sorry, can\'t map "UserFilesPath" to a physical path. You must set the "UserFilesAbsolutePath" value in "editor/filemanager/connectors/php/config.php".' ) ;

	return substr( $sRealPath, 0, $position ) ;
}

// Emulate the asp Server.mapPath function.
// given an url path return the physical directory that it corresponds to
function Server_MapPath( $path )
{
	// This function is available only for Apache
	if ( function_exists( 'apache_lookup_uri' ) )
	{
		$info = apache_lookup_uri( $path ) ;
		return $info->filename . $info->path_info ;
	}

	// This isn't correct but for the moment there's no other solution
	// If this script is under a virtual directory or symlink it will detect the problem and stop
	return GetRootPath() . $path ;
}

function IsAllowedExt( $sExtension, $resourceType )
{
	global $Config ;
	// Get the allowed and denied extensions arrays.
	$arAllowed	= $Config['AllowedExtensions'][$resourceType] ;
	$arDenied	= $Config['DeniedExtensions'][$resourceType] ;

	if ( count($arAllowed) > 0 && !in_array( $sExtension, $arAllowed ) )
		return false ;

	if ( count($arDenied) > 0 && in_array( $sExtension, $arDenied ) )
		return false ;

	return true ;
}

function IsAllowedType( $resourceType )
{
	global $Config ;
	if ( !in_array( $resourceType, $Config['ConfigAllowedTypes'] ) )
		return false ;

	return true ;
}

function IsAllowedCommand( $sCommand )
{
	global $Config ;

	if ( !in_array( $sCommand, $Config['ConfigAllowedCommands'] ) )
		return false ;

	return true ;
}

function GetCurrentFolder()
{
	if (!isset($_GET)) {
		global $_GET;
	}
	$sCurrentFolder	= isset( $_GET['CurrentFolder'] ) ? $_GET['CurrentFolder'] : '/' ;

	// Check the current folder syntax (must begin and start with a slash).
	if ( !preg_match( '|/$|', $sCurrentFolder ) )
		$sCurrentFolder .= '/' ;
	if ( strpos( $sCurrentFolder, '/' ) !== 0 )
		$sCurrentFolder = '/' . $sCurrentFolder ;

	// Ensure the folder path has no double-slashes
	while ( strpos ($sCurrentFolder, '//') !== false ) {
		$sCurrentFolder = str_replace ('//', '/', $sCurrentFolder) ;
	}

	// Check for invalid folder paths (..)
	if ( strpos( $sCurrentFolder, '..' ) || strpos( $sCurrentFolder, "\\" ))
		SendError( 102, '' ) ;

	return $sCurrentFolder ;
}

// Do a cleanup of the folder name to avoid possible problems
function SanitizeFolderName( $sNewFolderName )
{
	$sNewFolderName = stripslashes( $sNewFolderName ) ;

	// Remove . \ / | : ? * " < >
	$sNewFolderName = preg_replace( '/\\.|\\\\|\\/|\\||\\:|\\?|\\*|"|<|>|[[:cntrl:]]/', '_', $sNewFolderName ) ;

	return $sNewFolderName ;
}

// Do a cleanup of the file name to avoid possible problems
function SanitizeFileName( $sNewFileName )
{
	global $Config ;

	$sNewFileName = stripslashes( $sNewFileName ) ;

	// Replace dots in the name with underscores (only one dot can be there... security issue).
	if ( $Config['ForceSingleExtension'] )
		$sNewFileName = preg_replace( '/\\.(?![^.]*$)/', '_', $sNewFileName ) ;

	// Remove \ / | : ? * " < >
	$sNewFileName = preg_replace( '/\\\\|\\/|\\||\\:|\\?|\\*|"|<|>|[[:cntrl:]]/', '_', $sNewFileName ) ;

	return $sNewFileName ;
}

// This is the function that sends the results of the uploading process.
function SendUploadResults( $errorNumber, $fileUrl = '', $fileName = '', $customMsg = '' )
{
	echo <<<EOF
<script type="text/javascript">
(function()
{
	var d = document.domain ;

	while ( true )
	{
		// Test if we can access a parent property.
		try
		{
			var test = window.top.opener.document.domain ;
			break ;
		}
		catch( e ) {}

		// Remove a domain part: www.mytest.example.com => mytest.example.com => example.com ...
		d = d.replace( /.*?(?:\.|$)/, '' ) ;

		if ( d.length == 0 )
			break ;		// It was not able to detect the domain.

		try
		{
			document.domain = d ;
		}
		catch (e)
		{
			break ;
		}
	}
})() ;

EOF;
	$rpl = array( '\\' => '\\\\', '"' => '\\"' ) ;
	echo 'window.parent.OnUploadCompleted(' . $errorNumber . ',"' . strtr( $fileUrl, $rpl ) . '","' . strtr( $fileName, $rpl ) . '", "' . strtr( $customMsg, $rpl ) . '") ;' ;
	echo '</script>' ;
	exit ;
}

?>
