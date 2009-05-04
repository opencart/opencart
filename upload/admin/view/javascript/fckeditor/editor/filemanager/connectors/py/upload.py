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

This is the "File Uploader" for Python

"""
import os

from fckutil import *
from fckcommands import * 	# default command's implementation
from fckconnector import FCKeditorConnectorBase # import base connector
import config as Config

class FCKeditorQuickUpload(	FCKeditorConnectorBase,
							UploadFileCommandMixin,
							BaseHttpMixin, BaseHtmlMixin):
	def doResponse(self):
		"Main function. Process the request, set headers and return a string as response."
		# Check if this connector is disabled
		if not(Config.Enabled):
			return self.sendUploadResults(1, "This file uploader is disabled. Please check the \"editor/filemanager/connectors/py/config.py\"")
		command = 'QuickUpload'
		# The file type (from the QueryString, by default 'File').
		resourceType  = self.request.get('Type','File')
		currentFolder = getCurrentFolder(self.request.get("CurrentFolder",""))
		# Check for invalid paths
		if currentFolder is None:
			return self.sendUploadResults(102, '', '', "")

		# Check if it is an allowed command
		if ( not command in Config.ConfigAllowedCommands ):
			return self.sendUploadResults( 1, '', '', 'The %s command isn\'t allowed' % command )

		if ( not resourceType in Config.ConfigAllowedTypes  ):
			return self.sendUploadResults( 1, '', '', 'Invalid type specified' )

		# Setup paths
		self.userFilesFolder = Config.QuickUploadAbsolutePath[resourceType]
		self.webUserFilesFolder =  Config.QuickUploadPath[resourceType]
		if not self.userFilesFolder: # no absolute path given (dangerous...)
			self.userFilesFolder = mapServerPath(self.environ,
									self.webUserFilesFolder)

		# Ensure that the directory exists.
		if not os.path.exists(self.userFilesFolder):
			try:
				self.createServerFoldercreateServerFolder( self.userFilesFolder )
			except:
				return self.sendError(1, "This connector couldn\'t access to local user\'s files directories.  Please check the UserFilesAbsolutePath in \"editor/filemanager/connectors/py/config.py\" and try again. ")

		# File upload doesn't have to return XML, so intercept here
		return self.uploadFile(resourceType, currentFolder)

# Running from command line (plain old CGI)
if __name__ == '__main__':
	try:
		# Create a Connector Instance
		conn = FCKeditorQuickUpload()
		data = conn.doResponse()
		for header in conn.headers:
			if not header is None:
				print '%s: %s' % header
		print
		print data
	except:
		print "Content-Type: text/plain"
		print
		import cgi
		cgi.print_exception()
