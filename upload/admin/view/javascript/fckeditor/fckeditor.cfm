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
 * ColdFusion integration.
 * Note this module is created for use with Coldfusion 4.52 and above.
 * For a cfc version for coldfusion mx check the fckeditor.cfc.
 *
 * Syntax:
 *
 * <cfmodule name="path/to/cfc/fckeditor"
 * 	instanceName="myEditor"
 * 	toolbarSet="..."
 * 	width="..."
 * 	height="..:"
 * 	value="..."
 * 	config="..."
 * >
--->
<!--- ::
	 * 	Attribute validation
	:: --->
<cfparam name="attributes.instanceName" type="string">
<cfparam name="attributes.width" 		type="string" default="100%">
<cfparam name="attributes.height" 		type="string" default="200">
<cfparam name="attributes.toolbarSet" 	type="string" default="Default">
<cfparam name="attributes.value" 		type="string" default="">
<cfparam name="attributes.basePath" 	type="string" default="/fckeditor/">
<cfparam name="attributes.checkBrowser" type="boolean" default="true">
<cfparam name="attributes.config" 		type="struct" default="#structNew()#">
<cfinclude template="fckutils.cfm">

<!--- ::
	 * check browser compatibility via HTTP_USER_AGENT, if checkBrowser is true
	:: --->

<cfscript>
if( attributes.checkBrowser )
{
	isCompatibleBrowser = FCKeditor_IsCompatibleBrowser();
}
else
{
	// If we should not check browser compatibility, assume true
	isCompatibleBrowser = true;
}
</cfscript>

