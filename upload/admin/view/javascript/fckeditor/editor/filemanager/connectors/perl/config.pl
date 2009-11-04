#####
#  FCKeditor - The text editor for Internet - http://www.fckeditor.net
#  Copyright (C) 2003-2009 Frederico Caldeira Knabben
#
#  == BEGIN LICENSE ==
#
#  Licensed under the terms of any of the following licenses at your
#  choice:
#
#   - GNU General Public License Version 2 or later (the "GPL")
#     http://www.gnu.org/licenses/gpl.html
#
#   - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
#     http://www.gnu.org/licenses/lgpl.html
#
#   - Mozilla Public License Version 1.1 or later (the "MPL")
#     http://www.mozilla.org/MPL/MPL-1.1.html
#
#  == END LICENSE ==
#
#  This is the File Manager Connector for Perl.
#####

##
# SECURITY: REMOVE/COMMENT THE FOLLOWING LINE TO ENABLE THIS CONNECTOR.
##
&SendError( 1, 'This connector is disabled. Please check the "editor/filemanager/connectors/perl/config.cgi" file' ) ;

$GLOBALS{'UserFilesPath'} = '/userfiles/';

# Map the "UserFiles" path to a local directory.
$rootpath = &GetRootPath();
$GLOBALS{'UserFilesDirectory'} = $rootpath . $GLOBALS{'UserFilesPath'};

%allowedExtensions =  ("File", "7z|aiff|asf|avi|bmp|csv|doc|fla|flv|gif|gz|gzip|jpeg|jpg|mid|mov|mp3|mp4|mpc|mpeg|mpg|ods|odt|pdf|png|ppt|pxd|qt|ram|rar|rm|rmi|rmvb|rtf|sdc|sitd|swf|sxc|sxw|tar|tgz|tif|tiff|txt|vsd|wav|wma|wmv|xls|xml|zip",
"Image", "bmp|gif|jpeg|jpg|png",
"Flash", "swf|flv",
"Media", "aiff|asf|avi|bmp|fla|flv|gif|jpeg|jpg|mid|mov|mp3|mp4|mpc|mpeg|mpg|png|qt|ram|rm|rmi|rmvb|swf|tif|tiff|wav|wma|wmv"
);
