#!/usr/bin/env python

"""
FCKeditor - The text editor for Internet - http://www.fckeditor.net
Copyright (C) 2003-2009 Frederico Caldeira Knabben

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

Connector for Python (CGI and WSGI).

See config.py for configuration settings

"""
import os

from fckutil import *
from fckcommands import * 	# default command's implementation
from fckoutput import * 	# base http, xml and html output mixins
from fckconnector import FCKeditorConnectorBase # import base connector
import config as Config

class FCKeditorConnector(	FCKeditorConnectorBase,
							GetFoldersCommandMixin,
							GetFoldersAndFilesCommandMixin,
							CreateFolderCommandMixin,
							UploadFileCommandMixin,
							BaseHttpMixin, BaseXmlMixin, BaseHtmlMixin  ):
	"The Standard connector class."
	def doResponse(self):
		"Main function. Process the request, set headers and return a string as response."
		s = ""
		# Check if this connector is disabled
		if not(Config.Enabled):
			return self.sendError(1, "This connector is disabled.  Please check the connector configurations in \"editor/filemanager/connectors/py/config.py\" and try again.")
		# Make sure we have valid inputs
		for key in ("Command","Type","CurrentFolder"):
			if not self.request.has_key (key):
				return
		# Get command, resource type and current folder
		command = self.request.get("Command")
		resourceType = self.request.get("Type")
		currentFolder = getCurrentFolder(self.request.get("CurrentFolder"))
		# Check for invalid paths
		if currentFolder is None:
			if (command == "FileUpload"):
				return self.sendUploadResults( errorNo = 102, customMsg = "" )
			else:
				return self.sendError(102, "")

		# Check if it is an allowed command
		if ( not command in Config.ConfigAllowedCommands ):
			return self.sendError( 1, 'The %s command isn\'t allowed' % command )

		if ( not resourceType in Config.ConfigAllowedTypes  ):
			return self.sendError( 1, 'Invalid type specified' )

		# Setup paths
		if command == "QuickUpload":
			self.userFilesFolder = Config.QuickUploadAbsolutePath[resourceType]
			self.webUserFilesFolder =  Config.QuickUploadPath[resourceType]
		else:
			self.userFilesFolder = Config.FileTypesAbsolutePath[resourceType]
			self.webUserFilesFolder = Config.FileTypesPath[resourceType]

		if not self.userFilesFolder: # no absolute path given (dangerous...)
			self.userFilesFolder = mapServerPath(self.environ,
									self.webUserFilesFolder)
		# Ensure that the directory exists.
		if not os.path.exists(self.userFilesFolder):
			try:
				self.createServerFolder( self.userFilesFolder )
			except:
				return self.sendError(1, "This connector couldn\'t access to local user\'s files directories.  Please check the UserFilesAbsolutePath in \"editor/filemanager/connectors/py/config.py\" and try again. ")

		# File upload doesn't have to return XML, so intercept here
		if (command == "FileUpload"):
			return self.uploadFile(resourceType, currentFolder)

		# Create Url
		url = combinePaths( self.webUserFilesFolder, currentFolder )

		# Begin XML
		s += self.createXmlHeader(command, resourceType, currentFolder, url)
		# Execute the command
		selector = {"GetFolders": self.getFolders,
					"GetFoldersAndFiles": self.getFoldersAndFiles,
					"CreateFolder": self.createFolder,
					}
		s += selector[command](resourceType, currentFolder)
		s += self.createXmlFooter()
		return s

# Running from command line (plain old CGI)
if __name__ == '__main__':
	try:
		# Create a Connector Instance
		conn = FCKeditorConnector()
		data = conn.doResponse()
		for header in conn.headers:
			print '%s: %s' % header
		print
		print data
	except:
		print "Content-Type: text/plain"
		print
		import cgi
		cgi.print_exception()
