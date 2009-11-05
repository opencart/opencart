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
 * Latvian language file.
 */

var CKFLang =
{

Dir : 'ltr',
HelpLang : 'en',
LangCode : 'lv',

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
DateTime : 'dd/mm/yyyy H:MM',
DateAmPm : ['AM','PM'],

// Folders
FoldersTitle	: 'Mapes',
FolderLoading	: 'Ielādē...',
FolderNew		: 'Lūdzu ierakstiet mapes nosaukumu: ',
FolderRename	: 'Lūdzu ierakstiet jauno mapes nosaukumu: ',
FolderDelete	: 'Vai tiešām vēlaties neatgriezeniski dzēst mapi "%1"?',
FolderRenaming	: ' (Pārsauc...)',
FolderDeleting	: ' (Dzēš...)',

// Files
FileRename		: 'Lūdzu ierakstiet jauno faila nosaukumu: ',
FileRenameExt	: 'Vai tiešām vēlaties mainīt faila paplašinājumu? Fails var palikt nelietojams.',
FileRenaming	: 'Pārsauc...',
FileDelete		: 'Vai tiešām vēlaties neatgriezeniski dzēst failu "%1"?',

// Toolbar Buttons (some used elsewhere)
Upload		: 'Augšupielādēt',
UploadTip	: 'Augšupielādēt jaunu failu',
Refresh		: 'Pārlādēt',
Settings	: 'Uzstādījumi',
Help		: 'Palīdzība',
HelpTip		: 'Palīdzība',

// Context Menus
Select		: 'Izvēlēties',
SelectThumbnail : 'Izvēlēties sīkbildi',
View		: 'Skatīt',
Download	: 'Lejupielādēt',

NewSubFolder	: 'Jauna apakšmape',
Rename			: 'Pārsaukt',
Delete			: 'Dzēst',

// Generic
OkBtn		: 'Labi',
CancelBtn	: 'Atcelt',
CloseBtn	: 'Aizvērt',

// Upload Panel
UploadTitle			: 'Jauna faila augšupielādēšana',
UploadSelectLbl		: 'Izvēlaties failu, ko augšupielādēt',
UploadProgressLbl	: '(Augšupielādē, lūdzu uzgaidiet...)',
UploadBtn			: 'Augšupielādēt izvēlēto failu',

UploadNoFileMsg		: 'Lūdzu izvēlaties failu no sava datora',

// Settings Panel
SetTitle		: 'Uzstādījumi',
SetView			: 'Attēlot:',
SetViewThumb	: 'Sīkbildes',
SetViewList		: 'Failu Sarakstu',
SetDisplay		: 'Rādīt:',
SetDisplayName	: 'Faila Nosaukumu',
SetDisplayDate	: 'Datumu',
SetDisplaySize	: 'Faila Izmēru',
SetSort			: 'Kārtot:',
SetSortName		: 'pēc Faila Nosaukuma',
SetSortDate		: 'pēc Datuma',
SetSortSize		: 'pēc Izmēra',

// Status Bar
FilesCountEmpty : '<Tukša mape>',
FilesCountOne	: '1 fails',
FilesCountMany	: '%1 faili',

// Size and Speed
Kb				: '%1 kB',
KbPerSecond		: '%1 kB/s',

// Connector Error Messages.
ErrorUnknown : 'Nebija iespējams pabeigt pieprasījumu. (Kļūda %1)',
Errors :
{
 10 : 'Nederīga komanda.',
 11 : 'Resursa veids netika norādīts pieprasījumā.',
 12 : 'Pieprasītais resursa veids nav derīgs.',
102 : 'Nederīgs faila vai mapes nosaukums.',
103 : 'Nav iespējams pabeigt pieprasījumu, autorizācijas aizliegumu dēļ.',
104 : 'Nav iespējams pabeigt pieprasījumu, failu sistēmas atļauju ierobežojumu dēļ.',
105 : 'Neatļauts faila paplašinājums.',
109 : 'Nederīgs pieprasījums.',
110 : 'Nezināma kļūda.',
115 : 'Fails vai mape ar šādu nosaukumu jau pastāv.',
116 : 'Mape nav atrasta. Lūdzu pārlādējiet šo logu un mēģiniet vēlreiz.',
117 : 'Fails nav atrasts. Lūdzu pārlādējiet failu sarakstu un mēģiniet vēlreiz.',
201 : 'Fails ar šādu nosaukumu jau eksistē. Augšupielādētais fails tika pārsaukts par "%1"',
202 : 'Nederīgs fails',
203 : 'Nederīgs fails. Faila izmērs pārsniedz pieļaujamo.',
204 : 'Augšupielādētais fails ir bojāts.',
205 : 'Neviena pagaidu mape nav pieejama priekš augšupielādēšanas uz servera.',
206 : 'Augšupielāde atcelta drošības apsvērumu dēļ. Fails satur HTML veida datus.',
500 : 'Failu pārlūks ir atslēgts drošības apsvērumu dēļ. Lūdzu sazinieties ar šīs sistēmas tehnisko administratoru vai pārbaudiet CKFinder konfigurācijas failu.',
501 : 'Sīkbilžu atbalsts ir atslēgts.'
},

// Other Error Messages.
ErrorMsg :
{
FileEmpty		: 'Faila nosaukumā nevar būt tukšums',
FolderEmpty		: 'Mapes nosaukumā nevar būt tukšums',

FileInvChar		: 'Faila nosaukums nedrīkst saturēt nevienu no sekojošajām zīmēm: \n\\ / : * ? " < > |',
FolderInvChar	: 'Mapes nosaukums nedrīkst saturēt nevienu no sekojošajām zīmēm: \n\\ / : * ? " < > |',

PopupBlockView	: 'Nav iespējams failu atvērt jaunā logā. Lūdzu veiciet izmaiņas uzstādījumos savai interneta pārlūkprogrammai un izslēdziet visus uznirstošo logu bloķētājus šai adresei.'
}

} ;
