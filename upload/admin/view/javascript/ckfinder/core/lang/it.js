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
 * Italian language file.
 */

var CKFLang =
{

Dir : 'ltr',
HelpLang : 'en',
LangCode : 'it',

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
FoldersTitle	: 'Cartelle',
FolderLoading	: 'Caricando...',
FolderNew		: 'Nome della cartella: ',
FolderRename	: 'Nuovo nome della cartella: ',
FolderDelete	: 'Se sicuro di voler eliminare la cartella "%1"?',
FolderRenaming	: ' (Rinominando...)',
FolderDeleting	: ' (Eliminando...)',

// Files
FileRename		: 'Nuovo nome del file: ',
FileRenameExt	: 'Sei sicure di voler cambiare la estensione del file? Il file può risultare inusabile',
FileRenaming	: 'Rinominando...',
FileDelete		: 'Sei sicuro di voler eliminare il file "%1"?',

// Toolbar Buttons (some used elsewhere)
Upload		: 'Upload',
UploadTip	: 'Carica Nuovo File',
Refresh		: 'Aggiorna',
Settings	: 'Configurazioni',
Help		: 'Aiuto',
HelpTip		: 'Aiuto (Inglese)',

// Context Menus
Select		: 'Seleziona',
SelectThumbnail		: 'Seleziona la miniatura',
View		: 'Vedi',
Download	: 'Scarica',

NewSubFolder	: 'Nuova Sottocartella',
Rename			: 'Rinomina',
Delete			: 'Elimina',

// Generic
OkBtn		: 'OK',
CancelBtn	: 'Anulla',
CloseBtn	: 'Chiudi',

// Upload Panel
UploadTitle			: 'Carica Nuovo File',
UploadSelectLbl		: 'Seleziona il file',
UploadProgressLbl	: '(Caricamento in corso, attendere prego...)',
UploadBtn			: 'Carica File',

UploadNoFileMsg		: 'Seleziona il file da caricare',

// Settings Panel
SetTitle		: 'Configurazioni',
SetView			: 'Vedi:',
SetViewThumb	: 'Anteprima',
SetViewList		: 'Lista',
SetDisplay		: 'Informazioni:',
SetDisplayName	: 'Nome del File',
SetDisplayDate	: 'Data',
SetDisplaySize	: 'Dimensione',
SetSort			: 'Ordina:',
SetSortName		: 'per Nome',
SetSortDate		: 'per Data',
SetSortSize		: 'per Dimensione',

// Status Bar
FilesCountEmpty	: '<Nessun file>',
FilesCountOne	: '1 file',
FilesCountMany	: '%1 file',

// Size and Speed
Kb				: '%1 kB',
KbPerSecond		: '%1 kB/s',

// Connector Error Messages.
ErrorUnknown : 'Impossibile completare la richiesta. (Errore %1)',
Errors :
{
 10 : 'Commando non valido.',
 11 : 'Il tipo di risorsa non è stato specificato nella richiesta.',
 12 : 'Il tipo di risorsa richiesto non è valido.',
102 : 'Nome di file o cartella non valido.',
103 : 'Non è stato possibile completare la richiesta a causa di restrizioni di autorizazione.',
104 : 'Non è stato possibile completare la richiesta a causa di restrizioni nei permessi del file system.',
105 : 'L\'estensione del file non è valida.',
109 : 'Richiesta invalida.',
110 : 'Errore sconosciuto.',
115 : 'Un file o cartella con lo stesso nome è già esistente.',
116 : 'Cartella non trovata. Prego aggiornare e riprovare.',
117 : 'File non trovato. Prego aggirnare la lista dei file e riprovare.',
201 : 'Un file con lo stesso nome è già disponibile. Il file caricato è stato rinominato in "%1".',
202 : 'File invalido',
203 : 'File invalido. La dimensione del file eccede i limiti del sistema.',
204 : 'Il file caricato è corrotto.',
205 : 'Il folder temporario non è disponibile new server.',
206 : 'Upload annullato per motivi di sicurezza. Il file contiene dati in formatto HTML.',
500 : 'Questo programma è disabilitato per motivi di sicurezza. Prego contattare l\'amministratore del sistema e verificare le configurazioni di CKFinder.',
501 : 'Il supporto alle anteprime non è attivo.'
},

// Other Error Messages.
ErrorMsg :
{
FileEmpty		: 'Il nome del file non può essere vuoto',
FolderEmpty		: 'Il nome della cartella non può essere vuoto',

FileInvChar		: 'I seguenti caratteri non possono essere usati per comporre il nome del file: \n\\ / : * ? " < > |',
FolderInvChar	: 'I seguenti caratteri non possono essere usati per comporre il nome della cartella: \n\\ / : * ? " < > |',

PopupBlockView	: 'Non è stato possile aprire il file in una nuova finestra. Prego configurare il browser e disabilitare i blocchi delle popup.'
}

} ;
