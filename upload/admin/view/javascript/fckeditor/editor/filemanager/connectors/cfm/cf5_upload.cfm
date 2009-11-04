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
 * This is the "File Uploader" for ColdFusion 5.
 * Based on connector.cfm by Mark Woods (mark@thickpaddy.com)
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

<cfparam name="url.command" default="QuickUpload">
<cfparam name="url.type" default="File">
<cfparam name="url.currentFolder" default="/">

<cfif url.command eq "QuickUpload">
	<cfset url.currentFolder = "/">
</cfif>

<cfif not isDefined("config_included")>
	<cfinclude template="config.cfm">
</cfif>

<cfscript>
	function SendUploadResults(errorNumber, fileUrl, fileName, customMsg)
	{
		WriteOutput('<script type="text/javascript">');
		// Minified version of the document.domain automatic fix script (#1919).
		// The original script can be found at _dev/domain_fix_template.js
		WriteOutput("(function(){var d=document.domain;while (true){try{var A=window.parent.document.domain;break;}catch(e) {};d=d.replace(/.*?(?:\.|$)/,'');if (d.length==0) break;try{document.domain=d;}catch (e){break;}}})();");
		WriteOutput('window.parent.OnUploadCompleted(' & errorNumber & ', "' & JSStringFormat(fileUrl) & '", "' & JSStringFormat(fileName) & '", "' & JSStringFormat(customMsg) & '");' );
		WriteOutput('</script>');
	}
</cfscript>

<cfif NOT config.enabled>
	<cfset SendUploadResults(1, "", "", "This file uploader is disabled. Please check the ""editor/filemanager/connectors/cfm/config.cfm"" file")>
	<cfabort>
</cfif>

