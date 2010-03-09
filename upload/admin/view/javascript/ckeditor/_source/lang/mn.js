/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Mongolian language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['mn'] =
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
	editorTitle		: 'Rich text editor, %1', // MISSING

	// Toolbar buttons without dialogs.
	source			: 'Код',
	newPage			: 'Шинэ хуудас',
	save			: 'Хадгалах',
	preview			: 'Уридчлан харах',
	cut				: 'Хайчлах',
	copy			: 'Хуулах',
	paste			: 'Буулгах',
	print			: 'Хэвлэх',
	underline		: 'Доогуур нь зураастай болгох',
	bold			: 'Тод бүдүүн',
	italic			: 'Налуу',
	selectAll		: 'Бүгдийг нь сонгох',
	removeFormat	: 'Формат авч хаях',
	strike			: 'Дундуур нь зураастай болгох',
	subscript		: 'Суурь болгох',
	superscript		: 'Зэрэг болгох',
	horizontalrule	: 'Хөндлөн зураас оруулах',
	pagebreak		: 'Хуудас тусгаарлагч оруулах',
	unlink			: 'Линк авч хаях',
	undo			: 'Хүчингүй болгох',
	redo			: 'Өмнөх үйлдлээ сэргээх',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Сервер харуулах',
		url				: 'URL',
		protocol		: 'Протокол',
		upload			: 'Хуулах',
		uploadSubmit	: 'Үүнийг сервэррүү илгээ',
		image			: 'Зураг',
		flash			: 'Флаш',
		form			: 'Форм',
		checkbox		: 'Чекбокс',
		radio		: 'Радио товч',
		textField		: 'Техт талбар',
		textarea		: 'Техт орчин',
		hiddenField		: 'Нууц талбар',
		button			: 'Товч',
		select	: 'Сонгогч талбар',
		imageButton		: 'Зурагтай товч',
		notSet			: '<Оноохгүй>',
		id				: 'Id',
		name			: 'Нэр',
		langDir			: 'Хэлний чиглэл',
		langDirLtr		: 'Зүүнээс баруун (LTR)',
		langDirRtl		: 'Баруунаас зүүн (RTL)',
		langCode		: 'Хэлний код',
		longDescr		: 'URL-ын тайлбар',
		cssClass		: 'Stylesheet классууд',
		advisoryTitle	: 'Зөвлөлдөх гарчиг',
		cssStyle		: 'Загвар',
		ok				: 'OK',
		cancel			: 'Болих',
		generalTab		: 'General', // MISSING
		advancedTab		: 'Нэмэлт',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Онцгой тэмдэгт оруулах',
		title		: 'Онцгой тэмдэгт сонгох'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Линк Оруулах/Засварлах',
		menu		: 'Холбоос засварлах',
		title		: 'Линк',
		info		: 'Линкийн мэдээлэл',
		target		: 'Байрлал',
		upload		: 'Хуулах',
		advanced	: 'Нэмэлт',
		type		: 'Линкийн төрөл',
		toAnchor	: 'Энэ хуудасандах холбоос',
		toEmail		: 'E-Mail',
		target		: 'Байрлал',
		targetNotSet	: '<Оноохгүй>',
		targetFrame	: '<Агуулах хүрээ>',
		targetPopup	: '<popup цонх>',
		targetNew	: 'Шинэ цонх (_blank)',
		targetTop	: 'Хамгийн түрүүн байх цонх (_top)',
		targetSelf	: 'Төстэй цонх (_self)',
		targetParent	: 'Эцэг цонх (_parent)',
		targetFrameName	: 'Очих фремын нэр',
		targetPopupName	: 'Popup цонхны нэр',
		popupFeatures	: 'Popup цонхны онцлог',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Статус хэсэг',
		popupLocationBar	: 'Location хэсэг',
		popupToolbar	: 'Багажны хэсэг',
		popupMenuBar	: 'Meню хэсэг',
		popupFullScreen	: 'Цонх дүүргэх (IE)',
		popupScrollBars	: 'Скрол хэсэгүүд',
		popupDependent	: 'Хамаатай (Netscape)',
		popupWidth		: 'Өргөн',
		popupLeft		: 'Зүүн байрлал',
		popupHeight		: 'Өндөр',
		popupTop		: 'Дээд байрлал',
		id				: 'Id', // MISSING
		langDir			: 'Хэлний чиглэл',
		langDirNotSet	: '<Оноохгүй>',
		langDirLTR		: 'Зүүнээс баруун (LTR)',
		langDirRTL		: 'Баруунаас зүүн (RTL)',
		acccessKey		: 'Холбох түлхүүр',
		name			: 'Нэр',
		langCode		: 'Хэлний чиглэл',
		tabIndex		: 'Tab индекс',
		advisoryTitle	: 'Зөвлөлдөх гарчиг',
		advisoryContentType	: 'Зөвлөлдөх төрлийн агуулга',
		cssClasses		: 'Stylesheet классууд',
		charset			: 'Тэмдэгт оноох нөөцөд холбогдсон',
		styles			: 'Загвар',
		selectAnchor	: 'Холбоос сонгох',
		anchorName		: 'Холбоосын нэрээр',
		anchorId		: 'Элемэнт Id-гаар',
		emailAddress	: 'E-Mail Хаяг',
		emailSubject	: 'Message гарчиг',
		emailBody		: 'Message-ийн агуулга',
		noAnchors		: '(Баримт бичиг холбоосгүй байна)',
		noUrl			: 'Линк URL-ээ төрөлжүүлнэ үү',
		noEmail			: 'Е-mail хаягаа төрөлжүүлнэ үү'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Холбоос Оруулах/Засварлах',
		menu		: 'Холбоос шинж чанар',
		title		: 'Холбоос шинж чанар',
		name		: 'Холбоос нэр',
		errorName	: 'Холбоос төрөл оруулна уу'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Хай мөн Дарж бич',
		find				: 'Хайх',
		replace				: 'Солих',
		findWhat			: 'Хайх үг/үсэг:',
		replaceWith			: 'Солих үг:',
		notFoundMsg			: 'Хайсан текст олсонгүй.',
		matchCase			: 'Тэнцэх төлөв',
		matchWord			: 'Тэнцэх бүтэн үг',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Бүгдийг нь Солих',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Хүснэгт',
		title		: 'Хүснэгт',
		menu		: 'Хүснэгт',
		deleteTable	: 'Хүснэгт устгах',
		rows		: 'Мөр',
		columns		: 'Багана',
		border		: 'Хүрээний хэмжээ',
		align		: 'Эгнээ',
		alignNotSet	: '<Оноохгүй>',
		alignLeft	: 'Зүүн талд',
		alignCenter	: 'Төвд',
		alignRight	: 'Баруун талд',
		width		: 'Өргөн',
		widthPx		: 'цэг',
		widthPc		: 'хувь',
		height		: 'Өндөр',
		cellSpace	: 'Нүх хоорондын зай (spacing)',
		cellPad		: 'Нүх доторлох(padding)',
		caption		: 'Тайлбар',
		summary		: 'Тайлбар',
		headers		: 'Headers', // MISSING
		headersNone		: 'None', // MISSING
		headersColumn	: 'First column', // MISSING
		headersRow		: 'First Row', // MISSING
		headersBoth		: 'Both', // MISSING
		invalidRows		: 'Number of rows must be a number greater than 0.', // MISSING
		invalidCols		: 'Number of columns must be a number greater than 0.', // MISSING
		invalidBorder	: 'Border size must be a number.', // MISSING
		invalidWidth	: 'Table width must be a number.', // MISSING
		invalidHeight	: 'Table height must be a number.', // MISSING
		invalidCellSpacing	: 'Cell spacing must be a number.', // MISSING
		invalidCellPadding	: 'Cell padding must be a number.', // MISSING

		cell :
		{
			menu			: 'Нүх/зай',
			insertBefore	: 'Нүх/зай өмнө нь оруулах',
			insertAfter		: 'Нүх/зай дараа нь оруулах',
			deleteCell		: 'Нүх устгах',
			merge			: 'Нүх нэгтэх',
			mergeRight		: 'Баруун тийш нэгтгэх',
			mergeDown		: 'Доош нэгтгэх',
			splitHorizontal	: 'Нүх/зайг босоогоор нь тусгаарлах',
			splitVertical	: 'Нүх/зайг хөндлөнгөөр нь тусгаарлах',
			title			: 'Cell Properties', // MISSING
			cellType		: 'Cell Type', // MISSING
			rowSpan			: 'Rows Span', // MISSING
			colSpan			: 'Columns Span', // MISSING
			wordWrap		: 'Word Wrap', // MISSING
			hAlign			: 'Horizontal Alignment', // MISSING
			vAlign			: 'Vertical Alignment', // MISSING
			alignTop		: 'Top', // MISSING
			alignMiddle		: 'Middle', // MISSING
			alignBottom		: 'Bottom', // MISSING
			alignBaseline	: 'Baseline', // MISSING
			bgColor			: 'Background Color', // MISSING
			borderColor		: 'Border Color', // MISSING
			data			: 'Data', // MISSING
			header			: 'Header', // MISSING
			yes				: 'Yes', // MISSING
			no				: 'No', // MISSING
			invalidWidth	: 'Cell width must be a number.', // MISSING
			invalidHeight	: 'Cell height must be a number.', // MISSING
			invalidRowSpan	: 'Rows span must be a whole number.', // MISSING
			invalidColSpan	: 'Columns span must be a whole number.', // MISSING
			chooseColor : 'Choose' // MISSING
		},

		row :
		{
			menu			: 'Мөр',
			insertBefore	: 'Мөр өмнө нь оруулах',
			insertAfter		: 'Мөр дараа нь оруулах',
			deleteRow		: 'Мөр устгах'
		},

		column :
		{
			menu			: 'Багана',
			insertBefore	: 'Багана өмнө нь оруулах',
			insertAfter		: 'Багана дараа нь оруулах',
			deleteColumn	: 'Багана устгах'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Товчны шинж чанар',
		text		: 'Тэкст (Утга)',
		type		: 'Төрөл',
		typeBtn		: 'Товч',
		typeSbm		: 'Submit',
		typeRst		: 'Болих'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Чекбоксны шинж чанар',
		radioTitle	: 'Радио товчны шинж чанар',
		value		: 'Утга',
		selected	: 'Сонгогдсон'
	},

	// Form Dialog.
	form :
	{
		title		: 'Форм шинж чанар',
		menu		: 'Форм шинж чанар',
		action		: 'Үйлдэл',
		method		: 'Арга',
		encoding	: 'Encoding', // MISSING
		target		: 'Байрлал',
		targetNotSet	: '<Оноохгүй>',
		targetNew	: 'Шинэ цонх (_blank)',
		targetTop	: 'Хамгийн түрүүн байх цонх (_top)',
		targetSelf	: 'Төстэй цонх (_self)',
		targetParent	: 'Эцэг цонх (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Согогч талбарын шинж чанар',
		selectInfo	: 'Мэдээлэл',
		opAvail		: 'Идвэхтэй сонголт',
		value		: 'Утга',
		size		: 'Хэмжээ',
		lines		: 'Мөр',
		chkMulti	: 'Олон сонголт зөвшөөрөх',
		opText		: 'Тэкст',
		opValue		: 'Утга',
		btnAdd		: 'Нэмэх',
		btnModify	: 'Өөрчлөх',
		btnUp		: 'Дээш',
		btnDown		: 'Доош',
		btnSetValue : 'Сонгогдсан утга оноох',
		btnDelete	: 'Устгах'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Текст орчны шинж чанар',
		cols		: 'Багана',
		rows		: 'Мөр'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Текст талбарын шинж чанар',
		name		: 'Нэр',
		value		: 'Утга',
		charWidth	: 'Тэмдэгтын өргөн',
		maxChars	: 'Хамгийн их тэмдэгт',
		type		: 'Төрөл',
		typeText	: 'Текст',
		typePass	: 'Нууц үг'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Нууц талбарын шинж чанар',
		name	: 'Нэр',
		value	: 'Утга'
	},

	// Image Dialog.
	image :
	{
		title		: 'Зураг',
		titleButton	: 'Зурган товчны шинж чанар',
		menu		: 'Зураг',
		infoTab	: 'Зурагны мэдээлэл',
		btnUpload	: 'Үүнийг сервэррүү илгээ',
		url		: 'URL',
		upload	: 'Хуулах',
		alt		: 'Тайлбар текст',
		width		: 'Өргөн',
		height	: 'Өндөр',
		lockRatio	: 'Радио түгжих',
		resetSize	: 'хэмжээ дахин оноох',
		border	: 'Хүрээ',
		hSpace	: 'Хөндлөн зай',
		vSpace	: 'Босоо зай',
		align		: 'Эгнээ',
		alignLeft	: 'Зүүн',
		alignAbsBottom: 'Abs доод талд',
		alignAbsMiddle: 'Abs Дунд талд',
		alignBaseline	: 'Baseline',
		alignBottom	: 'Доод талд',
		alignMiddle	: 'Дунд талд',
		alignRight	: 'Баруун',
		alignTextTop	: 'Текст дээр',
		alignTop	: 'Дээд талд',
		preview	: 'Уридчлан харах',
		alertUrl	: 'Зурагны URL-ын төрлийн сонгоно уу',
		linkTab	: 'Линк',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Флаш шинж чанар',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Флаш  шинж чанар',
		chkPlay		: 'Автоматаар тоглох',
		chkLoop		: 'Давтах',
		chkMenu		: 'Флаш цэс идвэхжүүлэх',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Өргөгтгөх',
		scaleAll		: 'Бүгдийг харуулах',
		scaleNoBorder	: 'Хүрээгүй',
		scaleFit		: 'Яг тааруулах',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Эгнээ',
		alignLeft	: 'Зүүн',
		alignAbsBottom: 'Abs доод талд',
		alignAbsMiddle: 'Abs Дунд талд',
		alignBaseline	: 'Baseline',
		alignBottom	: 'Доод талд',
		alignMiddle	: 'Дунд талд',
		alignRight	: 'Баруун',
		alignTextTop	: 'Текст дээр',
		alignTop	: 'Дээд талд',
		quality		: 'Quality', // MISSING
		qualityBest		 : 'Best', // MISSING
		qualityHigh		 : 'High', // MISSING
		qualityAutoHigh	 : 'Auto High', // MISSING
		qualityMedium	 : 'Medium', // MISSING
		qualityAutoLow	 : 'Auto Low', // MISSING
		qualityLow		 : 'Low', // MISSING
		windowModeWindow	 : 'Window', // MISSING
		windowModeOpaque	 : 'Opaque', // MISSING
		windowModeTransparent	 : 'Transparent', // MISSING
		windowMode	: 'Window mode', // MISSING
		flashvars	: 'Variables for Flash', // MISSING
		bgcolor	: 'Фонны өнгө',
		width	: 'Өргөн',
		height	: 'Өндөр',
		hSpace	: 'Хөндлөн зай',
		vSpace	: 'Босоо зай',
		validateSrc : 'Линк URL-ээ төрөлжүүлнэ үү',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Үгийн дүрэх шалгах',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Толь бичиггүй',
		changeTo		: 'Өөрчлөх',
		btnIgnore		: 'Зөвшөөрөх',
		btnIgnoreAll	: 'Бүгдийг зөвшөөрөх',
		btnReplace		: 'Дарж бичих',
		btnReplaceAll	: 'Бүгдийг Дарж бичих',
		btnUndo			: 'Буцаах',
		noSuggestions	: '- Тайлбаргүй -',
		progress		: 'Дүрэм шалгаж байгаа үйл явц...',
		noMispell		: 'Дүрэм шалгаад дууссан: Алдаа олдсонгүй',
		noChanges		: 'Дүрэм шалгаад дууссан: үг өөрчлөгдөөгүй',
		oneChange		: 'Дүрэм шалгаад дууссан: 1 үг өөрчлөгдсөн',
		manyChanges		: 'Дүрэм шалгаад дууссан: %1 үг өөрчлөгдсөн',
		ieSpellDownload	: 'Дүрэм шалгагч суугаагүй байна. Татаж авахыг хүсч байна уу?'
	},

	smiley :
	{
		toolbar	: 'Тодорхойлолт',
		title	: 'Тодорхойлолт оруулах'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Дугаарлагдсан жагсаалт',
	bulletedlist : 'Цэгтэй жагсаалт',
	indent : 'Догол мөр хасах',
	outdent : 'Догол мөр нэмэх',

	justify :
	{
		left : 'Зүүн талд байрлуулах',
		center : 'Төвд байрлуулах',
		right : 'Баруун талд байрлуулах',
		block : 'Блок хэлбэрээр байрлуулах'
	},

	blockquote : 'Хайрцаглах',

	clipboard :
	{
		title		: 'Буулгах',
		cutError	: 'Таны browser-ын хамгаалалтын тохиргоо editor-д автоматаар хайчлах үйлдэлийг зөвшөөрөхгүй байна. (Ctrl+X) товчны хослолыг ашиглана уу.',
		copyError	: 'Таны browser-ын хамгаалалтын тохиргоо editor-д автоматаар хуулах үйлдэлийг зөвшөөрөхгүй байна. (Ctrl+C) товчны хослолыг ашиглана уу.',
		pasteMsg	: '(<strong>Ctrl+V</strong>) товчийг ашиглан paste хийнэ үү. Мөн <strong>OK</strong> дар.',
		securityMsg	: 'Таны үзүүлэгч/browser/-н хамгаалалтын тохиргооноос болоод editor clipboard өгөгдөлрүү шууд хандах боломжгүй. Энэ цонход дахин paste хийхийг оролд.'
	},

	pastefromword :
	{
		toolbar : 'Word-оос буулгах',
		title : 'Word-оос буулгах',
		advice : '(<strong>Ctrl+V</strong>) товчийг ашиглан paste хийнэ үү. Мөн <strong>OK</strong> дар.',
		ignoreFontFace : 'Тодорхойлогдсон Font Face зөвшөөрнө',
		removeStyle : 'Тодорхойлогдсон загварыг авах'
	},

	pasteText :
	{
		button : 'Plain Text-ээс буулгах',
		title : 'Plain Text-ээс буулгах'
	},

	templates :
	{
		button : 'Загварууд',
		title : 'Загварын агуулга',
		insertOption: 'Одоогийн агууллагыг дарж бичих',
		selectPromptMsg: 'Загварыг нээж editor-рүү сонгож оруулна уу<br />(Одоогийн агууллагыг устаж магадгүй):',
		emptyListMsg : '(Загвар тодорхойлогдоогүй байна)'
	},

	showBlocks : 'Block-уудыг үзүүлэх',

	stylesCombo :
	{
		label : 'Загвар',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'Формат',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'Формат',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'Хэвийн',
		tag_pre : 'Formatted',
		tag_address : 'Хаяг',
		tag_h1 : 'Heading 1',
		tag_h2 : 'Heading 2',
		tag_h3 : 'Heading 3',
		tag_h4 : 'Heading 4',
		tag_h5 : 'Heading 5',
		tag_h6 : 'Heading 6',
		tag_div : 'Paragraph (DIV)'
	},

	font :
	{
		label : 'Фонт',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'Фонт',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'Хэмжээ',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Хэмжээ',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Фонтны өнгө',
		bgColorTitle : 'Фонны өнгө',
		auto : 'Автоматаар',
		more : 'Нэмэлт өнгөнүүд...'
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
		title : 'Spell Check As You Type', // MISSING
		enable : 'Enable SCAYT', // MISSING
		disable : 'Disable SCAYT', // MISSING
		about : 'About SCAYT', // MISSING
		toggle : 'Toggle SCAYT', // MISSING
		options : 'Options', // MISSING
		langs : 'Languages', // MISSING
		moreSuggestions : 'More suggestions', // MISSING
		ignore : 'Ignore', // MISSING
		ignoreAll : 'Ignore All', // MISSING
		addWord : 'Add Word', // MISSING
		emptyDic : 'Dictionary name should not be empty.', // MISSING
		optionsTab : 'Options', // MISSING
		languagesTab : 'Languages', // MISSING
		dictionariesTab : 'Dictionaries', // MISSING
		aboutTab : 'About' // MISSING
	},

	about :
	{
		title : 'About CKEditor', // MISSING
		dlgTitle : 'About CKEditor', // MISSING
		moreInfo : 'For licensing information please visit our web site:', // MISSING
		copy : 'Copyright &copy; $1. All rights reserved.' // MISSING
	},

	maximize : 'Maximize', // MISSING
	minimize : 'Minimize', // MISSING

	fakeobjects :
	{
		anchor : 'Anchor', // MISSING
		flash : 'Flash Animation', // MISSING
		div : 'Page Break', // MISSING
		unknown : 'Unknown Object' // MISSING
	},

	resize : 'Drag to resize', // MISSING

	colordialog :
	{
		title : 'Select color', // MISSING
		highlight : 'Highlight', // MISSING
		selected : 'Selected', // MISSING
		clear : 'Clear' // MISSING
	}
};
