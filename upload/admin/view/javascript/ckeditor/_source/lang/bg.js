/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Bulgarian language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['bg'] =
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
	newPage			: 'Нова страница',
	save			: 'Запази',
	preview			: 'Предварителен изглед',
	cut				: 'Изрежи',
	copy			: 'Запамети',
	paste			: 'Вмъкни',
	print			: 'Печат',
	underline		: 'Подчертан',
	bold			: 'Удебелен',
	italic			: 'Курсив',
	selectAll		: 'Селектирай всичко',
	removeFormat	: 'Изтрий форматирането',
	strike			: 'Зачертан',
	subscript		: 'Индекс за база',
	superscript		: 'Индекс за степен',
	horizontalrule	: 'Вмъкни хоризонтална линия',
	pagebreak		: 'Вмъкни нов ред',
	unlink			: 'Изтрий връзка',
	undo			: 'Отмени',
	redo			: 'Повтори',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Разгледай сървъра',
		url				: 'Пълен път (URL)',
		protocol		: 'Протокол',
		upload			: 'Качи',
		uploadSubmit	: 'Прати към сървъра',
		image			: 'Изображение',
		flash			: 'Flash',
		form			: 'Формуляр',
		checkbox		: 'Поле за отметка',
		radio		: 'Поле за опция',
		textField		: 'Текстово поле',
		textarea		: 'Текстова област',
		hiddenField		: 'Скрито поле',
		button			: 'Бутон',
		select	: 'Падащо меню с опции',
		imageButton		: 'Бутон-изображение',
		notSet			: '<не е настроен>',
		id				: 'Идентификатор',
		name			: 'Име',
		langDir			: 'посока на речта',
		langDirLtr		: 'От ляво на дясно',
		langDirRtl		: 'От дясно на ляво',
		langCode		: 'Код на езика',
		longDescr		: 'Описание на връзката',
		cssClass		: 'Клас от стиловите таблици',
		advisoryTitle	: 'Препоръчително заглавие',
		cssStyle		: 'Стил',
		ok				: 'ОК',
		cancel			: 'Отказ',
		generalTab		: 'General', // MISSING
		advancedTab		: 'Подробности...',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Вмъкни специален символ',
		title		: 'Изберете специален символ'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Добави/Редактирай връзка',
		menu		: 'Редактирай връзка',
		title		: 'Връзка',
		info		: 'Информация за връзката',
		target		: 'Цел',
		upload		: 'Качи',
		advanced	: 'Подробности...',
		type		: 'Вид на връзката',
		toAnchor	: 'Котва в текущата страница',
		toEmail		: 'Е-поща',
		target		: 'Цел',
		targetNotSet	: '<не е настроен>',
		targetFrame	: '<рамка>',
		targetPopup	: '<дъщерен прозорец>',
		targetNew	: 'Нов прозорец (_blank)',
		targetTop	: 'Целия прозорец (_top)',
		targetSelf	: 'Активния прозорец (_self)',
		targetParent	: 'Родителски прозорец (_parent)',
		targetFrameName	: 'Име на целевия прозорец',
		targetPopupName	: 'Име на дъщерния прозорец',
		popupFeatures	: 'Параметри на дъщерния прозорец',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Поле за статус',
		popupLocationBar	: 'Поле за адрес',
		popupToolbar	: 'Панел с бутони',
		popupMenuBar	: 'Меню',
		popupFullScreen	: 'Голям екран (MS IE)',
		popupScrollBars	: 'Плъзгач',
		popupDependent	: 'Зависим (Netscape)',
		popupWidth		: 'Ширина',
		popupLeft		: 'Координати - X',
		popupHeight		: 'Височина',
		popupTop		: 'Координати - Y',
		id				: 'Id', // MISSING
		langDir			: 'посока на речта',
		langDirNotSet	: '<не е настроен>',
		langDirLTR		: 'От ляво на дясно',
		langDirRTL		: 'От дясно на ляво',
		acccessKey		: 'Бърз клавиш',
		name			: 'Име',
		langCode		: 'посока на речта',
		tabIndex		: 'Ред на достъп',
		advisoryTitle	: 'Препоръчително заглавие',
		advisoryContentType	: 'Препоръчителен тип на съдържанието',
		cssClasses		: 'Клас от стиловите таблици',
		charset			: 'Тип на свързания ресурс',
		styles			: 'Стил',
		selectAnchor	: 'Изберете котва',
		anchorName		: 'По име на котвата',
		anchorId		: 'По идентификатор на елемент',
		emailAddress	: 'Адрес за е-поща',
		emailSubject	: 'Тема на писмото',
		emailBody		: 'Текст на писмото',
		noAnchors		: '(Няма котви в текущия документ)',
		noUrl			: 'Моля, напишете пълния път (URL)',
		noEmail			: 'Моля, напишете адреса за е-поща'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Добави/Редактирай котва',
		menu		: 'Параметри на котвата',
		title		: 'Параметри на котвата',
		name		: 'Име на котвата',
		errorName	: 'Моля, въведете име на котвата'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Find and Replace', // MISSING
		find				: 'Търси',
		replace				: 'Замести',
		findWhat			: 'Търси:',
		replaceWith			: 'Замести с:',
		notFoundMsg			: 'Указания текст не беше намерен.',
		matchCase			: 'Със същия регистър',
		matchWord			: 'Търси същата дума',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Замести всички',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Таблица',
		title		: 'Параметри на таблицата',
		menu		: 'Параметри на таблицата',
		deleteTable	: 'Изтрий таблицата',
		rows		: 'Редове',
		columns		: 'Колони',
		border		: 'Размер на рамката',
		align		: 'Подравняване',
		alignNotSet	: '<Не е избрано>',
		alignLeft	: 'Ляво',
		alignCenter	: 'Център',
		alignRight	: 'Дясно',
		width		: 'Ширина',
		widthPx		: 'пиксели',
		widthPc		: 'проценти',
		height		: 'Височина',
		cellSpace	: 'Разстояние между клетките',
		cellPad		: 'Отстъп на съдържанието в клетките',
		caption		: 'Заглавие',
		summary		: 'Резюме',
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
			deleteCell		: 'Изтрий клетките',
			merge			: 'Обедини клетките',
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
			deleteRow		: 'Изтрий редовете'
		},

		column :
		{
			menu			: 'Column', // MISSING
			insertBefore	: 'Insert Column Before', // MISSING
			insertAfter		: 'Insert Column After', // MISSING
			deleteColumn	: 'Изтрий колоните'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Параметри на бутона',
		text		: 'Текст (Стойност)',
		type		: 'Тип',
		typeBtn		: 'Button', // MISSING
		typeSbm		: 'Submit', // MISSING
		typeRst		: 'Reset' // MISSING
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Параметри на полето за отметка',
		radioTitle	: 'Параметри на полето за опция',
		value		: 'Стойност',
		selected	: 'Отметнато'
	},

	// Form Dialog.
	form :
	{
		title		: 'Параметри на формуляра',
		menu		: 'Параметри на формуляра',
		action		: 'Действие',
		method		: 'Метод',
		encoding	: 'Encoding', // MISSING
		target		: 'Цел',
		targetNotSet	: '<не е настроен>',
		targetNew	: 'Нов прозорец (_blank)',
		targetTop	: 'Целия прозорец (_top)',
		targetSelf	: 'Активния прозорец (_self)',
		targetParent	: 'Родителски прозорец (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Параметри на падащото меню с опции',
		selectInfo	: 'Информация',
		opAvail		: 'Възможни опции',
		value		: 'Стойност',
		size		: 'Размер',
		lines		: 'линии',
		chkMulti	: 'Разрешено множествено селектиране',
		opText		: 'Текст',
		opValue		: 'Стойност',
		btnAdd		: 'Добави',
		btnModify	: 'Промени',
		btnUp		: 'Нагоре',
		btnDown		: 'Надолу',
		btnSetValue : 'Настрой като избрана стойност',
		btnDelete	: 'Изтрий'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Параметри на текстовата област',
		cols		: 'Колони',
		rows		: 'Редове'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Параметри на текстовото-поле',
		name		: 'Име',
		value		: 'Стойност',
		charWidth	: 'Ширина на символите',
		maxChars	: 'Максимум символи',
		type		: 'Тип',
		typeText	: 'Текст',
		typePass	: 'Парола'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Параметри на скритото поле',
		name	: 'Име',
		value	: 'Стойност'
	},

	// Image Dialog.
	image :
	{
		title		: 'Параметри на изображението',
		titleButton	: 'Параметри на бутона-изображение',
		menu		: 'Параметри на изображението',
		infoTab	: 'Информация за изображението',
		btnUpload	: 'Прати към сървъра',
		url		: 'Пълен път (URL)',
		upload	: 'Качи',
		alt		: 'Алтернативен текст',
		width		: 'Ширина',
		height	: 'Височина',
		lockRatio	: 'Запази пропорцията',
		resetSize	: 'Възстанови размера',
		border	: 'Рамка',
		hSpace	: 'Хоризонтален отстъп',
		vSpace	: 'Вертикален отстъп',
		align		: 'Подравняване',
		alignLeft	: 'Ляво',
		alignAbsBottom: 'Най-долу',
		alignAbsMiddle: 'Точно по средата',
		alignBaseline	: 'По базовата линия',
		alignBottom	: 'Долу',
		alignMiddle	: 'По средата',
		alignRight	: 'Дясно',
		alignTextTop	: 'Върху текста',
		alignTop	: 'Отгоре',
		preview	: 'Изглед',
		alertUrl	: 'Моля, въведете пълния път до изображението',
		linkTab	: 'Връзка',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Параметри на Flash обекта',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Параметри на Flash обекта',
		chkPlay		: 'Автоматично стартиране',
		chkLoop		: 'Ново стартиране след завършването',
		chkMenu		: 'Разрешено Flash меню',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Оразмеряване',
		scaleAll		: 'Покажи целия обект',
		scaleNoBorder	: 'Без рамка',
		scaleFit		: 'Според мястото',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Подравняване',
		alignLeft	: 'Ляво',
		alignAbsBottom: 'Най-долу',
		alignAbsMiddle: 'Точно по средата',
		alignBaseline	: 'По базовата линия',
		alignBottom	: 'Долу',
		alignMiddle	: 'По средата',
		alignRight	: 'Дясно',
		alignTextTop	: 'Върху текста',
		alignTop	: 'Отгоре',
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
		bgcolor	: 'Цвят на фона',
		width	: 'Ширина',
		height	: 'Височина',
		hSpace	: 'Хоризонтален отстъп',
		vSpace	: 'Вертикален отстъп',
		validateSrc : 'Моля, напишете пълния път (URL)',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Провери правописа',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Липсва в речника',
		changeTo		: 'Промени на',
		btnIgnore		: 'Игнорирай',
		btnIgnoreAll	: 'Игнорирай всички',
		btnReplace		: 'Замести',
		btnReplaceAll	: 'Замести всички',
		btnUndo			: 'Отмени',
		noSuggestions	: '- Няма предложения -',
		progress		: 'Извършване на проверката за правопис...',
		noMispell		: 'Проверката за правопис завършена: не са открити правописни грешки',
		noChanges		: 'Проверката за правопис завършена: няма променени думи',
		oneChange		: 'Проверката за правопис завършена: една дума е променена',
		manyChanges		: 'Проверката за правопис завършена: %1 думи са променени',
		ieSpellDownload	: 'Инструментът за проверка на правопис не е инсталиран. Желаете ли да го инсталирате ?'
	},

	smiley :
	{
		toolbar	: 'Усмивка',
		title	: 'Добави усмивка'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Нумериран списък',
	bulletedlist : 'Ненумериран списък',
	indent : 'Увеличи отстъпа',
	outdent : 'Намали отстъпа',

	justify :
	{
		left : 'Подравняване в ляво',
		center : 'Подравнявне в средата',
		right : 'Подравняване в дясно',
		block : 'Двустранно подравняване'
	},

	blockquote : 'Blockquote', // MISSING

	clipboard :
	{
		title		: 'Вмъкни',
		cutError	: 'Настройките за сигурност на вашия бразуър не разрешават на редактора да изпълни изрязването. За целта използвайте клавиатурата (Ctrl+X).',
		copyError	: 'Настройките за сигурност на вашия бразуър не разрешават на редактора да изпълни запаметяването. За целта използвайте клавиатурата (Ctrl+C).',
		pasteMsg	: 'Вмъкнете тук съдъжанието с клавиатуарата (<STRONG>Ctrl+V</STRONG>) и натиснете <STRONG>OK</STRONG>.',
		securityMsg	: 'Because of your browser security settings, the editor is not able to access your clipboard data directly. You are required to paste it again in this window.' // MISSING
	},

	pastefromword :
	{
		toolbar : 'Вмъкни от MS Word',
		title : 'Вмъкни от MS Word',
		advice : 'Вмъкнете тук съдъжанието с клавиатуарата (<STRONG>Ctrl+V</STRONG>) и натиснете <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Игнорирай шрифтовите дефиниции',
		removeStyle : 'Изтрий стиловите дефиниции'
	},

	pasteText :
	{
		button : 'Вмъкни като чист текст',
		title : 'Вмъкни като чист текст'
	},

	templates :
	{
		button : 'Шаблони',
		title : 'Шаблони',
		insertOption: 'Replace actual contents', // MISSING
		selectPromptMsg: 'Изберете шаблон <br>(текущото съдържание на редактора ще бъде загубено):',
		emptyListMsg : '(Няма дефинирани шаблони)'
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

		tag_p : 'Нормален',
		tag_pre : 'Форматиран',
		tag_address : 'Адрес',
		tag_h1 : 'Заглавие 1',
		tag_h2 : 'Заглавие 2',
		tag_h3 : 'Заглавие 3',
		tag_h4 : 'Заглавие 4',
		tag_h5 : 'Заглавие 5',
		tag_h6 : 'Заглавие 6',
		tag_div : 'Параграф (DIV)'
	},

	font :
	{
		label : 'Шрифт',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'Шрифт',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'Размер',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Размер',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Цвят на текста',
		bgColorTitle : 'Цвят на фона',
		auto : 'По подразбиране',
		more : 'Други цветове...'
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