<cfif isCompatibleBrowser>

	<!--- ::
		 * show html editor area for compatible browser
		:: --->

	<cfscript>
		// try to fix the basePath, if ending slash is missing
		if( len( attributes.basePath) and right( attributes.basePath, 1 ) is not "/" )
			attributes.basePath = attributes.basePath & "/";

		// construct the url
		sURL = attributes.basePath & "editor/fckeditor.html?InstanceName=" & attributes.instanceName;

		// append toolbarset name to the url
		if( len( attributes.toolbarSet ) )
			sURL = sURL & "&amp;Toolbar=" & attributes.toolbarSet;

		// create configuration string: Key1=Value1&Key2=Value2&... (Key/Value:HTML encoded)

		/**
		 * CFML doesn't store casesensitive names for structure keys, but the configuration names must be casesensitive for js.
		 * So we need to find out the correct case for the configuration keys.
		 * We "fix" this by comparing the caseless configuration keys to a list of all available configuration options in the correct case.
		 * changed 20041206 hk@lwd.de (improvements are welcome!)
		 */
		lConfigKeys = "";
		lConfigKeys = lConfigKeys & "CustomConfigurationsPath,EditorAreaCSS,ToolbarComboPreviewCSS,DocType";
		lConfigKeys = lConfigKeys & ",BaseHref,FullPage,Debug,AllowQueryStringDebug,SkinPath";
		lConfigKeys = lConfigKeys & ",PreloadImages,PluginsPath,AutoDetectLanguage,DefaultLanguage,ContentLangDirection";
		lConfigKeys = lConfigKeys & ",ProcessHTMLEntities,IncludeLatinEntities,IncludeGreekEntities,ProcessNumericEntities,AdditionalNumericEntities";
		lConfigKeys = lConfigKeys & ",FillEmptyBlocks,FormatSource,FormatOutput,FormatIndentator";
		lConfigKeys = lConfigKeys & ",StartupFocus,ForcePasteAsPlainText,AutoDetectPasteFromWord,ForceSimpleAmpersand";
		lConfigKeys = lConfigKeys & ",TabSpaces,ShowBorders,SourcePopup,ToolbarStartExpanded,ToolbarCanCollapse";
		lConfigKeys = lConfigKeys & ",IgnoreEmptyParagraphValue,PreserveSessionOnFileBrowser,FloatingPanelsZIndex,TemplateReplaceAll,TemplateReplaceCheckbox";
		lConfigKeys = lConfigKeys & ",ToolbarLocation,ToolbarSets,EnterMode,ShiftEnterMode,Keystrokes";
		lConfigKeys = lConfigKeys & ",ContextMenu,BrowserContextMenuOnCtrl,FontColors,FontNames,FontSizes";
		lConfigKeys = lConfigKeys & ",FontFormats,StylesXmlPath,TemplatesXmlPath,SpellChecker,IeSpellDownloadUrl";
		lConfigKeys = lConfigKeys & ",SpellerPagesServerScript,FirefoxSpellChecker,MaxUndoLevels,DisableObjectResizing,DisableFFTableHandles";
		lConfigKeys = lConfigKeys & ",LinkDlgHideTarget	,LinkDlgHideAdvanced,ImageDlgHideLink	,ImageDlgHideAdvanced,FlashDlgHideAdvanced";
		lConfigKeys = lConfigKeys & ",ProtectedTags,BodyId,BodyClass,DefaultLinkTarget,CleanWordKeepsStructure";
		lConfigKeys = lConfigKeys & ",LinkBrowser,LinkBrowserURL,LinkBrowserWindowWidth,LinkBrowserWindowHeight,ImageBrowser";
		lConfigKeys = lConfigKeys & ",ImageBrowserURL,ImageBrowserWindowWidth,ImageBrowserWindowHeight,FlashBrowser,FlashBrowserURL";
		lConfigKeys = lConfigKeys & ",FlashBrowserWindowWidth ,FlashBrowserWindowHeight,LinkUpload,LinkUploadURL,LinkUploadWindowWidth";
		lConfigKeys = lConfigKeys & ",LinkUploadWindowHeight,LinkUploadAllowedExtensions,LinkUploadDeniedExtensions,ImageUpload,ImageUploadURL";
		lConfigKeys = lConfigKeys & ",ImageUploadAllowedExtensions,ImageUploadDeniedExtensions,FlashUpload,FlashUploadURL,FlashUploadAllowedExtensions";
		lConfigKeys = lConfigKeys & ",FlashUploadDeniedExtensions,SmileyPath,SmileyImages,SmileyColumns,SmileyWindowWidth,SmileyWindowHeight";

		sConfig = "";

		for( key in attributes.config )
		{
			iPos = listFindNoCase( lConfigKeys, key );
			if( iPos GT 0 )
			{
				if( len( sConfig ) )
					sConfig = sConfig & "&amp;";

				fieldValue = attributes.config[key];
				fieldName = listGetAt( lConfigKeys, iPos );

				sConfig = sConfig & urlEncodedFormat( fieldName ) & '=' & urlEncodedFormat( fieldValue );
			}
		}
	</cfscript>

	<cfoutput>
	<input type="hidden" id="#attributes.instanceName#" name="#attributes.instanceName#" value="#HTMLEditFormat(attributes.value)#" style="display:none" />
	<input type="hidden" id="#attributes.instanceName#___Config" value="#sConfig#" style="display:none" />
	<iframe id="#attributes.instanceName#___Frame" src="#sURL#" width="#attributes.width#" height="#attributes.height#" frameborder="0" scrolling="no"></iframe>
	</cfoutput>

<cfelse>

	<!--- ::
		 * show	plain textarea for non compatible browser
		:: --->

	<cfscript>
		// append unit "px" for numeric width and/or height values
		if( isNumeric( attributes.width ) )
			attributes.width = attributes.width & "px";
		if( isNumeric( attributes.height ) )
			attributes.height = attributes.height & "px";
	</cfscript>

	<!--- Fixed Bug ##1075166. hk@lwd.de 20041206 --->
	<cfoutput>
	<textarea name="#attributes.instanceName#" rows="4" cols="40" style="WIDTH: #attributes.width#; HEIGHT: #attributes.height#">#HTMLEditFormat(attributes.value)#</textarea>
	</cfoutput>

</cfif>

<cfsetting enablecfoutputonly="No"><cfexit method="exittag">
