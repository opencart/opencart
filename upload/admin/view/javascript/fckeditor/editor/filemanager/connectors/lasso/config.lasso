[//lasso
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
 * Configuration file for the File Manager Connector for Lasso.
 */

    /*.....................................................................
    The connector uses the file tags, which require authentication. Enter a
    valid username and password from Lasso admin for a group with file tags
    permissions for uploads and the path you define in UserFilesPath below.
    */

	var('connection') = array(
		-username='xxxxxxxx',
		-password='xxxxxxxx'
	);


    /*.....................................................................
    Set the base path for files that users can upload and browse (relative
    to server root).

    Set which file extensions are allowed and/or denied for each file type.
    */
	var('config') = map(
		'Enabled' = false,
		'UserFilesPath' = '/userfiles/',
		'Subdirectories' = map(
			'File' = 'File/',
			'Image' = 'Image/',
			'Flash' = 'Flash/',
			'Media' = 'Media/'
		),
		'AllowedExtensions' = map(
			'File' = array('7z','aiff','asf','avi','bmp','csv','doc','fla','flv','gif','gz','gzip','jpeg','jpg','mid','mov','mp3','mp4','mpc','mpeg','mpg','ods','odt','pdf','png','ppt','pxd','qt','ram','rar','rm','rmi','rmvb','rtf','sdc','sitd','swf','sxc','sxw','tar','tgz','tif','tiff','txt','vsd','wav','wma','wmv','xls','xml','zip'),
			'Image' = array('bmp','gif','jpeg','jpg','png'),
			'Flash' = array('swf','flv'),
			'Media' = array('aiff','asf','avi','bmp','fla','flv','gif','jpeg','jpg','mid','mov','mp3','mp4','mpc','mpeg','mpg','png','qt','ram','rm','rmi','rmvb','swf','tif','tiff','wav','wma','wmv')
		),
		'DeniedExtensions' = map(
			'File' = array(),
			'Image' = array(),
			'Flash' = array(),
			'Media' = array()
		)
	);
]
