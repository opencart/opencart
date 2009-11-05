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
 * French language file.
 */

var CKFLang =
{

Dir : 'ltr',
HelpLang : 'en',
LangCode : 'fr',

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
FoldersTitle	: 'Dossiers',
FolderLoading	: 'Chargement...',
FolderNew		: 'Entrez le nouveau nom du dossier: ',
FolderRename	: 'Entrez le nouveau nom du dossier: ',
FolderDelete	: 'Êtes-vous sûr de vouloir effacer le dossier "%1" ?',
FolderRenaming	: ' (Renommage en cours...)',
FolderDeleting	: ' (Suppression en cours...)',

// Files
FileRename		: 'Entrez le nouveau nom du fichier: ',
FileRenameExt	: 'Êtes-vous sûr de vouloir ¨changer l\'extension de ce fichier? Le fichier pourrait devenir inutilisable',
FileRenaming	: 'Renommage en cours...',
FileDelete		: 'Êtes-vous sûr de vouloir effacer le fichier "%1" ?',

// Toolbar Buttons (some used elsewhere)
Upload		: 'Téléverser',
UploadTip	: 'Téléverser un nouveau fichier',
Refresh		: 'Rafraîchir',
Settings	: 'Configuration',
Help		: 'Aide',
HelpTip		: 'Aide',

// Context Menus
Select		: 'Choisir',
SelectThumbnail : 'Choisir une miniature',
View		: 'Voir',
Download	: 'Télécharger',

NewSubFolder	: 'Nouveau sous-dossier',
Rename			: 'Renommer',
Delete			: 'Effacer',

// Generic
OkBtn		: 'OK',
CancelBtn	: 'Annuler',
CloseBtn	: 'Fermer',

// Upload Panel
UploadTitle			: 'Téléverser un nouveau fichier',
UploadSelectLbl		: 'Sélectionner le fichier à téléverser',
UploadProgressLbl	: '(Téléversement en cours, veuillez patienter...)',
UploadBtn			: 'Téléverser le fichier sélectionné',

UploadNoFileMsg		: 'Sélectionner un fichier sur votre ordinateur',

// Settings Panel
SetTitle		: 'Configuration',
SetView			: 'Voir:',
SetViewThumb	: 'Miniatures',
SetViewList		: 'Liste',
SetDisplay		: 'Affichage:',
SetDisplayName	: 'Nom du fichier',
SetDisplayDate	: 'Date',
SetDisplaySize	: 'Taille du fichier',
SetSort			: 'Classement:',
SetSortName		: 'par Nom de Fichier',
SetSortDate		: 'par Date',
SetSortSize		: 'par Taille',

// Status Bar
FilesCountEmpty : '<Dossier Vide>',
FilesCountOne	: '1 fichier',
FilesCountMany	: '%1 fichiers',

// Connector Error Messages.
ErrorUnknown : 'La demande n\'a pas abouti. (Erreur %1)',
Errors :
{
 10 : 'Commande invalide.',
 11 : 'Le type de ressource n\'a pas été spécifié dans la commande.',
 12 : 'Le type de ressource n\'est pas valide.',
102 : 'Nom de fichier ou de dossier invalide.',
103 : 'La demande n\'a pas abouti : problème d\'autorisations.',
104 : 'La demande n\'a pas abouti : problème de restrictions de permissions.',
105 : 'Extension de fichier invalide.',
109 : 'Demande invalide.',
110 : 'Erreur inconnue.',
115 : 'Un fichier ou un dossier avec ce nom existe déjà.',
116 : 'Ce dossier n\'existe pas. Veuillez rafraîchir la page et réessayer.',
117 : 'Ce fichier n\'existe pas. Veuillez rafraîchir la page et réessayer.',
201 : 'Un fichier avec ce nom existe déjà. Le fichier téléversé a été renommé en "%1"',
202 : 'Fichier invalide',
203 : 'Fichier invalide. La taille est trop grande.',
204 : 'Le fichier téléversé est corrompu.',
205 : 'Aucun dossier temporaire n\'est disponible sur le serveur.',
206 : 'Téléversement interrompu pour raisons de sécurité. Le fichier contient des données de type HTML.',
500 : 'L\'interface de gestion des fichiers est désactivé. Contactez votre administrateur et vérifier le fichier de configuration de CKFinder.',
501 : 'La fonction "miniatures" est désactivée.'
},

// Other Error Messages.
ErrorMsg :
{
FileEmpty		: 'Le nom du fichier ne peut être vide',
FolderEmpty		: 'Le nom du dossier ne peut être vide',

FileInvChar		: 'Le nom du fichier ne peut pas contenir les charactères suivants : \n\\ / : * ? " < > |',
FolderInvChar	: 'Le nom du dossier ne peut pas contenir les charactères suivants : \n\\ / : * ? " < > |',

PopupBlockView	: 'Il n\'a pas été possible d\'ouvrir la nouvelle fenêtre. Désactiver votre bloqueur de fenêtres pour ce site.'
}

} ;
