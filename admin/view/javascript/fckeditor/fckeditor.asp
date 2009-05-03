<!--
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
 * This is the integration file for ASP.
 *
 * It defines the FCKeditor class that can be used to create editor
 * instances in ASP pages on server side.
-->
<%
Class FCKeditor

	private sBasePath
	private sInstanceName
	private sWidth
	private sHeight
	private sToolbarSet
	private sValue

	private oConfig

	Private Sub Class_Initialize()
		sBasePath		= "/fckeditor/"
		sWidth			= "100%"
		sHeight			= "200"
		sToolbarSet		= "Default"
		sValue			= ""

		Set oConfig = CreateObject("Scripting.Dictionary")
	End Sub

	Public Property Let BasePath( basePathValue )
		sBasePath = basePathValue
	End Property

	Public Property Let InstanceName( instanceNameValue )
		sInstanceName = instanceNameValue
	End Property

	Public Property Let Width( widthValue )
		sWidth = widthValue
	End Property

	Public Property Let Height( heightValue )
		sHeight = heightValue
	End Property

	Public Property Let ToolbarSet( toolbarSetValue )
		sToolbarSet = toolbarSetValue
	End Property

	Public Property Let Value( newValue )
		If ( IsNull( newValue ) OR IsEmpty( newValue ) ) Then
			sValue = ""
		Else
			sValue = newValue
		End If
	End Property

	Public Property Let Config( configKey, configValue )
		oConfig.Add configKey, configValue
	End Property

	' Generates the instace of the editor in the HTML output of the page.
	Public Sub Create( instanceName )
		response.write CreateHtml( instanceName )
	end Sub

	' Returns the html code that must be used to generate an instance of FCKeditor.
	Public Function CreateHtml( instanceName )
		dim html

		If IsCompatible() Then

			Dim sFile, sLink
			If Request.QueryString( "fcksource" ) = "true" Then
				sFile = "fckeditor.original.html"
			Else
				sFile = "fckeditor.html"
			End If

			sLink = sBasePath & "editor/" & sFile & "?InstanceName=" + instanceName

			If (sToolbarSet & "") <> "" Then
				sLink = sLink + "&amp;Toolbar=" & sToolbarSet
			End If

			html = ""
			' Render the linked hidden field.
			html = html & "<input type=""hidden"" id=""" & instanceName & """ name=""" & instanceName & """ value=""" & Server.HTMLEncode( sValue ) & """ style=""display:none"" />"

			' Render the configurations hidden field.
			html = html & "<input type=""hidden"" id=""" & instanceName & "___Config"" value=""" & GetConfigFieldString() & """ style=""display:none"" />"

			' Render the editor IFRAME.
			html = html & "<iframe id=""" & instanceName & "___Frame"" src=""" & sLink & """ width=""" & sWidth & """ height=""" & sHeight & """ frameborder=""0"" scrolling=""no""></iframe>"

		Else

			Dim sWidthCSS, sHeightCSS

			If InStr( sWidth, "%" ) > 0  Then
				sWidthCSS = sWidth
			Else
				sWidthCSS = sWidth & "px"
			End If

			If InStr( sHeight, "%" ) > 0  Then
				sHeightCSS = sHeight
			Else
				sHeightCSS = sHeight & "px"
			End If

			html = "<textarea name=""" & instanceName & """ rows=""4"" cols=""40"" style=""width: " & sWidthCSS & "; height: " & sHeightCSS & """>" & Server.HTMLEncode( sValue ) & "</textarea>"

		End If

		CreateHtml = html

	End Function

	Private Function IsCompatible()

		IsCompatible = FCKeditor_IsCompatibleBrowser()

	End Function

	Private Function GetConfigFieldString()

		Dim sParams

		Dim bFirst
		bFirst = True

		Dim sKey
		For Each sKey in oConfig

			If bFirst = False Then
				sParams = sParams & "&amp;"
			Else
				bFirst = False
			End If

			sParams = sParams & EncodeConfig( sKey ) & "=" & EncodeConfig( oConfig(sKey) )

		Next

		GetConfigFieldString = sParams

	End Function

	Private Function EncodeConfig( valueToEncode )
		' The locale of the asp server makes the conversion of a boolean to string different to "true" or "false"
		' so we must do it manually
    If vartype(valueToEncode) = vbBoolean then
			If valueToEncode=True Then
				EncodeConfig="True"
			Else
				EncodeConfig="False"
			End If
		Else
			EncodeConfig = Replace( valueToEncode, "&", "%26" )
			EncodeConfig = Replace( EncodeConfig , "=", "%3D" )
			EncodeConfig = Replace( EncodeConfig , """", "%22" )
		End if

	End Function

End Class


' A function that can be used to check if the current browser is compatible with FCKeditor
' without the need to create an instance of the class.
Function FCKeditor_IsCompatibleBrowser()


	Dim sAgent
	sAgent = Request.ServerVariables("HTTP_USER_AGENT")

	Dim iVersion
	Dim re, Matches

	If InStr(sAgent, "MSIE") > 0 AND InStr(sAgent, "mac") <= 0  AND InStr(sAgent, "Opera") <= 0 Then
		iVersion = CInt( FCKeditor_ToNumericFormat( Mid(sAgent, InStr(sAgent, "MSIE") + 5, 3) ) )
		FCKeditor_IsCompatibleBrowser = ( iVersion >= 5.5 )
	ElseIf InStr(sAgent, "Gecko/") > 0 Then
		iVersion = CLng( Mid( sAgent, InStr( sAgent, "Gecko/" ) + 6, 8 ) )
		FCKeditor_IsCompatibleBrowser = ( iVersion >= 20030210 )
	ElseIf InStr(sAgent, "Opera/") > 0 Then
		iVersion = CSng( FCKeditor_ToNumericFormat( Mid( sAgent, InStr( sAgent, "Opera/" ) + 6, 4 ) ) )
		FCKeditor_IsCompatibleBrowser = ( iVersion >= 9.5 )
	ElseIf InStr(sAgent, "AppleWebKit/") > 0 Then
		Set re = new RegExp
		re.IgnoreCase = true
		re.global = false
		re.Pattern = "AppleWebKit/(\d+)"
		Set Matches = re.Execute(sAgent)
		FCKeditor_IsCompatibleBrowser = ( re.Replace(Matches.Item(0).Value, "$1") >= 522 )
	Else
		FCKeditor_IsCompatibleBrowser = False
	End If

End Function


' By Agrotic
' On ASP, when converting string to numbers, the number decimal separator is localized
' so 5.5 will not work on systems were the separator is "," and vice versa.
Private Function FCKeditor_ToNumericFormat( numberStr )

	If IsNumeric( "5.5" ) Then
		FCKeditor_ToNumericFormat = Replace( numberStr, ",", ".")
	Else
		FCKeditor_ToNumericFormat = Replace( numberStr, ".", ",")
	End If

End Function

%>
