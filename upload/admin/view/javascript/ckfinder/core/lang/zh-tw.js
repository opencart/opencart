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
 * Chinese (Taiwan) language file.
 */

var CKFLang =
{

Dir : 'ltr',
HelpLang : 'zh-tw',
LangCode : 'zh-tw',

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
DateAmPm : ['上午','下午'],

// Folders
FoldersTitle	: '目錄',
FolderLoading	: '載入中...',
FolderNew		: '請輸入新目錄名稱: ',
FolderRename	: '請輸入新目錄名稱: ',
FolderDelete	: '確定刪除 "%1" 這個目錄嗎?',
FolderRenaming	: ' (修改目錄...)',
FolderDeleting	: ' (刪除目錄...)',

// Files
FileRename		: '請輸入新檔案名稱: ',
FileRenameExt	: '確定變更這個檔案的副檔名嗎? 變更後 , 此檔案可能會無法使用 !',
FileRenaming	: '修改檔案名稱...',
FileDelete		: '確定要刪除這個檔案 "%1"?',

// Toolbar Buttons (some used elsewhere)
Upload		: '上傳檔案',
UploadTip	: '上傳一個新檔案',
Refresh		: '重新整理',
Settings	: '偏好設定',
Help		: '說明',
HelpTip		: '說明',

// Context Menus
Select		: '選擇',
View		: '瀏覽',
Download	: '下載',

NewSubFolder	: '建立新子目錄',
Rename			: '重新命名',
Delete			: '刪除',

// Generic
OkBtn		: '確定',
CancelBtn	: '取消',
CloseBtn	: '關閉',

// Upload Panel
UploadTitle			: '上傳新檔案',
UploadSelectLbl		: '請選擇要上傳的檔案',
UploadProgressLbl	: '(檔案上傳中 , 請稍候...)',
UploadBtn			: '將檔案上傳到伺服器',

UploadNoFileMsg		: '請從你的電腦選擇一個檔案',

// Settings Panel
SetTitle		: '設定',
SetView			: '瀏覽方式:',
SetViewThumb	: '縮圖預覽',
SetViewList		: '清單列表',
SetDisplay		: '顯示欄位:',
SetDisplayName	: '檔案名稱',
SetDisplayDate	: '檔案日期',
SetDisplaySize	: '檔案大小',
SetSort			: '排序方式:',
SetSortName		: '依 檔案名稱',
SetSortDate		: '依 檔案日期',
SetSortSize		: '依 檔案大小',

// Status Bar
FilesCountEmpty : '<此目錄沒有任何檔案>',
FilesCountOne	: '1 個檔案',
FilesCountMany	: '%1 個檔案',

// Size and Speed
Kb				: '%1 kB',
KbPerSecond		: '%1 kB/s',

// Connector Error Messages.
ErrorUnknown : '無法連接到伺服器 ! (錯誤代碼 %1)',
Errors :
{
 10 : '不合法的指令.',
 11 : '連接過程中 , 未指定資源形態 !',
 12 : '連接過程中出現不合法的資源形態 !',
102 : '不合法的檔案或目錄名稱 !',
103 : '無法連接：可能是使用者權限設定錯誤 !',
104 : '無法連接：可能是伺服器檔案權限設定錯誤 !',
105 : '無法上傳：不合法的副檔名 !',
109 : '不合法的請求 !',
110 : '不明錯誤 !',
115 : '檔案或目錄名稱重複 !',
116 : '找不到目錄 ! 請先重新整理 , 然後再試一次 !',
117 : '找不到檔案 ! 請先重新整理 , 然後再試一次 !',
201 : '伺服器上已有相同的檔案名稱 ! 您上傳的檔案名稱將會自動更改為 "%1"',
202 : '不合法的檔案 !',
203 : '不合法的檔案 ! 檔案大小超過預設值 !',
204 : '您上傳的檔案已經損毀 !',
205 : '伺服器上沒有預設的暫存目錄 !',
206 : '檔案上傳程序因為安全因素已被系統自動取消 ! 可能是上傳的檔案內容包含 HTML 碼 !',
500 : '因為安全因素 , 檔案瀏覽器已被停用 ! 請聯絡您的系統管理者並檢查 CKFinder 的設定檔 config.php !',
501 : '縮圖預覽功能已被停用 !'
},

// Other Error Messages.
ErrorMsg :
{
FileEmpty		: '檔案名稱不能空白 !',
FolderEmpty		: '目錄名稱不能空白 !',

FileInvChar		: '檔案名稱不能包含以下字元： \n\\ / : * ? " < > |',
FolderInvChar	: '目錄名稱不能包含以下字元： \n\\ / : * ? " < > |',

PopupBlockView	: '無法在新視窗開啟檔案 ! 請檢查瀏覽器的設定並且針對這個網站 關閉 <封鎖彈跳視窗> 這個功能 !'
}

} ;
