#!/usr/bin/env python

"""
FCKeditor - The text editor for Internet - http://www.fckeditor.net
Copyright (C) 2003-2008 Frederico Caldeira Knabben

== BEGIN LICENSE ==

Licensed under the terms of any of the following licenses at your
choice:

- GNU General Public License Version 2 or later (the "GPL")
http://www.gnu.org/licenses/gpl.html

- GNU Lesser General Public License Version 2.1 or later (the "LGPL")
http://www.gnu.org/licenses/lgpl.html

- Mozilla Public License Version 1.1 or later (the "MPL")
http://www.mozilla.org/MPL/MPL-1.1.html

== END LICENSE ==

Utility functions for the File Manager Connector for Python

"""

import string, re
import os
import config as Config

# Generic manipulation functions

def removeExtension(fileName):
	index = fileName.rindex(".")
	newFileName = fileName[0:index]
	return newFileName

def getExtension(fileName):
	index = fileName.rindex(".") + 1
	fileExtension = fileName[index:]
	return fileExtension

def removeFromStart(string, char):
	return string.lstrip(char)

def removeFromEnd(string, char):
	return string.rstrip(char)

# Path functions

def combinePaths( basePath, folder ):
	return removeFromEnd( basePath, '/' ) + '/' + removeFromStart( folder, '/' )

def getFileName(filename):
	" Purpose: helper function to extrapolate the filename "
	for splitChar in ["/", "\\"]:
		array = filename.split(splitChar)
		if (len(array) > 1):
			filename = array[-1]
	return filename

def sanitizeFolderName( newFolderName ):
	"Do a cleanup of the folder name to avoid possible problems"
	# Remove . \ / | : ? * " < > and control characters
	return re.sub( '(?u)\\.|\\\\|\\/|\\||\\:|\\?|\\*|"|<|>|[^\u0000-\u001f\u007f-\u009f]', '_', newFolderName )

def sanitizeFileName( newFileName ):
	"Do a cleanup of the file name to avoid possible problems"
	# Replace dots in the name with underscores (only one dot can be there... security issue).
	if ( Config.ForceSingleExtension ): # remove dots
		newFileName = re.sub ( '/\\.(?![^.]*$)/', '_', newFileName ) ;
	newFileName = newFileName.replace('\\','/')		# convert windows to unix path
	newFileName = os.path.basename (newFileName)	# strip directories
	# Remove \ / | : ? *
	return re.sub ( '(?u)/\\\\|\\/|\\||\\:|\\?|\\*|"|<|>|[^\u0000-\u001f\u007f-\u009f]/', '_', newFileName )

def getCurrentFolder(currentFolder):
	if not currentFolder:
		currentFolder = '/'

	# Check the current folder syntax (must begin and end with a slash).
	if (currentFolder[-1] <> "/"):
		currentFolder += "/"
	if (currentFolder[0] <> "/"):
		currentFolder = "/" + currentFolder

	# Ensure the folder path has no double-slashes
	while '//' in currentFolder:
		currentFolder = currentFolder.replace('//','/')

	# Check for invalid folder paths (..)
	if '..' in currentFolder or '\\' in currentFolder:
		return None

	return currentFolder

def mapServerPath( environ, url):
	" Emulate the asp Server.mapPath function. Given an url path return the physical directory that it corresponds to "
	# This isn't correct but for the moment there's no other solution
	# If this script is under a virtual directory or symlink it will detect the problem and stop
	return combinePaths( getRootPath(environ), url )

def mapServerFolder(resourceTypePath, folderPath):
	return combinePaths ( resourceTypePath  , folderPath )

def getRootPath(environ):
	"Purpose: returns the root path on the server"
	# WARNING: this may not be thread safe, and doesn't work w/ VirtualServer/mod_python
	# Use Config.UserFilesAbsolutePath instead

	if environ.has_key('DOCUMENT_ROOT'):
		return environ['DOCUMENT_ROOT']
	else:
		realPath = os.path.realpath( './' )
		selfPath = environ['SCRIPT_FILENAME']
		selfPath = selfPath [ :  selfPath.rfind( '/'  ) ]
		selfPath = selfPath.replace( '/', os.path.sep)

		position = realPath.find(selfPath)

		# This can check only that this script isn't run from a virtual dir
		# But it avoids the problems that arise if it isn't checked
		raise realPath
		if ( position < 0 or position <> len(realPath) - len(selfPath) or realPath[ : position ]==''):
			raise Exception('Sorry, can\'t map "UserFilesPath" to a physical path. You must set the "UserFilesAbsolutePath" value in "editor/filemanager/connectors/py/config.py".')
		return realPath[ : position ]
