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

# image data save dir
$img_dir	= './temp/';


# File size max(unit KB)
$MAX_CONTENT_SIZE =  30000;

# After file is uploaded, sometimes it is required to change its permissions
# so that it was possible to access it at the later time.
# If possible, it is recommended to set more restrictive permissions, like 0755.
# Set to 0 to disable this feature.
$CHMOD_ON_UPLOAD = 0777;

# See comments above.
# Used when creating folders that does not exist.
$CHMOD_ON_FOLDER_CREATE = 0755;

# Filelock (1=use,0=not use)
$PM{'flock'}		= '1';


# upload Content-Type list
my %UPLOAD_CONTENT_TYPE_LIST = (
	'image/(x-)?png'						=>	'png',	# PNG image
	'image/p?jpe?g'							=>	'jpg',	# JPEG image
	'image/gif'								=>	'gif',	# GIF image
	'image/x-xbitmap'						=>	'xbm',	# XBM image

	'image/(x-(MS-)?)?bmp'					=>	'bmp',	# Windows BMP image
	'image/pict'							=>	'pict',	# Macintosh PICT image
	'image/tiff'							=>	'tif',	# TIFF image
	'application/pdf'						=>	'pdf',	# PDF image
	'application/x-shockwave-flash'			=>	'swf',	# Shockwave Flash

	'video/(x-)?msvideo'					=>	'avi',	# Microsoft Video
	'video/quicktime'						=>	'mov',	# QuickTime Video
	'video/mpeg'							=>	'mpeg',	# MPEG Video
	'video/x-mpeg2'							=>	'mpv2', # MPEG2 Video

	'audio/(x-)?midi?'						=>	'mid',	# MIDI Audio
	'audio/(x-)?wav'						=>	'wav',	# WAV Audio
	'audio/basic'							=>	'au',	# ULAW Audio
	'audio/mpeg'							=>	'mpga',	# MPEG Audio

	'application/(x-)?zip(-compressed)?'	=>	'zip',	# ZIP Compress

	'text/html'								=>	'html', # HTML
	'text/plain'							=>	'txt',	# TEXT
	'(?:application|text)/(?:rtf|richtext)'	=>	'rtf',	# RichText

	'application/msword'					=>	'doc',	# Microsoft Word
	'application/vnd.ms-excel'				=>	'xls',	# Microsoft Excel

	''
);

# Upload is permitted.
# A regular expression is possible.
my %UPLOAD_EXT_LIST = (
	'png'					=>	'PNG image',
	'p?jpe?g|jpe|jfif|pjp'	=>	'JPEG image',
	'gif'					=>	'GIF image',
	'xbm'					=>	'XBM image',

	'bmp|dib|rle'			=>	'Windows BMP image',
	'pi?ct'					=>	'Macintosh PICT image',
	'tiff?'					=>	'TIFF image',
	'pdf'					=>	'PDF image',
	'swf'					=>	'Shockwave Flash',

	'avi'					=>	'Microsoft Video',
	'moo?v|qt'				=>	'QuickTime Video',
	'm(p(e?gv?|e|v)|1v)'	=>	'MPEG Video',
	'mp(v2|2v)'				=>	'MPEG2 Video',

	'midi?|kar|smf|rmi|mff'	=>	'MIDI Audio',
	'wav'					=>	'WAVE Audio',
	'au|snd'				=>	'ULAW Audio',
	'mp(e?ga|2|a|3)|abs'	=>	'MPEG Audio',

	'zip'					=>	'ZIP Compress',
	'lzh'					=>	'LZH Compress',
	'cab'					=>	'CAB Compress',

	'd?html?'				=>	'HTML',
	'rtf|rtx'				=>	'RichText',
	'txt|text'				=>	'Text',

	''
);


# sjis or euc
my $CHARCODE = 'sjis';

$TRANS_2BYTE_CODE = 0;

