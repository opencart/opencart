/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Ukrainian language. Translated by Alexander Pervak.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['uk'] =
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
	editorTitle		: 'Візуальний текстовий редактор, %1',

	// Toolbar buttons without dialogs.
	source			: 'Джерело',
	newPage			: 'Нова сторінка',
	save			: 'Зберегти',
	preview			: 'Попередній перегляд',
	cut				: 'Вирізати',
	copy			: 'Копіювати',
	paste			: 'Вставити',
	print			: 'Друк',
	underline		: 'Підкреслений',
	bold			: 'Жирний',
	italic			: 'Курсив',
	selectAll		: 'Виділити все',
	removeFormat	: 'Прибрати форматування',
	strike			: 'Закреслений',
	subscript		: 'Підрядковий індекс',
	superscript		: 'Надрядковий индекс',
	horizontalrule	: 'Вставити горизонтальну лінію',
	pagebreak		: 'Вставити розривши сторінки',
	unlink			: 'Знищити посилання',
	undo			: 'Повернути',
	redo			: 'Повторити',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Передивитися на сервері',
		url				: 'URL',
		protocol		: 'Протокол',
		upload			: 'Закачати',
		uploadSubmit	: 'Надіслати на сервер',
		image			: 'Зображення',
		flash			: 'Flash',
		form			: 'Форма',
		checkbox		: 'Флагова кнопка',
		radio		: 'Кнопка вибору',
		textField		: 'Текстове поле',
		textarea		: 'Текстова область',
		hiddenField		: 'Приховане поле',
		button			: 'Кнопка',
		select	: 'Список',
		imageButton		: 'Кнопка із зображенням',
		notSet			: '<не визначено>',
		id				: 'Ідентифікатор',
		name			: 'Им\'я',
		langDir			: 'Напрямок мови',
		langDirLtr		: 'Зліва на право (LTR)',
		langDirRtl		: 'Зправа на ліво (RTL)',
		langCode		: 'Мова',
		longDescr		: 'Довгий опис URL',
		cssClass		: 'Клас CSS',
		advisoryTitle	: 'Заголовок',
		cssStyle		: 'Стиль CSS',
		ok				: 'ОК',
		cancel			: 'Скасувати',
		generalTab		: 'Загальна',
		advancedTab		: 'Розширений',
		validateNumberFailed	: 'Значення не є числом.',
		confirmNewPage	: 'Всі не збережені зміни будуть втрачені. Ви впевнені, що хочете завантажити нову сторінку?',
		confirmCancel	: 'Деякі опції були змінені. Закрити вікно?',

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, не доступне</span>'
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Вставити спеціальний символ',
		title		: 'Оберіть спеціальний символ'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Вставити/Редагувати посилання',
		menu		: 'Вставити посилання',
		title		: 'Посилання',
		info		: 'Інформація посилання',
		target		: 'Ціль',
		upload		: 'Закачати',
		advanced	: 'Розширений',
		type		: 'Тип посилання',
		toAnchor	: 'Якір на цю сторінку',
		toEmail		: 'Эл. пошта',
		target		: 'Ціль',
		targetNotSet	: '<не визначено>',
		targetFrame	: '<фрейм>',
		targetPopup	: '<спливаюче вікно>',
		targetNew	: 'Нове вікно (_blank)',
		targetTop	: 'Найвище вікно (_top)',
		targetSelf	: 'Теж вікно (_self)',
		targetParent	: 'Батьківське вікно (_parent)',
		targetFrameName	: 'Ім\'я целевого фрейма',
		targetPopupName	: 'Ім\'я спливаючого вікна',
		popupFeatures	: 'Властивості спливаючого вікна',
		popupResizable	: 'Масштабоване',
		popupStatusBar	: 'Строка статусу',
		popupLocationBar	: 'Панель локації',
		popupToolbar	: 'Панель інструментів',
		popupMenuBar	: 'Панель меню',
		popupFullScreen	: 'Повний екран (IE)',
		popupScrollBars	: 'Полоси прокрутки',
		popupDependent	: 'Залежний (Netscape)',
		popupWidth		: 'Ширина',
		popupLeft		: 'Позиція зліва',
		popupHeight		: 'Висота',
		popupTop		: 'Позиція зверху',
		id				: 'Ідентифікатор (Id)',
		langDir			: 'Напрямок мови',
		langDirNotSet	: '<не визначено>',
		langDirLTR		: 'Зліва на право (LTR)',
		langDirRTL		: 'Зправа на ліво (RTL)',
		acccessKey		: 'Гаряча клавіша',
		name			: 'Им\'я',
		langCode		: 'Напрямок мови',
		tabIndex		: 'Послідовність переходу',
		advisoryTitle	: 'Заголовок',
		advisoryContentType	: 'Тип вмісту',
		cssClasses		: 'Клас CSS',
		charset			: 'Кодировка',
		styles			: 'Стиль CSS',
		selectAnchor	: 'Оберіть якір',
		anchorName		: 'За ім\'ям якоря',
		anchorId		: 'За ідентифікатором елемента',
		emailAddress	: 'Адреса ел. пошти',
		emailSubject	: 'Тема листа',
		emailBody		: 'Тіло повідомлення',
		noAnchors		: '(Немає якорів доступних в цьому документі)',
		noUrl			: 'Будь ласка, занесіть URL посилання',
		noEmail			: 'Будь ласка, занесіть адрес эл. почты'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Вставити/Редагувати якір',
		menu		: 'Властивості якоря',
		title		: 'Властивості якоря',
		name		: 'Ім\'я якоря',
		errorName	: 'Будь ласка, занесіть ім\'я якоря'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Знайти і замінити',
		find				: 'Пошук',
		replace				: 'Заміна',
		findWhat			: 'Шукати:',
		replaceWith			: 'Замінити на:',
		notFoundMsg			: 'Вказаний текст не знайдений.',
		matchCase			: 'Враховувати регістр',
		matchWord			: 'Збіг цілих слів',
		matchCyclic			: 'Циклічна заміна',
		replaceAll			: 'Замінити все',
		replaceSuccessMsg	: '%1 співпадінь(я) замінено.'
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Таблиця',
		title		: 'Властивості таблиці',
		menu		: 'Властивості таблиці',
		deleteTable	: 'Видалити таблицю',
		rows		: 'Строки',
		columns		: 'Колонки',
		border		: 'Розмір бордюра',
		align		: 'Вирівнювання',
		alignNotSet	: '<Не вст.>',
		alignLeft	: 'Зліва',
		alignCenter	: 'По центру',
		alignRight	: 'Зправа',
		width		: 'Ширина',
		widthPx		: 'пікселів',
		widthPc		: 'відсотків',
		height		: 'Висота',
		cellSpace	: 'Проміжок (spacing)',
		cellPad		: 'Відступ (padding)',
		caption		: 'Заголовок',
		summary		: 'Резюме',
		headers		: 'Заголовки',
		headersNone		: 'Жодного',
		headersColumn	: 'Перша колонка',
		headersRow		: 'Перший рядок',
		headersBoth		: 'Обидва',
		invalidRows		: 'Кількість рядків повинна бути числом більше за 0.',
		invalidCols		: 'Кількість колонок повинна бути числом більше за  0.',
		invalidBorder	: 'Розмір бордюра повинен бути числом.',
		invalidWidth	: 'Ширина таблиці повинна бути числом.',
		invalidHeight	: 'Висота таблиці повинна бути числом.',
		invalidCellSpacing	: 'Проміжок (spacing) комірки повинен бути числом.',
		invalidCellPadding	: 'Відступ (padding) комірки повинен бути числом.',

		cell :
		{
			menu			: 'Осередок',
			insertBefore	: 'Вставити комірку до',
			insertAfter		: 'Вставити комірку після',
			deleteCell		: 'Видалити комірки',
			merge			: 'Об\'єднати комірки',
			mergeRight		: 'Об\'єднати зправа',
			mergeDown		: 'Об\'єднати до низу',
			splitHorizontal	: 'Розділити комірку по горизонталі',
			splitVertical	: 'Розділити комірку по вертикалі',
			title			: 'Властивості комірки',
			cellType		: 'Тип комірки',
			rowSpan			: 'Обєднання рядків (Rows Span)',
			colSpan			: 'Обєднання стовпчиків (Columns Span)',
			wordWrap		: 'Авто згортання тексту (Word Wrap)',
			hAlign			: 'Горизонтальне вирівнювання',
			vAlign			: 'Вертикальне вирівнювання',
			alignTop		: 'До верху',
			alignMiddle		: 'Посередині',
			alignBottom		: 'До низу',
			alignBaseline	: 'По базовій лінії',
			bgColor			: 'Колір фону',
			borderColor		: 'Колір бордюру',
			data			: 'Дані',
			header			: 'Заголовок',
			yes				: 'Так',
			no				: 'Ні',
			invalidWidth	: 'Ширина комірки повинна бути числом.',
			invalidHeight	: 'Висота комірки повинна бути числом.',
			invalidRowSpan	: 'Кількість обєднуваних рядків повинна бути цілим числом.',
			invalidColSpan	: 'Кількість обєднуваних стовпчиків повинна бути цілим числом.',
			chooseColor : 'Choose' // MISSING
		},

		row :
		{
			menu			: 'Рядок',
			insertBefore	: 'Вставити рядок до',
			insertAfter		: 'Вставити рядок після',
			deleteRow		: 'Видалити строки'
		},

		column :
		{
			menu			: 'Колонка',
			insertBefore	: 'Вставити колонку до',
			insertAfter		: 'Вставити колонку після',
			deleteColumn	: 'Видалити колонки'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Властивості кнопки',
		text		: 'Текст (Значення)',
		type		: 'Тип',
		typeBtn		: 'Кнопка',
		typeSbm		: 'Відправити',
		typeRst		: 'Скинути'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Властивості флагової кнопки',
		radioTitle	: 'Властивості кнопки вибору',
		value		: 'Значення',
		selected	: 'Обрана'
	},

	// Form Dialog.
	form :
	{
		title		: 'Властивості форми',
		menu		: 'Властивості форми',
		action		: 'Дія',
		method		: 'Метод',
		encoding	: 'Кодування',
		target		: 'Ціль',
		targetNotSet	: '<не визначено>',
		targetNew	: 'Нове вікно (_blank)',
		targetTop	: 'Найвище вікно (_top)',
		targetSelf	: 'Теж вікно (_self)',
		targetParent	: 'Батьківське вікно (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Властивості списку',
		selectInfo	: 'Інфо',
		opAvail		: 'Доступні варіанти',
		value		: 'Значення',
		size		: 'Розмір',
		lines		: 'лінії',
		chkMulti	: 'Дозволити обрання декількох позицій',
		opText		: 'Текст',
		opValue		: 'Значення',
		btnAdd		: 'Добавити',
		btnModify	: 'Змінити',
		btnUp		: 'Вгору',
		btnDown		: 'Вниз',
		btnSetValue : 'Встановити як вибране значення',
		btnDelete	: 'Видалити'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Властивості текстової області',
		cols		: 'Колонки',
		rows		: 'Строки'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Властивості текстового поля',
		name		: 'Ім\'я',
		value		: 'Значення',
		charWidth	: 'Ширина',
		maxChars	: 'Макс. кіл-ть символів',
		type		: 'Тип',
		typeText	: 'Текст',
		typePass	: 'Пароль'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Властивості прихованого поля',
		name	: 'Ім\'я',
		value	: 'Значення'
	},

	// Image Dialog.
	image :
	{
		title		: 'Властивості зображення',
		titleButton	: 'Властивості кнопки із зображенням',
		menu		: 'Властивості зображення',
		infoTab	: 'Інформація про изображении',
		btnUpload	: 'Надіслати на сервер',
		url		: 'URL',
		upload	: 'Закачати',
		alt		: 'Альтернативний текст',
		width		: 'Ширина',
		height	: 'Висота',
		lockRatio	: 'Зберегти пропорції',
		resetSize	: 'Скинути розмір',
		border	: 'Бордюр',
		hSpace	: 'Горизонтальний відступ',
		vSpace	: 'Вертикальний відступ',
		align		: 'Вирівнювання',
		alignLeft	: 'По лівому краю',
		alignAbsBottom: 'Абс по низу',
		alignAbsMiddle: 'Абс по середині',
		alignBaseline	: 'По базовій лінії',
		alignBottom	: 'По низу',
		alignMiddle	: 'По середині',
		alignRight	: 'По правому краю',
		alignTextTop	: 'Текст на верху',
		alignTop	: 'По верху',
		preview	: 'Попередній перегляд',
		alertUrl	: 'Будь ласка, введіть URL зображення',
		linkTab	: 'Посилання',
		button2Img	: 'Ви хочете перетворити обрану кнопку-зображення на просте зображення?',
		img2Button	: 'Ви хочете перетворити обране зображення на кнопку-зображення?',
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Властивості Flash',
		propertiesTab	: 'Властивості',
		title		: 'Властивості Flash',
		chkPlay		: 'Авто програвання',
		chkLoop		: 'Зациклити',
		chkMenu		: 'Дозволити меню Flash',
		chkFull		: 'Дозволити повноекранний перегляд',
 		scale		: 'Масштаб',
		scaleAll		: 'Показати всі',
		scaleNoBorder	: 'Без рамки',
		scaleFit		: 'Дійсний розмір',
		access			: 'Доступ до скрипта',
		accessAlways	: 'Завжди',
		accessSameDomain	: 'З того ж домена',
		accessNever	: 'Ніколи',
		align		: 'Вирівнювання',
		alignLeft	: 'По лівому краю',
		alignAbsBottom: 'Абс по низу',
		alignAbsMiddle: 'Абс по середині',
		alignBaseline	: 'По базовій лінії',
		alignBottom	: 'По низу',
		alignMiddle	: 'По середині',
		alignRight	: 'По правому краю',
		alignTextTop	: 'Текст на верху',
		alignTop	: 'По верху',
		quality		: 'Якість',
		qualityBest		 : 'Відмінна',
		qualityHigh		 : 'Висока',
		qualityAutoHigh	 : 'Авто відмінна',
		qualityMedium	 : 'Середня',
		qualityAutoLow	 : 'Авто низька',
		qualityLow		 : 'Низька',
		windowModeWindow	 : 'Вікно',
		windowModeOpaque	 : 'Непрозорість (Opaque)',
		windowModeTransparent	 : 'Прозорість (Transparent)',
		windowMode	: 'Режим вікна',
		flashvars	: 'Змінні Flash',
		bgcolor	: 'Колір фону',
		width	: 'Ширина',
		height	: 'Висота',
		hSpace	: 'Горизонтальний відступ',
		vSpace	: 'Вертикальний відступ',
		validateSrc : 'Будь ласка, занесіть URL посилання',
		validateWidth : 'Ширина повинна бути числом.',
		validateHeight : 'Висота повинна бути числом.',
		validateHSpace : 'HSpace повинна бути числом.',
		validateVSpace : 'VSpace повинна бути числом.'
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Перевірити орфографію',
		title			: 'Перевірка орфографії',
		notAvailable	: 'Вибачте, але сервіс наразі недоступний.',
		errorLoading	: 'Помилка завантаження : %s.',
		notInDic		: 'Не має в словнику',
		changeTo		: 'Замінити на',
		btnIgnore		: 'Ігнорувати',
		btnIgnoreAll	: 'Ігнорувати все',
		btnReplace		: 'Замінити',
		btnReplaceAll	: 'Замінити все',
		btnUndo			: 'Назад',
		noSuggestions	: '- Немає припущень -',
		progress		: 'Виконується перевірка орфографії...',
		noMispell		: 'Перевірку орфографії завершено: помилок не знайдено',
		noChanges		: 'Перевірку орфографії завершено: жодне слово не змінено',
		oneChange		: 'Перевірку орфографії завершено: змінено одно слово',
		manyChanges		: 'Перевірку орфографії завершено: 1% слів змінено',
		ieSpellDownload	: 'Модуль перевірки орфографії не встановлено. Бажаєтн завантажити його зараз?'
	},

	smiley :
	{
		toolbar	: 'Смайлик',
		title	: 'Вставити смайлик'
	},

	elementsPath :
	{
		eleTitle : '%1 елемент'
	},

	numberedlist : 'Нумерований список',
	bulletedlist : 'Маркований список',
	indent : 'Збільшити відступ',
	outdent : 'Зменшити відступ',

	justify :
	{
		left : 'По лівому краю',
		center : 'По центру',
		right : 'По правому краю',
		block : 'По ширині'
	},

	blockquote : 'Цитата',

	clipboard :
	{
		title		: 'Вставити',
		cutError	: 'Настройки безпеки вашого браузера не дозволяють редактору автоматично виконувати операції вирізування. Будь ласка, використовуйте клавіатуру для цього (Ctrl+X).',
		copyError	: 'Настройки безпеки вашого браузера не дозволяють редактору автоматично виконувати операції копіювання. Будь ласка, використовуйте клавіатуру для цього (Ctrl+C).',
		pasteMsg	: 'Будь ласка, вставте з буфера обміну в цю область, користуючись комбінацією клавіш (<STRONG>Ctrl+V</STRONG>) та натисніть <STRONG>OK</STRONG>.',
		securityMsg	: 'Редактор не може отримати прямий доступ до буферу обміну у зв\'язку з налаштуваннями вашого браузера. Вам потрібно вставити інформацію повторно в це вікно.'
	},

	pastefromword :
	{
		toolbar : 'Вставити з Word',
		title : 'Вставити з Word',
		advice : 'Будь-ласка, вставте з буфера обміну в цю область, користуючись комбінацією клавіш (<STRONG>Ctrl+V</STRONG>) та натисніть <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Ігнорувати налаштування шрифтів',
		removeStyle : 'Видалити налаштування стилів'
	},

	pasteText :
	{
		button : 'Вставити тільки текст',
		title : 'Вставити тільки текст'
	},

	templates :
	{
		button : 'Шаблони',
		title : 'Шаблони змісту',
		insertOption: 'Замінити поточний вміст',
		selectPromptMsg: 'Оберіть, будь ласка, шаблон для відкриття в редакторі<br>(поточний зміст буде втрачено):',
		emptyListMsg : '(Не визначено жодного шаблону)'
	},

	showBlocks : 'Показувати блоки',

	stylesCombo :
	{
		label : 'Стиль',
		voiceLabel : 'Стилі',
		panelVoiceLabel : 'Оберіть стиль',
		panelTitle1 : 'Block стилі',
		panelTitle2 : 'Inline стилі',
		panelTitle3 : 'Object стилі'
	},

	format :
	{
		label : 'Форматування',
		voiceLabel : 'Формат',
		panelTitle : 'Форматування',
		panelVoiceLabel : 'Оберіть формат абзацу',

		tag_p : 'Нормальний',
		tag_pre : 'Форматований',
		tag_address : 'Адреса',
		tag_h1 : 'Заголовок 1',
		tag_h2 : 'Заголовок 2',
		tag_h3 : 'Заголовок 3',
		tag_h4 : 'Заголовок 4',
		tag_h5 : 'Заголовок 5',
		tag_h6 : 'Заголовок 6',
		tag_div : 'Нормальний (DIV)'
	},

	font :
	{
		label : 'Шрифт',
		voiceLabel : 'Шрифт',
		panelTitle : 'Шрифт',
		panelVoiceLabel : 'Оберіть шрифт'
	},

	fontSize :
	{
		label : 'Розмір',
		voiceLabel : 'Розмір шрифта',
		panelTitle : 'Розмір',
		panelVoiceLabel : 'Оберіть розмір шрифта'
	},

	colorButton :
	{
		textColorTitle : 'Колір тексту',
		bgColorTitle : 'Колір фону',
		auto : 'Автоматичний',
		more : 'Кольори...'
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
		title : 'Перефірка орфографії по мірі набору',
		enable : 'Включити SCAYT',
		disable : 'Відключити SCAYT',
		about : 'Про SCAYT',
		toggle : 'Перемкнути SCAYT',
		options : 'Опції',
		langs : 'Мови',
		moreSuggestions : 'Більше пропозицій',
		ignore : 'Ігнорувати',
		ignoreAll : 'Ігнорувати всі',
		addWord : 'Додати слово',
		emptyDic : 'Назва словника повинна бути заповнена.',
		optionsTab : 'Опції',
		languagesTab : 'Мови',
		dictionariesTab : 'Словники',
		aboutTab : 'Про'
	},

	about :
	{
		title : 'Про CKEditor',
		dlgTitle : 'Про CKEditor',
		moreInfo : 'Щодо інформації з ліцензування завітайте до нашого сайту:',
		copy : 'Copyright &copy; $1. Всі права застережено.'
	},

	maximize : 'Максимізувати',
	minimize : 'Minimize', // MISSING

	fakeobjects :
	{
		anchor : 'Якір',
		flash : 'Flash анімація',
		div : 'Розрив сторінки',
		unknown : 'Невідомий об`єкт'
	},

	resize : 'Пересувайте для зміни розміру',

	colordialog :
	{
		title : 'Select color', // MISSING
		highlight : 'Highlight', // MISSING
		selected : 'Selected', // MISSING
		clear : 'Clear' // MISSING
	}
};
