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
 ' This file include IO specific functions used by the ASP Connector.
%>
<%
function CombinePaths( sBasePath, sFolder)
	CombinePaths =  RemoveFromEnd( sBasePath, "/" ) & "/" & RemoveFromStart( sFolder, "/" )
end function

Function GetResourceTypePath( resourceType, sCommand )
	if ( sCommand = "QuickUpload") then
		GetResourceTypePath = ConfigQuickUploadPath.Item( resourceType )
	else
		GetResourceTypePath = ConfigFileTypesPath.Item( resourceType )
	end if
end Function

Function GetResourceTypeDirectory( resourceType, sCommand )
	if ( sCommand = "QuickUpload") then

		if ( ConfigQuickUploadAbsolutePath.Item( resourceType ) <> "" ) then
			GetResourceTypeDirectory = ConfigQuickUploadAbsolutePath.Item( resourceType )
		else
			' Map the "UserFiles" path to a local directory.
			GetResourceTypeDirectory = Server.MapPath( ConfigQuickUploadPath.Item( resourceType ) )
		end if
	else
		if ( ConfigFileTypesAbsolutePath.Item( resourceType ) <> "" ) then
			GetResourceTypeDirectory = ConfigFileTypesAbsolutePath.Item( resourceType )
		else
			' Map the "UserFiles" path to a local directory.
			GetResourceTypeDirectory = Server.MapPath( ConfigFileTypesPath.Item( resourceType ) )
		end if
	end if
end Function

Function GetUrlFromPath( resourceType, folderPath, sCommand )
	GetUrlFromPath = CombinePaths( GetResourceTypePath( resourceType, sCommand ), folderPath )
End Function

Function RemoveExtension( fileName )
	RemoveExtension = Left( fileName, InStrRev( fileName, "." ) - 1 )
End Function

Function ServerMapFolder( resourceType, folderPath, sCommand )
	Dim sResourceTypePath
	' Get the resource type directory.
	sResourceTypePath = GetResourceTypeDirectory( resourceType, sCommand )

	' Ensure that the directory exists.
	CreateServerFolder sResourceTypePath

	' Return the resource type directory combined with the required path.
	ServerMapFolder = CombinePaths( sResourceTypePath, folderPath )
End Function

Sub CreateServerFolder( folderPath )
	Dim oFSO
	Set oFSO = Server.CreateObject( "Scripting.FileSystemObject" )

	Dim sParent
	sParent = oFSO.GetParentFolderName( folderPath )

	' Check if the parent exists, or create it.
	If ( NOT oFSO.FolderExists( sParent ) ) Then CreateServerFolder( sParent )

	If ( oFSO.FolderExists( folderPath ) = False ) Then
		On Error resume next
		oFSO.CreateFolder( folderPath )

		if err.number<>0 then
		dim sErrorNumber
		Dim iErrNumber, sErrDescription
		iErrNumber		= err.number
		sErrDescription	= err.Description

		On Error Goto 0

		Select Case iErrNumber
			Case 52
				sErrorNumber = "102"	' Invalid Folder Name.
			Case 70
				sErrorNumber = "103"	' Security Error.
			Case 76
				sErrorNumber = "102"	' Path too long.
			Case Else
				sErrorNumber = "110"
			End Select

			SendError sErrorNumber, "CreateServerFolder(" & folderPath & ") : " & sErrDescription
		end if

	End If

	Set oFSO = Nothing
End Sub

Function IsAllowedExt( extension, resourceType )
	Dim oRE
	Set oRE	= New RegExp
	oRE.IgnoreCase	= True
	oRE.Global		= True

	Dim sAllowed, sDenied
	sAllowed	= ConfigAllowedExtensions.Item( resourceType )
	sDenied		= ConfigDeniedExtensions.Item( resourceType )

	IsAllowedExt = True

	If sDenied <> "" Then
		oRE.Pattern	= sDenied
		IsAllowedExt	= Not oRE.Test( extension )
	End If

	If IsAllowedExt And sAllowed <> "" Then
		oRE.Pattern		= sAllowed
		IsAllowedExt	= oRE.Test( extension )
	End If

	Set oRE	= Nothing
End Function

