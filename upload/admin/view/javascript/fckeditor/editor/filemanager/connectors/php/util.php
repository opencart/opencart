<?php
/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2009 Frederico Caldeira Knabben
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
 * Utility functions for the File Manager Connector for PHP.
 */

function RemoveFromStart( $sourceString, $charToRemove )
{
	$sPattern = '|^' . $charToRemove . '+|' ;
	return preg_replace( $sPattern, '', $sourceString ) ;
}

function RemoveFromEnd( $sourceString, $charToRemove )
{
	$sPattern = '|' . $charToRemove . '+$|' ;
	return preg_replace( $sPattern, '', $sourceString ) ;
}

function FindBadUtf8( $string )
{
	$regex =
	'([\x00-\x7F]'.
	'|[\xC2-\xDF][\x80-\xBF]'.
	'|\xE0[\xA0-\xBF][\x80-\xBF]'.
	'|[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}'.
	'|\xED[\x80-\x9F][\x80-\xBF]'.
	'|\xF0[\x90-\xBF][\x80-\xBF]{2}'.
	'|[\xF1-\xF3][\x80-\xBF]{3}'.
	'|\xF4[\x80-\x8F][\x80-\xBF]{2}'.
	'|(.{1}))';

	while (preg_match('/'.$regex.'/S', $string, $matches)) {
		if ( isset($matches[2])) {
			return true;
		}
		$string = substr($string, strlen($matches[0]));
	}

	return false;
}

function ConvertToXmlAttribute( $value )
{
	if ( defined( 'PHP_OS' ) )
	{
		$os = PHP_OS ;
	}
	else
	{
		$os = php_uname() ;
	}

	if ( strtoupper( substr( $os, 0, 3 ) ) === 'WIN' || FindBadUtf8( $value ) )
	{
		return ( utf8_encode( htmlspecialchars( $value ) ) ) ;
	}
	else
	{
		return ( htmlspecialchars( $value ) ) ;
	}
}

/**
 * Check whether given extension is in html etensions list
 *
 * @param string $ext
 * @param array $htmlExtensions
 * @return boolean
 */
function IsHtmlExtension( $ext, $htmlExtensions )
{
	if ( !$htmlExtensions || !is_array( $htmlExtensions ) )
	{
		return false ;
	}
	$lcaseHtmlExtensions = array() ;
	foreach ( $htmlExtensions as $key => $val )
	{
		$lcaseHtmlExtensions[$key] = strtolower( $val ) ;
	}
	return in_array( $ext, $lcaseHtmlExtensions ) ;
}

/**
 * Detect HTML in the first KB to prevent against potential security issue with
 * IE/Safari/Opera file type auto detection bug.
 * Returns true if file contain insecure HTML code at the beginning.
 *
 * @param string $filePath absolute path to file
 * @return boolean
 */
function DetectHtml( $filePath )
{
	$fp = @fopen( $filePath, 'rb' ) ;

	//open_basedir restriction, see #1906
	if ( $fp === false || !flock( $fp, LOCK_SH ) )
	{
		return -1 ;
	}

	$chunk = fread( $fp, 1024 ) ;
	flock( $fp, LOCK_UN ) ;
	fclose( $fp ) ;

	$chunk = strtolower( $chunk ) ;

	if (!$chunk)
	{
		return false ;
	}

	$chunk = trim( $chunk ) ;

	if ( preg_match( "/<!DOCTYPE\W*X?HTML/sim", $chunk ) )
	{
		return true;
	}

	$tags = array( '<body', '<head', '<html', '<img', '<pre', '<script', '<table', '<title' ) ;

	foreach( $tags as $tag )
	{
		if( false !== strpos( $chunk, $tag ) )
		{
			return true ;
		}
	}

	//type = javascript
	if ( preg_match( '!type\s*=\s*[\'"]?\s*(?:\w*/)?(?:ecma|java)!sim', $chunk ) )
	{
		return true ;
	}

	//href = javascript
	//src = javascript
	//data = javascript
	if ( preg_match( '!(?:href|src|data)\s*=\s*[\'"]?\s*(?:ecma|java)script:!sim', $chunk ) )
	{
		return true ;
	}

	//url(javascript
	if ( preg_match( '!url\s*\(\s*[\'"]?\s*(?:ecma|java)script:!sim', $chunk ) )
	{
		return true ;
	}

	return false ;
}

/**
 * Check file content.
 * Currently this function validates only image files.
 * Returns false if file is invalid.
 *
 * @param string $filePath absolute path to file
 * @param string $extension file extension
 * @param integer $detectionLevel 0 = none, 1 = use getimagesize for images, 2 = use DetectHtml for images
 * @return boolean
 */
function IsImageValid( $filePath, $extension )
{
	if (!@is_readable($filePath)) {
		return -1;
	}

	$imageCheckExtensions = array('gif', 'jpeg', 'jpg', 'png', 'swf', 'psd', 'bmp', 'iff');

	// version_compare is available since PHP4 >= 4.0.7
	if ( function_exists( 'version_compare' ) ) {
		$sCurrentVersion = phpversion();
		if ( version_compare( $sCurrentVersion, "4.2.0" ) >= 0 ) {
			$imageCheckExtensions[] = "tiff";
			$imageCheckExtensions[] = "tif";
		}
		if ( version_compare( $sCurrentVersion, "4.3.0" ) >= 0 ) {
			$imageCheckExtensions[] = "swc";
		}
		if ( version_compare( $sCurrentVersion, "4.3.2" ) >= 0 ) {
			$imageCheckExtensions[] = "jpc";
			$imageCheckExtensions[] = "jp2";
			$imageCheckExtensions[] = "jpx";
			$imageCheckExtensions[] = "jb2";
			$imageCheckExtensions[] = "xbm";
			$imageCheckExtensions[] = "wbmp";
		}
	}

	if ( !in_array( $extension, $imageCheckExtensions ) ) {
		return true;
	}

	if ( @getimagesize( $filePath ) === false ) {
		return false ;
	}

	return true;
}

?>
