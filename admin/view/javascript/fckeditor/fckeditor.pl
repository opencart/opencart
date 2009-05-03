#####
#  FCKeditor - The text editor for Internet - http://www.fckeditor.net
#  Copyright (C) 2003-2008 Frederico Caldeira Knabben
#
#  == BEGIN LICENSE ==
#
#  Licensed under the terms of any of the following licenses at your
#  choice:
#
#   - GNU General Public License Version 2 or later (the "GPL")
#     http://www.gnu.org/licenses/gpl.html
#
#   - GNU Lesser General Public License Version 2.1 or later (the "LGPL")
#     http://www.gnu.org/licenses/lgpl.html
#
#   - Mozilla Public License Version 1.1 or later (the "MPL")
#     http://www.mozilla.org/MPL/MPL-1.1.html
#
#  == END LICENSE ==
#
#  This is the integration file for Perl.
#####

#my $InstanceName;
#my $BasePath;
#my $Width;
#my $Height;
#my $ToolbarSet;
#my $Value;
#my %Config;

sub FCKeditor
{

	local($instanceName) = @_;
	$InstanceName	= $instanceName;
	$BasePath		= '/fckeditor/';
	$Width			= '100%';
	$Height			= '200';
	$ToolbarSet		= 'Default';
	$Value			= '';
}

sub Create
{
	print &CreateHtml();
}

sub specialchar_cnv
{

	local($ch) = @_;

	$ch =~ s/&/&amp;/g;		# &
	$ch =~ s/\"/&quot;/g;	#"
	$ch =~ s/\'/&#39;/g;	# '
	$ch =~ s/</&lt;/g;		# <
	$ch =~ s/>/&gt;/g;		# >
	return($ch);
}

sub CreateHtml
{

	$HtmlValue = &specialchar_cnv($Value);
	$Html = '' ;
	if(&IsCompatible()) {
		$Link = $BasePath . "editor/fckeditor.html?InstanceName=$InstanceName";
		if($ToolbarSet ne '') {
			$Link .= "&amp;Toolbar=$ToolbarSet";
		}
		#// Render the linked hidden field.
		$Html .= "<input type=\"hidden\" id=\"$InstanceName\" name=\"$InstanceName\" value=\"$HtmlValue\" style=\"display:none\" />" ;

		#// Render the configurations hidden field.
		$cfgstr = &GetConfigFieldString();
		$wk = $InstanceName."___Config";
		$Html .= "<input type=\"hidden\" id=\"$wk\" value=\"$cfgstr\" style=\"display:none\" />" ;

		#// Render the editor IFRAME.
		$wk = $InstanceName."___Frame";
		$Html .= "<iframe id=\"$wk\" src=\"$Link\" width=\"$Width\" height=\"$Height\" frameborder=\"0\" scrolling=\"no\"></iframe>";
	} else {
		if($Width =~ /\%/g){
			$WidthCSS = $Width;
		} else {
			$WidthCSS = $Width . 'px';
		}
		if($Height =~ /\%/g){
			$HeightCSS = $Height;
		} else {
			$HeightCSS = $Height . 'px';
		}
		$Html .= "<textarea name=\"$InstanceName\" rows=\"4\" cols=\"40\" style=\"width: $WidthCSS; height: $HeightCSS\">$HtmlValue</textarea>";
	}
	return($Html);
}

sub IsCompatible
{

	$sAgent = $ENV{'HTTP_USER_AGENT'};
	if(($sAgent =~ /MSIE/i) && !($sAgent =~ /mac/i) && !($sAgent =~ /Opera/i)) {
		$iVersion = substr($sAgent,index($sAgent,'MSIE') + 5,3);
		return($iVersion >= 5.5) ;
	} elsif($sAgent =~ /Gecko\//i) {
		$iVersion = substr($sAgent,index($sAgent,'Gecko/') + 6,8);
		return($iVersion >= 20030210) ;
	} elsif($sAgent =~ /Opera\//i) {
		$iVersion = substr($sAgent,index($sAgent,'Opera/') + 6,4);
		return($iVersion >= 9.5) ;
	} elsif($sAgent =~ /AppleWebKit\/(\d+)/i) {
		return($1 >= 522) ;
	} else {
		return(0);		# 2.0 PR fix
	}
}

sub GetConfigFieldString
{
	$sParams = '';
	$bFirst = 0;
	foreach $sKey (keys %Config) {
		$sValue = $Config{$sKey};
		if($bFirst == 1) {
			$sParams .= '&amp;';
		} else {
			$bFirst = 1;
		}
		$k = &specialchar_cnv($sKey);
		$v = &specialchar_cnv($sValue);
		if($sValue eq "true") {
			$sParams .= "$k=true";
		} elsif($sValue eq "false") {
			$sParams .= "$k=false";
		} else {
			$sParams .= "$k=$v";
		}
	}
	return($sParams);
}

1;
