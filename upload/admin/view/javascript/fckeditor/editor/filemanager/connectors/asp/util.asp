<%
 ' FCKeditor - The text editor for Internet - http://www.fckeditor.net
 ' Copyright (C) 2003-2009 Frederico Caldeira Knabben
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
 ' This file include generic functions used by the ASP Connector.
%>
<%
Function RemoveFromStart( sourceString, charToRemove )
	Dim oRegex
	Set oRegex = New RegExp
	oRegex.Pattern = "^" & charToRemove & "+"

	RemoveFromStart = oRegex.Replace( sourceString, "" )
End Function

Function RemoveFromEnd( sourceString, charToRemove )
	Dim oRegex
	Set oRegex = New RegExp
	oRegex.Pattern = charToRemove & "+$"

	RemoveFromEnd = oRegex.Replace( sourceString, "" )
End Function

Function ConvertToXmlAttribute( value )
	ConvertToXmlAttribute = Replace( value, "&", "&amp;" )
End Function

Function InArray( value, sourceArray )
	Dim i
	For i = 0 to UBound( sourceArray )
		If sourceArray(i) = value Then
			InArray = True
			Exit Function
		End If
	Next
	InArray = False
End Function

%>
