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
 * This file include the functions that create the base XML output by the ColdFusion Connector (MX 6.0 and above).
--->

<cffunction name="SetXmlHeaders" returntype="void">
	<cfheader name="Expires" value="#GetHttpTimeString(Now())#">
	<cfheader name="Pragma" value="no-cache">
	<cfheader name="Cache-Control" value="no-cache, no-store, must-revalidate">
	<cfcontent reset="true" type="text/xml; charset=UTF-8">
</cffunction>

<cffunction name="CreateXmlHeader" returntype="void" output="true">
	<cfargument name="command" required="true">
	<cfargument name="resourceType" required="true">
	<cfargument name="currentFolder" required="true">

	<cfset SetXmlHeaders()>
	<cfoutput><?xml version="1.0" encoding="utf-8" ?></cfoutput>
	<cfoutput><Connector command="#ARGUMENTS.command#" resourceType="#ARGUMENTS.resourceType#"></cfoutput>
	<cfoutput><CurrentFolder path="#HTMLEditFormat(ARGUMENTS.currentFolder)#" url="#HTMLEditFormat( GetUrlFromPath( resourceType, currentFolder, command ) )#" /></cfoutput>
	<cfset REQUEST.HeaderSent = true>
</cffunction>

<cffunction name="CreateXmlFooter" returntype="void" output="true">
	<cfoutput></Connector></cfoutput>
</cffunction>

<cffunction name="SendError" returntype="void" output="true">
	<cfargument name="number" required="true" type="Numeric">
	<cfargument name="text" required="true">
	<cfif isDefined("REQUEST.HeaderSent") and REQUEST.HeaderSent>
		<cfset SendErrorNode( ARGUMENTS.number, ARGUMENTS.text )>
		<cfset CreateXmlFooter() >
	<cfelse>
		<cfset SetXmlHeaders()>
		<cfoutput><?xml version="1.0" encoding="utf-8" ?></cfoutput>
		<cfoutput><Connector></cfoutput>
		<cfset SendErrorNode( ARGUMENTS.number, ARGUMENTS.text )>
		<cfset CreateXmlFooter() >
	</cfif>
	<cfabort>
</cffunction>

<cffunction name="SendErrorNode" returntype="void" output="true">
	<cfargument name="number" required="true" type="Numeric">
	<cfargument name="text" required="true">
	<cfoutput><Error number="#ARGUMENTS.number#" text="#htmleditformat(ARGUMENTS.text)#" /></cfoutput>
</cffunction>
