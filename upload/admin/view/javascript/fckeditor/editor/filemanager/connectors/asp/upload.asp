<%@ CodePage=65001 Language="VBScript"%>
<%
Option Explicit
Response.Buffer = True
%>
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
 ' This is the "File Uploader" for ASP.
%>
<!--#include file="config.asp"-->
<!--#include file="util.asp"-->
<!--#include file="io.asp"-->
<!--#include file="commands.asp"-->
<!--#include file="class_upload.asp"-->
<%

Sub SendError( number, text )
	SendUploadResults number, "", "", text
End Sub

' Check if this uploader has been enabled.
If ( ConfigIsEnabled = False ) Then
	SendUploadResults "1", "", "", "This file uploader is disabled. Please check the ""editor/filemanager/connectors/asp/config.asp"" file"
End If

	Dim sCommand, sResourceType, sCurrentFolder

	sCommand = "QuickUpload"

	sResourceType = Request.QueryString("Type")
	If ( sResourceType = "" ) Then sResourceType = "File"

	sCurrentFolder = "/"

	' Is Upload enabled?
	if ( Not IsAllowedCommand( sCommand ) ) then
		SendUploadResults "1", "", "", "The """ & sCommand & """ command isn't allowed"
	end if

	' Check if it is an allowed resource type.
	if ( Not IsAllowedType( sResourceType ) ) Then
		SendUploadResults "1", "", "", "The " & sResourceType & " resource type isn't allowed"
	end if

	FileUpload sResourceType, sCurrentFolder, sCommand

%>
