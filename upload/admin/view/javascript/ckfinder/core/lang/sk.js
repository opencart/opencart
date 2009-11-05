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
 * Slovak language file.
 */

var CKFLang =
{

Dir : 'ltr',
HelpLang : 'en',
LangCode : 'sk',

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
DateTime : 'mm/dd/yyyy HH:MM',
DateAmPm : ['AM','PM'],

// Folders
FoldersTitle	: 'Adresáre',
FolderLoading	: 'Nahrávam...',
FolderNew	: 'Zadajte prosím meno nového adresára: ',
FolderRename	: 'Zadajte prosím meno nového adresára: ',
FolderDelete	: 'Skutočne zmazať adresár "%1" ?',
FolderRenaming	: ' (Prebieha premenovanie adresára...)',
FolderDeleting	: ' (Prebieha zmazanie adresára...)',

// Files
FileRename	: 'Zadajte prosím meno nového súboru: ',
FileRenameExt	: 'Skutočne chcete zmeniť príponu súboru? Upozornenie: zmenou prípony sa súbor môže stať nepoužiteľným, pokiaľ prípona nie je podporovaná.',
FileRenaming	: 'Prebieha premenovanie súboru...',
FileDelete	: 'Skutočne chcete odstrániť súbor "%1"?',

// Toolbar Buttons (some used elsewhere)
Upload		: 'Prekopírovať na server (Upload)',
UploadTip	: 'Prekopírovať nový súbor',
Refresh		: 'Znovunačítať (Refresh)',
Settings	: 'Nastavenia',
Help		: 'Pomoc',
HelpTip		: 'Pomoc',

// Context Menus
Select		: 'Vybrať',
View		: 'Náhľad',
Download	: 'Stiahnuť',

NewSubFolder	: 'Nový podadresár',
Rename		: 'Premenovať',
Delete		: 'Zmazať',

// Generic
OkBtn		: 'OK',
CancelBtn	: 'Zrušiť',
CloseBtn	: 'Zatvoriť',

// Upload Panel
UploadTitle		: 'Nahrať nový súbor',
UploadSelectLbl		: 'Vyberte súbor, ktorý chcete prekopírovať na server',
UploadProgressLbl	: '(Prebieha kopírovanie, čakajte prosím...)',
UploadBtn		: 'Prekopírovať vybratý súbor',

UploadNoFileMsg		: 'Vyberte prosím súbor na Vašom počítači!',

// Settings Panel
SetTitle		: 'Nastavenia',
SetView			: 'Náhľad:',
SetViewThumb	: 'Miniobrázky',
SetViewList		: 'Zoznam',
SetDisplay		: 'Zobraziť:',
SetDisplayName	: 'Názov súboru',
SetDisplayDate	: 'Dátum',
SetDisplaySize	: 'Veľkosť súboru',
SetSort			: 'Zoradenie:',
SetSortName		: 'podľa názvu súboru',
SetSortDate		: 'podľa dátumu',
SetSortSize		: 'podľa veľkosti',

// Status Bar
FilesCountEmpty : '<Prázdny adresár>',
FilesCountOne	: '1 súbor',
FilesCountMany	: '%1 súborov',

// Size and Speed
Kb				: '%1 kB',
KbPerSecond		: '%1 kB/s',

// Connector Error Messages.
ErrorUnknown : 'Server nemohol dokončiť spracovanie požiadavky. (Chyba %1)',
Errors :
{
 10 : 'Neplatný príkaz.',
 11 : 'V požiadavke nebol špecifikovaný typ súboru.',
 12 : 'Nepodporovaný typ súboru.',
102 : 'Neplatný názov súboru alebo adresára.',
103 : 'Nebolo možné dokončiť spracovanie požiadavky kvôli nepostačujúcej úrovni oprávnení.',
104 : 'Nebolo možné dokončiť spracovanie požiadavky kvôli obmedzeniam v prístupových právach ku súborom.',
105 : 'Neplatná prípona súboru.',
109 : 'Neplatná požiadavka.',
110 : 'Neidentifikovaná chyba.',
115 : 'Zadaný súbor alebo adresár už existuje.',
116 : 'Adresár nebol nájdený. Aktualizujte obsah adresára (Znovunačítať) a skúste znovu.',
117 : 'Súbor nebol nájdený. Aktualizujte obsah adresára (Znovunačítať) a skúste znovu.',
201 : 'Súbor so zadaným názvom už existuje. Prekopírovaný súbor bol premenovaný na "%1"',
202 : 'Neplatný súbor',
203 : 'Neplatný súbor - súbor presahuje maximálnu povolenú veľkosť.',
204 : 'Kopírovaný súbor je poškodený.',
205 : 'Server nemá špecifikovaný dočasný adresár pre kopírované súbory.',
206 : 'Kopírovanie prerušené kvôli nedostatočnému zabezpečeniu. Súbor obsahuje HTML data.',
500 : 'Prehliadanie súborov je zakázané kvôli bezpečnosti. Kontaktujte prosím administrátora a overte nastavenia v konfiguračnom súbore pre CKFinder.',
501 : 'Momentálne nie je zapnutá podpora pre generáciu miniobrázkov.'
},

// Other Error Messages.
ErrorMsg :
{
FileEmpty		: 'Názov súbor nesmie prázdny',
FolderEmpty		: 'Názov adresára nesmie byť prázdny',

FileInvChar		: 'Súbor nesmie obsahovať žiadny z nasledujúcich znakov: \n\\ / : * ? " < > |',
FolderInvChar	: 'Adresár nesmie obsahovať žiadny z nasledujúcich znakov: \n\\ / : * ? " < > |',

PopupBlockView	: 'Nebolo možné otvoriť súbor v novom okne. Overte nastavenia Vášho prehliadača a zakážte všetky blokovače popup okien pre túto webstránku.'
}

} ;
