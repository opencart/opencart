<cfsetting enablecfoutputonly="Yes">
<!---
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
 * Configuration file for the ColdFusion Connector (all versions).
--->

<cfscript>
	Config = StructNew() ;

	// SECURITY: You must explicitly enable this "connector". (Set enabled to "true")
	Config.Enabled = false ;


	// Path to uploaded files relative to the document root.
	Config.UserFilesPath = "/userfiles/" ;

	// Use this to force the server path if FCKeditor is not running directly off
	// the root of the application or the FCKeditor directory in the URL is a virtual directory
	// or a symbolic link / junction
	// Example: C:\inetpub\wwwroot\myDocs\
	Config.ServerPath = "" ;

	// Due to security issues with Apache modules, it is recommended to leave the
	// following setting enabled.
	Config.ForceSingleExtension = true ;

	// Perform additional checks for image files - if set to true, validate image size
	// (This feature works in MX 6.0 and above)
	Config.SecureImageUploads = true;

	// What the user can do with this connector
	Config.ConfigAllowedCommands 			= "QuickUpload,FileUpload,GetFolders,GetFoldersAndFiles,CreateFolder" ;

	//Allowed Resource Types
	Config.ConfigAllowedTypes 				= "File,Image,Flash,Media" ;

	// For security, HTML is allowed in the first Kb of data for files having the
	// following extensions only.
	// (This feature works in MX 6.0 and above))
	Config.HtmlExtensions					= "html,htm,xml,xsd,txt,js" ;

	//Due to known issues with GetTempDirectory function, it is
	//recommended to set this vairiable to a valid directory
	//instead of using the GetTempDirectory function
	//(used by MX 6.0 and above)
	Config.TempDirectory = GetTempDirectory();

