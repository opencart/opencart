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

"""

import os
try: # Windows needs stdio set for binary mode for file upload to work.
	import msvcrt
	msvcrt.setmode (0, os.O_BINARY) # stdin  = 0
	msvcrt.setmode (1, os.O_BINARY) # stdout = 1
except ImportError:
	pass

from fckutil import *
from fckoutput import *
import config as Config

class GetFoldersCommandMixin (object):
	def getFolders(self, resourceType, currentFolder):
		"""
		Purpose: command to recieve a list of folders
		"""
		# Map the virtual path to our local server
		serverPath = mapServerFolder(self.userFilesFolder,currentFolder)
		s = """<Folders>"""	 # Open the folders node
		for someObject in os.listdir(serverPath):
			someObjectPath = mapServerFolder(serverPath, someObject)
			if os.path.isdir(someObjectPath):
				s += """<Folder name="%s" />""" % (
						convertToXmlAttribute(someObject)
						)
		s += """</Folders>""" # Close the folders node
		return s

class GetFoldersAndFilesCommandMixin (object):
	def getFoldersAndFiles(self, resourceType, currentFolder):
		"""
		Purpose: command to recieve a list of folders and files
		"""
		# Map the virtual path to our local server
		serverPath = mapServerFolder(self.userFilesFolder,currentFolder)
		# Open the folders / files node
		folders = """<Folders>"""
		files = """<Files>"""
		for someObject in os.listdir(serverPath):
			someObjectPath = mapServerFolder(serverPath, someObject)
			if os.path.isdir(someObjectPath):
				folders += """<Folder name="%s" />""" % (
						convertToXmlAttribute(someObject)
						)
			elif os.path.isfile(someObjectPath):
				size = os.path.getsize(someObjectPath)
				if size > 0:
					size = round(size/1024)
					if size < 1:
						size = 1
				files += """<File name="%s" size="%d" />""" % (
						convertToXmlAttribute(someObject),
						size
						)
		# Close the folders / files node
		folders += """</Folders>"""
		files += """</Files>"""
		return folders + files

class CreateFolderCommandMixin (object):
	def createFolder(self, resourceType, currentFolder):
		"""
		Purpose: command to create a new folder
		"""
		errorNo = 0; errorMsg ='';
		if self.request.has_key("NewFolderName"):
			newFolder = self.request.get("NewFolderName", None)
			newFolder = sanitizeFolderName (newFolder)
			try:
				newFolderPath = mapServerFolder(self.userFilesFolder, combinePaths(currentFolder, newFolder))
				self.createServerFolder(newFolderPath)
			except Exception, e:
				errorMsg = str(e).decode('iso-8859-1').encode('utf-8') # warning with encodigns!!!
				if hasattr(e,'errno'):
					if e.errno==17: #file already exists
						errorNo=0
					elif e.errno==13: # permission denied
						errorNo = 103
					elif e.errno==36 or e.errno==2 or e.errno==22: # filename too long / no such file / invalid name
						errorNo = 102
				else:
					errorNo = 110
		else:
			errorNo = 102
		return self.sendErrorNode ( errorNo, errorMsg )

	def createServerFolder(self, folderPath):
		"Purpose: physically creates a folder on the server"
		# No need to check if the parent exists, just create all hierachy

		try:
			permissions = Config.ChmodOnFolderCreate
			if not permissions:
				os.makedirs(folderPath)
		except AttributeError: #ChmodOnFolderCreate undefined
			permissions = 0755

		if permissions:
			oldumask = os.umask(0)
			os.makedirs(folderPath,mode=0755)
			os.umask( oldumask )

class UploadFileCommandMixin (object):
	def uploadFile(self, resourceType, currentFolder):
		"""
		Purpose: command to upload files to server (same as FileUpload)
		"""
		errorNo = 0
		if self.request.has_key("NewFile"):
			# newFile has all the contents we need
			newFile = self.request.get("NewFile", "")
			# Get the file name
			newFileName = newFile.filename
			newFileName = sanitizeFileName( newFileName )
			newFileNameOnly = removeExtension(newFileName)
			newFileExtension = getExtension(newFileName).lower()
			allowedExtensions = Config.AllowedExtensions[resourceType]
			deniedExtensions = Config.DeniedExtensions[resourceType]

			if (allowedExtensions):
				# Check for allowed
				isAllowed = False
				if (newFileExtension in allowedExtensions):
					isAllowed = True
			elif (deniedExtensions):
				# Check for denied
				isAllowed = True
				if (newFileExtension in deniedExtensions):
					isAllowed = False
			else:
				# No extension limitations
				isAllowed = True

			if (isAllowed):
				# Upload to operating system
				# Map the virtual path to the local server path
				currentFolderPath = mapServerFolder(self.userFilesFolder, currentFolder)
				i = 0
				while (True):
					newFilePath = os.path.join (currentFolderPath,newFileName)
					if os.path.exists(newFilePath):
						i += 1
						newFileName = "%s(%d).%s" % (
								newFileNameOnly, i, newFileExtension
								)
						errorNo= 201 # file renamed
					else:
						# Read file contents and write to the desired path (similar to php's move_uploaded_file)
						fout = file(newFilePath, 'wb')
						while (True):
							chunk = newFile.file.read(100000)
							if not chunk: break
							fout.write (chunk)
						fout.close()

						if os.path.exists ( newFilePath ):
							doChmod = False
							try:
								doChmod = Config.ChmodOnUpload
								permissions = Config.ChmodOnUpload
							except AttributeError: #ChmodOnUpload undefined
								doChmod = True
								permissions = 0755
							if ( doChmod ):
								oldumask = os.umask(0)
								os.chmod( newFilePath, permissions )
								os.umask( oldumask )

						newFileUrl = combinePaths(self.webUserFilesFolder, currentFolder) + newFileName

						return self.sendUploadResults( errorNo , newFileUrl, newFileName )
			else:
				return self.sendUploadResults( errorNo = 202, customMsg = "" )
		else:
			return self.sendUploadResults( errorNo = 202, customMsg = "No File" )
