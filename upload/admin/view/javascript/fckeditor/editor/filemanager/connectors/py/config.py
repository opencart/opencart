#!/usr/bin/env python
"""
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
 * Configuration file for the File Manager Connector for Python
"""

# INSTALLATION NOTE: You must set up your server environment accordingly to run
# python scripts. This connector requires Python 2.4 or greater.
#
# Supported operation modes:
#  * WSGI (recommended): You'll need apache + mod_python + modpython_gateway
#                        or any web server capable of the WSGI python standard
#  * Plain Old CGI:      Any server capable of running standard python scripts
#                        (although mod_python is recommended for performance)
#                        This was the previous connector version operation mode
#
# If you're using Apache web server, replace the htaccess.txt to to .htaccess,
# and set the proper options and paths.
# For WSGI and mod_python, you may need to download modpython_gateway from:
# http://projects.amor.org/misc/svn/modpython_gateway.py and copy it in this
# directory.


# SECURITY: You must explicitly enable this "connector". (Set it to "True").
# WARNING: don't just set "ConfigIsEnabled = True", you must be sure that only
#		authenticated users can access this file or use some kind of session checking.
Enabled = False

# Path to user files relative to the document root.
UserFilesPath = '/userfiles/'

# Fill the following value it you prefer to specify the absolute path for the
# user files directory. Useful if you are using a virtual directory, symbolic
# link or alias. Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
# Attention: The above 'UserFilesPath' must point to the same directory.
# WARNING: GetRootPath may not work in virtual or mod_python configurations, and
# may not be thread safe. Use this configuration parameter instead.
UserFilesAbsolutePath = ''

# Due to security issues with Apache modules, it is recommended to leave the
# following setting enabled.
ForceSingleExtension = True

# What the user can do with this connector
ConfigAllowedCommands = [ 'QuickUpload', 'FileUpload', 'GetFolders', 'GetFoldersAndFiles', 'CreateFolder' ]

# Allowed Resource Types
ConfigAllowedTypes = ['File', 'Image', 'Flash', 'Media']

# After file is uploaded, sometimes it is required to change its permissions
# so that it was possible to access it at the later time.
# If possible, it is recommended to set more restrictive permissions, like 0755.
# Set to 0 to disable this feature.
# Note: not needed on Windows-based servers.
ChmodOnUpload = 0755

# See comments above.
# Used when creating folders that does not exist.
ChmodOnFolderCreate = 0755

# Do not touch this 3 lines, see "Configuration settings for each Resource Type"
AllowedExtensions = {}; DeniedExtensions = {};
FileTypesPath = {}; FileTypesAbsolutePath = {};
QuickUploadPath = {}; QuickUploadAbsolutePath = {};

#	Configuration settings for each Resource Type
#
#	- AllowedExtensions: the possible extensions that can be allowed.
#		If it is empty then any file type can be uploaded.
#	- DeniedExtensions: The extensions that won't be allowed.
#		If it is empty then no restrictions are done here.
#
#	For a file to be uploaded it has to fulfill both the AllowedExtensions
#	and DeniedExtensions (that's it: not being denied) conditions.
#
#	- FileTypesPath: the virtual folder relative to the document root where
#		these resources will be located.
#		Attention: It must start and end with a slash: '/'
#
#	- FileTypesAbsolutePath: the physical path to the above folder. It must be
#		an absolute path.
#		If it's an empty string then it will be autocalculated.
#		Useful if you are using a virtual directory, symbolic link or alias.
#		Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
#		Attention: The above 'FileTypesPath' must point to the same directory.
#		Attention: It must end with a slash: '/'
#
#
#	- QuickUploadPath: the virtual folder relative to the document root where
#		these resources will be uploaded using the Upload tab in the resources
#		dialogs.
#		Attention: It must start and end with a slash: '/'
#
#	- QuickUploadAbsolutePath: the physical path to the above folder. It must be
#		an absolute path.
#		If it's an empty string then it will be autocalculated.
#		Useful if you are using a virtual directory, symbolic link or alias.
#		Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
#		Attention: The above 'QuickUploadPath' must point to the same directory.
#		Attention: It must end with a slash: '/'

AllowedExtensions['File'] 		= ['7z','aiff','asf','avi','bmp','csv','doc','fla','flv','gif','gz','gzip','jpeg','jpg','mid','mov','mp3','mp4','mpc','mpeg','mpg','ods','odt','pdf','png','ppt','pxd','qt','ram','rar','rm','rmi','rmvb','rtf','sdc','sitd','swf','sxc','sxw','tar','tgz','tif','tiff','txt','vsd','wav','wma','wmv','xls','xml','zip']
DeniedExtensions['File'] 		= []
FileTypesPath['File'] 			= UserFilesPath + 'file/'
FileTypesAbsolutePath['File'] 	= (not UserFilesAbsolutePath == '') and (UserFilesAbsolutePath + 'file/') or ''
QuickUploadPath['File']			= FileTypesPath['File']
QuickUploadAbsolutePath['File']	= FileTypesAbsolutePath['File']

AllowedExtensions['Image']		= ['bmp','gif','jpeg','jpg','png']
DeniedExtensions['Image']		= []
FileTypesPath['Image']			= UserFilesPath + 'image/'
FileTypesAbsolutePath['Image']	= (not UserFilesAbsolutePath == '') and UserFilesAbsolutePath + 'image/' or ''
QuickUploadPath['Image']		= FileTypesPath['Image']
QuickUploadAbsolutePath['Image']= FileTypesAbsolutePath['Image']

AllowedExtensions['Flash']		= ['swf','flv']
DeniedExtensions['Flash']		= []
FileTypesPath['Flash']			= UserFilesPath + 'flash/'
FileTypesAbsolutePath['Flash']	= ( not UserFilesAbsolutePath == '') and UserFilesAbsolutePath + 'flash/' or ''
QuickUploadPath['Flash']		= FileTypesPath['Flash']
QuickUploadAbsolutePath['Flash']= FileTypesAbsolutePath['Flash']

AllowedExtensions['Media']		= ['aiff','asf','avi','bmp','fla', 'flv','gif','jpeg','jpg','mid','mov','mp3','mp4','mpc','mpeg','mpg','png','qt','ram','rm','rmi','rmvb','swf','tif','tiff','wav','wma','wmv']
DeniedExtensions['Media']		= []
FileTypesPath['Media']			= UserFilesPath + 'media/'
FileTypesAbsolutePath['Media']	= ( not UserFilesAbsolutePath == '') and UserFilesAbsolutePath + 'media/' or ''
QuickUploadPath['Media']		= FileTypesPath['Media']
QuickUploadAbsolutePath['Media']= FileTypesAbsolutePath['Media']