##############################################################################
# Summary
#
# Form Read input
#
# Parameters
# Returns
# Memo
##############################################################################
sub read_input
{
eval("use File::Copy;");
eval("use File::Path;");

	my ($FORM) = @_;

	if (defined $CHMOD_ON_FOLDER_CREATE && !$CHMOD_ON_FOLDER_CREATE) {
		mkdir("$img_dir");
	}
	else {
		umask(000);
		if (defined $CHMOD_ON_FOLDER_CREATE) {
			mkdir("$img_dir",$CHMOD_ON_FOLDER_CREATE);
		}
		else {
			mkdir("$img_dir",0777);
		}
	}

	undef $img_data_exists;
	undef @NEWFNAMES;
	undef @NEWFNAME_DATA;

	if($ENV{'CONTENT_LENGTH'} > 10000000 || $ENV{'CONTENT_LENGTH'} > $MAX_CONTENT_SIZE * 1024) {
		&upload_error(
			'Size Error',
			sprintf(
				"Transmitting size is too large.MAX <strong>%d KB</strong> Now Size <strong>%d KB</strong>(<strong>%d bytes</strong> Over)",
				$MAX_CONTENT_SIZE,
				int($ENV{'CONTENT_LENGTH'} / 1024),
				$ENV{'CONTENT_LENGTH'} - $MAX_CONTENT_SIZE * 1024
			)
		);
	}

	my $Buffer;
	if($ENV{'CONTENT_TYPE'} =~ /multipart\/form-data/) {
		# METHOD POST only
		return	unless($ENV{'CONTENT_LENGTH'});

		binmode(STDIN);
		# STDIN A pause character is detected.'(MacIE3.0 boundary of $ENV{'CONTENT_TYPE'} cannot be trusted.)
		my $Boundary = <STDIN>;
		$Boundary =~ s/\x0D\x0A//;
		$Boundary = quotemeta($Boundary);
		while(<STDIN>) {
			if(/^\s*Content-Disposition:/i) {
				my($name,$ContentType,$FileName);
				# form data get
				if(/\bname="([^"]+)"/i || /\bname=([^\s:;]+)/i) {
					$name = $1;
					$name	=~ tr/+/ /;
					$name	=~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
					&Encode(\$name);
				}
				if(/\bfilename="([^"]*)"/i || /\bfilename=([^\s:;]*)/i) {
					$FileName = $1 || 'unknown';
				}
				# head read
				while(<STDIN>) {
					last	if(! /\w/);
					if(/^\s*Content-Type:\s*"([^"]+)"/i || /^\s*Content-Type:\s*([^\s:;]+)/i) {
						$ContentType = $1;
					}
				}
				# body read
				$value = "";
				while(<STDIN>) {
					last	if(/^$Boundary/o);
					$value .= $_;
				};
				$lastline = $_;
				$value =~s /\x0D\x0A$//;
				if($value ne '') {
					if($FileName || $ContentType) {
						$img_data_exists = 1;
						(
							$FileName,		#
							$Ext,			#
							$Length,		#
							$ImageWidth,	#
							$ImageHeight,	#
							$ContentName	#
						) = &CheckContentType(\$value,$FileName,$ContentType);

						$FORM{$name}	= $FileName;
						$new_fname		= $FileName;
						push(@NEWFNAME_DATA,"$FileName\t$Ext\t$Length\t$ImageWidth\t$ImageHeight\t$ContentName");

						# Multi-upload correspondence
						push(@NEWFNAMES,$new_fname);
						open(OUT,">$img_dir/$new_fname");
						binmode(OUT);
						eval "flock(OUT,2);" if($PM{'flock'} == 1);
						print OUT $value;
						eval "flock(OUT,8);" if($PM{'flock'} == 1);
						close(OUT);

					} elsif($name) {
						$value	=~ tr/+/ /;
						$value	=~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
						&Encode(\$value,'trans');
						$FORM{$name} .= "\0"			if(defined($FORM{$name}));
						$FORM{$name} .= $value;
					}
				}
			};
			last if($lastline =~ /^$Boundary\-\-/o);
		}
	} elsif($ENV{'CONTENT_LENGTH'}) {
		read(STDIN,$Buffer,$ENV{'CONTENT_LENGTH'});
	}
	foreach(split(/&/,$Buffer),split(/&/,$ENV{'QUERY_STRING'})) {
		my($name, $value) = split(/=/);
		$name	=~ tr/+/ /;
		$name	=~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
		$value	=~ tr/+/ /;
		$value	=~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;

		&Encode(\$name);
		&Encode(\$value,'trans');
		$FORM{$name} .= "\0"			if(defined($FORM{$name}));
		$FORM{$name} .= $value;

	}

}

