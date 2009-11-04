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
 * This file include generic functions used by the ColdFusion Connector (MX 6.0 and above).
--->

<cffunction name="RemoveFromStart" output="false" returntype="String">
	<cfargument name="sourceString" type="String">
	<cfargument name="charToRemove" type="String">

	<cfif left(ARGUMENTS.sourceString, 1) eq ARGUMENTS.charToRemove>
		<cfreturn mid( ARGUMENTS.sourceString, 2, len(ARGUMENTS.sourceString) -1 )>
	</cfif>

	<cfreturn ARGUMENTS.sourceString>
</cffunction>

<cffunction name="RemoveFromEnd" output="false" returntype="String">
	<cfargument name="sourceString" type="String">
	<cfargument name="charToRemove" type="String">

	<cfif right(ARGUMENTS.sourceString, 1) eq ARGUMENTS.charToRemove>
		<cfreturn mid( ARGUMENTS.sourceString, 1, len(ARGUMENTS.sourceString) -1 )>
	</cfif>

	<cfreturn ARGUMENTS.sourceString>
</cffunction>

<!---
Check file content.
Currently this function validates only image files.
Returns false if file is invalid.
detectionLevel:
	0 = none
	1 = check image size for images,
	2 = use DetectHtml for images
---->
<cffunction name="IsImageValid" returntype="boolean" output="true">
	<cfargument name="filePath" required="true" type="String">
	<cfargument name="extension" required="true" type="String">

	<cfset var imageCFC = "">
	<cfset var imageInfo = "">

	<cfif not ListFindNoCase("gif,jpeg,jpg,png,swf,psd,bmp,iff,tiff,tif,swc,jpc,jp2,jpx,jb2,xmb,wbmp", ARGUMENTS.extension)>
		<cfreturn true>
	</cfif>

	<cftry>
		<cfif REQUEST.CFVersion gte 8>
			<cfset objImage = ImageRead(ARGUMENTS.filePath) >
			<cfset imageInfo = ImageInfo(objImage)>
			<!--- <cfimage action="info" source="#ARGUMENTS.filePath#" structName="imageInfo" /> --->
		<cfelse>
			<cfset imageCFC = createObject("component", "image")>
			<cfset imageInfo = imageCFC.getImageInfo("", ARGUMENTS.filePath)>
		</cfif>

		<cfif imageInfo.height lte 0 or imageInfo.width lte 0>
			<cfreturn false>
		</cfif>
	<cfcatch type="any">
		<cfreturn false>
	</cfcatch>
	</cftry>

	<cfreturn true>
</cffunction>

<!---
 Detect HTML in the first KB to prevent against potential security issue with
 IE/Safari/Opera file type auto detection bug.
 Returns true if file contain insecure HTML code at the beginning.
--->
<cffunction name="DetectHtml" output="false" returntype="boolean">
	<cfargument name="filePath" required="true" type="String">

	<cfset var tags = "<body,<head,<html,<img,<pre,<script,<table,<title">
	<cfset var chunk = lcase( Trim( BinaryFileRead( ARGUMENTS.filePath, 1024 ) ) )>

	<cfif not Len(chunk)>
		<cfreturn false>
	</cfif>

	<cfif refind('<!doctype\W*x?html', chunk)>
		<cfreturn true>
	</cfif>

	<cfloop index = "tag" list = "#tags#">
     	<cfif find( tag, chunk )>
			<cfreturn true>
		</cfif>
	</cfloop>

	<!--- type = javascript --->
	<cfif refind('type\s*=\s*[''"]?\s*(?:\w*/)?(?:ecma|java)', chunk)>
		<cfreturn true>
	</cfif> >

	<!--- href = javascript --->
	<!--- src = javascript --->
	<!--- data = javascript --->
	<cfif refind('(?:href|src|data)\s*=\s*[\''"]?\s*(?:ecma|java)script:', chunk)>
		<cfreturn true>
	</cfif>

	<!--- url(javascript --->
	<cfif refind('url\s*\(\s*[\''"]?\s*(?:ecma|java)script:', chunk)>
		<cfreturn true>
	</cfif>

	<cfreturn false>
</cffunction>
