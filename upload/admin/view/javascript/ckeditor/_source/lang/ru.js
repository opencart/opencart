/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Russian language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['ru'] =
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
	source			: 'Источник',
	newPage			: 'Новая страница',
	save			: 'Сохранить',
	preview			: 'Предварительный просмотр',
	cut				: 'Вырезать',
	copy			: 'Копировать',
	paste			: 'Вставить',
	print			: 'Печать',
	underline		: 'Подчеркнутый',
	bold			: 'Жирный',
	italic			: 'Курсив',
	selectAll		: 'Выделить все',
	removeFormat	: 'Убрать форматирование',
	strike			: 'Зачеркнутый',
	subscript		: 'Подстрочный индекс',
	superscript		: 'Надстрочный индекс',
	horizontalrule	: 'Вставить горизонтальную линию',
	pagebreak		: 'Вставить разрыв страницы',
	unlink			: 'Убрать ссылку',
	undo			: 'Отменить',
	redo			: 'Повторить',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Просмотреть на сервере',
		url				: 'URL',
		protocol		: 'Протокол',
		upload			: 'Закачать',
		uploadSubmit	: 'Послать на сервер',
		image			: 'Изображение',
		flash			: 'Flash',
		form			: 'Форма',
		checkbox		: 'Флаговая кнопка',
		radio		: 'Кнопка выбора',
		textField		: 'Текстовое поле',
		textarea		: 'Текстовая область',
		hiddenField		: 'Скрытое поле',
		button			: 'Кнопка',
		select	: 'Список',
		imageButton		: 'Кнопка с изображением',
		notSet			: '<не определено>',
		id				: 'Идентификатор',
		name			: 'Имя',
		langDir			: 'Направление языка',
		langDirLtr		: 'Слева на право (LTR)',
		langDirRtl		: 'Справа на лево (RTL)',
		langCode		: 'Язык',
		longDescr		: 'Длинное описание URL',
		cssClass		: 'Класс CSS',
		advisoryTitle	: 'Заголовок',
		cssStyle		: 'Стиль CSS',
		ok				: 'ОК',
		cancel			: 'Отмена',
		generalTab		: 'Информация',
		advancedTab		: 'Расширенный',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Вставить специальный символ',
		title		: 'Выберите специальный символ'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Вставить/Редактировать ссылку',
		menu		: 'Вставить ссылку',
		title		: 'Ссылка',
		info		: 'Информация ссылки',
		target		: 'Цель',
		upload		: 'Закачать',
		advanced	: 'Расширенный',
		type		: 'Тип ссылки',
		toAnchor	: 'Якорь на эту страницу',
		toEmail		: 'Эл. почта',
		target		: 'Цель',
		targetNotSet	: '<не определено>',
		targetFrame	: '<фрейм>',
		targetPopup	: '<всплывающее окно>',
		targetNew	: 'Новое окно (_blank)',
		targetTop	: 'Самое верхнее окно (_top)',
		targetSelf	: 'Тоже окно (_self)',
		targetParent	: 'Родительское окно (_parent)',
		targetFrameName	: 'Имя целевого фрейма',
		targetPopupName	: 'Имя всплывающего окна',
		popupFeatures	: 'Свойства всплывающего окна',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Строка состояния',
		popupLocationBar	: 'Панель локации',
		popupToolbar	: 'Панель инструментов',
		popupMenuBar	: 'Панель меню',
		popupFullScreen	: 'Полный экран (IE)',
		popupScrollBars	: 'Полосы прокрутки',
		popupDependent	: 'Зависимый (Netscape)',
		popupWidth		: 'Ширина',
		popupLeft		: 'Позиция слева',
		popupHeight		: 'Высота',
		popupTop		: 'Позиция сверху',
		id				: 'Id', // MISSING
		langDir			: 'Направление языка',
		langDirNotSet	: '<не определено>',
		langDirLTR		: 'Слева на право (LTR)',
		langDirRTL		: 'Справа на лево (RTL)',
		acccessKey		: 'Горячая клавиша',
		name			: 'Имя',
		langCode		: 'Направление языка',
		tabIndex		: 'Последовательность перехода',
		advisoryTitle	: 'Заголовок',
		advisoryContentType	: 'Тип содержимого',
		cssClasses		: 'Класс CSS',
		charset			: 'Кодировка',
		styles			: 'Стиль CSS',
		selectAnchor	: 'Выберите якорь',
		anchorName		: 'По имени якоря',
		anchorId		: 'По идентификатору элемента',
		emailAddress	: 'Адрес эл. почты',
		emailSubject	: 'Заголовок сообщения',
		emailBody		: 'Тело сообщения',
		noAnchors		: '(Нет якорей доступных в этом документе)',
		noUrl			: 'Пожалуйста, введите URL ссылки',
		noEmail			: 'Пожалуйста, введите адрес эл. почты'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Вставить/Редактировать якорь',
		menu		: 'Свойства якоря',
		title		: 'Свойства якоря',
		name		: 'Имя якоря',
		errorName	: 'Пожалуйста, введите имя якоря'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Найти и заменить',
		find				: 'Найти',
		replace				: 'Заменить',
		findWhat			: 'Найти:',
		replaceWith			: 'Заменить на:',
		notFoundMsg			: 'Указанный текст не найден.',
		matchCase			: 'Учитывать регистр',
		matchWord			: 'Совпадение целых слов',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Заменить все',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Таблица',
		title		: 'Свойства таблицы',
		menu		: 'Свойства таблицы',
		deleteTable	: 'Удалить таблицу',
		rows		: 'Строки',
		columns		: 'Колонки',
		border		: 'Размер бордюра',
		align		: 'Выравнивание',
		alignNotSet	: '<Не уст.>',
		alignLeft	: 'Слева',
		alignCenter	: 'По центру',
		alignRight	: 'Справа',
		width		: 'Ширина',
		widthPx		: 'пикселей',
		widthPc		: 'процентов',
		height		: 'Высота',
		cellSpace	: 'Промежуток (spacing)',
		cellPad		: 'Отступ (padding)',
		caption		: 'Заголовок',
		summary		: 'Резюме',
		headers		: 'Заголовки',
		headersNone		: 'Нет',
		headersColumn	: 'Первый столбец',
		headersRow		: 'Первая строка',
		headersBoth		: 'Оба варианта',
		invalidRows		: 'Number of rows must be a number greater than 0.', // MISSING
		invalidCols		: 'Number of columns must be a number greater than 0.', // MISSING
		invalidBorder	: 'Border size must be a number.', // MISSING
		invalidWidth	: 'Table width must be a number.', // MISSING
		invalidHeight	: 'Table height must be a number.', // MISSING
		invalidCellSpacing	: 'Cell spacing must be a number.', // MISSING
		invalidCellPadding	: 'Cell padding must be a number.', // MISSING

		cell :
		{
			menu			: 'Ячейка',
			insertBefore	: 'Вставить ячейку до',
			insertAfter		: 'Вставить ячейку после',
			deleteCell		: 'Удалить ячейки',
			merge			: 'Соединить ячейки',
			mergeRight		: 'Соединить вправо',
			mergeDown		: 'Соединить вниз',
			splitHorizontal	: 'Разбить ячейку горизонтально',
			splitVertical	: 'Разбить ячейку вертикально',
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
			menu			: 'Строка',
			insertBefore	: 'Вставить строку до',
			insertAfter		: 'Вставить строку после',
			deleteRow		: 'Удалить строки'
		},

		column :
		{
			menu			: 'Колонка',
			insertBefore	: 'Вставить колонку до',
			insertAfter		: 'Вставить колонку после',
			deleteColumn	: 'Удалить колонки'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Свойства кнопки',
		text		: 'Текст (Значение)',
		type		: 'Тип',
		typeBtn		: 'Кнопка',
		typeSbm		: 'Отправить',
		typeRst		: 'Сбросить'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Свойства флаговой кнопки',
		radioTitle	: 'Свойства кнопки выбора',
		value		: 'Значение',
		selected	: 'Выбранная'
	},

	// Form Dialog.
	form :
	{
		title		: 'Свойства формы',
		menu		: 'Свойства формы',
		action		: 'Действие',
		method		: 'Метод',
		encoding	: 'Encoding', // MISSING
		target		: 'Цель',
		targetNotSet	: '<не определено>',
		targetNew	: 'Новое окно (_blank)',
		targetTop	: 'Самое верхнее окно (_top)',
		targetSelf	: 'Тоже окно (_self)',
		targetParent	: 'Родительское окно (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Свойства списка',
		selectInfo	: 'Информация',
		opAvail		: 'Доступные варианты',
		value		: 'Значение',
		size		: 'Размер',
		lines		: 'линии',
		chkMulti	: 'Разрешить множественный выбор',
		opText		: 'Текст',
		opValue		: 'Значение',
		btnAdd		: 'Добавить',
		btnModify	: 'Модифицировать',
		btnUp		: 'Вверх',
		btnDown		: 'Вниз',
		btnSetValue : 'Установить как выбранное значение',
		btnDelete	: 'Удалить'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Свойства текстовой области',
		cols		: 'Колонки',
		rows		: 'Строки'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Свойства текстового поля',
		name		: 'Имя',
		value		: 'Значение',
		charWidth	: 'Ширина',
		maxChars	: 'Макс. кол-во символов',
		type		: 'Тип',
		typeText	: 'Текст',
		typePass	: 'Пароль'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Свойства скрытого поля',
		name	: 'Имя',
		value	: 'Значение'
	},

	// Image Dialog.
	image :
	{
		title		: 'Свойства изображения',
		titleButton	: 'Свойства кнопки с изображением',
		menu		: 'Свойства изображения',
		infoTab	: 'Информация о изображении',
		btnUpload	: 'Послать на сервер',
		url		: 'URL',
		upload	: 'Закачать',
		alt		: 'Альтернативный текст',
		width		: 'Ширина',
		height	: 'Высота',
		lockRatio	: 'Сохранять пропорции',
		resetSize	: 'Сбросить размер',
		border	: 'Бордюр',
		hSpace	: 'Горизонтальный отступ',
		vSpace	: 'Вертикальный отступ',
		align		: 'Выравнивание',
		alignLeft	: 'По левому краю',
		alignAbsBottom: 'Абс понизу',
		alignAbsMiddle: 'Абс посередине',
		alignBaseline	: 'По базовой линии',
		alignBottom	: 'Понизу',
		alignMiddle	: 'Посередине',
		alignRight	: 'По правому краю',
		alignTextTop	: 'Текст наверху',
		alignTop	: 'По верху',
		preview	: 'Предварительный просмотр',
		alertUrl	: 'Пожалуйста, введите URL изображения',
		linkTab	: 'Ссылка',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Свойства Flash',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Свойства Flash',
		chkPlay		: 'Авто проигрывание',
		chkLoop		: 'Повтор',
		chkMenu		: 'Включить меню Flash',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Масштабировать',
		scaleAll		: 'Показывать все',
		scaleNoBorder	: 'Без бордюра',
		scaleFit		: 'Точное совпадение',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Выравнивание',
		alignLeft	: 'По левому краю',
		alignAbsBottom: 'Абс понизу',
		alignAbsMiddle: 'Абс посередине',
		alignBaseline	: 'По базовой линии',
		alignBottom	: 'Понизу',
		alignMiddle	: 'Посередине',
		alignRight	: 'По правому краю',
		alignTextTop	: 'Текст наверху',
		alignTop	: 'По верху',
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
		bgcolor	: 'Цвет фона',
		width	: 'Ширина',
		height	: 'Высота',
		hSpace	: 'Горизонтальный отступ',
		vSpace	: 'Вертикальный отступ',
		validateSrc : 'Пожалуйста, введите URL ссылки',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Проверить орфографию',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Нет в словаре',
		changeTo		: 'Заменить на',
		btnIgnore		: 'Игнорировать',
		btnIgnoreAll	: 'Игнорировать все',
		btnReplace		: 'Заменить',
		btnReplaceAll	: 'Заменить все',
		btnUndo			: 'Отменить',
		noSuggestions	: '- Нет предположений -',
		progress		: 'Идет проверка орфографии...',
		noMispell		: 'Проверка орфографии закончена: ошибок не найдено',
		noChanges		: 'Проверка орфографии закончена: ни одного слова не изменено',
		oneChange		: 'Проверка орфографии закончена: одно слово изменено',
		manyChanges		: 'Проверка орфографии закончена: 1% слов изменен',
		ieSpellDownload	: 'Модуль проверки орфографии не установлен. Хотите скачать его сейчас?'
	},

	smiley :
	{
		toolbar	: 'Смайлик',
		title	: 'Вставить смайлик'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Нумерованный список',
	bulletedlist : 'Маркированный список',
	indent : 'Увеличить отступ',
	outdent : 'Уменьшить отступ',

	justify :
	{
		left : 'По левому краю',
		center : 'По центру',
		right : 'По правому краю',
		block : 'По ширине'
	},

	blockquote : 'Цитата',

	clipboard :
	{
		title		: 'Вставить',
		cutError	: 'Настройки безопасности вашего браузера не позволяют редактору автоматически выполнять операции вырезания. Пожалуйста, используйте клавиатуру для этого (Ctrl+X).',
		copyError	: 'Настройки безопасности вашего браузера не позволяют редактору автоматически выполнять операции копирования. Пожалуйста, используйте клавиатуру для этого (Ctrl+C).',
		pasteMsg	: 'Пожалуйста, вставьте текст в прямоугольник, используя сочетание клавиш (<STRONG>Ctrl+V</STRONG>), и нажмите <STRONG>OK</STRONG>.',
		securityMsg	: 'По причине настроек безопасности браузера, редактор не имеет доступа к данным буфера обмена напрямую. Вам необходимо вставить текст снова в это окно.'
	},

	pastefromword :
	{
		toolbar : 'Вставить из Word',
		title : 'Вставить из Word',
		advice : 'Пожалуйста, вставьте текст в прямоугольник, используя сочетание клавиш (<STRONG>Ctrl+V</STRONG>), и нажмите <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Игнорировать определения гарнитуры',
		removeStyle : 'Убрать определения стилей'
	},

	pasteText :
	{
		button : 'Вставить только текст',
		title : 'Вставить только текст'
	},

	templates :
	{
		button : 'Шаблоны',
		title : 'Шаблоны содержимого',
		insertOption: 'Заменить текущее содержание',
		selectPromptMsg: 'Пожалуйста, выберете шаблон для открытия в редакторе<br>(текущее содержимое будет потеряно):',
		emptyListMsg : '(Ни одного шаблона не определено)'
	},

	showBlocks : 'Показать блоки',

	stylesCombo :
	{
		label : 'Стиль',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'Форматирование',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'Форматирование',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'Нормальный',
		tag_pre : 'Форматированный',
		tag_address : 'Адрес',
		tag_h1 : 'Заголовок 1',
		tag_h2 : 'Заголовок 2',
		tag_h3 : 'Заголовок 3',
		tag_h4 : 'Заголовок 4',
		tag_h5 : 'Заголовок 5',
		tag_h6 : 'Заголовок 6',
		tag_div : 'Нормальный (DIV)'
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
		textColorTitle : 'Цвет текста',
		bgColorTitle : 'Цвет фона',
		auto : 'Автоматический',
		more : 'Цвета...'
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
