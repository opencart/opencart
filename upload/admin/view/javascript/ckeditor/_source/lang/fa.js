/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Persian language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['fa'] =
{
	/**
	 * The language reading direction. Possible values are "rtl" for
	 * Right-To-Left languages (like Arabic) and "ltr" for Left-To-Right
	 * languages (like English).
	 * @default 'ltr'
	 */
	dir : 'rtl',

	/*
	 * Screenreader titles. Please note that screenreaders are not always capable
	 * of reading non-English words. So be careful while translating it.
	 */
	editorTitle		: 'Rich text editor, %1', // MISSING

	// Toolbar buttons without dialogs.
	source			: 'منبع',
	newPage			: 'برگهٴ تازه',
	save			: 'ذخیره',
	preview			: 'پیشنمایش',
	cut				: 'برش',
	copy			: 'کپی',
	paste			: 'چسباندن',
	print			: 'چاپ',
	underline		: 'خطزیردار',
	bold			: 'درشت',
	italic			: 'خمیده',
	selectAll		: 'گزینش همه',
	removeFormat	: 'برداشتن فرمت',
	strike			: 'میانخط',
	subscript		: 'زیرنویس',
	superscript		: 'بالانویس',
	horizontalrule	: 'گنجاندن خط ِافقی',
	pagebreak		: 'گنجاندن شکستگی ِپایان ِبرگه',
	unlink			: 'برداشتن پیوند',
	undo			: 'واچیدن',
	redo			: 'بازچیدن',

	// Common messages and labels.
	common :
	{
		browseServer	: 'فهرستنمایی سرور',
		url				: 'URL',
		protocol		: 'پروتکل',
		upload			: 'انتقال به سرور',
		uploadSubmit	: 'به سرور بفرست',
		image			: 'تصویر',
		flash			: 'Flash',
		form			: 'فرم',
		checkbox		: 'خانهٴ گزینهای',
		radio		: 'دکمهٴ رادیویی',
		textField		: 'فیلد متنی',
		textarea		: 'ناحیهٴ متنی',
		hiddenField		: 'فیلد پنهان',
		button			: 'دکمه',
		select	: 'فیلد چندگزینهای',
		imageButton		: 'دکمهٴ تصویری',
		notSet			: '<تعیننشده>',
		id				: 'شناسه',
		name			: 'نام',
		langDir			: 'جهتنمای زبان',
		langDirLtr		: 'چپ به راست (LTR)',
		langDirRtl		: 'راست به چپ (RTL)',
		langCode		: 'کد زبان',
		longDescr		: 'URL توصیف طولانی',
		cssClass		: 'کلاسهای شیوهنامه(Stylesheet)',
		advisoryTitle	: 'عنوان کمکی',
		cssStyle		: 'شیوه(style)',
		ok				: 'پذیرش',
		cancel			: 'انصراف',
		generalTab		: 'General', // MISSING
		advancedTab		: 'پیشرفته',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'گنجاندن نویسهٴ ویژه',
		title		: 'گزینش نویسهٴویژه'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'گنجاندن/ویرایش ِپیوند',
		menu		: 'ویرایش پیوند',
		title		: 'پیوند',
		info		: 'اطلاعات پیوند',
		target		: 'مقصد',
		upload		: 'انتقال به سرور',
		advanced	: 'پیشرفته',
		type		: 'نوع پیوند',
		toAnchor	: 'لنگر در همین صفحه',
		toEmail		: 'پست الکترونیکی',
		target		: 'مقصد',
		targetNotSet	: '<تعیننشده>',
		targetFrame	: '<فریم>',
		targetPopup	: '<پنجرهٴ پاپاپ>',
		targetNew	: 'پنجرهٴ دیگر (_blank)',
		targetTop	: 'بالاترین پنجره (_top)',
		targetSelf	: 'همان پنجره (_self)',
		targetParent	: 'پنجرهٴ والد (_parent)',
		targetFrameName	: 'نام فریم مقصد',
		targetPopupName	: 'نام پنجرهٴ پاپاپ',
		popupFeatures	: 'ویژگیهای پنجرهٴ پاپاپ',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'نوار وضعیت',
		popupLocationBar	: 'نوار موقعیت',
		popupToolbar	: 'نوارابزار',
		popupMenuBar	: 'نوار منو',
		popupFullScreen	: 'تمامصفحه (IE)',
		popupScrollBars	: 'میلههای پیمایش',
		popupDependent	: 'وابسته (Netscape)',
		popupWidth		: 'پهنا',
		popupLeft		: 'موقعیت ِچپ',
		popupHeight		: 'درازا',
		popupTop		: 'موقعیت ِبالا',
		id				: 'Id', // MISSING
		langDir			: 'جهتنمای زبان',
		langDirNotSet	: '<تعیننشده>',
		langDirLTR		: 'چپ به راست (LTR)',
		langDirRTL		: 'راست به چپ (RTL)',
		acccessKey		: 'کلید دستیابی',
		name			: 'نام',
		langCode		: 'جهتنمای زبان',
		tabIndex		: 'نمایهٴ دسترسی با Tab',
		advisoryTitle	: 'عنوان کمکی',
		advisoryContentType	: 'نوع محتوای کمکی',
		cssClasses		: 'کلاسهای شیوهنامه(Stylesheet)',
		charset			: 'نویسهگان منبع ِپیوندشده',
		styles			: 'شیوه(style)',
		selectAnchor	: 'یک لنگر برگزینید',
		anchorName		: 'با نام لنگر',
		anchorId		: 'با شناسهٴ المان',
		emailAddress	: 'نشانی پست الکترونیکی',
		emailSubject	: 'موضوع پیام',
		emailBody		: 'متن پیام',
		noAnchors		: '(در این سند لنگری دردسترس نیست)',
		noUrl			: 'لطفا URL پیوند را بنویسید',
		noEmail			: 'لطفا نشانی پست الکترونیکی را بنویسید'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'گنجاندن/ویرایش ِلنگر',
		menu		: 'ویژگیهای لنگر',
		title		: 'ویژگیهای لنگر',
		name		: 'نام لنگر',
		errorName	: 'لطفا نام لنگر را بنویسید'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'جستجو و جایگزینی',
		find				: 'جستجو',
		replace				: 'جایگزینی',
		findWhat			: 'چهچیز را مییابید:',
		replaceWith			: 'جایگزینی با:',
		notFoundMsg			: 'متن موردنظر یافت نشد.',
		matchCase			: 'همسانی در بزرگی و کوچکی نویسهها',
		matchWord			: 'همسانی با واژهٴ کامل',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'جایگزینی همهٴ یافتهها',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'جدول',
		title		: 'ویژگیهای جدول',
		menu		: 'ویژگیهای جدول',
		deleteTable	: 'پاککردن جدول',
		rows		: 'سطرها',
		columns		: 'ستونها',
		border		: 'اندازهٴ لبه',
		align		: 'چینش',
		alignNotSet	: '<تعیننشده>',
		alignLeft	: 'چپ',
		alignCenter	: 'وسط',
		alignRight	: 'راست',
		width		: 'پهنا',
		widthPx		: 'پیکسل',
		widthPc		: 'درصد',
		height		: 'درازا',
		cellSpace	: 'فاصلهٴ میان سلولها',
		cellPad		: 'فاصلهٴ پرشده در سلول',
		caption		: 'عنوان',
		summary		: 'خلاصه',
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
			menu			: 'سلول',
			insertBefore	: 'افزودن سلول قبل از',
			insertAfter		: 'افزودن سلول بعد از',
			deleteCell		: 'حذف سلولها',
			merge			: 'ادغام سلولها',
			mergeRight		: 'ادغام به راست',
			mergeDown		: 'ادغام به پایین',
			splitHorizontal	: 'جدا کردن افقی سلول',
			splitVertical	: 'جدا کردن عمودی سلول',
			title			: 'ویژگیهای سلول',
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
			menu			: 'سطر',
			insertBefore	: 'افزودن سطر قبل از',
			insertAfter		: 'افزودن سطر بعد از',
			deleteRow		: 'حذف سطرها'
		},

		column :
		{
			menu			: 'ستون',
			insertBefore	: 'افزودن ستون قبل از',
			insertAfter		: 'افزودن ستون بعد از',
			deleteColumn	: 'حذف ستونها'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'ویژگیهای دکمه',
		text		: 'متن (مقدار)',
		type		: 'نوع',
		typeBtn		: 'دکمه',
		typeSbm		: 'Submit',
		typeRst		: 'بازنشانی (Reset)'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'ویژگیهای خانهٴ گزینهای',
		radioTitle	: 'ویژگیهای دکمهٴ رادیویی',
		value		: 'مقدار',
		selected	: 'برگزیده'
	},

	// Form Dialog.
	form :
	{
		title		: 'ویژگیهای فرم',
		menu		: 'ویژگیهای فرم',
		action		: 'رویداد',
		method		: 'متد',
		encoding	: 'Encoding', // MISSING
		target		: 'مقصد',
		targetNotSet	: '<تعیننشده>',
		targetNew	: 'پنجرهٴ دیگر (_blank)',
		targetTop	: 'بالاترین پنجره (_top)',
		targetSelf	: 'همان پنجره (_self)',
		targetParent	: 'پنجرهٴ والد (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'ویژگیهای فیلد چندگزینهای',
		selectInfo	: 'اطلاعات',
		opAvail		: 'گزینههای دردسترس',
		value		: 'مقدار',
		size		: 'اندازه',
		lines		: 'خطوط',
		chkMulti	: 'گزینش چندگانه فراهم باشد',
		opText		: 'متن',
		opValue		: 'مقدار',
		btnAdd		: 'افزودن',
		btnModify	: 'ویرایش',
		btnUp		: 'بالا',
		btnDown		: 'پائین',
		btnSetValue : 'تنظیم به عنوان مقدار ِبرگزیده',
		btnDelete	: 'پاککردن'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'ویژگیهای ناحیهٴ متنی',
		cols		: 'ستونها',
		rows		: 'سطرها'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'ویژگیهای فیلد متنی',
		name		: 'نام',
		value		: 'مقدار',
		charWidth	: 'پهنای نویسه',
		maxChars	: 'بیشینهٴ نویسهها',
		type		: 'نوع',
		typeText	: 'متن',
		typePass	: 'گذرواژه'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'ویژگیهای فیلد پنهان',
		name	: 'نام',
		value	: 'مقدار'
	},

	// Image Dialog.
	image :
	{
		title		: 'ویژگیهای تصویر',
		titleButton	: 'ویژگیهای دکمهٴ تصویری',
		menu		: 'ویژگیهای تصویر',
		infoTab	: 'اطلاعات تصویر',
		btnUpload	: 'به سرور بفرست',
		url		: 'URL',
		upload	: 'انتقال به سرور',
		alt		: 'متن جایگزین',
		width		: 'پهنا',
		height	: 'درازا',
		lockRatio	: 'قفلکردن ِنسبت',
		resetSize	: 'بازنشانی اندازه',
		border	: 'لبه',
		hSpace	: 'فاصلهٴ افقی',
		vSpace	: 'فاصلهٴ عمودی',
		align		: 'چینش',
		alignLeft	: 'چپ',
		alignAbsBottom: 'پائین مطلق',
		alignAbsMiddle: 'وسط مطلق',
		alignBaseline	: 'خطپایه',
		alignBottom	: 'پائین',
		alignMiddle	: 'وسط',
		alignRight	: 'راست',
		alignTextTop	: 'متن بالا',
		alignTop	: 'بالا',
		preview	: 'پیشنمایش',
		alertUrl	: 'لطفا URL تصویر را بنویسید',
		linkTab	: 'پیوند',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'ویژگیهای Flash',
		propertiesTab	: 'Properties', // MISSING
		title		: 'ویژگیهای Flash',
		chkPlay		: 'آغاز ِخودکار',
		chkLoop		: 'اجرای پیاپی',
		chkMenu		: 'دردسترسبودن منوی Flash',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'مقیاس',
		scaleAll		: 'نمایش همه',
		scaleNoBorder	: 'بدون کران',
		scaleFit		: 'جایگیری کامل',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'چینش',
		alignLeft	: 'چپ',
		alignAbsBottom: 'پائین مطلق',
		alignAbsMiddle: 'وسط مطلق',
		alignBaseline	: 'خطپایه',
		alignBottom	: 'پائین',
		alignMiddle	: 'وسط',
		alignRight	: 'راست',
		alignTextTop	: 'متن بالا',
		alignTop	: 'بالا',
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
		bgcolor	: 'رنگ پسزمینه',
		width	: 'پهنا',
		height	: 'درازا',
		hSpace	: 'فاصلهٴ افقی',
		vSpace	: 'فاصلهٴ عمودی',
		validateSrc : 'لطفا URL پیوند را بنویسید',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'بررسی املا',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'در واژهنامه یافت نشد',
		changeTo		: 'تغییر به',
		btnIgnore		: 'چشمپوشی',
		btnIgnoreAll	: 'چشمپوشی همه',
		btnReplace		: 'جایگزینی',
		btnReplaceAll	: 'جایگزینی همه',
		btnUndo			: 'واچینش',
		noSuggestions	: '- پیشنهادی نیست -',
		progress		: 'بررسی املا در حال انجام...',
		noMispell		: 'بررسی املا انجام شد. هیچ غلطاملائی یافت نشد',
		noChanges		: 'بررسی املا انجام شد. هیچ واژهای تغییر نیافت',
		oneChange		: 'بررسی املا انجام شد. یک واژه تغییر یافت',
		manyChanges		: 'بررسی املا انجام شد. %1 واژه تغییر یافت',
		ieSpellDownload	: 'بررسیکنندهٴ املا نصب نشده است. آیا میخواهید آن را هماکنون دریافت کنید؟'
	},

	smiley :
	{
		toolbar	: 'خندانک',
		title	: 'گنجاندن خندانک'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'فهرست شمارهدار',
	bulletedlist : 'فهرست نقطهای',
	indent : 'افزایش تورفتگی',
	outdent : 'کاهش تورفتگی',

	justify :
	{
		left : 'چپچین',
		center : 'میانچین',
		right : 'راستچین',
		block : 'بلوکچین'
	},

	blockquote : 'بلوک نقل قول',

	clipboard :
	{
		title		: 'چسباندن',
		cutError	: 'تنظیمات امنیتی مرورگر شما اجازه نمیدهد که ویرایشگر به طور خودکار عملکردهای برش را انجام دهد. لطفا با دکمههای صفحهکلید این کار را انجام دهید (Ctrl+X).',
		copyError	: 'تنظیمات امنیتی مرورگر شما اجازه نمیدهد که ویرایشگر به طور خودکار عملکردهای کپیکردن را انجام دهد. لطفا با دکمههای صفحهکلید این کار را انجام دهید (Ctrl+C).',
		pasteMsg	: 'لطفا متن را با کلیدهای (<STRONG>Ctrl+V</STRONG>) در این جعبهٴ متنی بچسبانید و <STRONG>پذیرش</STRONG> را بزنید.',
		securityMsg	: 'به خاطر تنظیمات امنیتی مرورگر شما، ویرایشگر نمیتواند دسترسی مستقیم به دادههای clipboard داشته باشد. شما باید دوباره آنرا در این پنجره بچسبانید.'
	},

	pastefromword :
	{
		toolbar : 'چسباندن از Word',
		title : 'چسباندن از Word',
		advice : 'لطفا متن را با کلیدهای (<STRONG>Ctrl+V</STRONG>) در این جعبهٴ متنی بچسبانید و <STRONG>پذیرش</STRONG> را بزنید.',
		ignoreFontFace : 'چشمپوشی از تعاریف نوع قلم',
		removeStyle : 'چشمپوشی از تعاریف سبک (style)'
	},

	pasteText :
	{
		button : 'چسباندن به عنوان متن ِساده',
		title : 'چسباندن به عنوان متن ِساده'
	},

	templates :
	{
		button : 'الگوها',
		title : 'الگوهای محتویات',
		insertOption: 'محتویات کنونی جایگزین شوند',
		selectPromptMsg: 'لطفا الگوی موردنظر را برای بازکردن در ویرایشگر برگزینید<br>(محتویات کنونی از دست خواهند رفت):',
		emptyListMsg : '(الگوئی تعریف نشده است)'
	},

	showBlocks : 'نمایش بلوکها',

	stylesCombo :
	{
		label : 'سبک',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'فرمت',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'فرمت',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'نرمال',
		tag_pre : 'فرمتشده',
		tag_address : 'آدرس',
		tag_h1 : 'سرنویس 1',
		tag_h2 : 'سرنویس 2',
		tag_h3 : 'سرنویس 3',
		tag_h4 : 'سرنویس 4',
		tag_h5 : 'سرنویس 5',
		tag_h6 : 'سرنویس 6',
		tag_div : 'بند'
	},

	font :
	{
		label : 'قلم',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'قلم',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'اندازه',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'اندازه',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'رنگ متن',
		bgColorTitle : 'رنگ پسزمینه',
		auto : 'خودکار',
		more : 'رنگهای بیشتر...'
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
