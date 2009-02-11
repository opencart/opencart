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
--->

<cfparam name="url.type" default="File">
<cfparam name="url.currentFolder" default="/">

<!--- note: no serverPath url parameter - see config.cfm if you need to set the serverPath manually --->

<cfinclude template="config.cfm">
<cfinclude template="cf_util.cfm">
<cfinclude template="cf_io.cfm">
<cfinclude template="cf_commands.cfm">

<cffunction name="SendError" returntype="void" output="true">
	<cfargument name="number" required="true" type="Numeric">
	<cfargument name="text" required="true">
	<cfreturn SendUploadResults( "#ARGUMENTS.number#", "", "", "ARGUMENTS.text" )>
</cffunction>

<cfset REQUEST.Config = Config>
<cfif find( "/", getBaseTemplatePath() ) >
	<cfset REQUEST.Fs = "/">
<cfelse>
	<cfset REQUEST.Fs = "\">
</cfif>

<cfif not Config.Enabled>
	<cfset SendUploadResults( '1', '', '', 'This file uploader is disabled. Please check the "editor/filemanager/connectors/cfm/config.cfm" file' )>
</cfif>

<cfset sCommand = 'QuickUpload'>
<cfset sType = "File">

<cfif isDefined( "URL.Type" )>
	<cfset sType = URL.Type>
</cfif>

<cfset sCurrentFolder = GetCurrentFolder()>

<!--- Is enabled the upload? --->
<cfif not IsAllowedCommand( sCommand )>
	<cfset SendUploadResults( "1", "", "", "The """ & sCommand & """ command isn't allowed" )>
</cfif>

<!--- Check if it is an allowed type. --->
<cfif not IsAllowedType( sType )>
	<cfset SendUploadResults( "1", "", "", "Invalid type specified" ) >
</cfif>

<cfset FileUpload( sType, sCurrentFolder, sCommand )>
