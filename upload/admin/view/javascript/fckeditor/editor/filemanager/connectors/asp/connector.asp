<%@ CodePage=65001 Language="VBScript"%>
<%
Option Explicit
Response.Buffer = True
%>
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
 ' This is the File Manager Connector for ASP.
%>
<!--#include file="config.asp"-->
<!--#include file="util.asp"-->
<!--#include file="io.asp"-->
<!--#include file="basexml.asp"-->
<!--#include file="commands.asp"-->
<!--#include file="class_upload.asp"-->
<%

If ( ConfigIsEnabled = False ) Then
	SendError 1, "This connector is disabled. Please check the ""editor/filemanager/connectors/asp/config.asp"" file"
End If

DoResponse

Sub DoResponse()
	Dim sCommand, sResourceType, sCurrentFolder

	' Get the main request information.
	sCommand = Request.QueryString("Command")

	sResourceType = Request.QueryString("Type")
	If ( sResourceType = "" ) Then sResourceType = "File"

	sCurrentFolder = GetCurrentFolder()

	' Check if it is an allowed command
	if ( Not IsAllowedCommand( sCommand ) ) then
		SendError 1, "The """ & sCommand & """ command isn't allowed"
	end if

	' Check if it is an allowed resource type.
	if ( Not IsAllowedType( sResourceType ) ) Then
		SendError 1, "The """ & sResourceType & """ resource type isn't allowed"
	end if

	' File Upload doesn't have to Return XML, so it must be intercepted before anything.
	If ( sCommand = "FileUpload" ) Then
		FileUpload sResourceType, sCurrentFolder, sCommand
		Exit Sub
	End If

	SetXmlHeaders

	CreateXmlHeader sCommand, sResourceType, sCurrentFolder, GetUrlFromPath( sResourceType, sCurrentFolder, sCommand)

	' Execute the required command.
	Select Case sCommand
		Case "GetFolders"
			GetFolders sResourceType, sCurrentFolder
		Case "GetFoldersAndFiles"
			GetFoldersAndFiles sResourceType, sCurrentFolder
		Case "CreateFolder"
			CreateFolder sResourceType, sCurrentFolder
	End Select

	CreateXmlFooter

	Response.End
End Sub

%>
