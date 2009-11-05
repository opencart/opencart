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
 * Danish language file.
 */

var CKFLang =
{

Dir : 'ltr',
HelpLang : 'en',
LangCode : 'da',

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
DateTime : 'dd/mm/yyyy HH:MM',
DateAmPm : ['AM','PM'],

// Folders
FoldersTitle	: 'Mapper',
FolderLoading	: 'Indlæser...',
FolderNew		: 'Skriv navnet på den nye mappe: ',
FolderRename	: 'Skriv det nye navn på mappen: ',
FolderDelete	: 'Er du sikker på, at du vil slette mappen "%1" ?',
FolderRenaming	: ' (Omdøber...)',
FolderDeleting	: ' (Sletter...)',

// Files
FileRename		: 'Skriv navnet på den nye fil: ',
FileRenameExt	: 'Er du sikker på, at du vil ændre filtypen? Filen kan muligvis ikke bruges bagefter.',
FileRenaming	: '(Omdøber...)',
FileDelete		: 'Er du sikker på, at du vil slette filen "%1" ?',

// Toolbar Buttons (some used elsewhere)
Upload		: 'Upload',
UploadTip	: 'Upload ny fil',
Refresh		: 'Opdatér',
Settings	: 'Indstillinger',
Help		: 'Hjælp',
HelpTip		: 'Hjælp',

// Context Menus
Select		: 'Vælg',
SelectThumbnail : 'Vælg thumbnail',
View		: 'Vis',
Download	: 'Download',

NewSubFolder	: 'Ny undermappe',
Rename			: 'Omdøb',
Delete			: 'Slet',

// Generic
OkBtn		: 'OK',
CancelBtn	: 'Annullér',
CloseBtn	: 'Luk',

// Upload Panel
UploadTitle			: 'Upload ny fil',
UploadSelectLbl		: 'Vælg den fil, som du vil uploade',
UploadProgressLbl	: '(Uploader, vent venligst...)',
UploadBtn			: 'Upload valgt fil',

UploadNoFileMsg		: 'Vælg en fil på din computer',

// Settings Panel
SetTitle		: 'Indstillinger',
SetView			: 'Vis:',
SetViewThumb	: 'Thumbnails',
SetViewList		: 'Liste',
SetDisplay		: 'Thumbnails:',
SetDisplayName	: 'Filnavn',
SetDisplayDate	: 'Dato',
SetDisplaySize	: 'Størrelse',
SetSort			: 'Sortering:',
SetSortName		: 'efter filnavn',
SetSortDate		: 'efter dato',
SetSortSize		: 'efter størrelse',

// Status Bar
FilesCountEmpty : '<tom mappe>',
FilesCountOne	: '1 fil',
FilesCountMany	: '%1 filer',

// Size and Speed
Kb				: '%1 kB',
KbPerSecond		: '%1 kB/s',

// Connector Error Messages.
ErrorUnknown : 'Det var ikke muligt at fuldføre handlingen. (Fejl: %1)',
Errors :
{
 10 : 'Ugyldig handling.',
 11 : 'Ressourcetypen blev ikke angivet i anmodningen.',
 12 : 'Ressourcetypen er ikke gyldig.',
102 : 'Ugyldig fil eller mappenavn.',
103 : 'Det var ikke muligt at fuldføre handlingen på grund af en begrænsning i rettigheder.',
104 : 'Det var ikke muligt at fuldføre handlingen på grund af en begrænsning i filsystem rettigheder.',
105 : 'Ugyldig filtype.',
109 : 'Ugyldig anmodning.',
110 : 'Ukendt fejl.',
115 : 'En fil eller mappe med det samme navn eksisterer allerede.',
116 : 'Mappen blev ikke fundet. Opdatér listen eller prøv igen.',
117 : 'Filen blev ikke fundet. Opdatér listen eller prøv igen.',
201 : 'En fil med det samme filnavn eksisterer allerede. Den uploadede fil er blevet omdøbt til "%1"',
202 : 'Ugyldig fil.',
203 : 'Ugyldig fil. Filstørrelsen er for stor.',
204 : 'Den uploadede fil er korrupt.',
205 : 'Der er ikke en midlertidig mappe til upload til rådighed på serveren.',
206 : 'Upload annulleret af sikkerhedsmæssige årsager. Filen indeholder HTML-lignende data.',
500 : 'Filbrowseren er deaktiveret af sikkerhedsmæssige årsager. Kontakt systemadministratoren eller kontrollér CKFinders konfigurationsfil.',
501 : 'Understøttelse af thumbnails er deaktiveret.'
},

// Other Error Messages.
ErrorMsg :
{
FileEmpty		: 'Filnavnet må ikke være tomt',
FolderEmpty		: 'Mappenavnet må ikke være tomt',

FileInvChar		: 'Filnavnet må ikke indeholde et af følgende tegn: \n\\ / : * ? " < > |',
FolderInvChar	: 'Mappenavnet må ikke indeholde et af følgende tegn: \n\\ / : * ? " < > |',

PopupBlockView	: 'Det var ikke muligt at åbne filen i et nyt vindue. Kontrollér konfigurationen i din browser, og deaktivér eventuelle popup-blokkere for denne hjemmeside.'
}

} ;
