/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Bengali/Bangla language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['bn'] =
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
	source			: 'সোর্স',
	newPage			: 'নতুন পেজ',
	save			: 'সংরক্ষন কর',
	preview			: 'প্রিভিউ',
	cut				: 'কাট',
	copy			: 'কপি',
	paste			: 'পেস্ট',
	print			: 'প্রিন্ট',
	underline		: 'আন্ডারলাইন',
	bold			: 'বোল্ড',
	italic			: 'ইটালিক',
	selectAll		: 'সব সিলেক্ট কর',
	removeFormat	: 'ফরমেট সরাও',
	strike			: 'স্ট্রাইক থ্রু',
	subscript		: 'অধোলেখ',
	superscript		: 'অভিলেখ',
	horizontalrule	: 'রেখা যুক্ত কর',
	pagebreak		: 'পেজ ব্রেক',
	unlink			: 'লিংক সরাও',
	undo			: 'আনডু',
	redo			: 'রি-ডু',

	// Common messages and labels.
	common :
	{
		browseServer	: 'ব্রাউজ সার্ভার',
		url				: 'URL',
		protocol		: 'প্রোটোকল',
		upload			: 'আপলোড',
		uploadSubmit	: 'ইহাকে সার্ভারে প্রেরন কর',
		image			: 'ছবির লেবেল যুক্ত কর',
		flash			: 'ফ্লাশ লেবেল যুক্ত কর',
		form			: 'ফর্ম',
		checkbox		: 'চেক বাক্স',
		radio		: 'রেডিও বাটন',
		textField		: 'টেক্সট ফীল্ড',
		textarea		: 'টেক্সট এরিয়া',
		hiddenField		: 'গুপ্ত ফীল্ড',
		button			: 'বাটন',
		select	: 'বাছাই ফীল্ড',
		imageButton		: 'ছবির বাটন',
		notSet			: '<সেট নেই>',
		id				: 'আইডি',
		name			: 'নাম',
		langDir			: 'ভাষা লেখার দিক',
		langDirLtr		: 'বাম থেকে ডান (LTR)',
		langDirRtl		: 'ডান থেকে বাম (RTL)',
		langCode		: 'ভাষা কোড',
		longDescr		: 'URL এর লম্বা বর্ণনা',
		cssClass		: 'স্টাইল-শীট ক্লাস',
		advisoryTitle	: 'পরামর্শ শীর্ষক',
		cssStyle		: 'স্টাইল',
		ok				: 'ওকে',
		cancel			: 'বাতিল',
		generalTab		: 'General', // MISSING
		advancedTab		: 'এডভান্সড',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'বিশেষ অক্ষর যুক্ত কর',
		title		: 'বিশেষ ক্যারেক্টার বাছাই কর'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'লিংক যুক্ত কর',
		menu		: 'লিংক সম্পাদন',
		title		: 'লিংক',
		info		: 'লিংক তথ্য',
		target		: 'টার্গেট',
		upload		: 'আপলোড',
		advanced	: 'এডভান্সড',
		type		: 'লিংক প্রকার',
		toAnchor	: 'এই পেজে নোঙর কর',
		toEmail		: 'ইমেইল',
		target		: 'টার্গেট',
		targetNotSet	: '<সেট নেই>',
		targetFrame	: '<ফ্রেম>',
		targetPopup	: '<পপআপ উইন্ডো>',
		targetNew	: 'নতুন উইন্ডো (_blank)',
		targetTop	: 'শীর্ষ উইন্ডো (_top)',
		targetSelf	: 'এই উইন্ডো (_self)',
		targetParent	: 'মূল উইন্ডো (_parent)',
		targetFrameName	: 'টার্গেট ফ্রেমের নাম',
		targetPopupName	: 'পপআপ উইন্ডোর নাম',
		popupFeatures	: 'পপআপ উইন্ডো ফীচার সমূহ',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'স্ট্যাটাস বার',
		popupLocationBar	: 'লোকেশন বার',
		popupToolbar	: 'টুল বার',
		popupMenuBar	: 'মেন্যু বার',
		popupFullScreen	: 'পূর্ণ পর্দা জুড়ে (IE)',
		popupScrollBars	: 'স্ক্রল বার',
		popupDependent	: 'ডিপেন্ডেন্ট (Netscape)',
		popupWidth		: 'প্রস্থ',
		popupLeft		: 'বামের পজিশন',
		popupHeight		: 'দৈর্ঘ্য',
		popupTop		: 'ডানের পজিশন',
		id				: 'Id', // MISSING
		langDir			: 'ভাষা লেখার দিক',
		langDirNotSet	: '<সেট নেই>',
		langDirLTR		: 'বাম থেকে ডান (LTR)',
		langDirRTL		: 'ডান থেকে বাম (RTL)',
		acccessKey		: 'এক্সেস কী',
		name			: 'নাম',
		langCode		: 'ভাষা লেখার দিক',
		tabIndex		: 'ট্যাব ইন্ডেক্স',
		advisoryTitle	: 'পরামর্শ শীর্ষক',
		advisoryContentType	: 'পরামর্শ কন্টেন্টের প্রকার',
		cssClasses		: 'স্টাইল-শীট ক্লাস',
		charset			: 'লিংক রিসোর্স ক্যারেক্টর সেট',
		styles			: 'স্টাইল',
		selectAnchor	: 'নোঙর বাছাই',
		anchorName		: 'নোঙরের নাম দিয়ে',
		anchorId		: 'নোঙরের আইডি দিয়ে',
		emailAddress	: 'ইমেইল ঠিকানা',
		emailSubject	: 'মেসেজের বিষয়',
		emailBody		: 'মেসেজের দেহ',
		noAnchors		: '(No anchors available in the document)', // MISSING
		noUrl			: 'অনুগ্রহ করে URL লিংক টাইপ করুন',
		noEmail			: 'অনুগ্রহ করে ইমেইল এড্রেস টাইপ করুন'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'নোঙ্গর',
		menu		: 'নোঙর প্রোপার্টি',
		title		: 'নোঙর প্রোপার্টি',
		name		: 'নোঙরের নাম',
		errorName	: 'নোঙরের নাম টাইপ করুন'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Find and Replace', // MISSING
		find				: 'খোজো',
		replace				: 'রিপ্লেস',
		findWhat			: 'যা খুঁজতে হবে:',
		replaceWith			: 'যার সাথে বদলাতে হবে:',
		notFoundMsg			: 'আপনার উল্লেখিত টেকস্ট পাওয়া যায়নি',
		matchCase			: 'কেস মিলাও',
		matchWord			: 'পুরা শব্দ মেলাও',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'সব বদলে দাও',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'টেবিলের লেবেল যুক্ত কর',
		title		: 'টেবিল প্রোপার্টি',
		menu		: 'টেবিল প্রোপার্টি',
		deleteTable	: 'টেবিল ডিলীট কর',
		rows		: 'রো',
		columns		: 'কলাম',
		border		: 'বর্ডার সাইজ',
		align		: 'এলাইনমেন্ট',
		alignNotSet	: '<সেট নেই>',
		alignLeft	: 'বামে',
		alignCenter	: 'মাঝখানে',
		alignRight	: 'ডানে',
		width		: 'প্রস্থ',
		widthPx		: 'পিক্সেল',
		widthPc		: 'শতকরা',
		height		: 'দৈর্ঘ্য',
		cellSpace	: 'সেল স্পেস',
		cellPad		: 'সেল প্যাডিং',
		caption		: 'শীর্ষক',
		summary		: 'সারাংশ',
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
			menu			: 'সেল',
			insertBefore	: 'Insert Cell Before', // MISSING
			insertAfter		: 'Insert Cell After', // MISSING
			deleteCell		: 'সেল মুছে দাও',
			merge			: 'সেল জোড়া দাও',
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
			menu			: 'রো',
			insertBefore	: 'Insert Row Before', // MISSING
			insertAfter		: 'Insert Row After', // MISSING
			deleteRow		: 'রো মুছে দাও'
		},

		column :
		{
			menu			: 'কলাম',
			insertBefore	: 'Insert Column Before', // MISSING
			insertAfter		: 'Insert Column After', // MISSING
			deleteColumn	: 'কলাম মুছে দাও'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'বাটন প্রোপার্টি',
		text		: 'টেক্সট (ভ্যালু)',
		type		: 'প্রকার',
		typeBtn		: 'Button', // MISSING
		typeSbm		: 'Submit', // MISSING
		typeRst		: 'Reset' // MISSING
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'চেক বক্স প্রোপার্টি',
		radioTitle	: 'রেডিও বাটন প্রোপার্টি',
		value		: 'ভ্যালু',
		selected	: 'সিলেক্টেড'
	},

	// Form Dialog.
	form :
	{
		title		: 'ফর্ম প্রোপার্টি',
		menu		: 'ফর্ম প্রোপার্টি',
		action		: 'একশ্যন',
		method		: 'পদ্ধতি',
		encoding	: 'Encoding', // MISSING
		target		: 'টার্গেট',
		targetNotSet	: '<সেট নেই>',
		targetNew	: 'নতুন উইন্ডো (_blank)',
		targetTop	: 'শীর্ষ উইন্ডো (_top)',
		targetSelf	: 'এই উইন্ডো (_self)',
		targetParent	: 'মূল উইন্ডো (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'বাছাই ফীল্ড প্রোপার্টি',
		selectInfo	: 'তথ্য',
		opAvail		: 'অন্যান্য বিকল্প',
		value		: 'ভ্যালু',
		size		: 'সাইজ',
		lines		: 'লাইন সমূহ',
		chkMulti	: 'একাধিক সিলেকশন এলাউ কর',
		opText		: 'টেক্সট',
		opValue		: 'ভ্যালু',
		btnAdd		: 'যুক্ত',
		btnModify	: 'বদলে দাও',
		btnUp		: 'উপর',
		btnDown		: 'নীচে',
		btnSetValue : 'বাছাই করা ভ্যালু হিসেবে সেট কর',
		btnDelete	: 'ডিলীট'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'টেক্সট এরিয়া প্রোপার্টি',
		cols		: 'কলাম',
		rows		: 'রো'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'টেক্সট ফীল্ড প্রোপার্টি',
		name		: 'নাম',
		value		: 'ভ্যালু',
		charWidth	: 'ক্যারেক্টার প্রশস্ততা',
		maxChars	: 'সর্বাধিক ক্যারেক্টার',
		type		: 'টাইপ',
		typeText	: 'টেক্সট',
		typePass	: 'পাসওয়ার্ড'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'গুপ্ত ফীল্ড প্রোপার্টি',
		name	: 'নাম',
		value	: 'ভ্যালু'
	},

	// Image Dialog.
	image :
	{
		title		: 'ছবির প্রোপার্টি',
		titleButton	: 'ছবি বাটন প্রোপার্টি',
		menu		: 'ছবির প্রোপার্টি',
		infoTab	: 'ছবির তথ্য',
		btnUpload	: 'ইহাকে সার্ভারে প্রেরন কর',
		url		: 'URL',
		upload	: 'আপলোড',
		alt		: 'বিকল্প টেক্সট',
		width		: 'প্রস্থ',
		height	: 'দৈর্ঘ্য',
		lockRatio	: 'অনুপাত লক কর',
		resetSize	: 'সাইজ পূর্বাবস্থায় ফিরিয়ে দাও',
		border	: 'বর্ডার',
		hSpace	: 'হরাইজন্টাল স্পেস',
		vSpace	: 'ভার্টিকেল স্পেস',
		align		: 'এলাইন',
		alignLeft	: 'বামে',
		alignAbsBottom: 'Abs নীচে',
		alignAbsMiddle: 'Abs উপর',
		alignBaseline	: 'মূল রেখা',
		alignBottom	: 'নীচে',
		alignMiddle	: 'মধ্য',
		alignRight	: 'ডানে',
		alignTextTop	: 'টেক্সট উপর',
		alignTop	: 'উপর',
		preview	: 'প্রীভিউ',
		alertUrl	: 'অনুগ্রহক করে ছবির URL টাইপ করুন',
		linkTab	: 'লিংক',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'ফ্লাশ প্রোপার্টি',
		propertiesTab	: 'Properties', // MISSING
		title		: 'ফ্ল্যাশ প্রোপার্টি',
		chkPlay		: 'অটো প্লে',
		chkLoop		: 'লূপ',
		chkMenu		: 'ফ্ল্যাশ মেনু এনাবল কর',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'স্কেল',
		scaleAll		: 'সব দেখাও',
		scaleNoBorder	: 'কোনো বর্ডার নেই',
		scaleFit		: 'নিখুঁত ফিট',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'এলাইন',
		alignLeft	: 'বামে',
		alignAbsBottom: 'Abs নীচে',
		alignAbsMiddle: 'Abs উপর',
		alignBaseline	: 'মূল রেখা',
		alignBottom	: 'নীচে',
		alignMiddle	: 'মধ্য',
		alignRight	: 'ডানে',
		alignTextTop	: 'টেক্সট উপর',
		alignTop	: 'উপর',
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
		bgcolor	: 'বেকগ্রাউন্ড রং',
		width	: 'প্রস্থ',
		height	: 'দৈর্ঘ্য',
		hSpace	: 'হরাইজন্টাল স্পেস',
		vSpace	: 'ভার্টিকেল স্পেস',
		validateSrc : 'অনুগ্রহ করে URL লিংক টাইপ করুন',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'বানান চেক',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'শব্দকোষে নেই',
		changeTo		: 'এতে বদলাও',
		btnIgnore		: 'ইগনোর কর',
		btnIgnoreAll	: 'সব ইগনোর কর',
		btnReplace		: 'বদলে দাও',
		btnReplaceAll	: 'সব বদলে দাও',
		btnUndo			: 'আন্ডু',
		noSuggestions	: '- কোন সাজেশন নেই -',
		progress		: 'বানান পরীক্ষা চলছে...',
		noMispell		: 'বানান পরীক্ষা শেষ: কোন ভুল বানান পাওয়া যায়নি',
		noChanges		: 'বানান পরীক্ষা শেষ: কোন শব্দ পরিবর্তন করা হয়নি',
		oneChange		: 'বানান পরীক্ষা শেষ: একটি মাত্র শব্দ পরিবর্তন করা হয়েছে',
		manyChanges		: 'বানান পরীক্ষা শেষ: %1 গুলো শব্দ বদলে গ্যাছে',
		ieSpellDownload	: 'বানান পরীক্ষক ইনস্টল করা নেই। আপনি কি এখনই এটা ডাউনলোড করতে চান?'
	},

	smiley :
	{
		toolbar	: 'স্মাইলী',
		title	: 'স্মাইলী যুক্ত কর'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'সাংখ্যিক লিস্টের লেবেল',
	bulletedlist : 'বুলেট লিস্ট লেবেল',
	indent : 'ইনডেন্ট বাড়াও',
	outdent : 'ইনডেন্ট কমাও',

	justify :
	{
		left : 'বা দিকে ঘেঁষা',
		center : 'মাঝ বরাবর ঘেষা',
		right : 'ডান দিকে ঘেঁষা',
		block : 'ব্লক জাস্টিফাই'
	},

	blockquote : 'Blockquote', // MISSING

	clipboard :
	{
		title		: 'পেস্ট',
		cutError	: 'আপনার ব্রাউজারের সুরক্ষা সেটিংস এডিটরকে অটোমেটিক কাট করার অনুমতি দেয়নি। দয়া করে এই কাজের জন্য কিবোর্ড ব্যবহার করুন (Ctrl+X)।',
		copyError	: 'আপনার ব্রাউজারের সুরক্ষা সেটিংস এডিটরকে অটোমেটিক কপি করার অনুমতি দেয়নি। দয়া করে এই কাজের জন্য কিবোর্ড ব্যবহার করুন (Ctrl+C)।',
		pasteMsg	: 'অনুগ্রহ করে নীচের বাক্সে কিবোর্ড ব্যবহার করে (<STRONG>Ctrl+V</STRONG>) পেস্ট করুন এবং <STRONG>OK</STRONG> চাপ দিন',
		securityMsg	: 'Because of your browser security settings, the editor is not able to access your clipboard data directly. You are required to paste it again in this window.' // MISSING
	},

	pastefromword :
	{
		toolbar : 'পেস্ট (শব্দ)',
		title : 'পেস্ট (শব্দ)',
		advice : 'অনুগ্রহ করে নীচের বাক্সে কিবোর্ড ব্যবহার করে (<STRONG>Ctrl+V</STRONG>) পেস্ট করুন এবং <STRONG>OK</STRONG> চাপ দিন',
		ignoreFontFace : 'ফন্ট ফেস ডেফিনেশন ইগনোর করুন',
		removeStyle : 'স্টাইল ডেফিনেশন সরিয়ে দিন'
	},

	pasteText :
	{
		button : 'সাদা টেক্সট হিসেবে পেস্ট কর',
		title : 'সাদা টেক্সট হিসেবে পেস্ট কর'
	},

	templates :
	{
		button : 'টেমপ্লেট',
		title : 'কনটেন্ট টেমপ্লেট',
		insertOption: 'Replace actual contents', // MISSING
		selectPromptMsg: 'অনুগ্রহ করে এডিটরে ওপেন করার জন্য টেমপ্লেট বাছাই করুন<br>(আসল কনটেন্ট হারিয়ে যাবে):',
		emptyListMsg : '(কোন টেমপ্লেট ডিফাইন করা নেই)'
	},

	showBlocks : 'Show Blocks', // MISSING

	stylesCombo :
	{
		label : 'স্টাইল',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'ফন্ট ফরমেট',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'ফন্ট ফরমেট',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'সাধারণ',
		tag_pre : 'ফর্মেটেড',
		tag_address : 'ঠিকানা',
		tag_h1 : 'শীর্ষক ১',
		tag_h2 : 'শীর্ষক ২',
		tag_h3 : 'শীর্ষক ৩',
		tag_h4 : 'শীর্ষক ৪',
		tag_h5 : 'শীর্ষক ৫',
		tag_h6 : 'শীর্ষক ৬',
		tag_div : 'শীর্ষক (DIV)'
	},

	font :
	{
		label : 'ফন্ট',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'ফন্ট',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'সাইজ',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'সাইজ',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'টেক্স্ট রং',
		bgColorTitle : 'বেকগ্রাউন্ড রং',
		auto : 'অটোমেটিক',
		more : 'আরও রং...'
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