##############################################################################
# Summary
#
#	CheckContentType
#
# Parameters
# Returns
# Memo
##############################################################################
sub CheckContentType
{

	my($DATA,$FileName,$ContentType) = @_;
	my($Ext,$ImageWidth,$ImageHeight,$ContentName,$Infomation);
	my $DataLength = length($$DATA);

	# An unknown file type

	$_ = $ContentType;
	my $UnknownType = (
		!$_
		|| /^application\/(x-)?macbinary$/i
		|| /^application\/applefile$/i
		|| /^application\/octet-stream$/i
		|| /^text\/plane$/i
		|| /^x-unknown-content-type/i
	);

	# MacBinary(Mac Unnecessary data are deleted.)
	if($UnknownType || $ENV{'HTTP_USER_AGENT'} =~ /Macintosh|Mac_/) {
		if($DataLength > 128 && !unpack("C",substr($$DATA,0,1)) && !unpack("C",substr($$DATA,74,1)) && !unpack("C",substr($$DATA,82,1)) ) {
			my $MacBinary_ForkLength = unpack("N", substr($$DATA, 83, 4));		# ForkLength Get
			my $MacBinary_FileName = quotemeta(substr($$DATA, 2, unpack("C",substr($$DATA, 1, 1))));
			if($MacBinary_FileName && $MacBinary_ForkLength && $DataLength >= $MacBinary_ForkLength + 128
					&& ($FileName =~ /$MacBinary_FileName/i || substr($$DATA,102,4) eq 'mBIN')) {	# DATA TOP 128byte MacBinary!!
				$$DATA				= substr($$DATA,128,$MacBinary_ForkLength);
				my $ResourceLength	= $DataLength - $MacBinary_ForkLength - 128;
				$DataLength			= $MacBinary_ForkLength;
			}
		}
	}

	# A file name is changed into EUC.
#	&jcode::convert(\$FileName,'euc',$FormCodeDefault);
#	&jcode::h2z_euc(\$FileName);
	$FileName =~ s/^.*\\//;					# Windows, Mac
	$FileName =~ s/^.*\///;					# UNIX
	$FileName =~ s/&/&amp;/g;
	$FileName =~ s/"/&quot;/g;
	$FileName =~ s/</&lt;/g;
	$FileName =~ s/>/&gt;/g;
#
#	if($CHARCODE ne 'euc') {
#		&jcode::convert(\$FileName,$CHARCODE,'euc');
#	}

	# An extension is extracted and it changes into a small letter.
	my $FileExt;
	if($FileName =~ /\.(\w+)$/) {
		$FileExt = $1;
		$FileExt =~ tr/A-Z/a-z/;
	}

	# Executable file detection (ban on upload)
	if($$DATA =~ /^MZ/) {
		$Ext = 'exe';
	}
	# text
	if(!$Ext && ($UnknownType || $ContentType =~ /^text\//i || $ContentType =~ /^application\/(?:rtf|richtext)$/i || $ContentType =~ /^image\/x-xbitmap$/i)
				&& ! $$DATA =~ /[\000-\006\177\377]/) {
#		$$DATA =~ s/\x0D\x0A/\n/g;
#		$$DATA =~ tr/\x0D\x0A/\n\n/;
#
#		if(
#			$$DATA =~ /<\s*SCRIPT(?:.|\n)*?>/i
#				|| $$DATA =~ /<\s*(?:.|\n)*?\bONLOAD\s*=(?:.|\n)*?>/i
#				|| $$DATA =~ /<\s*(?:.|\n)*?\bONCLICK\s*=(?:.|\n)*?>/i
#				) {
#			$Infomation = '(JavaScript contains)';
#		}
#		if($$DATA =~ /<\s*TABLE(?:.|\n)*?>/i
#				|| $$DATA =~ /<\s*BLINK(?:.|\n)*?>/i
#				|| $$DATA =~ /<\s*MARQUEE(?:.|\n)*?>/i
#				|| $$DATA =~ /<\s*OBJECT(?:.|\n)*?>/i
#				|| $$DATA =~ /<\s*EMBED(?:.|\n)*?>/i
#				|| $$DATA =~ /<\s*FRAME(?:.|\n)*?>/i
#				|| $$DATA =~ /<\s*APPLET(?:.|\n)*?>/i
#				|| $$DATA =~ /<\s*FORM(?:.|\n)*?>/i
#				|| $$DATA =~ /<\s*(?:.|\n)*?\bSRC\s*=(?:.|\n)*?>/i
#				|| $$DATA =~ /<\s*(?:.|\n)*?\bDYNSRC\s*=(?:.|\n)*?>/i
#				) {
#			$Infomation = '(the HTML tag which is not safe is included)';
#		}

		if($FileExt =~ /^txt$/i || $FileExt =~ /^cgi$/i || $FileExt =~ /^pl$/i) {								# Text File
			$Ext = 'txt';
		} elsif($ContentType =~ /^text\/html$/i || $FileExt =~ /html?/i || $$DATA =~ /<\s*HTML(?:.|\n)*?>/i) {	# HTML File
			$Ext = 'html';
		} elsif($ContentType =~ /^image\/x-xbitmap$/i || $FileExt =~ /^xbm$/i) {								# XBM(x-BitMap) Image
			my $XbmName = $1;
			my ($XbmWidth, $XbmHeight);
			if($$DATA =~ /\#define\s*$XbmName\_width\s*(\d+)/i) {
				$XbmWidth = $1;
			}
			if($$DATA =~ /\#define\s*$XbmName\_height\s*(\d+)/i) {
				$XbmHeight = $1;
			}
			if($XbmWidth && $XbmHeight) {
				$Ext = 'xbm';
				$ImageWidth		= $XbmWidth;
				$ImageHeight	= $XbmHeight;
			}
		} else {		#
			$Ext = 'txt';
		}
	}

	# image
	if(!$Ext && ($UnknownType || $ContentType =~ /^image\//i)) {
		# PNG
		if($$DATA =~ /^\x89PNG\x0D\x0A\x1A\x0A/) {
			if(substr($$DATA, 12, 4) eq 'IHDR') {
				$Ext = 'png';
				($ImageWidth, $ImageHeight) = unpack("N2", substr($$DATA, 16, 8));
			}
		} elsif($$DATA =~ /^GIF8(?:9|7)a/) {															# GIF89a(modified), GIF89a, GIF87a
			$Ext = 'gif';
			($ImageWidth, $ImageHeight) = unpack("v2", substr($$DATA, 6, 4));
		} elsif($$DATA =~ /^II\x2a\x00\x08\x00\x00\x00/ || $$DATA =~ /^MM\x00\x2a\x00\x00\x00\x08/) {	# TIFF
			$Ext = 'tif';
		} elsif($$DATA =~ /^BM/) {																		# BMP
			$Ext = 'bmp';
		} elsif($$DATA =~ /^\xFF\xD8\xFF/ || $$DATA =~ /JFIF/) {										# JPEG
			my $HeaderPoint = index($$DATA, "\xFF\xD8\xFF", 0);
			my $Point = $HeaderPoint + 2;
			while($Point < $DataLength) {
				my($Maker, $MakerType, $MakerLength) = unpack("C2n",substr($$DATA,$Point,4));
				if($Maker != 0xFF || $MakerType == 0xd9 || $MakerType == 0xda) {
					last;
				} elsif($MakerType >= 0xC0 && $MakerType <= 0xC3) {
					$Ext = 'jpg';
					($ImageHeight, $ImageWidth) = unpack("n2", substr($$DATA, $Point + 5, 4));
					if($HeaderPoint > 0) {
						$$DATA = substr($$DATA, $HeaderPoint);
						$DataLength = length($$DATA);
					}
					last;
				} else {
					$Point += $MakerLength + 2;
				}
			}
		}
	}

	# audio
	if(!$Ext && ($UnknownType || $ContentType =~ /^audio\//i)) {
		# MIDI Audio
		if($$DATA =~ /^MThd/) {
			$Ext = 'mid';
		} elsif($$DATA =~ /^\x2esnd/) {		# ULAW Audio
			$Ext = 'au';
		} elsif($$DATA =~ /^RIFF/ || $$DATA =~ /^ID3/ && $$DATA =~ /RIFF/) {
			my $HeaderPoint = index($$DATA, "RIFF", 0);
			$_ = substr($$DATA, $HeaderPoint + 8, 8);
			if(/^WAVEfmt $/) {
				# WAVE
				if(unpack("V",substr($$DATA, $HeaderPoint + 16, 4)) == 16) {
					$Ext = 'wav';
				} else {					# RIFF WAVE MP3
					$Ext = 'mp3';
				}
			} elsif(/^RMIDdata$/) {			# RIFF MIDI
				$Ext = 'rmi';
			} elsif(/^RMP3data$/) {			# RIFF MP3
				$Ext = 'rmp';
			}
			if($ContentType =~ /^audio\//i) {
				$Infomation .= '(RIFF '. substr($$DATA, $HeaderPoint + 8, 4). ')';
			}
		}
	}

	# a binary file
	unless ($Ext) {
		# PDF image
		if($$DATA =~ /^\%PDF/) {
			# Picture size is not measured.
			$Ext = 'pdf';
		} elsif($$DATA =~ /^FWS/) {		# Shockwave Flash
			$Ext = 'swf';
		} elsif($$DATA =~ /^RIFF/ || $$DATA =~ /^ID3/ && $$DATA =~ /RIFF/) {
			my $HeaderPoint = index($$DATA, "RIFF", 0);
			$_ = substr($$DATA,$HeaderPoint + 8, 8);
			# AVI
			if(/^AVI LIST$/) {
				$Ext = 'avi';
			}
			if($ContentType =~ /^video\//i) {
				$Infomation .= '(RIFF '. substr($$DATA, $HeaderPoint + 8, 4). ')';
			}
		} elsif($$DATA =~ /^PK/) {			# ZIP Compress File
			$Ext = 'zip';
		} elsif($$DATA =~ /^MSCF/) {		# CAB Compress File
			$Ext = 'cab';
		} elsif($$DATA =~ /^Rar\!/) {		# RAR Compress File
			$Ext = 'rar';
		} elsif(substr($$DATA, 2, 5) =~ /^\-lh(\d+|d)\-$/) {		# LHA Compress File
			$Infomation .= "(lh$1)";
			$Ext = 'lzh';
		} elsif(substr($$DATA, 325, 25) eq "Apple Video Media Handler" || substr($$DATA, 325, 30) eq "Apple \x83\x72\x83\x66\x83\x49\x81\x45\x83\x81\x83\x66\x83\x42\x83\x41\x83\x6E\x83\x93\x83\x68\x83\x89") {
			# QuickTime
			$Ext = 'mov';
		}
	}

	# Header analysis failure
	unless ($Ext) {
		# It will be followed if it applies for the MIME type from the browser.
		foreach (keys %UPLOAD_CONTENT_TYPE_LIST) {
			next unless ($_);
			if($ContentType =~ /^$_$/i) {
				$Ext = $UPLOAD_CONTENT_TYPE_LIST{$_};
				$ContentName = &CheckContentExt($Ext);
				if(
					grep {$_ eq $Ext;} (
						'png',
						'gif',
						'jpg',
						'xbm',
						'tif',
						'bmp',
						'pdf',
						'swf',
						'mov',
						'zip',
						'cab',
						'lzh',
						'rar',
						'mid',
						'rmi',
						'au',
						'wav',
						'avi',
						'exe'
					)
				) {
					$Infomation .= ' / Header analysis failure';
				}
				if($Ext ne $FileExt && &CheckContentExt($FileExt) eq $ContentName) {
					$Ext = $FileExt;
				}
				last;
			}
		}
		# a MIME type is unknown--It judges from an extension.
		unless ($Ext) {
			$ContentName = &CheckContentExt($FileExt);
			if($ContentName) {
				$Ext = $FileExt;
				$Infomation .= ' /	MIME type is unknown('. $ContentType. ')';
				last;
			}
		}
	}

#	$ContentName = &CheckContentExt($Ext)	unless($ContentName);
#	if($Ext && $ContentName) {
#		$ContentName .=  $Infomation;
#	} else {
#		&upload_error(
#			'Extension Error',
#			"$FileName A not corresponding extension ($Ext)<BR>The extension which can be responded ". join(',', sort values(%UPLOAD_EXT_LIST))
#		);
#	}

#	# SSI Tag Deletion
#	if($Ext =~ /.?html?/ && $$DATA =~ /<\!/) {
#		foreach (
#			'config',
#			'echo',
#			'exec',
#			'flastmod',
#			'fsize',
#			'include'
#		) {
#			$$DATA =~ s/\#\s*$_/\&\#35\;$_/ig
#		}
#	}

	return (
		$FileName,
		$Ext,
		int($DataLength / 1024 + 1),
		$ImageWidth,
		$ImageHeight,
		$ContentName
	);
}

##############################################################################
# Summary
#
# Extension discernment
#
# Parameters
# Returns
# Memo
##############################################################################

sub CheckContentExt
{

	my($Ext) = @_;
	my $ContentName;
	foreach (keys %UPLOAD_EXT_LIST) {
		next	unless ($_);
		if($_ && $Ext =~ /^$_$/) {
			$ContentName = $UPLOAD_EXT_LIST{$_};
			last;
		}
	}
	return $ContentName;

}

##############################################################################
# Summary
#
# Form decode
#
# Parameters
# Returns
# Memo
##############################################################################
sub Encode
{

	my($value,$Trans) = @_;

#	my $FormCode = &jcode::getcode($value) || $FormCodeDefault;
#	$FormCodeDefault ||= $FormCode;
#
#	if($Trans && $TRANS_2BYTE_CODE) {
#		if($FormCode ne 'euc') {
#			&jcode::convert($value, 'euc', $FormCode);
#		}
#		&jcode::tr(
#			$value,
#			"\xA3\xB0-\xA3\xB9\xA3\xC1-\xA3\xDA\xA3\xE1-\xA3\xFA",
#			'0-9A-Za-z'
#		);
#		if($CHARCODE ne 'euc') {
#			&jcode::convert($value,$CHARCODE,'euc');
#		}
#	} else {
#		if($CHARCODE ne $FormCode) {
#			&jcode::convert($value,$CHARCODE,$FormCode);
#		}
#	}
#	if($CHARCODE eq 'euc') {
#		&jcode::h2z_euc($value);
#	} elsif($CHARCODE eq 'sjis') {
#		&jcode::h2z_sjis($value);
#	}

}

##############################################################################
# Summary
#
# Error Msg
#
# Parameters
# Returns
# Memo
##############################################################################

sub upload_error
{

	local($error_message)	= $_[0];
	local($error_message2)	= $_[1];

	print "Content-type: text/html\n\n";
	print<<EOF;
<HTML>
<HEAD>
<TITLE>Error Message</TITLE></HEAD>
<BODY>
<table border="1" cellspacing="10" cellpadding="10">
	<TR bgcolor="#0000B0">
	<TD bgcolor="#0000B0" NOWRAP><font size="-1" color="white"><B>Error Message</B></font></TD>
	</TR>
</table>
<UL>
<H4> $error_message </H4>
$error_message2 <BR>
</UL>
</BODY>
</HTML>
EOF
	&rm_tmp_uploaded_files; 		# Image Temporary deletion
	exit;
}

##############################################################################
# Summary
#
# Image Temporary deletion
#
# Parameters
# Returns
# Memo
##############################################################################

sub rm_tmp_uploaded_files
{
	if($img_data_exists == 1){
		sleep 1;
		foreach $fname_list(@NEWFNAMES) {
			if(-e "$img_dir/$fname_list") {
				unlink("$img_dir/$fname_list");
			}
		}
	}

}
1;
