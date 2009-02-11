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
 * This file include the functions that handle the Command requests
 * in the ColdFusion Connector (MX 6.0 and above).
--->

<cffunction name="FileUpload" returntype="void" output="true">
	<cfargument name="resourceType" type="string" required="yes" default="">
	<cfargument name="currentFolder" type="string" required="yes" default="">
	<cfargument name="sCommand" type="string" required="yes" default="">

	<cfset var sFileName = "">
	<cfset var sFilePart = "">
	<cfset var sFileExt = "">
	<cfset var sFileUrl = "">
	<cfset var sTempDir = "">
	<cfset var sTempFilePath = "">
	<cfset var errorNumber = 0>
	<cfset var customMsg = 0>
	<cfset var counter = 0>
	<cfset var destination = "">

	<cftry>
		<cfif isDefined( "REQUEST.Config.TempDirectory" )>
			<cfset sTempDir = REQUEST.Config.TempDirectory>
		<cfelse>
			<cfset sTempDir = GetTempDirectory()>
		</cfif>
		<cfif NOT DirectoryExists (sTempDir)>
			<cfthrow message="Invalid temporary directory: #sTempDir#">
		</cfif>

		<cffile action="UPLOAD" filefield="NewFile" destination="#sTempDir#" nameconflict="makeunique" mode="0755" />
		<cfset sTempFilePath = CFFILE.ServerDirectory & REQUEST.fs & CFFILE.ServerFile>

		<!--- Map the virtual path to the local server path. --->
		<cfset sServerDir = ServerMapFolder( ARGUMENTS.resourceType, ARGUMENTS.currentFolder, ARGUMENTS.sCommand) >
		<!--- Get the uploaded file name. --->
		<cfset sFileName = SanitizeFileName( CFFILE.ClientFile ) >
		<cfset sOriginalFileName = sFileName >

		<cfif isDefined( "REQUEST.Config.SecureImageUploads" ) and REQUEST.Config.SecureImageUploads>
			<cfif not IsImageValid( sTempFilePath, CFFILE.ClientFileExt )>
				<cftry>
				<cffile action="delete" file="#sTempFilePath#">
				<cfcatch type="any">
				</cfcatch>
				</cftry>
				<cfthrow errorcode="202" type="fckeditor">
			</cfif>
		</cfif>

		<cfif isDefined( "REQUEST.Config.HtmlExtensions" ) and not listFindNoCase( REQUEST.Config.HtmlExtensions, CFFILE.ClientFileExt )>
			<cfif DetectHtml( sTempFilePath )>
				<cftry>
				<cffile action="delete" file="#sTempFilePath#">
				<cfcatch type="any">
				</cfcatch>
				</cftry>
				<cfthrow errorcode="202" type="fckeditor">
			</cfif>
		</cfif>

		<cfif not IsAllowedExt( CFFILE.ClientFileExt, ARGUMENTS.resourceType )>
			<cftry>
			<cffile action="delete" file="#sTempFilePath#">
			<cfcatch type="any">
			</cfcatch>
			</cftry>
			<cfthrow errorcode="202" type="fckeditor">
		</cfif>

		<!--- When the original filename already exists, add numbers (0), (1), (2), ... at the end of the filename. --->
		<cfscript>
			sFileExt = GetExtension( sFileName ) ;
			sFilePart = RemoveExtension( sFileName );
			while( fileExists( sServerDir & sFileName ) )
			{
				counter = counter + 1;
				sFileName = sFilePart & '(#counter#).' & CFFILE.ClientFileExt;
				errorNumber = 201;
			}
		</cfscript>

 		<cfset destination = sServerDir & sFileName>

		<cflock name="#destination#" timeout="30" type="Exclusive">
		<cftry>
			<cffile action="move" source="#sTempFilePath#" destination="#destination#" mode="755">
			<!--- omit CF 6.1 error during moving uploaded file, just copy that file instead of moving --->
			<cfcatch type="any">
				<cffile action="copy" source="#sTempFilePath#" destination="#destination#" mode="755">
			</cfcatch>
		</cftry>
		</cflock>

		<cfset sFileUrl = CombinePaths( GetResourceTypePath( ARGUMENTS.resourceType, sCommand ) , ARGUMENTS.currentFolder ) >
		<cfset sFileUrl = CombinePaths( sFileUrl , sFileName ) >

		<cfcatch type="fckeditor">
			<cfset errorNumber = CFCATCH.ErrorCode>
		</cfcatch>

		<cfcatch type="any">
			<cfset errorNumber = "1">
			<cfset customMsg = CFCATCH.Message >
		</cfcatch>

	</cftry>

	<cfset SendUploadResults( errorNumber, sFileUrl, sFileName, customMsg ) >
