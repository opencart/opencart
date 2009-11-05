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
 * Swedish language file.
 */

var CKFLang =
{

Dir : 'ltr',
HelpLang : 'en',

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
DateTime : 'yyyy-mm-dd HH:MM',
DateAmPm : ['AM','PM'],

// Folders
FoldersTitle	: 'Mappar',
FolderLoading	: 'Laddar...',
FolderNew		: 'Skriv namnet på den nya mappen: ',
FolderRename	: 'Skriv det nya namnet på mappen: ',
FolderDelete	: 'Är du säker på att du vill radera mappen "%1"?',
FolderRenaming	: ' (Byter mappens namn...)',
FolderDeleting	: ' (Raderar...)',

// Files
FileRename		: 'Skriv det nya filnamnet: ',
FileRenameExt	: 'Är du säker på att du fill ändra på filändelsen? Filen kan bli oanvändbar',
FileRenaming	: 'Byter filnamn...',
FileDelete		: 'Är du säker på att du vill radera filen "%1"?',

// Toolbar Buttons (some used elsewhere)
Upload		: 'Ladda upp',
UploadTip	: 'Ladda upp en ny fil',
Refresh		: 'Uppdatera',
Settings	: 'Inställningar',
Help		: 'Hjälp',
HelpTip		: 'Hjälp',

// Context Menus
Select		: 'Infoga bild',
SelectThumbnail : 'Infoga som tumnagel',
View		: 'Visa',
Download	: 'Ladda ner',

NewSubFolder	: 'Ny Undermapp',
Rename			: 'Byt namn',
Delete			: 'Radera',

// Generic
OkBtn		: 'OK',
CancelBtn	: 'Avbryt',
CloseBtn	: 'Stäng',

// Upload Panel
UploadTitle			: 'Ladda upp en ny fil',
UploadSelectLbl		: 'Välj fil att ladda upp',
UploadProgressLbl	: '(Laddar upp filen, var god vänta...)',
UploadBtn			: 'Ladda upp den valda filen',

UploadNoFileMsg		: 'Välj en fil från din dator',

// Settings Panel
SetTitle		: 'Inställningar',
SetView			: 'Visa:',
SetViewThumb	: 'Tumnaglar',
SetViewList		: 'Lista',
SetDisplay		: 'Visa:',
SetDisplayName	: 'Filnamn',
SetDisplayDate	: 'Datum',
SetDisplaySize	: 'Filstorlek',
SetSort			: 'Sortering:',
SetSortName		: 'Filnamn',
SetSortDate		: 'Datum',
SetSortSize		: 'Storlek',

// Status Bar
FilesCountEmpty : '<Tom Mapp>',
FilesCountOne	: '1 fil',
FilesCountMany	: '%1 filer',

// Connector Error Messages.
ErrorUnknown : 'Begäran kunde inte utföras eftersom ett fel uppstod. (Error %1)',
Errors :
{
 10 : 'Ogiltig begäran.',
 11 : 'Resursens typ var inte specificerad i förfrågan.',
 12 : 'Den efterfrågade resurstypen är inte giltig.',
102 : 'Ogiltigt fil- eller mappnamn.',
103 : 'Begäran kunde inte utföras p.g.a. restriktioner av rättigheterna.',
104 : 'Begäran kunde inte utföras p.g.a. restriktioner av rättigheter i filsystemet.',
105 : 'Ogiltig filändelse.',
109 : 'Ogiltig begäran.',
110 : 'Okänt fel.',
115 : 'En fil eller mapp med aktuellt namn finns redan.',
116 : 'Mappen kunde inte hittas. Var god uppdatera sidan och försök igen.',
117 : 'Filen kunde inte hittas. Var god uppdatera sidan och försök igen.',
201 : 'En fil med aktuellt namn fanns redan. Den uppladdade filen har döpts om till "%1"',
202 : 'Ogiltig fil',
203 : 'Ogiltig fil. Filen var för stor.',
204 : 'Den uppladdade filen var korrupt.',
205 : 'En tillfällig mapp för uppladdning är inte tillgänglig på servern.',
206 : 'Uppladdningen stoppades av säkerhetsskäl. Filen innehåller HTML-liknande data.',
500 : 'Filhanteraren har stoppats av säkerhetsskäl. Var god kontakta administratören för att kontrollera konfigurationsfilen för CKFinder.',
501 : 'Stöd för tumnaglar har stängts av.'
},

// Other Error Messages.
ErrorMsg :
{
FileEmpty		: 'Filnamnet får inte vara tomt',
FolderEmpty		: 'Mappens namn får inte vara tomt',

FileInvChar		: 'Filnamnet får inte innehålla något av följande tecken: \n\\ / : * ? " < > |',
FolderInvChar	: 'Mappens namn får inte innehålla något av följande tecken: \n\\ / : * ? " < > |',

PopupBlockView	: 'Det gick inte att öppna filen i ett nytt fönster. Ändra inställningarna i din webbläsare och tillåt popupfönster för den här hemsidan.'
}

} ;
