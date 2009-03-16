<cfsetting enablecfoutputonly="yes" showdebugoutput="no">
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
 * File Browser connector for ColdFusion (MX 6.0 and above).
 * (based on the original CF connector by Hendrik Kramer - hk@lwd.de)
 *
--->

<cfparam name="url.command">
<cfparam name="url.type">
<cfparam name="url.currentFolder">

<!--- note: no serverPath url parameter - see config.cfm if you need to set the serverPath manually --->

<cfinclude template="config.cfm">
<cfinclude template="cf_util.cfm">
<cfinclude template="cf_io.cfm">
<cfinclude template="cf_basexml.cfm">
<cfinclude template="cf_commands.cfm">

<cfif not Config.Enabled>
	<cfset SendError( 1, 'This connector is disabled. Please check the "editor/filemanager/connectors/cfm/config.cfm" file' )>
</cfif>

<cfset REQUEST.Config = Config>
<cfif find( "/", getBaseTemplatePath() ) >
	<cfset REQUEST.Fs = "/">
<cfelse>
	<cfset REQUEST.Fs = "\">
</cfif>

<cfset DoResponse() >

<cffunction name="DoResponse" output="true" returntype="void">

	<!--- Get the main request informaiton. --->
	<cfset var sCommand	= "#URL.Command#" >
	<cfset var sResourceType	= URL.Type >
	<cfset var sCurrentFolder	= GetCurrentFolder() >

	<!--- Check if it is an allowed command --->
	<cfif not IsAllowedCommand( sCommand ) >
		<cfset SendError( 1, "The """ & sCommand & """ command isn't allowed" ) >
	</cfif>

	<!--- Check if it is an allowed type. --->
	<cfif not IsAllowedType( sResourceType ) >
		<cfset SendError( 1, 'Invalid type specified' ) >
	</cfif>

	<!--- File Upload doesn't have to Return XML, so it must be intercepted before anything. --->
	<cfif sCommand eq "FileUpload">
		<cfset FileUpload( sResourceType, sCurrentFolder, sCommand )>
		<cfabort>
	</cfif>

	<cfset CreateXmlHeader( sCommand, sResourceType, sCurrentFolder )>

	<!--- Execute the required command. --->
	<cfif sCommand eq "GetFolders">
		<cfset GetFolders( sResourceType, sCurrentFolder ) >
	<cfelseif sCommand eq "GetFoldersAndFiles">
		<cfset GetFoldersAndFiles( sResourceType, sCurrentFolder ) >
	<cfelseif sCommand eq "CreateFolder">
		<cfset CreateFolder( sResourceType, sCurrentFolder ) >
	</cfif>

	<cfset CreateXmlFooter()>

	<cfexit>
</cffunction>
