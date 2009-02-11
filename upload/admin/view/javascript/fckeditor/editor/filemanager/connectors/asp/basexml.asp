<%
 ' FCKeditor - The text editor for Internet - http://www.fckeditor.net
 ' Copyright (C) 2003-2008 Frederico Caldeira Knabben
 '
 ' == BEGIN LICENSE ==
 '
 ' Licensed under the terms of any of the following licenses at your
 ' choice:
 '
 '  - GNU General Public License Version 2 or later (the "GPL")
 '    http://www.gnu.org/licenses/gpl.html
 '
 '  - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
 '    http://www.gnu.org/licenses/lgpl.html
 '
 '  - Mozilla Public License Version 1.1 or later (the "MPL")
 '    http://www.mozilla.org/MPL/MPL-1.1.html
 '
 ' == END LICENSE ==
 '
 ' This file include the functions that create the base XML output.
%>
<%

Sub SetXmlHeaders()
	' Cleans the response buffer.
	Response.Clear()

	' Prevent the browser from caching the result.
	Response.CacheControl = "no-cache"

	' Set the response format.
	Response.CharSet		= "UTF-8"
	Response.ContentType	= "text/xml"
End Sub

Sub CreateXmlHeader( command, resourceType, currentFolder, url )
	' Create the XML document header.
	Response.Write "<?xml version=""1.0"" encoding=""utf-8"" ?>"

	' Create the main "Connector" node.
	Response.Write "<Connector command=""" & command & """ resourceType=""" & resourceType & """>"

	' Add the current folder node.
	Response.Write "<CurrentFolder path=""" & ConvertToXmlAttribute( currentFolder ) & """ url=""" & ConvertToXmlAttribute( url ) & """ />"
End Sub

Sub CreateXmlFooter()
	Response.Write "</Connector>"
End Sub

Sub SendError( number, text )
	SetXmlHeaders

	' Create the XML document header.
	Response.Write "<?xml version=""1.0"" encoding=""utf-8"" ?>"

	Response.Write "<Connector><Error number=""" & number & """ text=""" & Server.HTMLEncode( text ) & """ /></Connector>"

	Response.End
End Sub
%>