//	Configuration settings for each Resource Type
//
//	- AllowedExtensions: the possible extensions that can be allowed.
//		If it is empty then any file type can be uploaded.
//	- DeniedExtensions: The extensions that won't be allowed.
//		If it is empty then no restrictions are done here.
//
//	For a file to be uploaded it has to fulfill both the AllowedExtensions
//	and DeniedExtensions (that's it: not being denied) conditions.
//
//	- FileTypesPath: the virtual folder relative to the document root where
//		these resources will be located.
//		Attention: It must start and end with a slash: '/'
//
//	- FileTypesAbsolutePath: the physical path to the above folder. It must be
//		an absolute path.
//		If it's an empty string then it will be autocalculated.
//		Usefull if you are using a virtual directory, symbolic link or alias.
//		Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
//		Attention: The above 'FileTypesPath' must point to the same directory.
//		Attention: It must end with a slash: '/'
//
//
//	 - QuickUploadPath: the virtual folder relative to the document root where
//		these resources will be uploaded using the Upload tab in the resources
//		dialogs.
//		Attention: It must start and end with a slash: '/'
//
//	 - QuickUploadAbsolutePath: the physical path to the above folder. It must be
//		an absolute path.
//		If it's an empty string then it will be autocalculated.
//		Usefull if you are using a virtual directory, symbolic link or alias.
//		Examples: 'C:\\MySite\\userfiles\\' or '/root/mysite/userfiles/'.
//		Attention: The above 'QuickUploadPath' must point to the same directory.
//		Attention: It must end with a slash: '/'

	Config.AllowedExtensions 				= StructNew() ;
	Config.DeniedExtensions 				= StructNew() ;
	Config.FileTypesPath 					= StructNew() ;
	Config.FileTypesAbsolutePath 			= StructNew() ;
	Config.QuickUploadPath 					= StructNew() ;
	Config.QuickUploadAbsolutePath 			= StructNew() ;

	Config.AllowedExtensions["File"]	 	= "7z,aiff,asf,avi,bmp,csv,doc,fla,flv,gif,gz,gzip,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,ods,odt,pdf,png,ppt,pxd,qt,ram,rar,rm,rmi,rmvb,rtf,sdc,sitd,swf,sxc,sxw,tar,tgz,tif,tiff,txt,vsd,wav,wma,wmv,xls,xml,zip" ;
	Config.DeniedExtensions["File"] 		= "" ;
	Config.FileTypesPath["File"] 			= Config.UserFilesPath & 'file/' ;
	Config.FileTypesAbsolutePath["File"] 	= iif( Config.ServerPath eq "", de(""), de(Config.ServerPath & 'file/') ) ;
	Config.QuickUploadPath["File"] 			= Config.FileTypesPath["File"] ;
	Config.QuickUploadAbsolutePath["File"] 	= Config.FileTypesAbsolutePath["File"] ;

	Config.AllowedExtensions["Image"] 		= "bmp,gif,jpeg,jpg,png" ;
	Config.DeniedExtensions["Image"] 		= "" ;
	Config.FileTypesPath["Image"] 			= Config.UserFilesPath & 'image/' ;
	Config.FileTypesAbsolutePath["Image"] 	= iif( Config.ServerPath eq "", de(""), de(Config.ServerPath & 'image/') ) ;
	Config.QuickUploadPath["Image"] 		= Config.FileTypesPath["Image"] ;
	Config.QuickUploadAbsolutePath["Image"] = Config.FileTypesAbsolutePath["Image"] ;

	Config.AllowedExtensions["Flash"] 		= "swf,flv" ;
	Config.DeniedExtensions["Flash"] 		= "" ;
	Config.FileTypesPath["Flash"] 			= Config.UserFilesPath & 'flash/' ;
	Config.FileTypesAbsolutePath["Flash"] 	= iif( Config.ServerPath eq "", de(""), de(Config.ServerPath & 'flash/') ) ;
	Config.QuickUploadPath["Flash"] 		= Config.FileTypesPath["Flash"] ;
	Config.QuickUploadAbsolutePath["Flash"] = Config.FileTypesAbsolutePath["Flash"] ;

	Config.AllowedExtensions["Media"] 		= "aiff,asf,avi,bmp,fla,flv,gif,jpeg,jpg,mid,mov,mp3,mp4,mpc,mpeg,mpg,png,qt,ram,rm,rmi,rmvb,swf,tif,tiff,wav,wma,wmv" ;
	Config.DeniedExtensions["Media"] 		= "" ;
	Config.FileTypesPath["Media"] 			= Config.UserFilesPath & 'media/' ;
	Config.FileTypesAbsolutePath["Media"] 	= iif( Config.ServerPath eq "", de(""), de(Config.ServerPath & 'media/') ) ;
	Config.QuickUploadPath["Media"] 		= Config.FileTypesPath["Media"] ;
	Config.QuickUploadAbsolutePath["Media"] = Config.FileTypesAbsolutePath["Media"] ;
</cfscript>

<cftry>
<!--- code to maintain backwards compatibility with previous version of cfm connector --->
<cfif isDefined("application.userFilesPath")>

	<cflock scope="application" type="readonly" timeout="5">
		<cfset config.userFilesPath = application.userFilesPath>
	</cflock>

<cfelseif isDefined("server.userFilesPath")>

	<cflock scope="server" type="readonly" timeout="5">
		<cfset config.userFilesPath = server.userFilesPath>
	</cflock>

</cfif>

<!--- look for config struct in application and server scopes --->
<cfif isDefined("application.FCKeditor") and isStruct(application.FCKeditor)>

	<cflock scope="application" type="readonly" timeout="5">
	<cfset variables.FCKeditor = duplicate(application.FCKeditor)>
	</cflock>

<cfelseif isDefined("server.FCKeditor") and isStruct(server.FCKeditor)>

	<cflock scope="server" type="readonly" timeout="5">
	<cfset variables.FCKeditor = duplicate(server.FCKeditor)>
	</cflock>

</cfif>
	<!--- catch potential "The requested scope application has not been enabled" exception --->
	<cfcatch type="any">
	</cfcatch>
</cftry>

<cfif isDefined("FCKeditor")>

	<!--- copy key values from external to local config (i.e. override default config as required) --->
	<cfscript>
		function structCopyKeys(stFrom, stTo) {
			for ( key in stFrom ) {
				if ( isStruct(stFrom[key]) ) {
					structCopyKeys(stFrom[key],stTo[key]);
				} else {
					stTo[key] = stFrom[key];
				}
			}
		}
		structCopyKeys(FCKeditor, config);
	</cfscript>

</cfif>
