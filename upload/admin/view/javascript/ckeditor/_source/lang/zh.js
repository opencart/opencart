/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Chinese Traditional language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['zh'] =
{
	/**
	 * The language reading direction. Possible values are "rtl" for
	 * Right-To-Left languages (like Arabic) and "ltr" for Left-To-Right
	 * languages (like English).
	 * @default 'ltr'
	 */
	dir : 'ltr',

	/*
	 * Screenreader titles. Please note that screenreaders are not always capable
	 * of reading non-English words. So be careful while translating it.
	 */
	editorTitle		: '文書處理器, %1',

	// Toolbar buttons without dialogs.
	source			: '原始碼',
	newPage			: '開新檔案',
	save			: '儲存',
	preview			: '預覽',
	cut				: '剪下',
	copy			: '複製',
	paste			: '貼上',
	print			: '列印',
	underline		: '底線',
	bold			: '粗體',
	italic			: '斜體',
	selectAll		: '全選',
	removeFormat	: '清除格式',
	strike			: '刪除線',
	subscript		: '下標',
	superscript		: '上標',
	horizontalrule	: '插入水平線',
	pagebreak		: '插入分頁符號',
	unlink			: '移除超連結',
	undo			: '復原',
	redo			: '重複',

	// Common messages and labels.
	common :
	{
		browseServer	: '瀏覽伺服器端',
		url				: 'URL',
		protocol		: '通訊協定',
		upload			: '上傳',
		uploadSubmit	: '上傳至伺服器',
		image			: '影像',
		flash			: 'Flash',
		form			: '表單',
		checkbox		: '核取方塊',
		radio		: '選項按鈕',
		textField		: '文字方塊',
		textarea		: '文字區域',
		hiddenField		: '隱藏欄位',
		button			: '按鈕',
		select	: '清單/選單',
		imageButton		: '影像按鈕',
		notSet			: '<尚未設定>',
		id				: 'ID',
		name			: '名稱',
		langDir			: '語言方向',
		langDirLtr		: '由左而右 (LTR)',
		langDirRtl		: '由右而左 (RTL)',
		langCode		: '語言代碼',
		longDescr		: '詳細 URL',
		cssClass		: '樣式表類別',
		advisoryTitle	: '標題',
		cssStyle		: '樣式',
		ok				: '確定',
		cancel			: '取消',
		generalTab		: '一般',
		advancedTab		: '進階',
		validateNumberFailed	: '需要輸入數字格式',
		confirmNewPage	: '現存的修改尚未儲存，要開新檔案？',
		confirmCancel	: '部份選項尚未儲存，要關閉對話盒？',

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, 已關閉</span>'
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: '插入特殊符號',
		title		: '請選擇特殊符號'
	},

	// Link dialog.
	link :
	{
		toolbar		: '插入/編輯超連結',
		menu		: '編輯超連結',
		title		: '超連結',
		info		: '超連結資訊',
		target		: '目標',
		upload		: '上傳',
		advanced	: '進階',
		type		: '超連接類型',
		toAnchor	: '本頁錨點',
		toEmail		: '電子郵件',
		target		: '目標',
		targetNotSet	: '<尚未設定>',
		targetFrame	: '<框架>',
		targetPopup	: '<快顯視窗>',
		targetNew	: '新視窗 (_blank)',
		targetTop	: '最上層視窗 (_top)',
		targetSelf	: '本視窗 (_self)',
		targetParent	: '父視窗 (_parent)',
		targetFrameName	: '目標框架名稱',
		targetPopupName	: '快顯視窗名稱',
		popupFeatures	: '快顯視窗屬性',
		popupResizable	: '可縮放',
		popupStatusBar	: '狀態列',
		popupLocationBar	: '網址列',
		popupToolbar	: '工具列',
		popupMenuBar	: '選單列',
		popupFullScreen	: '全螢幕 (IE)',
		popupScrollBars	: '捲軸',
		popupDependent	: '從屬 (NS)',
		popupWidth		: '寬',
		popupLeft		: '左',
		popupHeight		: '高',
		popupTop		: '右',
		id				: 'ID',
		langDir			: '語言方向',
		langDirNotSet	: '<尚未設定>',
		langDirLTR		: '由左而右 (LTR)',
		langDirRTL		: '由右而左 (RTL)',
		acccessKey		: '存取鍵',
		name			: '名稱',
		langCode		: '語言方向',
		tabIndex		: '定位順序',
		advisoryTitle	: '標題',
		advisoryContentType	: '內容類型',
		cssClasses		: '樣式表類別',
		charset			: '連結資源之編碼',
		styles			: '樣式',
		selectAnchor	: '請選擇錨點',
		anchorName		: '依錨點名稱',
		anchorId		: '依元件 ID',
		emailAddress	: '電子郵件',
		emailSubject	: '郵件主旨',
		emailBody		: '郵件內容',
		noAnchors		: '(本文件尚無可用之錨點)',
		noUrl			: '請輸入欲連結的 URL',
		noEmail			: '請輸入電子郵件位址'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: '插入/編輯錨點',
		menu		: '錨點屬性',
		title		: '錨點屬性',
		name		: '錨點名稱',
		errorName	: '請輸入錨點名稱'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: '尋找與取代',
		find				: '尋找',
		replace				: '取代',
		findWhat			: '尋找:',
		replaceWith			: '取代:',
		notFoundMsg			: '未找到指定的文字。',
		matchCase			: '大小寫須相符',
		matchWord			: '全字相符',
		matchCyclic			: '循環搜索',
		replaceAll			: '全部取代',
		replaceSuccessMsg	: '共完成 %1 次取代'
	},

	// Table Dialog
	table :
	{
		toolbar		: '表格',
		title		: '表格屬性',
		menu		: '表格屬性',
		deleteTable	: '刪除表格',
		rows		: '列數',
		columns		: '欄數',
		border		: '邊框',
		align		: '對齊',
		alignNotSet	: '<未設定>',
		alignLeft	: '靠左對齊',
		alignCenter	: '置中',
		alignRight	: '靠右對齊',
		width		: '寬度',
		widthPx		: '像素',
		widthPc		: '百分比',
		height		: '高度',
		cellSpace	: '間距',
		cellPad		: '內距',
		caption		: '標題',
		summary		: '摘要',
		headers		: '標題',
		headersNone		: '無標題',
		headersColumn	: '第一欄',
		headersRow		: '第一列',
		headersBoth		: '第一欄和第一列',
		invalidRows		: '必須有一或更多的列',
		invalidCols		: '必須有一或更多的欄',
		invalidBorder	: '邊框大小必須為數字格式',
		invalidWidth	: '表格寬度必須為數字格式',
		invalidHeight	: '表格高度必須為數字格式',
		invalidCellSpacing	: '儲存格間距必須為數字格式',
		invalidCellPadding	: '儲存格內距必須為數字格式',

		cell :
		{
			menu			: '儲存格',
			insertBefore	: '向左插入儲存格',
			insertAfter		: '向右插入儲存格',
			deleteCell		: '刪除儲存格',
			merge			: '合併儲存格',
			mergeRight		: '向右合併儲存格',
			mergeDown		: '向下合併儲存格',
			splitHorizontal	: '橫向分割儲存格',
			splitVertical	: '縱向分割儲存格',
			title			: '儲存格屬性',
			cellType		: '儲存格類別',
			rowSpan			: '儲存格列數',
			colSpan			: '儲存格欄數',
			wordWrap		: '自動換行',
			hAlign			: '水平對齊',
			vAlign			: '垂直對齊',
			alignTop		: '向上對齊',
			alignMiddle		: '置中對齊',
			alignBottom		: '向下對齊',
			alignBaseline	: '基線對齊',
			bgColor			: '背景顏色',
			borderColor		: '邊框顏色',
			data			: '數據',
			header			: '標題',
			yes				: '是',
			no				: '否',
			invalidWidth	: '儲存格寬度必須為數字格式',
			invalidHeight	: '儲存格高度必須為數字格式',
			invalidRowSpan	: '儲存格列數必須為整數格式',
			invalidColSpan	: '儲存格欄數度必須為整數格式',
			chooseColor : 'Choose' // MISSING
		},

		row :
		{
			menu			: '列',
			insertBefore	: '向上插入列',
			insertAfter		: '向下插入列',
			deleteRow		: '刪除列'
		},

		column :
		{
			menu			: '欄',
			insertBefore	: '向左插入欄',
			insertAfter		: '向右插入欄',
			deleteColumn	: '刪除欄'
		}
	},

	// Button Dialog.
	button :
	{
		title		: '按鈕屬性',
		text		: '顯示文字 (值)',
		type		: '類型',
		typeBtn		: '按鈕 (Button)',
		typeSbm		: '送出 (Submit)',
		typeRst		: '重設 (Reset)'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : '核取方塊屬性',
		radioTitle	: '選項按鈕屬性',
		value		: '選取值',
		selected	: '已選取'
	},

	// Form Dialog.
	form :
	{
		title		: '表單屬性',
		menu		: '表單屬性',
		action		: '動作',
		method		: '方法',
		encoding	: '表單編碼',
		target		: '目標',
		targetNotSet	: '<尚未設定>',
		targetNew	: '新視窗 (_blank)',
		targetTop	: '最上層視窗 (_top)',
		targetSelf	: '本視窗 (_self)',
		targetParent	: '父視窗 (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: '清單/選單屬性',
		selectInfo	: '資訊',
		opAvail		: '可用選項',
		value		: '值',
		size		: '大小',
		lines		: '行',
		chkMulti	: '可多選',
		opText		: '顯示文字',
		opValue		: '選取值',
		btnAdd		: '新增',
		btnModify	: '修改',
		btnUp		: '上移',
		btnDown		: '下移',
		btnSetValue : '設為預設值',
		btnDelete	: '刪除'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: '文字區域屬性',
		cols		: '字元寬度',
		rows		: '列數'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: '文字方塊屬性',
		name		: '名稱',
		value		: '值',
		charWidth	: '字元寬度',
		maxChars	: '最多字元數',
		type		: '類型',
		typeText	: '文字',
		typePass	: '密碼'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: '隱藏欄位屬性',
		name	: '名稱',
		value	: '值'
	},

	// Image Dialog.
	image :
	{
		title		: '影像屬性',
		titleButton	: '影像按鈕屬性',
		menu		: '影像屬性',
		infoTab	: '影像資訊',
		btnUpload	: '上傳至伺服器',
		url		: 'URL',
		upload	: '上傳',
		alt		: '替代文字',
		width		: '寬度',
		height	: '高度',
		lockRatio	: '等比例',
		resetSize	: '重設為原大小',
		border	: '邊框',
		hSpace	: '水平距離',
		vSpace	: '垂直距離',
		align		: '對齊',
		alignLeft	: '靠左對齊',
		alignAbsBottom: '絕對下方',
		alignAbsMiddle: '絕對中間',
		alignBaseline	: '基準線',
		alignBottom	: '靠下對齊',
		alignMiddle	: '置中對齊',
		alignRight	: '靠右對齊',
		alignTextTop	: '文字上方',
		alignTop	: '靠上對齊',
		preview	: '預覽',
		alertUrl	: '請輸入影像 URL',
		linkTab	: '超連結',
		button2Img	: '要把影像按鈕改成影像嗎？',
		img2Button	: '要把影像改成影像按鈕嗎？',
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Flash 屬性',
		propertiesTab	: '屬性',
		title		: 'Flash 屬性',
		chkPlay		: '自動播放',
		chkLoop		: '重複',
		chkMenu		: '開啟選單',
		chkFull		: '啟動全螢幕顯示',
 		scale		: '縮放',
		scaleAll		: '全部顯示',
		scaleNoBorder	: '無邊框',
		scaleFit		: '精確符合',
		access			: '允許腳本訪問',
		accessAlways	: '永遠',
		accessSameDomain	: '相同域名',
		accessNever	: '永不',
		align		: '對齊',
		alignLeft	: '靠左對齊',
		alignAbsBottom: '絕對下方',
		alignAbsMiddle: '絕對中間',
		alignBaseline	: '基準線',
		alignBottom	: '靠下對齊',
		alignMiddle	: '置中對齊',
		alignRight	: '靠右對齊',
		alignTextTop	: '文字上方',
		alignTop	: '靠上對齊',
		quality		: '質素',
		qualityBest		 : '最好',
		qualityHigh		 : '高',
		qualityAutoHigh	 : '高（自動）',
		qualityMedium	 : '中（自動）',
		qualityAutoLow	 : '低（自動）',
		qualityLow		 : '低',
		windowModeWindow	 : '視窗',
		windowModeOpaque	 : '不透明',
		windowModeTransparent	 : '透明',
		windowMode	: '視窗模式',
		flashvars	: 'Flash 變數',
		bgcolor	: '背景顏色',
		width	: '寬度',
		height	: '高度',
		hSpace	: '水平距離',
		vSpace	: '垂直距離',
		validateSrc : '請輸入欲連結的 URL',
		validateWidth : '寬度必須為數字格式',
		validateHeight : '高度必須為數字格式',
		validateHSpace : '水平間距必須為數字格式',
		validateVSpace : '垂直間距必須為數字格式'
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: '拼字檢查',
		title			: '拼字檢查',
		notAvailable	: '抱歉，服務目前暫不可用',
		errorLoading	: '無法聯系侍服器: %s.',
		notInDic		: '不在字典中',
		changeTo		: '更改為',
		btnIgnore		: '忽略',
		btnIgnoreAll	: '全部忽略',
		btnReplace		: '取代',
		btnReplaceAll	: '全部取代',
		btnUndo			: '復原',
		noSuggestions	: '- 無建議值 -',
		progress		: '進行拼字檢查中…',
		noMispell		: '拼字檢查完成：未發現拼字錯誤',
		noChanges		: '拼字檢查完成：未更改任何單字',
		oneChange		: '拼字檢查完成：更改了 1 個單字',
		manyChanges		: '拼字檢查完成：更改了 %1 個單字',
		ieSpellDownload	: '尚未安裝拼字檢查元件。您是否想要現在下載？'
	},

	smiley :
	{
		toolbar	: '表情符號',
		title	: '插入表情符號'
	},

	elementsPath :
	{
		eleTitle : '%1 元素'
	},

	numberedlist : '編號清單',
	bulletedlist : '項目清單',
	indent : '增加縮排',
	outdent : '減少縮排',

	justify :
	{
		left : '靠左對齊',
		center : '置中',
		right : '靠右對齊',
		block : '左右對齊'
	},

	blockquote : '引用文字',

	clipboard :
	{
		title		: '貼上',
		cutError	: '瀏覽器的安全性設定不允許編輯器自動執行剪下動作。請使用快捷鍵 (Ctrl+X) 剪下。',
		copyError	: '瀏覽器的安全性設定不允許編輯器自動執行複製動作。請使用快捷鍵 (Ctrl+C) 複製。',
		pasteMsg	: '請使用快捷鍵 (<strong>Ctrl+V</strong>) 貼到下方區域中並按下 <strong>確定</strong>',
		securityMsg	: '因為瀏覽器的安全性設定，本編輯器無法直接存取您的剪貼簿資料，請您自行在本視窗進行貼上動作。'
	},

	pastefromword :
	{
		toolbar : '自 Word 貼上',
		title : '自 Word 貼上',
		advice : '請使用快捷鍵 (<strong>Ctrl+V</strong>) 貼到下方區域中並按下 <strong>確定</strong>',
		ignoreFontFace : '移除字型設定',
		removeStyle : '移除樣式設定'
	},

	pasteText :
	{
		button : '貼為純文字格式',
		title : '貼為純文字格式'
	},

	templates :
	{
		button : '樣版',
		title : '內容樣版',
		insertOption: '取代原有內容',
		selectPromptMsg: '請選擇欲開啟的樣版<br> (原有的內容將會被清除):',
		emptyListMsg : '(無樣版)'
	},

	showBlocks : '顯示區塊',

	stylesCombo :
	{
		label : '樣式',
		voiceLabel : '樣式',
		panelVoiceLabel : '選擇樣式',
		panelTitle1 : '塊級元素樣式',
		panelTitle2 : '內聯元素樣式',
		panelTitle3 : '物件元素樣式'
	},

	format :
	{
		label : '格式',
		voiceLabel : '格式',
		panelTitle : '格式',
		panelVoiceLabel : '選擇段落格式',

		tag_p : '一般',
		tag_pre : '已格式化',
		tag_address : '位址',
		tag_h1 : '標題 1',
		tag_h2 : '標題 2',
		tag_h3 : '標題 3',
		tag_h4 : '標題 4',
		tag_h5 : '標題 5',
		tag_h6 : '標題 6',
		tag_div : '一般 (DIV)'
	},

	font :
	{
		label : '字體',
		voiceLabel : '字體',
		panelTitle : '字體',
		panelVoiceLabel : '選擇字體'
	},

	fontSize :
	{
		label : '大小',
		voiceLabel : '文字大小',
		panelTitle : '大小',
		panelVoiceLabel : '選擇文字大小'
	},

	colorButton :
	{
		textColorTitle : '文字顏色',
		bgColorTitle : '背景顏色',
		auto : '自動',
		more : '更多顏色…'
	},

	colors :
	{
		'000' : 'Black',
		'800000' : 'Maroon',
		'8B4513' : 'Saddle Brown',
		'2F4F4F' : 'Dark Slate Gray',
		'008080' : 'Teal',
		'000080' : 'Navy',
		'4B0082' : 'Indigo',
		'696969' : 'Dim Gray',
		'B22222' : 'Fire Brick',
		'A52A2A' : 'Brown',
		'DAA520' : 'Golden Rod',
		'006400' : 'Dark Green',
		'40E0D0' : 'Turquoise',
		'0000CD' : 'Medium Blue',
		'800080' : 'Purple',
		'808080' : 'Gray',
		'F00' : 'Red',
		'FF8C00' : 'Dark Orange',
		'FFD700' : 'Gold',
		'008000' : 'Green',
		'0FF' : 'Cyan',
		'00F' : 'Blue',
		'EE82EE' : 'Violet',
		'A9A9A9' : 'Dark Gray',
		'FFA07A' : 'Light Salmon',
		'FFA500' : 'Orange',
		'FFFF00' : 'Yellow',
		'00FF00' : 'Lime',
		'AFEEEE' : 'Pale Turquoise',
		'ADD8E6' : 'Light Blue',
		'DDA0DD' : 'Plum',
		'D3D3D3' : 'Light Grey',
		'FFF0F5' : 'Lavender Blush',
		'FAEBD7' : 'Antique White',
		'FFFFE0' : 'Light Yellow',
		'F0FFF0' : 'Honeydew',
		'F0FFFF' : 'Azure',
		'F0F8FF' : 'Alice Blue',
		'E6E6FA' : 'Lavender',
		'FFF' : 'White'
	},

	scayt :
	{
		title : '即時拼寫檢查',
		enable : '啟用即時拼寫檢查',
		disable : '關閉即時拼寫檢查',
		about : '關於即時拼寫檢查',
		toggle : '啟用／關閉即時拼寫檢查',
		options : '選項',
		langs : '語言',
		moreSuggestions : '更多拼寫建議',
		ignore : '忽略',
		ignoreAll : '全部忽略',
		addWord : '添加單詞',
		emptyDic : '字典名不應為空.',
		optionsTab : '選項',
		languagesTab : '語言',
		dictionariesTab : '字典',
		aboutTab : '關於'
	},

	about :
	{
		title : '關於 CKEditor',
		dlgTitle : '關於 CKEditor',
		moreInfo : '訪問我們的網站以獲取更多關於協議的信息',
		copy : 'Copyright &copy; $1. All rights reserved.'
	},

	maximize : '最大化',
	minimize : 'Minimize', // MISSING

	fakeobjects :
	{
		anchor : '錨點',
		flash : 'Flash 動畫',
		div : '分頁',
		unknown : '不明物件'
	},

	resize : '拖拽改變大小',

	colordialog :
	{
		title : 'Select color', // MISSING
		highlight : 'Highlight', // MISSING
		selected : 'Selected', // MISSING
		clear : 'Clear' // MISSING
	}
};
