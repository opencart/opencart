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
 * Russian language file.
 */

var CKFLang =
{

Dir : 'ltr',
HelpLang : 'en',
LangCode : 'ru',

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
FoldersTitle	: 'Папки',
FolderLoading	: 'Загрузка...',
FolderNew		: 'Пожалуйста, введите новое имя папки: ',
FolderRename	: 'Пожалуйста, введите новое имя папки: ',
FolderDelete	: 'Вы уверены, что хотите удалить папку "%1"?',
FolderRenaming	: ' (Переименовываю...)',
FolderDeleting	: ' (Удаляю...)',

// Files
FileRename		: 'Пожалуйста, введите новое имя файла: ',
FileRenameExt	: 'Вы уверены, что хотите изменить расширение файла? Файл может стать недоступным',
FileRenaming	: 'Переименовываю...',
FileDelete		: 'Вы уверены, что хотите удалить файл "%1"?',

// Toolbar Buttons (some used elsewhere)
Upload		: 'Загрузка',
UploadTip	: 'Загрузить новый файл',
Refresh		: 'Обновить',
Settings	: 'Установки',
Help		: 'Помощь',
HelpTip		: 'Помощь',

// Context Menus
Select		: 'Выбрать',
View		: 'Посмотреть',
Download	: 'Сохранить',

NewSubFolder	: 'Новая папка',
Rename			: 'Переименовать',
Delete			: 'Удалить',

// Generic
OkBtn		: 'ОК',
CancelBtn	: 'Отмена',
CloseBtn	: 'Закрыть',

// Upload Panel
UploadTitle			: 'Загрузить новый файл',
UploadSelectLbl		: 'Выбрать файл для загрузки',
UploadProgressLbl	: '(Загрузка в процессе, пожалуйста подождите...)',
UploadBtn			: 'Загрузить выбранный файл',

UploadNoFileMsg		: 'Пожалуйста, выберите файл на вашем компьютере',

// Settings Panel
SetTitle		: 'Установки',
SetView			: 'Просмотр:',
SetViewThumb	: 'Эскизы',
SetViewList		: 'Список',
SetDisplay		: 'Отобразить:',
SetDisplayName	: 'Имя файла',
SetDisplayDate	: 'Дата',
SetDisplaySize	: 'Размер файла',
SetSort			: 'Сортировка:',
SetSortName		: 'по Имени файла',
SetSortDate		: 'по Дате',
SetSortSize		: 'по Размеру',

// Status Bar
FilesCountEmpty : '<Пустая папка>',
FilesCountOne	: '1 файл',
FilesCountMany	: '%1 файлов',

// Size and Speed
Kb				: '%1 kB',
KbPerSecond		: '%1 kB/s',

// Connector Error Messages.
ErrorUnknown : 'Невозможно завершить запрос. (Ошибка %1)',
Errors :
{
 10 : 'Неверная команда.',
 11 : 'Тип ресурса не указан в запросе.',
 12 : 'Неверный запрошенный тип ресурса.',
102 : 'Неверное имя файла или папки.',
103 : 'Невозможно завершить запрос из-за ограничений авторизации.',
104 : 'Невозможно завершить запрос из-за ограничения разрешений файловой системы.',
105 : 'Неверное расширение файла.',
109 : 'Неверный запрос.',
110 : 'Неизвестная ошибка.',
115 : 'Файл или папка с таким именем уже существует.',
116 : 'Папка не найдена. Пожалуйста, обновите вид папок и попробуйте еще раз.',
117 : 'Файл не найден. Пожалуйста, обновите список файлов и попробуйте еще раз.',
201 : 'Файл с таким именем уже существует. Загруженный файл был переименован в "%1"',
202 : 'Неверный файл',
203 : 'Неверный файл. Размер файла слишком большой.',
204 : 'Загруженный файл поврежден.',
205 : 'Не доступна временная папка для загрузки файлов на сервер.',
206 : 'Загрузка отменена из-за соображений безопасности. Файл содердит данные похожие на HTML.',
500 : 'Браузер файлов отключен из-за соображений безопасности. Пожалуйста, сообщите вашему системному администратру и проверьте конфигурационный файл CKFinder.',
501 : 'Поддержка эскизов отключена.'
},

// Other Error Messages.
ErrorMsg :
{
FileEmpty		: 'Имя файла не может быть пустым',
FolderEmpty		: 'Имя папки не может быть пустым',

FileInvChar		: 'Имя файла не может содержать любой из перечисленных символов: \n\\ / : * ? " < > |',
FolderInvChar	: 'Имя папки не может содержать любой из перечисленных символов: \n\\ / : * ? " < > |',

PopupBlockView	: 'Невозможно открыть файл в новом окне. Пожалуйста, проверьте настройки браузера и отключите все блокировки всплывающих окон для этого сайта.'
}

} ;
