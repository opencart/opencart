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
 * Latin American Spanish language file.
 */

var CKFLang =
{

Dir : 'ltr',
HelpLang : 'es-mx',
LangCode : 'es-mx',

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
FileRename		: 'Por favor, escriba el nuevo nombre del archivo: ',
FileRenameExt	: '¿Está seguro de querer cambiar la extensión del archivo? El archivo puede dejar de ser usable',
FileRenaming	: 'Renombrando...',
FileDelete		: '¿Está seguro de que quiere borrar el archivo "%1"?',

// Toolbar Buttons (some used elsewhere)
Upload		: 'Añadir',
UploadTip	: 'Añadir nuevo archivo',
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
UploadTitle			: 'Añadir nuevo archivo',
UploadSelectLbl		: 'Elija el archivo a subir',
UploadProgressLbl	: '(Subida en progreso, por favor espere...)',
UploadBtn			: 'Subir el archivo elegido',

UploadNoFileMsg		: 'Por favor, elija un archivo de su computadora',

// Settings Panel
SetTitle		: 'Configuración',
SetView			: 'Vista:',
SetViewThumb	: 'Iconos',
SetViewList		: 'Lista',
SetDisplay		: 'Mostrar:',
SetDisplayName	: 'Nombre de archivo',
SetDisplayDate	: 'Fecha',
SetDisplaySize	: 'Tamaño del archivo',
SetSort			: 'Ordenar:',
SetSortName		: 'por Nombre',
SetSortDate		: 'por Fecha',
SetSortSize		: 'por Tamaño',

// Status Bar
FilesCountEmpty : '<Carpeta vacía>',
FilesCountOne	: '1 archivo',
FilesCountMany	: '%1 archivos',

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
102 : 'Nombre de archivo o carpeta no válido.',
103 : 'No se ha podido completar la solicitud debido a las restricciones de autorización.',
104 : 'No ha sido posible completar la solicitud debido a restricciones en el sistema de archivos.',
105 : 'La extensión del archivo no es válida.',
109 : 'Petición inválida.',
110 : 'Error desconocido.',
115 : 'Ya existe un archivo o carpeta con ese nombre.',
116 : 'No se ha encontrado la carpeta. Por favor, actualice y pruebe de nuevo.',
117 : 'No se ha encontrado el archivo. Por favor, actualice la lista de archivos y pruebe de nuevo.',
201 : 'Ya existía un archivo con ese nombre. El archivo subido ha sido renombrado como "%1"',
202 : 'Archivo inválido',
203 : 'Archivo inválido. El tamaño es demasiado grande.',
204 : 'El archivo subido está corrupto.',
205 : 'La carpeta temporal no está disponible en el servidor para las subidas.',
206 : 'La subida se ha cancelado por razones de seguridad. El archivo contenía código HTML.',
500 : 'El navegador de archivos está deshabilitado por razones de seguridad. Por favor, contacte con el administrador de su sistema y compruebe el archivo de configuración de CKFinder.',
501 : 'El soporte para iconos está deshabilitado.'
},

// Other Error Messages.
ErrorMsg :
{
FileEmpty		: 'El nombre del archivo no puede estar vacío',
FolderEmpty		: 'El nombre de la carpeta no puede estar vacío',

FileInvChar		: 'El nombre del archivo no puede contener ninguno de los caracteres siguientes: \n\\ / : * ? " < > |',
FolderInvChar	: 'El nombre de la carpeta no puede contener ninguno de los caracteres siguientes: \n\\ / : * ? " < > |',

PopupBlockView	: 'No ha sido posible abrir el archivo en una nueva ventana. Por favor, configure su navegador y desactive todos los bloqueadores de ventanas para esta página.'
}

} ;
