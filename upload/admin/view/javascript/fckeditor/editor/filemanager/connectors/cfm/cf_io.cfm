<cfsetting enablecfoutputonly="Yes">
<!---
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
 * This file include IO specific functions used by the ColdFusion Connector (MX 6.0 and above).
 *
--->

<cffunction name="CombinePaths" returntype="String" output="true">
	<cfargument name="sBasePath" required="true">
	<cfargument name="sFolder" required="true">
	<cfset sBasePath = RemoveFromEnd( sBasePath, "/" )>
	<cfset sBasePath = RemoveFromEnd( sBasePath, "\" )>
	<cfreturn sBasePath & "/" & RemoveFromStart( ARGUMENTS.sFolder, '/' )>
</cffunction>

<cffunction name="GetResourceTypePath" returntype="String" output="false">
	<cfargument name="resourceType" required="true">
	<cfargument name="sCommand" required="true">

	<cfif ARGUMENTS.sCommand eq "QuickUpload">
		<cfreturn REQUEST.Config['QuickUploadPath'][ARGUMENTS.resourceType]>
	<cfelse>
		<cfreturn REQUEST.Config['FileTypesPath'][ARGUMENTS.resourceType]>
	</cfif>
</cffunction>

<cffunction name="GetResourceTypeDirectory" returntype="String" output="false">
	<cfargument name="resourceType" required="true">
	<cfargument name="sCommand" required="true">

	<cfif ARGUMENTS.sCommand eq "QuickUpload">
		<cfif isDefined( "REQUEST.Config.QuickUploadAbsolutePath" )
			and structkeyexists( REQUEST.Config.QuickUploadAbsolutePath, ARGUMENTS.resourceType )
			and Len( REQUEST.Config.QuickUploadAbsolutePath[ARGUMENTS.resourceType] )>
				<cfreturn REQUEST.Config.QuickUploadAbsolutePath[ARGUMENTS.resourceType]>
		</cfif>

		<cfreturn expandpath( REQUEST.Config.QuickUploadPath[ARGUMENTS.resourceType] )>
	<cfelse>
		<cfif isDefined( "REQUEST.Config.FileTypesAbsolutePath" )
			and structkeyexists( REQUEST.Config.FileTypesAbsolutePath, ARGUMENTS.resourceType )
			and Len( REQUEST.Config.FileTypesAbsolutePath[ARGUMENTS.resourceType] )>
				<cfreturn REQUEST.Config.FileTypesAbsolutePath[ARGUMENTS.resourceType]>
		</cfif>

		<cfreturn expandpath( REQUEST.Config.FileTypesPath[ARGUMENTS.resourceType] )>
	</cfif>
</cffunction>

<cffunction name="GetUrlFromPath" returntype="String" output="false">
	<cfargument name="resourceType" required="true">
	<cfargument name="folderPath" required="true">
	<cfargument name="sCommand" required="true">

	<cfreturn CombinePaths( GetResourceTypePath( ARGUMENTS.resourceType, ARGUMENTS.sCommand ), ARGUMENTS.folderPath )>
</cffunction>

<cffunction name="RemoveExtension" output="false" returntype="String">
	<cfargument name="fileName" required="true">
	<cfset var pos = find( ".", reverse ( ARGUMENTS.fileName ) )>

	<cfreturn mid( ARGUMENTS.fileName, 1, Len( ARGUMENTS.fileName ) - pos ) >
</cffunction>

<cffunction name="GetExtension" output="false" returntype="String">
	<cfargument name="fileName" required="true">
	<cfset var pos = find( ".", reverse ( ARGUMENTS.fileName ) )>

	<cfif not pos>
		<cfreturn "">
	</cfif>

	<cfreturn mid( ARGUMENTS.fileName, pos, Len( ARGUMENTS.fileName ) - pos ) >
</cffunction>

<cffunction name="ServerMapFolder" returntype="String" output="false">
	<cfargument name="resourceType" required="true">
	<cfargument name="folderPath" required="true">
	<cfargument name="sCommand" required="true">

	<!--- Get the resource type directory. --->
	<cfset var sResourceTypePath = GetResourceTypeDirectory( ARGUMENTS.resourceType, ARGUMENTS.sCommand ) >
	<!--- Ensure that the directory exists. --->
	<cfset var sErrorMsg = CreateServerFolder( sResourceTypePath ) >

	<cfif sErrorMsg neq ''>
		<cfset SendError( 1, 'Error creating folder "' & sResourceTypePath & '" (' & sErrorMsg & ')' )>
	</cfif>

	<!--- Return the resource type directory combined with the required path. --->
	<cfreturn CombinePaths( sResourceTypePath , ARGUMENTS.folderPath )>
</cffunction>

<cffunction name="GetParentFolder" returntype="string" output="false">
	<cfargument name="folderPath" required="true">

	<cfreturn rereplace(ARGUMENTS.folderPath, "[/\\\\][^/\\\\]+[/\\\\]?$", "")>
</cffunction>

<cffunction name="CreateServerFolder" returntype="String" output="false">
	<cfargument name="folderPath">

	<!--- Ensure the folder path has no double-slashes, or mkdir may fail on certain platforms --->
	<cfset folderPath = rereplace(ARGUMENTS.folderPath, "//+", "/", "all")>

	<cfif directoryexists(ARGUMENTS.folderPath) or fileexists(ARGUMENTS.folderPath)>
		<cfreturn "">
	<cfelse>
		<cftry>
			<cfdirectory action="create" mode="0755" directory="#ARGUMENTS.folderPath#">
		<cfcatch type="any">
			<cfreturn CFCATCH.Message>
		</cfcatch>
		</cftry>
	</cfif>

	<cfreturn "">
</cffunction>

<cffunction name="IsAllowedExt" returntype="boolean" output="false">
	<cfargument name="sExtension" required="true">
	<cfargument name="resourceType" required="true">

	<cfif isDefined( "REQUEST.Config.AllowedExtensions." & ARGUMENTS.resourceType )
			and listLen( REQUEST.Config.AllowedExtensions[ARGUMENTS.resourceType] )
			and not listFindNoCase( REQUEST.Config.AllowedExtensions[ARGUMENTS.resourceType], ARGUMENTS.sExtension )>
			<cfreturn false>
	</cfif>

	<cfif isDefined( "REQUEST.Config.DeniedExtensions." & ARGUMENTS.resourceType )
			and listLen( REQUEST.Config.DeniedExtensions[ARGUMENTS.resourceType] )
			and listFindNoCase( REQUEST.Config.DeniedExtensions[ARGUMENTS.resourceType], ARGUMENTS.sExtension )>
			<cfreturn false>
	</cfif>

	<cfreturn true>
</cffunction>

<cffunction name="IsAllowedType" returntype="boolean" output="false">
	<cfargument name="resourceType">

	<cfif not listFindNoCase( REQUEST.Config.ConfigAllowedTypes, ARGUMENTS.resourceType )>
		<cfreturn false>
	</cfif>

	<cfreturn true>
</cffunction>

<cffunction name="IsAllowedCommand" returntype="boolean" output="true">
	<cfargument name="sCommand" required="true" type="String">

	<cfif not listFindNoCase( REQUEST.Config.ConfigAllowedCommands, ARGUMENTS.sCommand )>
		<cfreturn false>
	</cfif>

	<cfreturn true>
</cffunction>

<cffunction name="GetCurrentFolder" returntype="String" output="true">
	<cfset var sCurrentFolder = "/">

	<cfif isDefined( "URL.CurrentFolder" )>
		<cfset sCurrentFolder = URL.CurrentFolder>
	</cfif>

	<!--- Check the current folder syntax (must begin and start with a slash). --->
	<cfif not refind( "/$", sCurrentFolder)>
		<cfset sCurrentFolder = sCurrentFolder & "/">
	</cfif>

	<cfif not refind( "^/", sCurrentFolder )>
		<cfset sCurrentFolder = "/" & sCurrentFolder>
	</cfif>

	<!--- Ensure the folder path has no double-slashes, or mkdir may fail on certain platforms --->
	<cfset sCurrentFolder = rereplace( sCurrentFolder, "//+", "/", "all" )>

	<cfif find( "..", sCurrentFolder) or find( "\", sCurrentFolder) or REFind('(/\.)|(//)|[[:cntrl:]]|([\\:\*\?\"<>])', sCurrentFolder)>
		<cfif URL.Command eq "FileUpload" or URL.Command eq "QuickUpload">
			<cfset SendUploadResults( 102, "", "", "") >
		<cfelse>
			<cfset SendError( 102, "" )>
		</cfif>
	</cfif>

	<cfreturn sCurrentFolder>
</cffunction>

<cffunction name="SanitizeFolderName" returntype="String" output="false">
	<cfargument name="sNewFolderName" required="true">

	<!--- Do a cleanup of the folder name to avoid possible problems --->
	<!--- Remove . \ / | : ? * " < > and control characters --->
	<cfset sNewFolderName = rereplace( sNewFolderName, '\.+|\\+|\/+|\|+|\:+|\?+|\*+|"+|<+|>+|[[:cntrl:]]+', "_", "all" )>

	<cfreturn sNewFolderName>
</cffunction>

<cffunction name="BinaryFileRead" returntype="String" output="true">
	<cfargument name="fileName" required="true" type="string">
	<cfargument name="bytes" required="true" type="Numeric">

	<cfscript>
	var chunk = "";
	var fileReaderClass = "";
	var fileReader = "";
	var file = "";
	var done = false;
	var counter = 0;
	var byteArray = "";

	if( not fileExists( ARGUMENTS.fileName ) )
	{
		return "" ;
	}

	if (REQUEST.CFVersion gte 8)
	{
		 file  = FileOpen( ARGUMENTS.fileName, "readbinary" ) ;
		 byteArray = FileRead( file, 1024 ) ;
		 chunk = toString( toBinary( toBase64( byteArray ) ) ) ;
		 FileClose( file ) ;
	}
	else
	{
		fileReaderClass = createObject("java", "java.io.FileInputStream");
		fileReader = fileReaderClass.init(fileName);

		while(not done)
		{
			char = fileReader.read();
			counter = counter + 1;
			if ( char eq -1 or counter eq ARGUMENTS.bytes)
			{
				done = true;
			}
			else
			{
				chunk = chunk & chr(char) ;
			}
		}
	}
	</cfscript>

	<cfreturn chunk>
</cffunction>

<cffunction name="SendUploadResults" returntype="String" output="true">
	<cfargument name="errorNumber" required="true" type="Numeric">
	<cfargument name="fileUrl" required="false" type="String" default="">
	<cfargument name="fileName" required="false" type="String" default="">
	<cfargument name="customMsg" required="false" type="String" default="">

	<cfif errorNumber and errorNumber neq 201>
		<cfset fileUrl = "">
		<cfset fileName = "">
	</cfif>
	<!--- Minified version of the document.domain automatic fix script (#1919).
	The original script can be found at _dev/domain_fix_template.js --->
	<cfoutput>
<script type="text/javascript">
(function(){var d=document.domain;while (true){try{var A=window.parent.document.domain;break;}catch(e) {};d=d.replace(/.*?(?:\.|$)/,'');if (d.length==0) break;try{document.domain=d;}catch (e){break;}}})();
window.parent.OnUploadCompleted( #errorNumber#, "#JSStringFormat(fileUrl)#", "#JSStringFormat(fileName)#", "#JSStringFormat(customMsg)#" );
</script>
	</cfoutput>
	<cfabort>
</cffunction>

<cffunction name="SanitizeFileName" returntype="String" output="false">
	<cfargument name="sNewFileName" required="true">

	<cfif isDefined("REQUEST.Config.ForceSingleExtension") and REQUEST.Config.ForceSingleExtension>
		<cfset sNewFileName = rereplace( sNewFileName, '\.(?![^.]*$)', "_", "all" )>
	</cfif>

	<!--- Do a cleanup of the file name to avoid possible problems --->
	<!--- Remove \ / | : ? * " < > and control characters --->
	<cfset sNewFileName = rereplace( sNewFileName, '\\[.]+|\\+|\/+|\|+|\:+|\?+|\*+|"+|<+|>+|[[:cntrl:]]+', "_", "all" )>

	<cfreturn sNewFileName>
</cffunction>
