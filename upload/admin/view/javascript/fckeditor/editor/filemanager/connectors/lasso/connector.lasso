[//lasso
/*
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
 * This is the File Manager Connector for Lasso.
 */

    /*.....................................................................
    Include global configuration. See config.lasso for details.
    */
	include('config.lasso');


    /*.....................................................................
    Translate current date/time to GMT for custom header.
    */
	var('headerDate') = date_localtogmt(date)->format('%a, %d %b %Y %T GMT');


    /*.....................................................................
    Convert query string parameters to variables and initialize output.
    */
	var(
		'Command'		=	action_param('Command'),
		'Type'			=	action_param('Type'),
		'CurrentFolder'	=	action_param('CurrentFolder'),
		'ServerPath'	=	action_param('ServerPath'),
		'NewFolderName'	=	action_param('NewFolderName'),
		'NewFile'		=	null,
		'NewFileName'	=	string,
		'OrigFilePath'	=	string,
		'NewFilePath'	=	string,
		'commandData'	=	string,
		'folders'		=	'\t<Folders>\n',
		'files'			=	'\t<Files>\n',
		'errorNumber'	=	integer,
		'responseType'	=	'xml',
		'uploadResult'	=	'0'
	);

	/*.....................................................................
	Custom tag sets the HTML response.
	*/

	define_tag(
		'htmlreply',
		-namespace='fck_',
		-priority='replace',
		-required='uploadResult',
		-optional='NewFilePath',
		-type='string',
		-description='Sets the HTML response for the FCKEditor File Upload feature.'
	);
		$__html_reply__ = '\
<script type="text/javascript">
(function()
{
	var d = document.domain ;

	while ( true )
	{
		// Test if we can access a parent property.
		try
		{
			var test = window.top.opener.document.domain ;
			break ;
		}
		catch( e ) {}

		// Remove a domain part: www.mytest.example.com => mytest.example.com => example.com ...
		d = d.replace( /.*?(?:\\.|$)/, "" ) ;

		if ( d.length == 0 )
			break ;		// It was not able to detect the domain.

		try
		{
			document.domain = d ;
		}
		catch (e)
		{
			break ;
		}
	}
})() ;
';
			if($uploadResult == '0' || $uploadResult == '201');
			$__html_reply__ = $__html_reply__ + '\
	window.parent.OnUploadCompleted(' + $uploadResult + ',\'' + $NewFilePath + '\',\'' + $NewFilePath->split('/')->last + '\');
</script>
			';
			else;
			$__html_reply__ = $__html_reply__ + '\
	window.parent.OnUploadCompleted(' + $uploadResult + ');
</script>
			';
			/if;
	/define_tag;


    /*.....................................................................
    Calculate the path to the current folder.
    */
	$ServerPath == '' ? $ServerPath = $config->find('UserFilesPath');

	var('currentFolderURL' = $ServerPath
		+ $config->find('Subdirectories')->find(action_param('Type'))
		+ $CurrentFolder
	);

	if($CurrentFolder->(Find: '..') || $CurrentFolder->(Find: '\\'));
		if($Command == 'FileUpload');
			$responseType = 'html';
			$uploadResult = '102';
			fck_htmlreply(
				-uploadResult=$uploadResult
			);
		else;
			$errorNumber = 102;
			$commandData += '<Error number="' + $errorNumber + '" />\n';
		/if;
	else;

    /*.....................................................................
    Build the appropriate response per the 'Command' parameter. Wrap the
    entire process in an inline for file tag permissions.
    */
	inline($connection);
		select($Command);
            /*.............................................................
            List all subdirectories in the 'Current Folder' directory.
            */
			case('GetFolders');
				$commandData += '\t<Folders>\n';

				iterate(file_listdirectory($currentFolderURL), local('this'));
					#this->endswith('/') ? $commandData += '\t\t<Folder name="' + #this->removetrailing('/')& + '" />\n';
				/iterate;

				$commandData += '\t</Folders>\n';


            /*.............................................................
            List both files and folders in the 'Current Folder' directory.
            Include the file sizes in kilobytes.
            */
			case('GetFoldersAndFiles');
				iterate(file_listdirectory($currentFolderURL), local('this'));
					if(#this->endswith('/'));
						$folders += '\t\t<Folder name="' + #this->removetrailing('/')& + '" />\n';
					else;
						local('size') = file_getsize($currentFolderURL + #this) / 1024;
						$files += '\t\t<File name="' + #this + '" size="' + #size + '" />\n';
					/if;
				/iterate;

				$folders += '\t</Folders>\n';
				$files += '\t</Files>\n';

				$commandData += $folders + $files;


            /*.............................................................
            Create a directory 'NewFolderName' within the 'Current Folder.'
            */
			case('CreateFolder');
				$NewFolderName = (String_ReplaceRegExp: $NewFolderName, -find='\\.|\\\\|\\/|\\||\\:|\\?|\\*|"|<|>', -replace='_');
				var('newFolder' = $currentFolderURL + $NewFolderName + '/');
				file_create($newFolder);


                /*.........................................................
                Map Lasso's file error codes to FCKEditor's error codes.
                */
				select(file_currenterror( -errorcode));
					case(0);
						$errorNumber = 0;
					case( -9983);
						$errorNumber = 101;
					case( -9976);
						$errorNumber = 102;
					case( -9977);
						$errorNumber = 102;
					case( -9961);
						$errorNumber = 103;
					case;
						$errorNumber = 110;
				/select;

				$commandData += '<Error number="' + $errorNumber + '" />\n';


            /*.............................................................
            Process an uploaded file.
            */
			case('FileUpload');
                /*.........................................................
                This is the only command that returns an HTML response.
                */
				$responseType = 'html';


                /*.........................................................
                Was a file actually uploaded?
                */
                if(file_uploads->size);
                	$NewFile = file_uploads->get(1);
                else;
                	$uploadResult = '202';
                /if;

				if($uploadResult == '0');
                    /*.....................................................
                    Split the file's extension from the filename in order
                    to follow the API's naming convention for duplicate
                    files. (Test.txt, Test(1).txt, Test(2).txt, etc.)
                    */
					$NewFileName = $NewFile->find('OrigName');
					$NewFileName = (String_ReplaceRegExp: $NewFileName, -find='\\\\|\\/|\\||\\:|\\?|\\*|"|<|>', -replace='_');
					$OrigFilePath = $currentFolderURL + $NewFileName;
					$NewFilePath = $OrigFilePath;
					local('fileExtension') = '.' + $NewFile->find('OrigExtension');
					#fileExtension = (String_ReplaceRegExp: #fileExtension, -find='\\\\|\\/|\\||\\:|\\?|\\*|"|<|>', -replace='_');
					local('shortFileName') = $NewFileName->removetrailing(#fileExtension)&;


                    /*.....................................................
                    Make sure the file extension is allowed.
                    */
					if($config->find('DeniedExtensions')->find($Type) >> $NewFile->find('OrigExtension'));
						$uploadResult = '202';
					else;
                        /*.................................................
                        Rename the target path until it is unique.
                        */
						while(file_exists($NewFilePath));
							$NewFilePath = $currentFolderURL + #shortFileName + '(' + loop_count + ')' + #fileExtension;
						/while;


                        /*.................................................
                        Copy the uploaded file to its final location.
                        */
						file_copy($NewFile->find('path'), $NewFilePath);


                        /*.................................................
                        Set the error code for the response. Note whether
                        the file had to be renamed.
                        */
						select(file_currenterror( -errorcode));
							case(0);
								$OrigFilePath != $NewFilePath ? $uploadResult = 201;
							case;
								$uploadResult = file_currenterror( -errorcode);
						/select;
					/if;
				/if;
				fck_htmlreply(
					-uploadResult=$uploadResult,
					-NewFilePath=$NewFilePath
				);
		/select;
	/inline;
	/if;

    /*.....................................................................
    Send a custom header for xml responses.
    */
	if($responseType == 'xml');
		header;
]
HTTP/1.0 200 OK
Date: [$headerDate]
Server: Lasso Professional [lasso_version( -lassoversion)]
Expires: Mon, 26 Jul 1997 05:00:00 GMT
Last-Modified: [$headerDate]
Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0
Pragma: no-cache
Keep-Alive: timeout=15, max=98
Connection: Keep-Alive
Content-Type: text/xml; charset=utf-8
[//lasso
/header;

		/*
			Set the content type encoding for Lasso.
		*/
		content_type('text/xml; charset=utf-8');

		/*
			Wrap the response as XML and output.
		*/
		$__html_reply__ = '\
<?xml version="1.0" encoding="utf-8" ?>
<Connector command="' + $Command + '" resourceType="' + $Type + '">';

		if($errorNumber != '102');
			$__html_reply__ += '<CurrentFolder path="' + $CurrentFolder + '" url="' + $currentFolderURL + '" />';
		/if;

		$__html_reply__ += $commandData + '
</Connector>';
	/if;
]
