#####
#  FCKeditor - The text editor for Internet - http://www.fckeditor.net
#  Copyright (C) 2003-2009 Frederico Caldeira Knabben
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
#  This is the File Manager Connector for Perl.
#####

sub RemoveFromStart
{
	local($sourceString, $charToRemove) = @_;
	$sPattern = '^' . $charToRemove . '+' ;
	$sourceString =~ s/^$charToRemove+//g;
	return $sourceString;
}

sub RemoveFromEnd
{
	local($sourceString, $charToRemove) = @_;
	$sPattern = $charToRemove . '+$' ;
	$sourceString =~ s/$charToRemove+$//g;
	return $sourceString;
}

sub ConvertToXmlAttribute
{
	local($value) = @_;
	return(&specialchar_cnv($value));
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

sub JS_cnv
{
	local($ch) = @_;

	$ch =~ s/\"/\\\"/g;	#"
	return($ch);
}

1;
