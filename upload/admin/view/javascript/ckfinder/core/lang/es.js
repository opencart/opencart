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
 * Spanish language file.
 */

var CKFLang =
{

Dir : 'ltr',
HelpLang : 'es',
LangCode : 'es',

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
FoldersTitle	: 'Carpetas',
FolderLoading	: 'Cargando...',
FolderNew		: 'Por favor, escriba el nombre para la nueva carpeta: ',
FolderRename	: 'Por favor, escriba el nuevo nombre para la carpeta: ',
FolderDelete	: '¿Está seguro de que quiere borrar la carpeta "%1"?',
FolderRenaming	: ' (Renombrando...)',
FolderDeleting	: ' (Borrando...)',

// Files
FileRename		: 'Por favor, escriba el nuevo nombre del fichero: ',
FileRenameExt	: '¿Está seguro de querer cambiar la extensión del fichero? El fichero puede dejar de ser usable',
FileRenaming	: 'Renombrando...',
FileDelete		: '¿Está seguro de que quiere borrar el fichero "%1"?',

// Toolbar Buttons (some used elsewhere)
Upload		: 'Añadir',
UploadTip	: 'Añadir nuevo fichero',
Refresh		: 'Actualizar',
Settings	: 'Configuración',
Help		: 'Ayuda',
HelpTip		: 'Ayuda',

// Context Menus
Select		: 'Seleccionar',
SelectThumbnail : 'Seleccionar el icono',
View		: 'Ver',
Download	: 'Descargar',

NewSubFolder	: 'Nueva Subcarpeta',
Rename			: 'Renombrar',
Delete			: 'Borrar',

// Generic
OkBtn		: 'Aceptar',
CancelBtn	: 'Cancelar',
CloseBtn	: 'Cerrar',

// Upload Panel
UploadTitle			: 'Añadir nuevo fichero',
UploadSelectLbl		: 'Elija el fichero a subir',
UploadProgressLbl	: '(Subida en progreso, por favor espere...)',
UploadBtn			: 'Subir el fichero elegido',

UploadNoFileMsg		: 'Por favor, elija un fichero de su ordenador',

// Settings Panel
SetTitle		: 'Configuración',
SetView			: 'Vista:',
SetViewThumb	: 'Iconos',
SetViewList		: 'Lista',
SetDisplay		: 'Mostrar:',
SetDisplayName	: 'Nombre de fichero',
SetDisplayDate	: 'Fecha',
SetDisplaySize	: 'Peso del fichero',
SetSort			: 'Ordenar:',
SetSortName		: 'por Nombre',
SetSortDate		: 'por Fecha',
SetSortSize		: 'por Peso',

// Status Bar
FilesCountEmpty : '<Carpeta vacía>',
FilesCountOne	: '1 fichero',
FilesCountMany	: '%1 ficheros',

// Size and Speed
Kb				: '%1 kB',
KbPerSecond		: '%1 kB/s',

// Connector Error Messages.
ErrorUnknown : 'No ha sido posible completar la solicitud. (Error %1)',
Errors :
{
 10 : 'Comando incorrecto.',
 11 : 'El tipo de recurso no ha sido especificado en la solicitud.',
 12 : 'El tipo de recurso solicitado no es válido.',
102 : 'Nombre de fichero o carpeta no válido.',
103 : 'No se ha podido completar la solicitud debido a las restricciones de autorización.',
104 : 'No ha sido posible completar la solicitud debido a restricciones en el sistema de ficheros.',
105 : 'La extensión del archivo no es válida.',
109 : 'Petición inválida.',
110 : 'Error desconocido.',
115 : 'Ya existe un fichero o carpeta con ese nombre.',
116 : 'No se ha encontrado la carpeta. Por favor, actualice y pruebe de nuevo.',
117 : 'No se ha encontrado el fichero. Por favor, actualice la lista de ficheros y pruebe de nuevo.',
201 : 'Ya existía un fichero con ese nombre. El fichero subido ha sido renombrado como "%1"',
202 : 'Fichero inválido',
203 : 'Fichero inválido. El peso es demasiado grande.',
204 : 'El fichero subido está corrupto.',
205 : 'La carpeta temporal no está disponible en el servidor para las subidas.',
206 : 'La subida se ha cancelado por razones de seguridad. El fichero contenía código HTML.',
500 : 'El navegador de archivos está deshabilitado por razones de seguridad. Por favor, contacte con el administrador de su sistema y compruebe el fichero de configuración de CKFinder.',
501 : 'El soporte para iconos está deshabilitado.'
},

// Other Error Messages.
ErrorMsg :
{
FileEmpty		: 'El nombre del fichero no puede estar vacío',
FolderEmpty		: 'El nombre de la carpeta no puede estar vacío',

FileInvChar		: 'El nombre del fichero no puede contener ninguno de los caracteres siguientes: \n\\ / : * ? " < > |',
FolderInvChar	: 'El nombre de la carpeta no puede contener ninguno de los caracteres siguientes: \n\\ / : * ? " < > |',

PopupBlockView	: 'No ha sido posible abrir el fichero en una nueva ventana. Por favor, configure su navegador y desactive todos los bloqueadores de ventanas para esta página.'
}

} ;
