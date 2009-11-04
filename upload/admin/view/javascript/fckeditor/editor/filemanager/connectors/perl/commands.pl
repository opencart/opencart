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

sub GetFolders
{

	local($resourceType, $currentFolder) = @_;

	# Map the virtual path to the local server path.
	$sServerDir = &ServerMapFolder($resourceType, $currentFolder);
	print "<Folders>";			# Open the "Folders" node.

	opendir(DIR,"$sServerDir");
	@files = grep(!/^\.\.?$/,readdir(DIR));
	closedir(DIR);

	foreach $sFile (@files) {
		if($sFile != '.' && $sFile != '..' && (-d "$sServerDir$sFile")) {
			$cnv_filename = &ConvertToXmlAttribute($sFile);
			print '<Folder name="' . $cnv_filename . '" />';
		}
	}
	print "</Folders>";			# Close the "Folders" node.
}

sub GetFoldersAndFiles
{

	local($resourceType, $currentFolder) = @_;
	# Map the virtual path to the local server path.
	$sServerDir = &ServerMapFolder($resourceType,$currentFolder);

	# Initialize the output buffers for "Folders" and "Files".
	$sFolders	= '<Folders>';
	$sFiles		= '<Files>';

	opendir(DIR,"$sServerDir");
	@files = grep(!/^\.\.?$/,readdir(DIR));
	closedir(DIR);

	foreach $sFile (@files) {
		if($sFile ne '.' && $sFile ne '..') {
			if(-d "$sServerDir$sFile") {
				$cnv_filename = &ConvertToXmlAttribute($sFile);
				$sFolders .= '<Folder name="' . $cnv_filename . '" />' ;
			} else {
				($iFileSize,$refdate,$filedate,$fileperm) = (stat("$sServerDir$sFile"))[7,8,9,2];
				if($iFileSize > 0) {
					$iFileSize = int($iFileSize / 1024);
					if($iFileSize < 1) {
						$iFileSize = 1;
					}
				}
				$cnv_filename = &ConvertToXmlAttribute($sFile);
				$sFiles	.= '<File name="' . $cnv_filename . '" size="' . $iFileSize . '" />' ;
			}
		}
	}
	print $sFolders ;
	print '</Folders>';			# Close the "Folders" node.
	print $sFiles ;
	print '</Files>';			# Close the "Files" node.
}

sub CreateFolder
{

	local($resourceType, $currentFolder) = @_;
	$sErrorNumber	= '0' ;
	$sErrorMsg		= '' ;

	if($FORM{'NewFolderName'} ne "") {
		$sNewFolderName = $FORM{'NewFolderName'};
		$sNewFolderName =~ s/\.|\\|\/|\||\:|\?|\*|\"|<|>|[[:cntrl:]]/_/g;
		# Map the virtual path to the local server path of the current folder.
		$sServerDir = &ServerMapFolder($resourceType, $currentFolder);
		if(-w $sServerDir) {
			$sServerDir .= $sNewFolderName;
			$sErrorMsg = &CreateServerFolder($sServerDir);
			if($sErrorMsg == 0) {
				$sErrorNumber = '0';
			} elsif($sErrorMsg eq 'Invalid argument' || $sErrorMsg eq 'No such file or directory') {
				$sErrorNumber = '102';		#// Path too long.
			} else {
				$sErrorNumber = '110';
			}
		} else {
			$sErrorNumber = '103';
		}
	} else {
		$sErrorNumber = '102' ;
	}
	# Create the "Error" node.
	$cnv_errmsg = &ConvertToXmlAttribute($sErrorMsg);
	print '<Error number="' . $sErrorNumber . '" />';
}

sub FileUpload
{
eval("use File::Copy;");

	local($resourceType, $currentFolder) = @_;
	$allowedExtensions = $allowedExtensions{$resourceType};

	$sErrorNumber = '0' ;
	$sFileName = '' ;
	if($new_fname) {
		# Map the virtual path to the local server path.
		$sServerDir = &ServerMapFolder($resourceType,$currentFolder);

		# Get the uploaded file name.
		$sFileName = $new_fname;
		$sFileName =~ s/\\|\/|\||\:|\?|\*|\"|<|>|[[:cntrl:]]/_/g;
		$sFileName =~ s/\.(?![^.]*$)/_/g;

		$ext = '';
		if($sFileName =~ /([^\\\/]*)\.(.*)$/) {
			$ext  = $2;
		}

		$allowedRegex = qr/^($allowedExtensions)$/i;
		if (!($ext =~ $allowedRegex)) {
			SendUploadResults('202', '', '', '');
		}

		$sOriginalFileName = $sFileName;

		$iCounter = 0;
		while(1) {
			$sFilePath = $sServerDir . $sFileName;
			if(-e $sFilePath) {
				$iCounter++ ;
				($path,$BaseName,$ext) = &RemoveExtension($sOriginalFileName);
				$sFileName = $BaseName . '(' . $iCounter . ').' . $ext;
				$sErrorNumber = '201';
			} else {
				copy("$img_dir/$new_fname","$sFilePath");
				if (defined $CHMOD_ON_UPLOAD) {
					if ($CHMOD_ON_UPLOAD) {
						umask(000);
						chmod($CHMOD_ON_UPLOAD,$sFilePath);
					}
				}
				else {
					umask(000);
					chmod(0777,$sFilePath);
				}
				unlink("$img_dir/$new_fname");
				last;
			}
		}
	} else {
		$sErrorNumber = '202' ;
	}
	$sFileName	=~ s/"/\\"/g;

	SendUploadResults($sErrorNumber, $GLOBALS{'UserFilesPath'}.$resourceType.$currentFolder.$sFileName, $sFileName, '');
}

sub SendUploadResults
{

	local($sErrorNumber, $sFileUrl, $sFileName, $customMsg) = @_;

	# Minified version of the document.domain automatic fix script (#1919).
	# The original script can be found at _dev/domain_fix_template.js
	# Note: in Perl replace \ with \\ and $ with \$
	print <<EOF;
Content-type: text/html

<script type="text/javascript">
(function(){var d=document.domain;while (true){try{var A=window.parent.document.domain;break;}catch(e) {};d=d.replace(/.*?(?:\\.|\$)/,'');if (d.length==0) break;try{document.domain=d;}catch (e){break;}}})();

EOF
	print 'window.parent.OnUploadCompleted(' . $sErrorNumber . ',"' . JS_cnv($sFileUrl) . '","' . JS_cnv($sFileName) . '","' . JS_cnv($customMsg) . '") ;';
	print '</script>';
	exit ;
}

1;
