/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Hebrew language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['he'] =
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
	editorTitle		: 'עורך טקסט עשיר, %1',

	// Toolbar buttons without dialogs.
	source			: 'מקור',
	newPage			: 'דף חדש',
	save			: 'שמירה',
	preview			: 'תצוגה מקדימה',
	cut				: 'גזירה',
	copy			: 'העתקה',
	paste			: 'הדבקה',
	print			: 'הדפסה',
	underline		: 'קו תחתון',
	bold			: 'מודגש',
	italic			: 'נטוי',
	selectAll		: 'בחירת הכל',
	removeFormat	: 'הסרת העיצוב',
	strike			: 'כתיב מחוק',
	subscript		: 'כתיב תחתון',
	superscript		: 'כתיב עליון',
	horizontalrule	: 'הוספת קו אופקי',
	pagebreak		: 'הוסף שבירת דף',
	unlink			: 'הסרת הקישור',
	undo			: 'ביטול צעד אחרון',
	redo			: 'חזרה על צעד אחרון',

	// Common messages and labels.
	common :
	{
		browseServer	: 'סייר השרת',
		url				: 'כתובת (URL)',
		protocol		: 'פרוטוקול',
		upload			: 'העלאה',
		uploadSubmit	: 'שליחה לשרת',
		image			: 'תמונה',
		flash			: 'פלאש',
		form			: 'טופס',
		checkbox		: 'תיבת סימון',
		radio		: 'לחצן אפשרויות',
		textField		: 'שדה טקסט',
		textarea		: 'איזור טקסט',
		hiddenField		: 'שדה חבוי',
		button			: 'כפתור',
		select	: 'שדה בחירה',
		imageButton		: 'כפתור תמונה',
		notSet			: '<לא נקבע>',
		id				: 'זיהוי (Id)',
		name			: 'שם',
		langDir			: 'כיוון שפה',
		langDirLtr		: 'שמאל לימין (LTR)',
		langDirRtl		: 'ימין לשמאל (RTL)',
		langCode		: 'קוד שפה',
		longDescr		: 'קישור לתיאור מפורט',
		cssClass		: 'גיליונות עיצוב קבוצות',
		advisoryTitle	: 'כותרת מוצעת',
		cssStyle		: 'סגנון',
		ok				: 'אישור',
		cancel			: 'ביטול',
		generalTab		: 'כללי',
		advancedTab		: 'אפשרויות מתקדמות',
		validateNumberFailed	: 'הערך חייב להיות מספר.',
		confirmNewPage	: 'כל השינויים שלא נשמרו יאבדו. האם להעלות דף חדש?',
		confirmCancel	: 'חלק מהאפשרויות שונו, האם לסגור את הדיאלוג. ?',

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, לא זמין</span>'
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'הוספת תו מיוחד',
		title		: 'בחירת תו מיוחד'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'הוספת/עריכת קישור',
		menu		: 'עריכת קישור',
		title		: 'קישור',
		info		: 'מידע על הקישור',
		target		: 'מטרה',
		upload		: 'העלאה',
		advanced	: 'אפשרויות מתקדמות',
		type		: 'סוג קישור',
		toAnchor	: 'עוגן בעמוד זה',
		toEmail		: 'דוא\'\'ל',
		target		: 'מטרה',
		targetNotSet	: '<לא נקבע>',
		targetFrame	: '<מסגרת>',
		targetPopup	: '<חלון קופץ>',
		targetNew	: 'חלון חדש (_blank)',
		targetTop	: 'חלון ראשי (_top)',
		targetSelf	: 'באותו החלון (_self)',
		targetParent	: 'חלון האב (_parent)',
		targetFrameName	: 'שם מסגרת היעד',
		targetPopupName	: 'שם החלון הקופץ',
		popupFeatures	: 'תכונות החלון הקופץ',
		popupResizable	: 'שינוי גודל',
		popupStatusBar	: 'סרגל חיווי',
		popupLocationBar	: 'סרגל כתובת',
		popupToolbar	: 'סרגל הכלים',
		popupMenuBar	: 'סרגל תפריט',
		popupFullScreen	: 'מסך מלא (IE)',
		popupScrollBars	: 'ניתן לגלילה',
		popupDependent	: 'תלוי (Netscape)',
		popupWidth		: 'רוחב',
		popupLeft		: 'מיקום צד שמאל',
		popupHeight		: 'גובה',
		popupTop		: 'מיקום צד עליון',
		id				: 'זיהוי (Id)',
		langDir			: 'כיוון שפה',
		langDirNotSet	: '<לא נקבע>',
		langDirLTR		: 'שמאל לימין (LTR)',
		langDirRTL		: 'ימין לשמאל (RTL)',
		acccessKey		: 'מקש גישה',
		name			: 'שם',
		langCode		: 'כיוון שפה',
		tabIndex		: 'מספר טאב',
		advisoryTitle	: 'כותרת מוצעת',
		advisoryContentType	: 'Content Type מוצע',
		cssClasses		: 'גיליונות עיצוב קבוצות',
		charset			: 'קידוד המשאב המקושר',
		styles			: 'סגנון',
		selectAnchor	: 'בחירת עוגן',
		anchorName		: 'עפ\'\'י שם העוגן',
		anchorId		: 'עפ\'\'י זיהוי (Id) הרכיב',
		emailAddress	: 'כתובת הדוא\'\'ל',
		emailSubject	: 'נושא ההודעה',
		emailBody		: 'גוף ההודעה',
		noAnchors		: '(אין עוגנים זמינים בדף)',
		noUrl			: 'נא להקליד את כתובת הקישור (URL)',
		noEmail			: 'נא להקליד את כתובת הדוא\'\'ל'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'הוספת/עריכת נקודת עיגון',
		menu		: 'מאפייני נקודת עיגון',
		title		: 'מאפייני נקודת עיגון',
		name		: 'שם לנקודת עיגון',
		errorName	: 'אנא הזן שם לנקודת עיגון'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'חפש והחלף',
		find				: 'חיפוש',
		replace				: 'החלפה',
		findWhat			: 'חיפוש מחרוזת:',
		replaceWith			: 'החלפה במחרוזת:',
		notFoundMsg			: 'הטקסט המבוקש לא נמצא.',
		matchCase			: 'התאמת סוג אותיות (Case)',
		matchWord			: 'התאמה למילה המלאה',
		matchCyclic			: 'התאמה מחזורית',
		replaceAll			: 'החלפה בכל העמוד',
		replaceSuccessMsg	: '%1 טקסטים הוחלפו.'
	},

	// Table Dialog
	table :
	{
		toolbar		: 'טבלה',
		title		: 'תכונות טבלה',
		menu		: 'תכונות טבלה',
		deleteTable	: 'מחק טבלה',
		rows		: 'שורות',
		columns		: 'עמודות',
		border		: 'גודל מסגרת',
		align		: 'יישור',
		alignNotSet	: '<לא נקבע>',
		alignLeft	: 'שמאל',
		alignCenter	: 'מרכז',
		alignRight	: 'ימין',
		width		: 'רוחב',
		widthPx		: 'פיקסלים',
		widthPc		: 'אחוז',
		height		: 'גובה',
		cellSpace	: 'מרווח תא',
		cellPad		: 'ריפוד תא',
		caption		: 'כיתוב',
		summary		: 'סיכום',
		headers		: 'כותרות',
		headersNone		: 'אין',
		headersColumn	: 'עמודה ראשונה',
		headersRow		: 'שורה ראשונה',
		headersBoth		: 'שניהם',
		invalidRows		: 'מספר השורות חייב להיות מספר גדול מ 0.',
		invalidCols		: 'מספר העמודות חייב להיות מספר גדול מ 0.',
		invalidBorder	: 'גודל מסגרת חייב להיות מספר.',
		invalidWidth	: 'רוחה טבלה חייב להיות רוחב.',
		invalidHeight	: 'גובה טבלה חייב להיות מספר.',
		invalidCellSpacing	: 'ריווח תאים חייב להיות מספר.',
		invalidCellPadding	: 'ריפוד תאים חייב להיות מספר.',

		cell :
		{
			menu			: 'תא',
			insertBefore	: 'הוסף תא אחרי',
			insertAfter		: 'הוסף תא אחרי',
			deleteCell		: 'מחיקת תאים',
			merge			: 'מיזוג תאים',
			mergeRight		: 'מזג ימינה',
			mergeDown		: 'מזג למטה',
			splitHorizontal	: 'פצל תא אופקית',
			splitVertical	: 'פצל תא אנכית',
			title			: 'תכונות התא',
			cellType		: 'סוג תא',
			rowSpan			: 'מתיחת שורות',
			colSpan			: 'מתיחת תאים',
			wordWrap		: 'מניעת גלישת שורות',
			hAlign			: 'יישור אופקי',
			vAlign			: 'יישור אנכי',
			alignTop		: 'למעלה',
			alignMiddle		: 'מרכז',
			alignBottom		: 'למטה',
			alignBaseline	: 'שורת בסיס',
			bgColor			: 'צבע רקע',
			borderColor		: 'צבע מסגרת',
			data			: 'מידע',
			header			: 'כותרת',
			yes				: 'כן',
			no				: 'לא',
			invalidWidth	: 'רוחב תא חייב להיות מספר.',
			invalidHeight	: 'גובה תא חייב להיות מספר.',
			invalidRowSpan	: 'מתיחת שורות חייב להיות מספר שלם.',
			invalidColSpan	: 'מתיחת עמודות חייב להיות מספר שלם.',
			chooseColor : 'בחר'
		},

		row :
		{
			menu			: 'שורה',
			insertBefore	: 'הוסף שורה לפני',
			insertAfter		: 'הוסף שורה אחרי',
			deleteRow		: 'מחיקת שורות'
		},

		column :
		{
			menu			: 'עמודה',
			insertBefore	: 'הוסף עמודה לפני',
			insertAfter		: 'הוסף עמודה אחרי',
			deleteColumn	: 'מחיקת עמודות'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'מאפייני כפתור',
		text		: 'טקסט (ערך)',
		type		: 'סוג',
		typeBtn		: 'כפתור',
		typeSbm		: 'שלח',
		typeRst		: 'אפס'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'מאפייני תיבת סימון',
		radioTitle	: 'מאפייני לחצן אפשרויות',
		value		: 'ערך',
		selected	: 'בחור'
	},

	// Form Dialog.
	form :
	{
		title		: 'מאפיני טופס',
		menu		: 'מאפיני טופס',
		action		: 'שלח אל',
		method		: 'סוג שליחה',
		encoding	: 'קידוד',
		target		: 'מטרה',
		targetNotSet	: '<לא נקבע>',
		targetNew	: 'חלון חדש (_blank)',
		targetTop	: 'חלון ראשי (_top)',
		targetSelf	: 'באותו החלון (_self)',
		targetParent	: 'חלון האב (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'מאפייני שדה בחירה',
		selectInfo	: 'מידע',
		opAvail		: 'אפשרויות זמינות',
		value		: 'ערך',
		size		: 'גודל',
		lines		: 'שורות',
		chkMulti	: 'אפשר בחירות מרובות',
		opText		: 'טקסט',
		opValue		: 'ערך',
		btnAdd		: 'הוסף',
		btnModify	: 'שנה',
		btnUp		: 'למעלה',
		btnDown		: 'למטה',
		btnSetValue : 'קבע כברירת מחדל',
		btnDelete	: 'מחק'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'מאפיני איזור טקסט',
		cols		: 'עמודות',
		rows		: 'שורות'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'מאפייני שדה טקסט',
		name		: 'שם',
		value		: 'ערך',
		charWidth	: 'רוחב באותיות',
		maxChars	: 'מקסימות אותיות',
		type		: 'סוג',
		typeText	: 'טקסט',
		typePass	: 'סיסמה'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'מאפיני שדה חבוי',
		name	: 'שם',
		value	: 'ערך'
	},

	// Image Dialog.
	image :
	{
		title		: 'תכונות התמונה',
		titleButton	: 'מאפיני כפתור תמונה',
		menu		: 'תכונות התמונה',
		infoTab	: 'מידע על התמונה',
		btnUpload	: 'שליחה לשרת',
		url		: 'כתובת (URL)',
		upload	: 'העלאה',
		alt		: 'טקסט חלופי',
		width		: 'רוחב',
		height	: 'גובה',
		lockRatio	: 'נעילת היחס',
		resetSize	: 'איפוס הגודל',
		border	: 'מסגרת',
		hSpace	: 'מרווח אופקי',
		vSpace	: 'מרווח אנכי',
		align		: 'יישור',
		alignLeft	: 'לשמאל',
		alignAbsBottom: 'לתחתית האבסולוטית',
		alignAbsMiddle: 'מרכוז אבסולוטי',
		alignBaseline	: 'לקו התחתית',
		alignBottom	: 'לתחתית',
		alignMiddle	: 'לאמצע',
		alignRight	: 'לימין',
		alignTextTop	: 'לראש הטקסט',
		alignTop	: 'למעלה',
		preview	: 'תצוגה מקדימה',
		alertUrl	: 'נא להקליד את כתובת התמונה',
		linkTab	: 'קישור',
		button2Img	: 'האם להפוך את תמונת כפתור לתמונה פשוטה?',
		img2Button	: 'האם להפוך את התמונה לכפתור תמונה?',
		urlMissing : 'כתובת התמונה חסרה.'
	},

	// Flash Dialog
	flash :
	{
		properties		: 'מאפייני פלאש',
		propertiesTab	: 'מאפיינים',
		title		: 'מאפיני פלאש',
		chkPlay		: 'נגן אוטומטי',
		chkLoop		: 'לולאה',
		chkMenu		: 'אפשר תפריט פלאש',
		chkFull		: 'אפשר חלון מלא',
 		scale		: 'גודל',
		scaleAll		: 'הצג הכל',
		scaleNoBorder	: 'ללא גבולות',
		scaleFit		: 'התאמה מושלמת',
		access			: 'גישת סקריפט',
		accessAlways	: 'תמיד',
		accessSameDomain	: 'דומיין זהה',
		accessNever	: 'אף פעם',
		align		: 'יישור',
		alignLeft	: 'לשמאל',
		alignAbsBottom: 'לתחתית האבסולוטית',
		alignAbsMiddle: 'מרכוז אבסולוטי',
		alignBaseline	: 'לקו התחתית',
		alignBottom	: 'לתחתית',
		alignMiddle	: 'לאמצע',
		alignRight	: 'לימין',
		alignTextTop	: 'לראש הטקסט',
		alignTop	: 'למעלה',
		quality		: 'איכות',
		qualityBest		 : 'מעולה',
		qualityHigh		 : 'גבוהה',
		qualityAutoHigh	 : 'אוטומטית גבוהה',
		qualityMedium	 : 'ממוצעת',
		qualityAutoLow	 : 'אוטומטית נמוך',
		qualityLow		 : 'נמוך',
		windowModeWindow	 : 'חלון',
		windowModeOpaque	 : 'אטום',
		windowModeTransparent	 : 'שקוף',
		windowMode	: 'מצב חלון',
		flashvars	: 'משתנים לפלאש',
		bgcolor	: 'צבע רקע',
		width	: 'רוחב',
		height	: 'גובה',
		hSpace	: 'מרווח אופקי',
		vSpace	: 'מרווח אנכי',
		validateSrc : 'נא להקליד את כתובת הקישור (URL)',
		validateWidth : 'רוחב חייב להיות מספר.',
		validateHeight : 'גובהה חייב להיות מספר.',
		validateHSpace : 'ריווח אופקי חייב להיות מספר.',
		validateVSpace : 'ריווח אנחי חייב להיות מספר.'
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'בדיקת איות',
		title			: 'בדיקת איות',
		notAvailable	: 'מצטערים לא נמצא שירות זמין.',
		errorLoading	: 'שגיעה בהעלאת שירת אפליקציה: %s.',
		notInDic		: 'לא נמצא במילון',
		changeTo		: 'שנה ל',
		btnIgnore		: 'התעלם',
		btnIgnoreAll	: 'התעלם מהכל',
		btnReplace		: 'החלף',
		btnReplaceAll	: 'החלף הכל',
		btnUndo			: 'החזר',
		noSuggestions	: '- אין הצעות -',
		progress		: 'בדיקות איות בתהליך ....',
		noMispell		: 'בדיקות איות הסתיימה: לא נמצאו שגיעות כתיב',
		noChanges		: 'בדיקות איות הסתיימה: לא שונתה אף מילה',
		oneChange		: 'בדיקות איות הסתיימה: שונתה מילה אחת',
		manyChanges		: 'בדיקות איות הסתיימה: %1 מילים שונו',
		ieSpellDownload	: 'בודק האיות לא מותקן, האם אתה מעוניין להוריד?'
	},

	smiley :
	{
		toolbar	: 'סמיילי',
		title	: 'הוספת סמיילי'
	},

	elementsPath :
	{
		eleTitle : '%1 אלמנט'
	},

	numberedlist : 'רשימה ממוספרת',
	bulletedlist : 'רשימת נקודות',
	indent : 'הגדלת אינדנטציה',
	outdent : 'הקטנת אינדנטציה',

	justify :
	{
		left : 'יישור לשמאל',
		center : 'מרכוז',
		right : 'יישור לימין',
		block : 'יישור לשוליים'
	},

	blockquote : 'בלוק ציטוט',

	clipboard :
	{
		title		: 'הדבקה',
		cutError	: 'הגדרות האבטחה בדפדפן שלך לא מאפשרות לעורך לבצע פעולות גזירה  אוטומטיות. יש להשתמש במקלדת לשם כך (Ctrl+X).',
		copyError	: 'הגדרות האבטחה בדפדפן שלך לא מאפשרות לעורך לבצע פעולות העתקה אוטומטיות. יש להשתמש במקלדת לשם כך (Ctrl+C).',
		pasteMsg	: 'אנא הדבק בתוך הקופסה באמצעות  (<STRONG>Ctrl+V</STRONG>) ולחץ על  <STRONG>אישור</STRONG>.',
		securityMsg	: 'עקב הגדרות אבטחה בדפדפן, לא ניתן לגשת אל לוח הגזירים (clipboard) בצורה ישירה.אנא בצע הדבק שוב בחלון זה.'
	},

	pastefromword :
	{
		toolbar : 'הדבקה מ-וורד',
		title : 'הדבקה מ-וורד',
		advice : 'אנא הדבק בתוך הקופסה באמצעות  (<STRONG>Ctrl+V</STRONG>) ולחץ על  <STRONG>אישור</STRONG>.',
		ignoreFontFace : 'התעלם מהגדרות סוג פונט',
		removeStyle : 'הסר הגדרות סגנון'
	},

	pasteText :
	{
		button : 'הדבקה כטקסט פשוט',
		title : 'הדבקה כטקסט פשוט'
	},

	templates :
	{
		button : 'תבניות',
		title : 'תביות תוכן',
		insertOption: 'החלפת תוכן ממשי',
		selectPromptMsg: 'אנא בחר תבנית לפתיחה בעורך <BR>התוכן המקורי ימחק:',
		emptyListMsg : '(לא הוגדרו תבניות)'
	},

	showBlocks : 'הצג בלוקים',

	stylesCombo :
	{
		label : 'סגנון',
		voiceLabel : 'סגנונות',
		panelVoiceLabel : 'בחר סגנון',
		panelTitle1 : 'סיגנונות בלוק',
		panelTitle2 : 'סגנונות רצף',
		panelTitle3 : 'סגנונות אובייקט'
	},

	format :
	{
		label : 'עיצוב',
		voiceLabel : 'עיצוב',
		panelTitle : 'עיצוב',
		panelVoiceLabel : 'בחר פיסקת עיצוב',

		tag_p : 'נורמלי',
		tag_pre : 'קוד',
		tag_address : 'כתובת',
		tag_h1 : 'כותרת',
		tag_h2 : 'כותרת 2',
		tag_h3 : 'כותרת 3',
		tag_h4 : 'כותרת 4',
		tag_h5 : 'כותרת 5',
		tag_h6 : 'כותרת 6',
		tag_div : 'נורמלי (DIV)'
	},

	font :
	{
		label : 'גופן',
		voiceLabel : 'גופן',
		panelTitle : 'גופן',
		panelVoiceLabel : 'בחר גופן'
	},

	fontSize :
	{
		label : 'גודל',
		voiceLabel : 'גודל גופן',
		panelTitle : 'גודל',
		panelVoiceLabel : 'בחר גודל גופן'
	},

	colorButton :
	{
		textColorTitle : 'צבע טקסט',
		bgColorTitle : 'צבע רקע',
		auto : 'אוטומטי',
		more : 'צבעים נוספים...'
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
		title : 'בדיקת איות בזמן כתיבה',
		enable : 'אפשר SCAYT',
		disable : 'בטל SCAYT',
		about : 'אודות SCAYT',
		toggle : 'שינוי SCAYT',
		options : 'אפשרויות',
		langs : 'שפות',
		moreSuggestions : 'עוד הצעות',
		ignore : 'התעלם',
		ignoreAll : 'התעלם מהכל',
		addWord : 'הודף מילה',
		emptyDic : 'אסור לשם המילון להיות ריק.',
		optionsTab : 'אפשרויות',
		languagesTab : 'שפות',
		dictionariesTab : 'מילון',
		aboutTab : 'אודות'
	},

	about :
	{
		title : 'אודות CKEditor',
		dlgTitle : 'אודות CKEditor',
		moreInfo : 'לרישוי אנה בקרו באתר שלנו:',
		copy : 'Copyright &copy; $1. כל הזכויות שמורות.'
	},

	maximize : 'להגדיל למקסימום',
	minimize : 'הקטן למינימום',

	fakeobjects :
	{
		anchor : 'עוגן',
		flash : 'אנימצית פלאש',
		div : 'שבירת דף',
		unknown : 'אובייקט לא ידוע'
	},

	resize : 'גרור בכדי לשנות גודל',

	colordialog :
	{
		title : 'בחר צבע',
		highlight : 'סמן',
		selected : 'נבחר',
		clear : 'נקה'
	}
};
