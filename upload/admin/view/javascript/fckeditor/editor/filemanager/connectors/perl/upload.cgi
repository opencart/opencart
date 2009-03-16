#!/usr/bin/env perl

#####
#  FCKeditor - The text editor for Internet - http://www.fckeditor.net
#  Copyright (C) 2003-2008 Frederico Caldeira Knabben
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
# ATTENTION: To enable this connector, look for the "SECURITY" comment in this file.
##

## START: Hack for Windows (Not important to understand the editor code... Perl specific).
if(Windows_check()) {
	chdir(GetScriptPath($0));
}

sub Windows_check
{
	# IIS,PWS(NT/95)
	$www_server_os = $^O;
	# Win98 & NT(SP4)
	if($www_server_os eq "") { $www_server_os= $ENV{'OS'}; }
	# AnHTTPd/Omni/IIS
	if($ENV{'SERVER_SOFTWARE'} =~ /AnWeb|Omni|IIS\//i) { $www_server_os= 'win'; }
	# Win Apache
	if($ENV{'WINDIR'} ne "") { $www_server_os= 'win'; }
	if($www_server_os=~ /win/i) { return(1); }
	return(0);
}

sub GetScriptPath {
	local($path) = @_;
	if($path =~ /[\:\/\\]/) { $path =~ s/(.*?)[\/\\][^\/\\]+$/$1/; } else { $path = '.'; }
	$path;
}
## END: Hack for IIS

require 'util.pl';
require 'io.pl';
require 'basexml.pl';
require 'commands.pl';
require 'upload_fck.pl';

##
# SECURITY: REMOVE/COMMENT THE FOLLOWING LINE TO ENABLE THIS CONNECTOR.
##
	&SendUploadResults(1, '', '', 'This connector is disabled. Please check the "editor/filemanager/connectors/perl/upload.cgi" file' ) ;

	&read_input();

	if($FORM{'ServerPath'} ne "") {
		$GLOBALS{'UserFilesPath'} = $FORM{'ServerPath'};
		if(!($GLOBALS{'UserFilesPath'} =~ /\/$/)) {
			$GLOBALS{'UserFilesPath'} .= '/' ;
		}
	} else {
		$GLOBALS{'UserFilesPath'} = '/userfiles/';
	}

	# Map the "UserFiles" path to a local directory.
	$rootpath = &GetRootPath();
	$GLOBALS{'UserFilesDirectory'} = $rootpath . $GLOBALS{'UserFilesPath'};

	&DoResponse();

sub DoResponse
{
	# Get the main request information.
	$sCommand		= 'FileUpload'; #$FORM{'Command'};
	$sResourceType	= $FORM{'Type'};
	$sCurrentFolder	= $FORM{'CurrentFolder'};

	if ($sResourceType eq '') {
		$sResourceType = 'File' ;
	}
	if ($sCurrentFolder eq '') {
		$sCurrentFolder = '/' ;
	}

	# Check the current folder syntax (must begin and start with a slash).
	if(!($sCurrentFolder =~ /\/$/)) {
		$sCurrentFolder .= '/';
	}
	if(!($sCurrentFolder =~ /^\//)) {
		$sCurrentFolder = '/' . $sCurrentFolder;
	}

	# Check for invalid folder paths (..)
	if ( $sCurrentFolder =~ /(?:\.\.|\\)/ ) {
		SendError( 102, "" ) ;
	}

	# File Upload doesn't have to Return XML, so it must be intercepted before anything.
	if($sCommand eq 'FileUpload') {
		FileUpload($sResourceType,$sCurrentFolder);
		return ;
	}

}
