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
 ' These are the classes used to handle ASP upload without using third
 ' part components (OCX/DLL).
%>
<%
'**********************************************
' File:		NetRube_Upload.asp
' Version:	NetRube Upload Class Version 2.3 Build 20070528
' Author:	NetRube
' Email:	NetRube@126.com
' Date:		05/28/2007
' Comments:	The code for the Upload.
'			This can free usage, but please
'			not to delete this copyright information.
'			If you have a modification version,
'			Please send out a duplicate to me.
'**********************************************
' 文件名:	NetRube_Upload.asp
' 版本:		NetRube Upload Class Version 2.3 Build 20070528
' 作者:		NetRube(网络乡巴佬)
' 电子邮件:	NetRube@126.com
' 日期:		2007年05月28日
' 声明:		文件上传类
'			本上传类可以自由使用，但请保留此版权声明信息
'			如果您对本上传类进行修改增强，
'			请发送一份给俺。
'**********************************************

Class NetRube_Upload

	Public	File, Form
	Private oSourceData
	Private nMaxSize, nErr, sAllowed, sDenied, sHtmlExtensions

	Private Sub Class_Initialize
		nErr		= 0
		nMaxSize	= 1048576

		Set File			= Server.CreateObject("Scripting.Dictionary")
		File.CompareMode	= 1
		Set Form			= Server.CreateObject("Scripting.Dictionary")
		Form.CompareMode	= 1

		Set oSourceData		= Server.CreateObject("ADODB.Stream")
		oSourceData.Type	= 1
		oSourceData.Mode	= 3
		oSourceData.Open
	End Sub

	Private Sub Class_Terminate
		Form.RemoveAll
		Set Form = Nothing
		File.RemoveAll
		Set File = Nothing

		oSourceData.Close
		Set oSourceData = Nothing
	End Sub

	Public Property Get Version
		Version = "NetRube Upload Class Version 2.3 Build 20070528"
	End Property

	Public Property Get ErrNum
		ErrNum	= nErr
	End Property

	Public Property Let MaxSize(nSize)
		nMaxSize	= nSize
	End Property

	Public Property Let Allowed(sExt)
		sAllowed	= sExt
	End Property

	Public Property Let Denied(sExt)
		sDenied	= sExt
	End Property

	Public Property Let HtmlExtensions(sExt)
		sHtmlExtensions	= sExt
	End Property

	Public Sub GetData
		Dim aCType
		aCType = Split(Request.ServerVariables("HTTP_CONTENT_TYPE"), ";")
		if ( uBound(aCType) < 0 ) then
			nErr = 1
			Exit Sub
		end if
		If aCType(0) <> "multipart/form-data" Then
			nErr = 1
			Exit Sub
		End If

		Dim nTotalSize
		nTotalSize	= Request.TotalBytes
		If nTotalSize < 1 Then
			nErr = 2
			Exit Sub
		End If
		If nMaxSize > 0 And nTotalSize > nMaxSize Then
			nErr = 3
			Exit Sub
		End If

		'Thankful long(yrl031715@163.com)
		'Fix upload large file.
		'**********************************************
		' 修正作者：long
		' 联系邮件: yrl031715@163.com
		' 修正时间：2007年5月6日
		' 修正说明：由于iis6的Content-Length 头信息中包含的请求长度超过了 AspMaxRequestEntityAllowed 的值（默认200K）, IIS 将返回一个 403 错误信息.
		'          直接导致在iis6下调试FCKeditor上传功能时，一旦文件超过200K,上传文件时文件管理器失去响应，受此影响，文件的快速上传功能也存在在缺陷。
		'          在参考 宝玉 的 Asp无组件上传带进度条 演示程序后作出如下修改，以修正在iis6下的错误。

		Dim nTotalBytes, nPartBytes, ReadBytes
		ReadBytes = 0
		nTotalBytes = Request.TotalBytes
		'循环分块读取
		Do While ReadBytes < nTotalBytes
			'分块读取
			nPartBytes = 64 * 1024 '分成每块64k
			If nPartBytes + ReadBytes > nTotalBytes Then
				nPartBytes = nTotalBytes - ReadBytes
			End If
			oSourceData.Write Request.BinaryRead(nPartBytes)
			ReadBytes = ReadBytes + nPartBytes
		Loop
		'**********************************************
		oSourceData.Position = 0

		Dim oTotalData, oFormStream, sFormHeader, sFormName, bCrLf, nBoundLen, nFormStart, nFormEnd, nPosStart, nPosEnd, sBoundary

		oTotalData	= oSourceData.Read
		bCrLf		= ChrB(13) & ChrB(10)
		sBoundary	= MidB(oTotalData, 1, InStrB(1, oTotalData, bCrLf) - 1)
		nBoundLen	= LenB(sBoundary) + 2
		nFormStart	= nBoundLen

		Set oFormStream = Server.CreateObject("ADODB.Stream")

		Do While (nFormStart + 2) < nTotalSize
			nFormEnd	= InStrB(nFormStart, oTotalData, bCrLf & bCrLf) + 3

			With oFormStream
				.Type	= 1
				.Mode	= 3
				.Open
				oSourceData.Position = nFormStart
				oSourceData.CopyTo oFormStream, nFormEnd - nFormStart
				.Position	= 0
				.Type		= 2
				.CharSet	= "UTF-8"
				sFormHeader	= .ReadText
				.Close
			End With

			nFormStart	= InStrB(nFormEnd, oTotalData, sBoundary) - 1
			nPosStart	= InStr(22, sFormHeader, " name=", 1) + 7
			nPosEnd		= InStr(nPosStart, sFormHeader, """")
			sFormName	= Mid(sFormHeader, nPosStart, nPosEnd - nPosStart)

			If InStr(45, sFormHeader, " filename=", 1) > 0 Then
				Set File(sFormName)			= New NetRube_FileInfo
				File(sFormName).FormName	= sFormName
				File(sFormName).Start		= nFormEnd
				File(sFormName).Size		= nFormStart - nFormEnd - 2
				nPosStart					= InStr(nPosEnd, sFormHeader, " filename=", 1) + 11
				nPosEnd						= InStr(nPosStart, sFormHeader, """")
				File(sFormName).ClientPath	= Mid(sFormHeader, nPosStart, nPosEnd - nPosStart)
				File(sFormName).Name		= Mid(File(sFormName).ClientPath, InStrRev(File(sFormName).ClientPath, "\") + 1)
				File(sFormName).Ext			= LCase(Mid(File(sFormName).Name, InStrRev(File(sFormName).Name, ".") + 1))
				nPosStart					= InStr(nPosEnd, sFormHeader, "Content-Type: ", 1) + 14
				nPosEnd						= InStr(nPosStart, sFormHeader, vbCr)
				File(sFormName).MIME		= Mid(sFormHeader, nPosStart, nPosEnd - nPosStart)
			Else
				With oFormStream
					.Type	= 1
					.Mode	= 3
					.Open
					oSourceData.Position = nFormEnd
					oSourceData.CopyTo oFormStream, nFormStart - nFormEnd - 2
					.Position	= 0
					.Type		= 2
					.CharSet	= "UTF-8"
					Form(sFormName)	= .ReadText
					.Close
				End With
			End If

			nFormStart	= nFormStart + nBoundLen
		Loop

		oTotalData = ""
		Set oFormStream = Nothing
	End Sub

	Public Sub SaveAs(sItem, sFileName)
		If File(sItem).Size < 1 Then
			nErr = 2
			Exit Sub
		End If

		If Not IsAllowed(File(sItem).Ext) Then
			nErr = 4
			Exit Sub
		End If

		If InStr( LCase( sFileName ), "::$data" ) > 0 Then
			nErr = 4
			Exit Sub
		End If

		Dim sFileExt, iFileSize
		sFileExt	= File(sItem).Ext
		iFileSize	= File(sItem).Size

		' Check XSS.
		If Not IsHtmlExtension( sFileExt ) Then
			' Calculate the size of data to load (max 1Kb).
			Dim iXSSSize
			iXSSSize = iFileSize

			If iXSSSize > 1024 Then
				iXSSSize = 1024
			End If

			' Read the data.
			Dim sData
			oSourceData.Position = File(sItem).Start
			sData = oSourceData.Read( iXSSSize )	' Byte Array
			sData = ByteArray2Text( sData )			' String

			' Sniff HTML data.
			If SniffHtml( sData ) Then
				nErr = 4
				Exit Sub
			End If
		End If

		Dim oFileStream
		Set oFileStream = Server.CreateObject("ADODB.Stream")
		With oFileStream
			.Type		= 1
			.Mode		= 3
			.Open
			oSourceData.Position = File(sItem).Start
			oSourceData.CopyTo oFileStream, File(sItem).Size
			.Position	= 0
			.SaveToFile sFileName, 2
			.Close
		End With
		Set oFileStream = Nothing
	End Sub

	Private Function IsAllowed(sExt)
		Dim oRE
		Set oRE	= New RegExp
		oRE.IgnoreCase	= True
		oRE.Global		= True

		If sDenied = "" Then
			oRE.Pattern	= sAllowed
			IsAllowed	= (sAllowed = "") Or oRE.Test(sExt)
		Else
			oRE.Pattern	= sDenied
			IsAllowed	= Not oRE.Test(sExt)
		End If

		Set oRE	= Nothing
	End Function

	Private Function IsHtmlExtension( sExt )
		If sHtmlExtensions = "" Then
			Exit Function
		End If

		Dim oRE
		Set oRE = New RegExp
		oRE.IgnoreCase	= True
		oRE.Global		= True
		oRE.Pattern		= sHtmlExtensions

		IsHtmlExtension = oRE.Test(sExt)

		Set oRE	= Nothing
	End Function

	Private Function SniffHtml( sData )

		Dim oRE
		Set oRE = New RegExp
		oRE.IgnoreCase	= True
		oRE.Global		= True

		Dim aPatterns
		aPatterns = Array( "<!DOCTYPE\W*X?HTML", "<(body|head|html|img|pre|script|table|title)", "type\s*=\s*[\'""]?\s*(?:\w*/)?(?:ecma|java)", "(?:href|src|data)\s*=\s*[\'""]?\s*(?:ecma|java)script:", "url\s*\(\s*[\'""]?\s*(?:ecma|java)script:" )

		Dim i
		For i = 0 to UBound( aPatterns )
			oRE.Pattern = aPatterns( i )
			If oRE.Test( sData ) Then
				SniffHtml = True
				Exit Function
			End If
		Next

		SniffHtml = False

	End Function

	' Thanks to http://www.ericphelps.com/q193998/index.htm
	Private Function ByteArray2Text(varByteArray)
		Dim strData, strBuffer, lngCounter
		strData = ""
		strBuffer = ""
		For lngCounter = 0 to UBound(varByteArray)
			strBuffer = strBuffer & Chr(255 And Ascb(Midb(varByteArray,lngCounter + 1, 1)))
			'Keep strBuffer at 1k bytes maximum
			If lngCounter Mod 1024 = 0 Then
				strData = strData & strBuffer
				strBuffer = ""
			End If
		Next
		ByteArray2Text = strData & strBuffer
	End Function

End Class

Class NetRube_FileInfo
	Dim FormName, ClientPath, Path, Name, Ext, Content, Size, MIME, Start
End Class
%>
