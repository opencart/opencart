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
#  This is the File Manager Connector for Perl.
#####

sub CreateXmlHeader
{
	local($command,$resourceType,$currentFolder) = @_;

	# Create the XML document header.
	print '<?xml version="1.0" encoding="utf-8" ?>';

	# Create the main "Connector" node.
	print '<Connector command="' . $command . '" resourceType="' . $resourceType . '">';

	# Add the current folder node.
	print '<CurrentFolder path="' . ConvertToXmlAttribute($currentFolder) . '" url="' . ConvertToXmlAttribute(GetUrlFromPath($resourceType,$currentFolder)) . '" />';
}

sub CreateXmlFooter
{
	print '</Connector>';
}

sub SendError
{
	local( $number, $text ) = @_;

	print << "_HTML_HEAD_";
Content-Type:text/xml; charset=utf-8
Pragma: no-cache
Cache-Control: no-cache
Expires: Thu, 01 Dec 1994 16:00:00 GMT

_HTML_HEAD_

	# Create the XML document header
	print '<?xml version="1.0" encoding="utf-8" ?>' ;

	print '<Connector><Error number="' . $number . '" text="' . &specialchar_cnv( $text ) . '" /></Connector>' ;

	exit ;
}

1;