</cffunction>

<cffunction name="GetFolders" returntype="void" output="true">
	<cfargument name="resourceType" type="String" required="true">
	<cfargument name="currentFolder" type="String" required="true">

	<cfset var i = 1>
	<cfset var folders = "">
	<!--- Map the virtual path to the local server path --->
	<cfset var sServerDir = ServerMapFolder( ARGUMENTS.resourceType, ARGUMENTS.currentFolder, "GetFolders" ) >

	<!--- Sort directories first, name ascending --->
	<cfdirectory action="list" directory="#sServerDir#" name="qDir" sort="type,name">
	<cfscript>
		while( i lte qDir.recordCount )
		{
			if( compareNoCase( qDir.type[i], "FILE" ) and not listFind( ".,..", qDir.name[i] ) )
			{
				folders = folders & '<Folder name="#HTMLEditFormat( qDir.name[i] )#" />' ;
			}
			i = i + 1;
		}
	</cfscript>
	<cfoutput><Folders>#folders#</Folders></cfoutput>
</cffunction>

<cffunction name="GetFoldersAndfiles" returntype="void" output="true">
	<cfargument name="resourceType" type="String" required="true">
	<cfargument name="currentFolder" type="String" required="true">

	<cfset var i = 1>
	<cfset var folders = "">
	<cfset var files = "">
	<!--- Map the virtual path to the local server path --->
	<cfset var sServerDir = ServerMapFolder( ARGUMENTS.resourceType, ARGUMENTS.currentFolder, "GetFolders" ) >

	<!--- Sort directories first, name ascending --->
	<cfdirectory action="list" directory="#sServerDir#" name="qDir" sort="type,name">
	<cfscript>
		while( i lte qDir.recordCount )
		{
			if( not compareNoCase( qDir.type[i], "DIR" ) and not listFind( ".,..", qDir.name[i] ) )
			{
				folders = folders & '<Folder name="#HTMLEditFormat(qDir.name[i])#" />' ;
			}
			else if( not compareNoCase( qDir.type[i], "FILE" ) )
			{
				fileSizeKB = round(qDir.size[i] / 1024) ;
				files = files & '<File name="#HTMLEditFormat(qDir.name[i])#" size="#IIf( fileSizeKB GT 0, DE( fileSizeKB ), 1)#" />' ;
			}
			i = i + 1 ;
		}
	</cfscript>
	<cfoutput><Folders>#folders#</Folders></cfoutput>
	<cfoutput><Files>#files#</Files></cfoutput>
</cffunction>

<cffunction name="CreateFolder" returntype="void" output="true">
	<cfargument name="resourceType" required="true" type="string">
	<cfargument name="currentFolder" required="true" type="string">

	<cfset var sNewFolderName = url.newFolderName >
	<cfset var sServerDir = "" >
	<cfset var errorNumber = 0>
	<cfset var sErrorMsg = "">
	<cfset var currentFolderPath = ServerMapFolder( ARGUMENTS.resourceType, ARGUMENTS.currentFolder, 'CreateFolder' )>

	<cfparam name="url.newFolderName" default="">

	<cfscript>
		sNewFolderName = SanitizeFolderName( sNewFolderName ) ;
	</cfscript>

	<cfif not len( sNewFolderName ) or len( sNewFolderName ) gt 255>
		<cfset errorNumber = 102>
	<cfelseif directoryExists( currentFolderPath & sNewFolderName )>
		<cfset errorNumber = 101>
	<cfelseif find( "..", sNewFolderName )>
		<cfset errorNumber = 103>
	<cfelse>
		<cfset errorNumber = 0>

		<!--- Map the virtual path to the local server path of the current folder. --->
		<cfset sServerDir = currentFolderPath & sNewFolderName >

		<cftry>
			<cfdirectory action="create" directory="#currentFolderPath##sNewFolderName#" mode="755">
			<cfcatch type="any">
			<!---
				un-resolvable error numbers in ColdFusion:
				* 102 : Invalid folder name.
				* 103 : You have no permissions to create the folder.
			--->
			<cfset errorNumber = 110>
			</cfcatch>
		</cftry>
	</cfif>

	<cfoutput><Error number="#errorNumber#" originalDescription="#HTMLEditFormat(sErrorMsg)#" /></cfoutput>
</cffunction>