Function IsAllowedType( resourceType )
	Dim oRE
	Set oRE	= New RegExp
	oRE.IgnoreCase	= True
	oRE.Global		= True
	oRE.Pattern		= "^(" & ConfigAllowedTypes & ")$"

	IsAllowedType = oRE.Test( resourceType )

	Set oRE	= Nothing
End Function

Function IsAllowedCommand( sCommand )
	Dim oRE
	Set oRE	= New RegExp
	oRE.IgnoreCase	= True
	oRE.Global		= True
	oRE.Pattern		= "^(" & ConfigAllowedCommands & ")$"

	IsAllowedCommand = oRE.Test( sCommand )

	Set oRE	= Nothing
End Function

function GetCurrentFolder()
	dim sCurrentFolder
	sCurrentFolder = Request.QueryString("CurrentFolder")
	If ( sCurrentFolder = "" ) Then sCurrentFolder = "/"

	' Check the current folder syntax (must begin and start with a slash).
	If ( Right( sCurrentFolder, 1 ) <> "/" ) Then sCurrentFolder = sCurrentFolder & "/"
	If ( Left( sCurrentFolder, 1 ) <> "/" ) Then sCurrentFolder = "/" & sCurrentFolder

	' Check for invalid folder paths (..)
	If ( InStr( 1, sCurrentFolder, ".." ) <> 0 OR InStr( 1, sCurrentFolder, "\" ) <> 0) Then
		SendError 102, ""
	End If

	GetCurrentFolder = sCurrentFolder
end function

' Do a cleanup of the folder name to avoid possible problems
function SanitizeFolderName( sNewFolderName )
	Dim oRegex
	Set oRegex = New RegExp
	oRegex.Global		= True

' remove . \ / | : ? *  " < > and control characters
	oRegex.Pattern = "(\.|\\|\/|\||:|\?|\*|""|\<|\>|[\u0000-\u001F]|\u007F)"
	SanitizeFolderName = oRegex.Replace( sNewFolderName, "_" )

	Set oRegex = Nothing
end function

' Do a cleanup of the file name to avoid possible problems
function SanitizeFileName( sNewFileName )
	Dim oRegex
	Set oRegex = New RegExp
	oRegex.Global		= True

	if ( ConfigForceSingleExtension = True ) then
		oRegex.Pattern = "\.(?![^.]*$)"
		sNewFileName = oRegex.Replace( sNewFileName, "_" )
	end if

' remove \ / | : ? *  " < > and control characters
	oRegex.Pattern = "(\\|\/|\||:|\?|\*|""|\<|\>|[\u0000-\u001F]|\u007F)"
	SanitizeFileName = oRegex.Replace( sNewFileName, "_" )

	Set oRegex = Nothing
end function

' This is the function that sends the results of the uploading process.
Sub SendUploadResults( errorNumber, fileUrl, fileName, customMsg )
	Response.Clear
	Response.Write "<script type=""text/javascript"">"
	Response.Write "(function()"
	Response.Write "{"
	Response.Write "var d = document.domain ;"

	Response.Write " while ( true )"
	Response.Write "	{"
	' Test if we can access a parent property.
	Response.Write "		try"
	Response.Write "		{"
	Response.Write "			var test = window.top.opener.document.domain ;"
	Response.Write "			break ;"
	Response.Write "		}"
	Response.Write "		catch( e ) {}"

	' Remove a domain part: www.mytest.example.com => mytest.example.com => example.com ...
	Response.Write "		d = d.replace( /.*?(?:\.|$)/, '' ) ;"

	Response.Write "		if ( d.length == 0 )"
	' It was not able to detect the domain.
	Response.Write "			break ;"
	Response.Write ""
	Response.Write "		try"
	Response.Write "		{"
	Response.Write "			document.domain = d ;"
	Response.Write "		}"
	Response.Write "		catch (e)"
	Response.Write "		{"
	Response.Write "			break ;"
	Response.Write "		}"
	Response.Write "	}"
	Response.Write "})() ;"

	Response.Write "window.parent.OnUploadCompleted(" & errorNumber & ",""" & Replace( fileUrl, """", "\""" ) & """,""" & Replace( fileName, """", "\""" ) & """,""" & Replace( customMsg , """", "\""" ) & """) ;"
	Response.Write "</script>"
	Response.End
End Sub

%>
