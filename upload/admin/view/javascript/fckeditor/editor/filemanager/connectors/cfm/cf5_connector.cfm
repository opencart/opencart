<cfsetting enablecfoutputonly="yes" showdebugoutput="no">
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
 * File Browser connector for ColdFusion 5.
 * (based on the original CF connector by Hendrik Kramer - hk@lwd.de)
 *
 * Note:
 * FCKeditor requires that the connector responds with UTF-8 encoded XML.
 * As ColdFusion 5 does not fully support UTF-8 encoding, we force ASCII
 * file and folder names in this connector to allow CF5 send a UTF-8
 * encoded response - code points under 127 in UTF-8 are stored using a
 * single byte, using the same encoding as ASCII, which is damn handy.
 * This is all grand for the English speakers, like meself, but I dunno
 * how others are gonna take to it. Well, the previous version of this
 * connector already did this with file names and nobody seemed to mind,
 * so fingers-crossed nobody will mind their folder names being munged too.
 *
--->

<cfparam name="url.command">
<cfparam name="url.type">
<cfparam name="url.currentFolder">
<!--- note: no serverPath url parameter - see config.cfm if you need to set the serverPath manually --->

<cfinclude template="config.cfm">

<cfscript>
	userFilesPath = config.userFilesPath;

	if ( userFilesPath eq "" )
	{
		userFilesPath = "/userfiles/";
	}

	// make sure the user files path is correctly formatted
	userFilesPath = replace(userFilesPath, "\", "/", "ALL");
	userFilesPath = replace(userFilesPath, '//', '/', 'ALL');
	if ( right(userFilesPath,1) NEQ "/" )
	{
		userFilesPath = userFilesPath & "/";
	}
	if ( left(userFilesPath,1) NEQ "/" )
	{
		userFilesPath = "/" & userFilesPath;
	}

	// make sure the current folder is correctly formatted
	url.currentFolder = replace(url.currentFolder, "\", "/", "ALL");
	url.currentFolder = replace(url.currentFolder, '//', '/', 'ALL');
	if ( right(url.currentFolder,1) neq "/" )
	{
		url.currentFolder = url.currentFolder & "/";
	}
	if ( left(url.currentFolder,1) neq "/" )
	{
		url.currentFolder = "/" & url.currentFolder;
	}

	if ( find("/",getBaseTemplatePath()) neq 0 )
	{
		fs = "/";
	}
	else
	{
		fs = "\";
	}

	// Get the base physical path to the web root for this application. The code to determine the path automatically assumes that
	// the "FCKeditor" directory in the http request path is directly off the web root for the application and that it's not a
	// virtual directory or a symbolic link / junction. Use the serverPath config setting to force a physical path if necessary.
	if ( len(config.serverPath) )
	{
		serverPath = config.serverPath;

		if ( right(serverPath,1) neq fs )
		{
			serverPath = serverPath & fs;
		}
	}
	else
	{
		serverPath = replaceNoCase(getBaseTemplatePath(),replace(cgi.script_name,"/",fs,"all"),"") & replace(userFilesPath,"/",fs,"all");
	}

	rootPath = left( serverPath, Len(serverPath) - Len(userFilesPath) ) ;
	xmlContent = ""; // append to this string to build content
	invalidName = false;
</cfscript>


<cfif not config.enabled>

	<cfset xmlContent = "<Error number=""1"" text=""This connector is disabled. Please check the 'editor/filemanager/connectors/cfm/config.cfm' file"" />">

<cfelseif find("..",url.currentFolder) or find("\",url.currentFolder) or REFind('(/\.)|(//)|[[:cntrl:]]|([\\:\*\?\"<>])', url.currentFolder)>

	<cfset invalidName = true>
	<cfset xmlContent = "<Error number=""102"" />">

<cfelseif isDefined("Config.ConfigAllowedCommands") and not ListFind(Config.ConfigAllowedCommands, url.command)>

	<cfset invalidName = true>
	<cfset xmlContent = '<Error number="1" text="The &quot;' & HTMLEditFormat(url.command) & '&quot; command isn''t allowed" />'>

<cfelseif isDefined("Config.ConfigAllowedTypes") and not ListFind(Config.ConfigAllowedTypes, url.type)>

	<cfset invalidName = true>
	<cfset xmlContent = '<Error number="1" text="Invalid type specified" />'>

</cfif>

<cfset resourceTypeUrl = "">
<cfif not len(xmlContent)>
<cfset resourceTypeUrl = rereplace( replace( Config.FileTypesPath[url.type], fs, "/", "all"), "/$", "") >

<cfif isDefined( "Config.FileTypesAbsolutePath" )
		and structkeyexists( Config.FileTypesAbsolutePath, url.type )
		and Len( Config.FileTypesAbsolutePath[url.type] )>

			<cfset userFilesServerPath = Config.FileTypesAbsolutePath[url.type] & url.currentFolder>
<cfelse>
	<cftry>
	<cfset userFilesServerPath = expandpath( resourceTypeUrl ) & url.currentFolder>
	<!--- Catch: Parameter 1 of function ExpandPath must be a relative path --->
	<cfcatch type="any">
		<cfset userFilesServerPath = rootPath & Config.FileTypesPath[url.type] & url.currentFolder>
	</cfcatch>
	</cftry>
</cfif>

<cfset userFilesServerPath = replace( userFilesServerPath, "/", fs, "all" ) >
<!--- get rid of double directory separators --->
<cfset userFilesServerPath = replace( userFilesServerPath, fs & fs, fs, "all") >

<cfset resourceTypeDirectory = left( userFilesServerPath, Len(userFilesServerPath) - Len(url.currentFolder) )>
</cfif>

<cfif not len(xmlContent) and not directoryexists(resourceTypeDirectory)>
	<!--- create directories in physical path if they don't already exist --->
	<cfset currentPath = "">
	<cftry>
		<cfloop list="#resourceTypeDirectory#" index="name" delimiters="#fs#">
			<cfif currentPath eq "" and fs eq "\">
				<!--- Without checking this, we would have in Windows \C:\ --->
				<cfif not directoryExists(name)>
					<cfdirectory action="create" directory="#name#" mode="755">
				</cfif>
			<cfelse>
				<cfif not directoryExists(currentPath & fs & name)>
					<cfdirectory action="create" directory="#currentPath##fs##name#" mode="755">
				</cfif>
			</cfif>

			<cfif fs eq "\" and currentPath eq "">
				<cfset currentPath = name>
			<cfelse>
				<cfset currentPath = currentPath & fs & name>
			</cfif>
		</cfloop>

	<cfcatch type="any">

		<!--- this should only occur as a result of a permissions problem --->
		<cfset xmlContent = "<Error number=""103"" />">

	</cfcatch>

	</cftry>
</cfif>

<cfif not len(xmlContent)>

	<!--- no errors thus far - run command --->

	<!--- we need to know the physical path to the current folder for all commands --->
	<cfset currentFolderPath = userFilesServerPath>

	<cfswitch expression="#url.command#">

		<cfcase value="FileUpload">
			<cfset config_included = true >
			<cfinclude template="cf5_upload.cfm">
			<cfabort>
		</cfcase>


		<cfcase value="GetFolders">

			<!--- Sort directories first, name ascending --->
			<cfdirectory
				action="list"
				directory="#currentFolderPath#"
				name="qDir"
				sort="type,name">

			<cfscript>
				i=1;
				folders = "";
				while( i lte qDir.recordCount ) {
					if( not compareNoCase( qDir.type[i], "FILE" ))
						break;
					if( not listFind(".,..", qDir.name[i]) )
						folders = folders & '<Folder name="#HTMLEditFormat( qDir.name[i] )#" />';
					i=i+1;
				}

				xmlContent = xmlContent & '<Folders>' & folders & '</Folders>';
			</cfscript>

		</cfcase>


		<cfcase value="GetFoldersAndFiles">

			<!--- Sort directories first, name ascending --->
			<cfdirectory
				action="list"
				directory="#currentFolderPath#"
				name="qDir"
				sort="type,name">

			<cfscript>
				i=1;
				folders = "";
				files = "";
				while( i lte qDir.recordCount ) {
					if( not compareNoCase( qDir.type[i], "DIR" ) and not listFind(".,..", qDir.name[i]) ) {
						folders = folders & '<Folder name="#HTMLEditFormat(qDir.name[i])#" />';
					} else if( not compareNoCase( qDir.type[i], "FILE" ) ) {
						fileSizeKB = round(qDir.size[i] / 1024);
						files = files & '<File name="#HTMLEditFormat(qDir.name[i])#" size="#IIf( fileSizeKB GT 0, DE( fileSizeKB ), 1)#" />';
					}
					i=i+1;
				}

				xmlContent = xmlContent & '<Folders>' & folders & '</Folders>';
				xmlContent = xmlContent & '<Files>' & files & '</Files>';
			</cfscript>

		</cfcase>


		<cfcase value="CreateFolder">

			<cfparam name="url.newFolderName" default="">

			<cfscript>
				newFolderName = url.newFolderName;
				if( reFind("[^A-Za-z0-9_\-\.]", newFolderName) ) {
					// Munge folder name same way as we do the filename
					// This means folder names are always US-ASCII so we don't have to worry about CF5 and UTF-8
					newFolderName = reReplace(newFolderName, "[^A-Za-z0-9\-\.]", "_", "all");
					newFolderName = reReplace(newFolderName, "_{2,}", "_", "all");
					newFolderName = reReplace(newFolderName, "([^_]+)_+$", "\1", "all");
					newFolderName = reReplace(newFolderName, "$_([^_]+)$", "\1", "all");
					newFolderName = reReplace(newFolderName, '\.+', "_", "all" );
				}
			</cfscript>

			<cfif not len(newFolderName) or len(newFolderName) gt 255>
				<cfset errorNumber = 102>
			<cfelseif directoryExists(currentFolderPath & newFolderName)>
				<cfset errorNumber = 101>
			<cfelseif reFind("^\.\.",newFolderName)>
				<cfset errorNumber = 102>
			<cfelse>
				<cfset errorNumber = 0>

				<cftry>
					<cfdirectory
						action="create"
						directory="#currentFolderPath##newFolderName#"
						mode="755">
					<cfcatch>
						<!---
						un-resolvable error numbers in ColdFusion:
						* 102 : Invalid folder name.
						* 103 : You have no permissions to create the folder.
						--->
						<cfset errorNumber = 110>
					</cfcatch>
				</cftry>
			</cfif>

			<cfset xmlContent = xmlContent & '<Error number="#errorNumber#" />'>

		</cfcase>

		<cfdefaultcase>
			<cfthrow type="fckeditor.connector" message="Illegal command: #url.command#">
		</cfdefaultcase>

	</cfswitch>
</cfif>

<cfscript>
	xmlHeader = '<?xml version="1.0" encoding="utf-8" ?>';
	if (invalidName) {
		xmlHeader = xmlHeader & '<Connector>';
	}
	else {
		xmlHeader = xmlHeader & '<Connector command="#url.command#" resourceType="#url.type#">';
		xmlHeader = xmlHeader & '<CurrentFolder path="#url.currentFolder#" url="#resourceTypeUrl##url.currentFolder#" />';
	}
	xmlFooter = '</Connector>';
</cfscript>

<cfheader name="Expires" value="#GetHttpTimeString(Now())#">
<cfheader name="Pragma" value="no-cache">
<cfheader name="Cache-Control" value="no-cache, no-store, must-revalidate">
<cfcontent reset="true" type="text/xml; charset=UTF-8">
<cfoutput>#xmlHeader##xmlContent##xmlFooter#</cfoutput>
