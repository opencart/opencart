/*
 * CKFinder
 * ========
 * http://ckfinder.com
 * Copyright (C) 2007-2009, CKSource - Frederico Knabben. All rights reserved.
 *
 * The software, this file and its contents are subject to the CKFinder
 * License. Please read the license.txt file before using, installing, copying,
 * modifying or distribute this file or part of its contents. The contents of
 * this file is part of the Source Code of CKFinder.
 *
 * ---
 * English language file.
 */

var CKFLang =
{

Dir : 'ltr',
HelpLang : 'en',
LangCode : 'en',

// Date Format
//		d    : Day
//		dd   : Day (padding zero)
//		m    : Month
//		mm   : Month (padding zero)
//		yy   : Year (two digits)
//		yyyy : Year (four digits)
//		h    : Hour (12 hour clock)
//		hh   : Hour (12 hour clock, padding zero)
//		H    : Hour (24 hour clock)
//		HH   : Hour (24 hour clock, padding zero)
//		M    : Minute
//		MM   : Minute (padding zero)
//		a    : Firt char of AM/PM
//		aa   : AM/PM
DateTime : 'm/d/yyyy h:MM aa',
DateAmPm : ['AM','PM'],

// Folders
FoldersTitle	: 'Folders',
FolderLoading	: 'Loading...',
FolderNew		: 'Please type the new folder name: ',
FolderRename	: 'Please type the new folder name: ',
FolderDelete	: 'Are you sure you want to delete the "%1" folder?',
FolderRenaming	: ' (Renaming...)',
FolderDeleting	: ' (Deleting...)',

// Files
FileRename		: 'Please type the new file name: ',
FileRenameExt	: 'Are you sure you want to change the file name extension? The file may become unusable',
FileRenaming	: 'Renaming...',
FileDelete		: 'Are you sure you want to delete the file "%1"?',

// Toolbar Buttons (some used elsewhere)
Upload		: 'Upload',
UploadTip	: 'Upload New File',
Refresh		: 'Refresh',
Settings	: 'Settings',
Help		: 'Help',
HelpTip		: 'Help',

// Context Menus
Select		: 'Select',
SelectThumbnail : 'Select Thumbnail',
View		: 'View',
Download	: 'Download',

NewSubFolder	: 'New Subfolder',
Rename			: 'Rename',
Delete			: 'Delete',

// Generic
OkBtn		: 'OK',
CancelBtn	: 'Cancel',
CloseBtn	: 'Close',

// Upload Panel
UploadTitle			: 'Upload New File',
UploadSelectLbl		: 'Select the file to upload',
UploadProgressLbl	: '(Upload in progress, please wait...)',
UploadBtn			: 'Upload Selected File',

UploadNoFileMsg		: 'Please select a file from your computer',

// Settings Panel
SetTitle		: 'Settings',
SetView			: 'View:',
SetViewThumb	: 'Thumbnails',
SetViewList		: 'List',
SetDisplay		: 'Display:',
SetDisplayName	: 'File Name',
SetDisplayDate	: 'Date',
SetDisplaySize	: 'File Size',
SetSort			: 'Sorting:',
SetSortName		: 'by File Name',
SetSortDate		: 'by Date',
SetSortSize		: 'by Size',

// Status Bar
FilesCountEmpty : '<Empty Folder>',
FilesCountOne	: '1 file',
FilesCountMany	: '%1 file',

// Size and Speed
Kb				: '%1 kB',
KbPerSecond		: '%1 kB/s',

// Connector Error Messages.
ErrorUnknown : 'It was not possible to complete the request. (Error %1)',
Errors :
{
 10 : 'Invalid command.',
 11 : 'The resource type was not specified in the request.',
 12 : 'The requested resource type is not valid.',
102 : 'Invalid file or folder name.',
103 : 'It was not possible to complete the request due to authorization restrictions.',
104 : 'It was not possible to complete the request due to file system permission restrictions.',
105 : 'Invalid file extension.',
109 : 'Invalid request.',
110 : 'Unknown error.',
115 : 'A file or folder with the same name already exists.',
116 : 'Folder not found. Please refresh and try again.',
117 : 'File not found. Please refresh the files list and try again.',
201 : 'A file with the same name is already available. The uploaded file has been renamed to "%1"',
202 : 'Invalid file',
203 : 'Invalid file. The file size is too big.',
204 : 'The uploaded file is corrupt.',
205 : 'No temporary folder is available for upload in the server.',
206 : 'Upload cancelled for security reasons. The file contains HTML like data.',
500 : 'The file browser is disabled for security reasons. Please contact your system administrator and check the CKFinder configuration file.',
501 : 'The thumbnails support is disabled.'
},

// Other Error Messages.
ErrorMsg :
{
FileEmpty		: 'The file name cannot be empty',
FolderEmpty		: 'The folder name cannot be empty',

FileInvChar		: 'The file name cannot contain any of the following characters: \n\\ / : * ? " < > |',
FolderInvChar	: 'The folder name cannot contain any of the following characters: \n\\ / : * ? " < > |',

PopupBlockView	: 'It was not possible to open the file in a new window. Please configure your browser and disable all popup blockers for this site.'
}

} ;
