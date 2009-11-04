[//lasso
/*
 * FCKeditor - The text editor for Internet - http://www.fckeditor.net
 * Copyright (C) 2003-2009 Frederico Caldeira Knabben
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
 * This is the "File Uploader" for Lasso.
 */

    /*.....................................................................
    Include global configuration. See config.lasso for details.
    */
	include('config.lasso');


    /*.....................................................................
    Convert query string parameters to variables and initialize output.
    */
	var(
		'Type'			=	(Encode_HTML: action_param('Type')),
		'CurrentFolder'	=	"/",
		'ServerPath'	=	action_param('ServerPath'),
		'NewFile'		=	null,
		'NewFileName'	=	string,
		'OrigFilePath'	=	string,
		'NewFilePath'	=	string,
		'errorNumber'	=	0,
		'customMsg'		=	''
	);

	$Type == '' ? $Type = 'File';


    /*.....................................................................
    Calculate the path to the current folder.
    */
	$ServerPath == '' ? $ServerPath = $config->find('UserFilesPath');

	var('currentFolderURL' = $ServerPath
		+ $config->find('Subdirectories')->find(action_param('Type'))
		+ $CurrentFolder
	);

	$currentFolderURL = string_replace($currentFolderURL, -find='//', -replace='/');

	/*.....................................................................
	Custom tag sets the HTML response.
	*/

	define_tag(
		'sendresults',
		-namespace='fck_',
		-priority='replace',
		-required='errorNumber',
		-type='integer',
		-optional='fileUrl',
		-type='string',
		-optional='fileName',
		-type='string',
		-optional='customMsg',
		-type='string',
		-description='Sets the HTML response for the FCKEditor Quick Upload feature.'
	);

		$__html_reply__ = '<script type="text/javascript">';

		// Minified version of the document.domain automatic fix script (#1919).
		// The original script can be found at _dev/domain_fix_template.js
		// Note: in Lasso replace \ with \\
		$__html_reply__ = $__html_reply__ + "(function(){var d=document.domain;while (true){try{var A=window.parent.document.domain;break;}catch(e) {};d=d.replace(/.*?(?:\\.|$)/,'');if (d.length==0) break;try{document.domain=d;}catch (e){break;}}})();";

		$__html_reply__ = $__html_reply__ + '\
	window.parent.OnUploadCompleted(' + #errorNumber + ',"'
		+ string_replace((Encode_HTML: #fileUrl), -find='"', -replace='\\"') + '","'
		+ string_replace((Encode_HTML: #fileUrl->split('/')->last), -find='"', -replace='\\"') + '","'
		+ string_replace((Encode_HTML: #customMsg), -find='"', -replace='\\"') + '");
</script>
		';
	/define_tag;

	if($CurrentFolder->(Find: '..') || (String_FindRegExp: $CurrentFolder, -Find='(/\\.)|(//)|[\\\\:\\*\\?\\""\\<\\>\\|]|\\000|[\u007F]|[\u0001-\u001F]'));
		$errorNumber = 102;
	/if;

	if($config->find('Enabled'));
		/*.................................................................
		Process an uploaded file.
		*/
		inline($connection);
			/*.............................................................
			Was a file actually uploaded?
			*/
			if($errorNumber != '102');
				file_uploads->size ? $NewFile = file_uploads->get(1) | $errorNumber = 202;
			/if;

			if($errorNumber == 0);
				/*.........................................................
				Split the file's extension from the filename in order
				to follow the API's naming convention for duplicate
				files. (Test.txt, Test(1).txt, Test(2).txt, etc.)
				*/
				$NewFileName = $NewFile->find('OrigName');
				$NewFileName = (String_ReplaceRegExp: $NewFileName, -find='\\\\|\\/|\\||\\:|\\?|\\*|"|<|>|\\000|[\u007F]|[\u0001-\u001F]', -replace='_');
				$NewFileName = (String_ReplaceRegExp: $NewFileName, -find='\\.(?![^.]*$)', -replace='_');
				$OrigFilePath = $currentFolderURL + $NewFileName;
				$NewFilePath = $OrigFilePath;
				local('fileExtension') = '.' + $NewFile->find('OrigExtension');
				local('shortFileName') = $NewFileName->removetrailing(#fileExtension)&;


				/*.........................................................
				Make sure the file extension is allowed.
				*/

				local('allowedExt') = $config->find('AllowedExtensions')->find($Type);
				local('deniedExt') = $config->find('DeniedExtensions')->find($Type);
				if($allowedExt->Size > 0 && $allowedExt !>> $NewFile->find('OrigExtension'));
					$errorNumber = 202;
				else($deniedExt->Size > 0 && $deniedExt >> $NewFile->find('OrigExtension'));
					$errorNumber = 202;
				else;
					/*.....................................................
					Rename the target path until it is unique.
					*/
					while(file_exists($NewFilePath));
						$NewFileName = #shortFileName + '(' + loop_count + ')' + #fileExtension;
						$NewFilePath = $currentFolderURL + $NewFileName;
					/while;


					/*.....................................................
					Copy the uploaded file to its final location.
					*/
					file_copy($NewFile->find('path'), $NewFilePath);


					/*.....................................................
					Set the error code for the response.
					*/
					select(file_currenterror( -errorcode));
						case(0);
							$OrigFilePath != $NewFilePath ? $errorNumber = 201;
						case;
							$errorNumber = 202;
					/select;
				/if;
			/if;
			if ($errorNumber != 0 && $errorNumber != 201);
				$NewFilePath = "";
			/if;
		/inline;
	else;
		$errorNumber = 1;
		$customMsg = 'This file uploader is disabled. Please check the "editor/filemanager/upload/lasso/config.lasso" file.';
	/if;

	fck_sendresults(
		-errorNumber=$errorNumber,
		-fileUrl=$NewFilePath,
		-customMsg=$customMsg
	);
]