<cfif isDefined("Config.ConfigAllowedCommands") and not ListFind(Config.ConfigAllowedCommands, url.command)>
	<cfset SendUploadResults(1, "", "", "The """ & url.command & """ command isn't allowed")>
	<cfabort>
</cfif>

<cfif isDefined("Config.ConfigAllowedTypes") and not ListFind(Config.ConfigAllowedTypes, url.type)>
	<cfset SendUploadResults(1, "", "", "The """ & url.type &  """ type isn't allowed")>
	<cfabort>
</cfif>

<cfif find( "..", url.currentFolder) or find( "\", url.currentFolder)>
	<cfset SendUploadResults(102)>
	<cfabort>
</cfif>

<cfif REFind('(/\.)|(//)|[[:cntrl:]]|([\\:\*\?\"<>])', url.currentFolder)>
	<cfset SendUploadResults(102)>
	<cfabort>
</cfif>


<cfscript>
	userFilesPath = config.userFilesPath;

	if ( userFilesPath eq "" ) {
		userFilesPath = "/userfiles/";
	}

	// make sure the user files path is correctly formatted
	userFilesPath = replace(userFilesPath, "\", "/", "ALL");
	userFilesPath = replace(userFilesPath, '//', '/', 'ALL');
	if ( right(userFilesPath,1) NEQ "/" ) {
		userFilesPath = userFilesPath & "/";
	}
	if ( left(userFilesPath,1) NEQ "/" ) {
		userFilesPath = "/" & userFilesPath;
	}

	// make sure the current folder is correctly formatted
	url.currentFolder = replace(url.currentFolder, "\", "/", "ALL");
	url.currentFolder = replace(url.currentFolder, '//', '/', 'ALL');
	if ( right(url.currentFolder,1) neq "/" ) {
		url.currentFolder = url.currentFolder & "/";
	}
	if ( left(url.currentFolder,1) neq "/" ) {
		url.currentFolder = "/" & url.currentFolder;
	}

	if (find("/",getBaseTemplatePath())) {
		fs = "/";
	} else {
		fs = "\";
	}

	// Get the base physical path to the web root for this application. The code to determine the path automatically assumes that
	// the "FCKeditor" directory in the http request path is directly off the web root for the application and that it's not a
	// virtual directory or a symbolic link / junction. Use the serverPath config setting to force a physical path if necessary.
	if ( len(config.serverPath) ) {
		serverPath = config.serverPath;

		if ( right(serverPath,1) neq fs ) {
			serverPath = serverPath & fs;
		}
	} else {
		serverPath = replaceNoCase(getBaseTemplatePath(),replace(cgi.script_name,"/",fs,"all"),"") & replace(userFilesPath,"/",fs,"all");
	}

	rootPath = left( serverPath, Len(serverPath) - Len(userFilesPath) ) ;
</cfscript>
<cfif url.command eq "QuickUpload">
	<cfset resourceTypeUrl = rereplace( replace( Config.QuickUploadPath[url.type], fs, "/", "all"), "/$", "") >
	<cfif isDefined( "Config.QuickUploadAbsolutePath" )
			and structkeyexists( Config.QuickUploadAbsolutePath, url.type )
			and Len( Config.QuickUploadAbsolutePath[url.type] )>
				<cfset userFilesServerPath = Config.QuickUploadAbsolutePath[url.type] & url.currentFolder>
	<cfelse>
		<cftry>
		<cfset userFilesServerPath = expandpath( resourceTypeUrl ) & url.currentFolder>
		<!--- Catch: Parameter 1 of function ExpandPath must be a relative path --->
		<cfcatch type="any">
			<cfset userFilesServerPath = rootPath & Config.QuickUploadPath[url.type] & url.currentFolder>
		</cfcatch>
		</cftry>
	</cfif>
<cfelseif url.command eq "FileUpload">
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
</cfif>

<cfset userFilesServerPath = replace( userFilesServerPath, "/", fs, "all" ) >
<!--- get rid of double directory separators --->
<cfset userFilesServerPath = replace( userFilesServerPath, fs & fs, fs, "all") >

<!--- create resource type directory if not exists --->
<cfset resourceTypeDirectory = left( userFilesServerPath, Len(userFilesServerPath) - Len(url.currentFolder) )>

<cfif not directoryexists( resourceTypeDirectory )>

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
		<cfset SendUploadResults(103)>
		<cfabort>

	</cfcatch>

	</cftry>
</cfif>

<cfset currentFolderPath = userFilesServerPath>
<cfset resourceType = url.type>

<cfset fileName = "">
<cfset fileExt = "">

<!--- Can be overwritten. The last value will be sent with the result --->
<cfset customMsg = "">

<cftry>
	<!--- first upload the file with an unique filename --->
	<cffile action="upload"
		fileField="NewFile"
		destination="#currentFolderPath#"
		nameConflict="makeunique"
		mode="644"
		attributes="normal">

	<cfif cffile.fileSize EQ 0>
		<cfthrow>
	</cfif>

	<cfset lAllowedExtensions = config.allowedExtensions[#resourceType#]>
	<cfset lDeniedExtensions = config.deniedExtensions[#resourceType#]>

	<cfif ( len(lAllowedExtensions) and not listFindNoCase(lAllowedExtensions,cffile.ServerFileExt) )
		or ( len(lDeniedExtensions) and listFindNoCase(lDeniedExtensions,cffile.ServerFileExt) )>

		<cfset errorNumber = "202">
		<cffile action="delete" file="#cffile.ServerDirectory##fs##cffile.ServerFile#">

	<cfelse>

		<cfscript>
		errorNumber = 0;
		fileName = cffile.ClientFileName ;
		fileExt = cffile.ServerFileExt ;
		fileExisted = false ;

		// munge filename for html download. Only a-z, 0-9, _, - and . are allowed
		if( reFind("[^A-Za-z0-9_\-\.]", fileName) ) {
			fileName = reReplace(fileName, "[^A-Za-z0-9\-\.]", "_", "ALL");
			fileName = reReplace(fileName, "_{2,}", "_", "ALL");
			fileName = reReplace(fileName, "([^_]+)_+$", "\1", "ALL");
			fileName = reReplace(fileName, "$_([^_]+)$", "\1", "ALL");
		}

		// remove additional dots from file name
		if( isDefined("Config.ForceSingleExtension") and Config.ForceSingleExtension )
			fileName = replace( fileName, '.', "_", "all" ) ;

		// When the original filename already exists, add numbers (0), (1), (2), ... at the end of the filename.
		if( compare( cffile.ServerFileName, fileName ) ) {
			counter = 0;
			tmpFileName = fileName;
			while( fileExists("#currentFolderPath##fileName#.#fileExt#") ) {
				fileExisted = true ;
				counter = counter + 1 ;
				fileName = tmpFileName & '(#counter#)' ;
			}
		}
		</cfscript>

		<!--- Rename the uploaded file, if neccessary --->
		<cfif compare(cffile.ServerFileName,fileName)>

			<cfif fileExisted>
				<cfset errorNumber = "201">
			</cfif>
			<cffile
				action="rename"
				source="#currentFolderPath##cffile.ServerFileName#.#cffile.ServerFileExt#"
				destination="#currentFolderPath##fileName#.#fileExt#"
				mode="644"
				attributes="normal">

		</cfif>

	</cfif>

	<cfcatch type="any">

		<cfset errorNumber = "1">
		<cfset customMsg = cfcatch.message >

	</cfcatch>
</cftry>

<cfif errorNumber EQ 0>
	<!--- file was uploaded succesfully --->
	<cfset SendUploadResults(errorNumber, '#resourceTypeUrl##url.currentFolder##fileName#.#fileExt#', replace( fileName & "." & fileExt, "'", "\'", "ALL"), "")>
	<cfabort>
<cfelseif errorNumber EQ 201>
	<!--- file was changed (201), submit the new filename --->
	<cfset SendUploadResults(errorNumber, '#resourceTypeUrl##url.currentFolder##fileName#.#fileExt#', replace( fileName & "." & fileExt, "'", "\'", "ALL"), customMsg)>
	<cfabort>
<cfelse>
	<!--- An error occured(202). Submit only the error code and a message (if available). --->
	<cfset SendUploadResults(errorNumber, '', '', customMsg)>
	<cfabort>
</cfif>
