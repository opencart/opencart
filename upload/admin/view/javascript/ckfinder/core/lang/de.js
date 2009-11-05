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
 * German language file.
 */

var CKFLang =
{

Dir : 'ltr',
HelpLang : 'en',
LangCode : 'de',

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
DateTime : 'd.m.yyyy H:MM',
DateAmPm : ['AM','PM'],

// Folders
FoldersTitle	: 'Verzeichnisse',
FolderLoading	: 'Laden...',
FolderNew		: 'Bitte geben Sie den neuen Verzeichnisnamen an: ',
FolderRename	: 'Bitte geben Sie den neuen Verzeichnisnamen an: ',
FolderDelete	: 'Wollen Sie wirklich den Ordner "%1" löschen?',
FolderRenaming	: ' (Umbenennen...)',
FolderDeleting	: ' (Löschen...)',

// Files
FileRename		: 'Bitte geben Sie den neuen Dateinamen an: ',
FileRenameExt	: 'Wollen Sie wirklich die Dateierweiterung ändern? Die Datei könnte unbrauchbar werden!',
FileRenaming	: 'Umbennenen...',
FileDelete		: 'Wollen Sie wirklich die Datei "%1" löschen?',

// Toolbar Buttons (some used elsewhere)
Upload		: 'Hochladen',
UploadTip	: 'Neue Datei hochladen',
Refresh		: 'Aktualisieren',
Settings	: 'Einstellungen',
Help		: 'Hilfe',
HelpTip		: 'Hilfe',

// Context Menus
Select		: 'Auswählen',
SelectThumbnail : 'Miniatur auswählen',
View		: 'Ansehen',
Download	: 'Herunterladen',

NewSubFolder	: 'Neues Unterverzeichnis',
Rename			: 'Umbenennen',
Delete			: 'Löschen',

// Generic
OkBtn		: 'OK',
CancelBtn	: 'Abbrechen',
CloseBtn	: 'Schließen',

// Upload Panel
UploadTitle			: 'Neue Datei hochladen',
UploadSelectLbl		: 'Bitte wählen Sie die Datei aus',
UploadProgressLbl	: '(Die Daten werden übertragen, bitte warten...)',
UploadBtn			: 'Ausgewählte Datei hochladen',

UploadNoFileMsg		: 'Bitte wählen Sie eine Datei auf Ihrem Computer aus',

// Settings Panel
SetTitle		: 'Einstellungen',
SetView			: 'Ansicht:',
SetViewThumb	: 'Miniaturansicht',
SetViewList		: 'Liste',
SetDisplay		: 'Anzeige:',
SetDisplayName	: 'Dateiname',
SetDisplayDate	: 'Datum',
SetDisplaySize	: 'Dateigröße',
SetSort			: 'Sortierung:',
SetSortName		: 'nach Dateinamen',
SetSortDate		: 'nach Datum',
SetSortSize		: 'nach Größe',

// Status Bar
FilesCountEmpty : '<Leeres Verzeichnis>',
FilesCountOne	: '1 Datei',
FilesCountMany	: '%1 Datei',

// Size and Speed
Kb				: '%1 kB',
KbPerSecond		: '%1 kB/s',

// Connector Error Messages.
ErrorUnknown : 'Ihre Anfrage konnte nicht bearbeitet werden. (Fehler %1)',
Errors :
{
 10 : 'Unbekannter Befehl.',
 11 : 'Der Ressourcentyp wurde nicht spezifiziert.',
 12 : 'Der Ressourcentyp ist nicht gültig.',
102 : 'Ungültiger Datei oder Verzeichnisname.',
103 : 'Ihre Anfrage konnte wegen Authorisierungseinschränkungen nicht durchgeführt werden.',
104 : 'Ihre Anfrage konnte wegen Dateisystemeinschränkungen nicht durchgeführt werden.',
105 : 'Invalid file extension.',
109 : 'Unbekannte Anfrage.',
110 : 'Unbekannter Fehler.',
115 : 'Es existiert bereits eine Datei oder ein Ordner mit dem gleichen Namen.',
116 : 'Verzeichnis nicht gefunden. Bitte aktualisieren Sie die Anzeige und versuchen es noch einmal.',
117 : 'Datei nicht gefunden. Bitte aktualisieren Sie die Dateiliste und versuchen es noch einmal.',
201 : 'Es existiert bereits eine Datei unter gleichem Namen. Die hochgeladene Datei wurde unter "%1" gespeichert.',
202 : 'Ungültige Datei',
203 : 'ungültige Datei. Die Dateigröße ist zu groß.',
204 : 'Die hochgeladene Datei ist korrupt.',
205 : 'Es existiert kein temp. Ordner für das Hochladen auf den Server.',
206 : 'Das Hochladen wurde aus Sicherheitsgründen abgebrochen. Die Datei enthält HTML-Daten.',
500 : 'Der Dateibrowser wurde aus Sicherheitsgründen deaktiviert. Bitte benachrichtigen Sie Ihren Systemadministrator und prüfen Sie die Konfigurationsdatei.',
501 : 'Die Miniaturansicht wurde deaktivert.'
},

// Other Error Messages.
ErrorMsg :
{
FileEmpty		: 'Der Dateinamen darf nicht leer sein',
FolderEmpty		: 'Der Verzeichnisname darf nicht leer sein',

FileInvChar		: 'Der Dateinamen darf nicht eines der folgenden Zeichen enthalten: \n\\ / : * ? " < > |',
FolderInvChar	: 'Der Verzeichnisname darf nicht eines der folgenden Zeichen enthalten: \n\\ / : * ? " < > |',

PopupBlockView	: 'Die Datei konnte nicht in einem neuen Fenster geöffnet werden. Bitte deaktivieren Sie in Ihrem Browser alle Popup-Blocker für diese Seite.'
}

} ;
