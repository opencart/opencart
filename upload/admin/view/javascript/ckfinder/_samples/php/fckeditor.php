<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
 * CKFinder
 * ========
 * http://ckfinder.com
 * Copyright (C) 2007-2009, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>CKFinder - Sample - FCKeditor Integration</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow" />
	<link href="../sample.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<h1>
		CKFinder - Sample - FCKeditor Integration
	</h1>
	<hr />
	<p>
		CKFinder can be easily integrated with FCKeditor. Try it now, by clicking
		the "Image" or "Link" icons and then the "<strong>Browse Server</strong>" button.</p>
	<p>
<?php

include_once '../../../fckeditor/fckeditor.php' ;
require_once '../../ckfinder.php' ;

// This is a check for the FCKeditor class. If not defined, the paths must be checked.
if ( !class_exists( 'FCKeditor' ) )
{
	echo
		'<br><strong><span style="color: #ff0000">Error</span>: FCKeditor not found</strong>. ' .
		'This sample assumes that FCKeditor (not included with CKFinder) is installed in ' .
		'the "fckeditor" sibling folder of the CKFinder installation folder. If you have it installed in ' .
		'a different place, just edit this file, changing the wrong paths in the include ' .
		'(line 17) and the "BasePath" values (line 35).' ;
}
else
{
	$fckeditor = new FCKeditor( 'FCKeditor1' ) ;
	$fckeditor->BasePath	= '../../../fckeditor/' ;
	$fckeditor->Value		= '<p>Just click the <b>Image</b> or <b>Link</b> button, and then <b>&quot;Browse Server&quot;</b>.</p>' ;
	
	// Just call CKFinder::SetupFCKeditor before calling Create() or CreateHtml()
	// in FCKeditor. The second parameter (optional), is the path for the
	// CKFinder installation (default = "/ckfinder/").
	CKFinder::SetupFCKeditor( $fckeditor, '../../' ) ;
	
	$fckeditor->Create() ;
}

?>
	</p>
</body>
</html>
