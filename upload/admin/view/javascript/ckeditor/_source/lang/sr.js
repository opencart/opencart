/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Serbian (Cyrillic) language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['sr'] =
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
	source			: 'Kôд',
	newPage			: 'Нова страница',
	save			: 'Сачувај',
	preview			: 'Изглед странице',
	cut				: 'Исеци',
	copy			: 'Копирај',
	paste			: 'Залепи',
	print			: 'Штампа',
	underline		: 'Подвучено',
	bold			: 'Подебљано',
	italic			: 'Курзив',
	selectAll		: 'Означи све',
	removeFormat	: 'Уклони форматирање',
	strike			: 'Прецртано',
	subscript		: 'Индекс',
	superscript		: 'Степен',
	horizontalrule	: 'Унеси хоризонталну линију',
	pagebreak		: 'Insert Page Break for Printing', // MISSING
	unlink			: 'Уклони линк',
	undo			: 'Поништи акцију',
	redo			: 'Понови акцију',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Претражи сервер',
		url				: 'УРЛ',
		protocol		: 'Протокол',
		upload			: 'Пошаљи',
		uploadSubmit	: 'Пошаљи на сервер',
		image			: 'Слика',
		flash			: 'Флеш елемент',
		form			: 'Форма',
		checkbox		: 'Поље за потврду',
		radio		: 'Радио-дугме',
		textField		: 'Текстуално поље',
		textarea		: 'Зона текста',
		hiddenField		: 'Скривено поље',
		button			: 'Дугме',
		select	: 'Изборно поље',
		imageButton		: 'Дугме са сликом',
		notSet			: '<није постављено>',
		id				: 'Ид',
		name			: 'Назив',
		langDir			: 'Смер језика',
		langDirLtr		: 'С лева на десно (LTR)',
		langDirRtl		: 'С десна на лево (RTL)',
		langCode		: 'Kôд језика',
		longDescr		: 'Пун опис УРЛ',
		cssClass		: 'Stylesheet класе',
		advisoryTitle	: 'Advisory наслов',
		cssStyle		: 'Стил',
		ok				: 'OK',
		cancel			: 'Oткажи',
		generalTab		: 'General', // MISSING
		advancedTab		: 'Напредни тагови',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Унеси специјални карактер',
		title		: 'Одаберите специјални карактер'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Унеси/измени линк',
		menu		: 'Промени линк',
		title		: 'Линк',
		info		: 'Линк инфо',
		target		: 'Meтa',
		upload		: 'Пошаљи',
		advanced	: 'Напредни тагови',
		type		: 'Врста линка',
		toAnchor	: 'Сидро на овој страници',
		toEmail		: 'Eлектронска пошта',
		target		: 'Meтa',
		targetNotSet	: '<није постављено>',
		targetFrame	: '<оквир>',
		targetPopup	: '<искачући прозор>',
		targetNew	: 'Нови прозор (_blank)',
		targetTop	: 'Прозор на врху (_top)',
		targetSelf	: 'Исти прозор (_self)',
		targetParent	: 'Родитељски прозор (_parent)',
		targetFrameName	: 'Назив одредишног фрејма',
		targetPopupName	: 'Назив искачућег прозора',
		popupFeatures	: 'Могућности искачућег прозора',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Статусна линија',
		popupLocationBar	: 'Локација',
		popupToolbar	: 'Toolbar',
		popupMenuBar	: 'Контекстни мени',
		popupFullScreen	: 'Приказ преко целог екрана (ИE)',
		popupScrollBars	: 'Скрол бар',
		popupDependent	: 'Зависно (Netscape)',
		popupWidth		: 'Ширина',
		popupLeft		: 'Од леве ивице екрана (пиксела)',
		popupHeight		: 'Висина',
		popupTop		: 'Од врха екрана (пиксела)',
		id				: 'Id', // MISSING
		langDir			: 'Смер језика',
		langDirNotSet	: '<није постављено>',
		langDirLTR		: 'С лева на десно (LTR)',
		langDirRTL		: 'С десна на лево (RTL)',
		acccessKey		: 'Приступни тастер',
		name			: 'Назив',
		langCode		: 'Смер језика',
		tabIndex		: 'Таб индекс',
		advisoryTitle	: 'Advisory наслов',
		advisoryContentType	: 'Advisory врста садржаја',
		cssClasses		: 'Stylesheet класе',
		charset			: 'Linked Resource Charset',
		styles			: 'Стил',
		selectAnchor	: 'Одабери сидро',
		anchorName		: 'По називу сидра',
		anchorId		: 'Пo Ид-jу елемента',
		emailAddress	: 'Адреса електронске поште',
		emailSubject	: 'Наслов',
		emailBody		: 'Садржај поруке',
		noAnchors		: '(Нема доступних сидра)',
		noUrl			: 'Унесите УРЛ линка',
		noEmail			: 'Откуцајте адресу електронске поште'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Унеси/измени сидро',
		menu		: 'Особине сидра',
		title		: 'Особине сидра',
		name		: 'Име сидра',
		errorName	: 'Молимо Вас да унесете име сидра'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Find and Replace', // MISSING
		find				: 'Претрага',
		replace				: 'Замена',
		findWhat			: 'Пронађи:',
		replaceWith			: 'Замени са:',
		notFoundMsg			: 'Тражени текст није пронађен.',
		matchCase			: 'Разликуј велика и мала слова',
		matchWord			: 'Упореди целе речи',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Замени све',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Табела',
		title		: 'Особине табеле',
		menu		: 'Особине табеле',
		deleteTable	: 'Delete Table', // MISSING
		rows		: 'Редова',
		columns		: 'Kолона',
		border		: 'Величина оквира',
		align		: 'Равнање',
		alignNotSet	: '<није постављено>',
		alignLeft	: 'Лево',
		alignCenter	: 'Средина',
		alignRight	: 'Десно',
		width		: 'Ширина',
		widthPx		: 'пиксела',
		widthPc		: 'процената',
		height		: 'Висина',
		cellSpace	: 'Ћелијски простор',
		cellPad		: 'Размак ћелија',
		caption		: 'Наслов табеле',
		summary		: 'Summary', // MISSING
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
			menu			: 'Cell', // MISSING
			insertBefore	: 'Insert Cell Before', // MISSING
			insertAfter		: 'Insert Cell After', // MISSING
			deleteCell		: 'Обриши ћелије',
			merge			: 'Спој ћелије',
			mergeRight		: 'Merge Right', // MISSING
			mergeDown		: 'Merge Down', // MISSING
			splitHorizontal	: 'Split Cell Horizontally', // MISSING
			splitVertical	: 'Split Cell Vertically', // MISSING
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
			menu			: 'Row', // MISSING
			insertBefore	: 'Insert Row Before', // MISSING
			insertAfter		: 'Insert Row After', // MISSING
			deleteRow		: 'Обриши редове'
		},

		column :
		{
			menu			: 'Column', // MISSING
			insertBefore	: 'Insert Column Before', // MISSING
			insertAfter		: 'Insert Column After', // MISSING
			deleteColumn	: 'Обриши колоне'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Особине дугмета',
		text		: 'Текст (вредност)',
		type		: 'Tип',
		typeBtn		: 'Button', // MISSING
		typeSbm		: 'Submit', // MISSING
		typeRst		: 'Reset' // MISSING
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Особине поља за потврду',
		radioTitle	: 'Особине радио-дугмета',
		value		: 'Вредност',
		selected	: 'Означено'
	},

	// Form Dialog.
	form :
	{
		title		: 'Особине форме',
		menu		: 'Особине форме',
		action		: 'Aкција',
		method		: 'Mетода',
		encoding	: 'Encoding', // MISSING
		target		: 'Meтa',
		targetNotSet	: '<није постављено>',
		targetNew	: 'Нови прозор (_blank)',
		targetTop	: 'Прозор на врху (_top)',
		targetSelf	: 'Исти прозор (_self)',
		targetParent	: 'Родитељски прозор (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Особине изборног поља',
		selectInfo	: 'Инфо',
		opAvail		: 'Доступне опције',
		value		: 'Вредност',
		size		: 'Величина',
		lines		: 'линија',
		chkMulti	: 'Дозволи вишеструку селекцију',
		opText		: 'Текст',
		opValue		: 'Вредност',
		btnAdd		: 'Додај',
		btnModify	: 'Измени',
		btnUp		: 'Горе',
		btnDown		: 'Доле',
		btnSetValue : 'Подеси као означену вредност',
		btnDelete	: 'Обриши'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Особине зоне текста',
		cols		: 'Број колона',
		rows		: 'Број редова'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Особине текстуалног поља',
		name		: 'Назив',
		value		: 'Вредност',
		charWidth	: 'Ширина (карактера)',
		maxChars	: 'Максимално карактера',
		type		: 'Тип',
		typeText	: 'Текст',
		typePass	: 'Лозинка'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Особине скривеног поља',
		name	: 'Назив',
		value	: 'Вредност'
	},

	// Image Dialog.
	image :
	{
		title		: 'Особине слика',
		titleButton	: 'Особине дугмета са сликом',
		menu		: 'Особине слика',
		infoTab	: 'Инфо слике',
		btnUpload	: 'Пошаљи на сервер',
		url		: 'УРЛ',
		upload	: 'Пошаљи',
		alt		: 'Алтернативни текст',
		width		: 'Ширина',
		height	: 'Висина',
		lockRatio	: 'Закључај однос',
		resetSize	: 'Ресетуј величину',
		border	: 'Оквир',
		hSpace	: 'HSpace',
		vSpace	: 'VSpace',
		align		: 'Равнање',
		alignLeft	: 'Лево',
		alignAbsBottom: 'Abs доле',
		alignAbsMiddle: 'Abs средина',
		alignBaseline	: 'Базно',
		alignBottom	: 'Доле',
		alignMiddle	: 'Средина',
		alignRight	: 'Десно',
		alignTextTop	: 'Врх текста',
		alignTop	: 'Врх',
		preview	: 'Изглед',
		alertUrl	: 'Унесите УРЛ слике',
		linkTab	: 'Линк',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Особине Флеша',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Особине флеша',
		chkPlay		: 'Аутоматски старт',
		chkLoop		: 'Понављај',
		chkMenu		: 'Укључи флеш мени',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Скалирај',
		scaleAll		: 'Прикажи све',
		scaleNoBorder	: 'Без ивице',
		scaleFit		: 'Попуни површину',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Равнање',
		alignLeft	: 'Лево',
		alignAbsBottom: 'Abs доле',
		alignAbsMiddle: 'Abs средина',
		alignBaseline	: 'Базно',
		alignBottom	: 'Доле',
		alignMiddle	: 'Средина',
		alignRight	: 'Десно',
		alignTextTop	: 'Врх текста',
		alignTop	: 'Врх',
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
		bgcolor	: 'Боја позадине',
		width	: 'Ширина',
		height	: 'Висина',
		hSpace	: 'HSpace',
		vSpace	: 'VSpace',
		validateSrc : 'Унесите УРЛ линка',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Провери спеловање',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Није у речнику',
		changeTo		: 'Измени',
		btnIgnore		: 'Игнориши',
		btnIgnoreAll	: 'Игнориши све',
		btnReplace		: 'Замени',
		btnReplaceAll	: 'Замени све',
		btnUndo			: 'Врати акцију',
		noSuggestions	: '- Без сугестија -',
		progress		: 'Провера спеловања у току...',
		noMispell		: 'Провера спеловања завршена: грешке нису пронађене',
		noChanges		: 'Провера спеловања завршена: Није измењена ниједна реч',
		oneChange		: 'Провера спеловања завршена: Измењена је једна реч',
		manyChanges		: 'Провера спеловања завршена:  %1 реч(и) је измењено',
		ieSpellDownload	: 'Провера спеловања није инсталирана. Да ли желите да је скинете са Интернета?'
	},

	smiley :
	{
		toolbar	: 'Смајли',
		title	: 'Унеси смајлија'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Набројиву листу',
	bulletedlist : 'Ненабројива листа',
	indent : 'Увећај леву маргину',
	outdent : 'Смањи леву маргину',

	justify :
	{
		left : 'Лево равнање',
		center : 'Центриран текст',
		right : 'Десно равнање',
		block : 'Обострано равнање'
	},

	blockquote : 'Blockquote', // MISSING

	clipboard :
	{
		title		: 'Залепи',
		cutError	: 'Сигурносна подешавања Вашег претраживача не дозвољавају операције аутоматског исецања текста. Молимо Вас да користите пречицу са тастатуре (Ctrl+X).',
		copyError	: 'Сигурносна подешавања Вашег претраживача не дозвољавају операције аутоматског копирања текста. Молимо Вас да користите пречицу са тастатуре (Ctrl+C).',
		pasteMsg	: 'Молимо Вас да залепите унутар доње површине користећи тастатурну пречицу (<STRONG>Ctrl+V</STRONG>) и да притиснете <STRONG>OK</STRONG>.',
		securityMsg	: 'Because of your browser security settings, the editor is not able to access your clipboard data directly. You are required to paste it again in this window.' // MISSING
	},

	pastefromword :
	{
		toolbar : 'Залепи из Worda',
		title : 'Залепи из Worda',
		advice : 'Молимо Вас да залепите унутар доње површине користећи тастатурну пречицу (<STRONG>Ctrl+V</STRONG>) и да притиснете <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Игнориши Font Face дефиниције',
		removeStyle : 'Уклони дефиниције стилова'
	},

	pasteText :
	{
		button : 'Залепи као чист текст',
		title : 'Залепи као чист текст'
	},

	templates :
	{
		button : 'Обрасци',
		title : 'Обрасци за садржај',
		insertOption: 'Replace actual contents', // MISSING
		selectPromptMsg: 'Молимо Вас да одаберете образац који ће бити примењен на страницу (тренутни садржај ће бити обрисан):',
		emptyListMsg : '(Нема дефинисаних образаца)'
	},

	showBlocks : 'Show Blocks', // MISSING

	stylesCombo :
	{
		label : 'Стил',
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

		tag_p : 'Normal',
		tag_pre : 'Formatirano',
		tag_address : 'Adresa',
		tag_h1 : 'Heading 1',
		tag_h2 : 'Heading 2',
		tag_h3 : 'Heading 3',
		tag_h4 : 'Heading 4',
		tag_h5 : 'Heading 5',
		tag_h6 : 'Heading 6',
		tag_div : 'Normal (DIV)' // MISSING
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
		label : 'Величина фонта',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Величина фонта',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Боја текста',
		bgColorTitle : 'Боја позадине',
		auto : 'Аутоматски',
		more : 'Више боја...'
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
