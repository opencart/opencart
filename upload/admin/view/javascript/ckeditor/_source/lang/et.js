/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Estonian language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['et'] =
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
	source			: 'Lähtekood',
	newPage			: 'Uus leht',
	save			: 'Salvesta',
	preview			: 'Eelvaade',
	cut				: 'Lõika',
	copy			: 'Kopeeri',
	paste			: 'Kleebi',
	print			: 'Prindi',
	underline		: 'Allajoonitud',
	bold			: 'Paks',
	italic			: 'Kursiiv',
	selectAll		: 'Vali kõik',
	removeFormat	: 'Eemalda vorming',
	strike			: 'Läbijoonitud',
	subscript		: 'Allindeks',
	superscript		: 'Ülaindeks',
	horizontalrule	: 'Sisesta horisontaaljoon',
	pagebreak		: 'Sisesta lehevahetuskoht',
	unlink			: 'Eemalda link',
	undo			: 'Võta tagasi',
	redo			: 'Korda toimingut',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Sirvi serverit',
		url				: 'URL',
		protocol		: 'Protokoll',
		upload			: 'Lae üles',
		uploadSubmit	: 'Saada serverissee',
		image			: 'Pilt',
		flash			: 'Flash',
		form			: 'Vorm',
		checkbox		: 'Märkeruut',
		radio		: 'Raadionupp',
		textField		: 'Tekstilahter',
		textarea		: 'Tekstiala',
		hiddenField		: 'Varjatud lahter',
		button			: 'Nupp',
		select	: 'Valiklahter',
		imageButton		: 'Piltnupp',
		notSet			: '<määramata>',
		id				: 'Id',
		name			: 'Nimi',
		langDir			: 'Keele suund',
		langDirLtr		: 'Vasakult paremale (LTR)',
		langDirRtl		: 'Paremalt vasakule (RTL)',
		langCode		: 'Keele kood',
		longDescr		: 'Pikk kirjeldus URL',
		cssClass		: 'Stiilistiku klassid',
		advisoryTitle	: 'Juhendav tiitel',
		cssStyle		: 'Laad',
		ok				: 'OK',
		cancel			: 'Loobu',
		generalTab		: 'General', // MISSING
		advancedTab		: 'Täpsemalt',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Sisesta erimärk',
		title		: 'Vali erimärk'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Sisesta link / Muuda linki',
		menu		: 'Muuda linki',
		title		: 'Link',
		info		: 'Lingi info',
		target		: 'Sihtkoht',
		upload		: 'Lae üles',
		advanced	: 'Täpsemalt',
		type		: 'Lingi tüüp',
		toAnchor	: 'Ankur sellel lehel',
		toEmail		: 'E-post',
		target		: 'Sihtkoht',
		targetNotSet	: '<määramata>',
		targetFrame	: '<raam>',
		targetPopup	: '<hüpikaken>',
		targetNew	: 'Uus aken (_blank)',
		targetTop	: 'Pealmine aken (_top)',
		targetSelf	: 'Sama aken (_self)',
		targetParent	: 'Esivanem aken (_parent)',
		targetFrameName	: 'Sihtmärk raami nimi',
		targetPopupName	: 'Hüpikakna nimi',
		popupFeatures	: 'Hüpikakna omadused',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Olekuriba',
		popupLocationBar	: 'Aadressiriba',
		popupToolbar	: 'Tööriistariba',
		popupMenuBar	: 'Menüüriba',
		popupFullScreen	: 'Täisekraan (IE)',
		popupScrollBars	: 'Kerimisribad',
		popupDependent	: 'Sõltuv (Netscape)',
		popupWidth		: 'Laius',
		popupLeft		: 'Vasak asukoht',
		popupHeight		: 'Kõrgus',
		popupTop		: 'Ülemine asukoht',
		id				: 'Id', // MISSING
		langDir			: 'Keele suund',
		langDirNotSet	: '<määramata>',
		langDirLTR		: 'Vasakult paremale (LTR)',
		langDirRTL		: 'Paremalt vasakule (RTL)',
		acccessKey		: 'Juurdepääsu võti',
		name			: 'Nimi',
		langCode		: 'Keele suund',
		tabIndex		: 'Tab indeks',
		advisoryTitle	: 'Juhendav tiitel',
		advisoryContentType	: 'Juhendava sisu tüüp',
		cssClasses		: 'Stiilistiku klassid',
		charset			: 'Lingitud ressurssi märgistik',
		styles			: 'Laad',
		selectAnchor	: 'Vali ankur',
		anchorName		: 'Ankru nime järgi',
		anchorId		: 'Elemendi id järgi',
		emailAddress	: 'E-posti aadress',
		emailSubject	: 'Sõnumi teema',
		emailBody		: 'Sõnumi tekst',
		noAnchors		: '(Selles dokumendis ei ole ankruid)',
		noUrl			: 'Palun kirjuta lingi URL',
		noEmail			: 'Palun kirjuta E-Posti aadress'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Sisesta ankur / Muuda ankrut',
		menu		: 'Ankru omadused',
		title		: 'Ankru omadused',
		name		: 'Ankru nimi',
		errorName	: 'Palun sisest ankru nimi'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Otsi ja asenda',
		find				: 'Otsi',
		replace				: 'Asenda',
		findWhat			: 'Leia mida:',
		replaceWith			: 'Asenda millega:',
		notFoundMsg			: 'Valitud teksti ei leitud.',
		matchCase			: 'Erista suur- ja väiketähti',
		matchWord			: 'Otsi terviklike sõnu',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Asenda kõik',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Tabel',
		title		: 'Tabeli atribuudid',
		menu		: 'Tabeli atribuudid',
		deleteTable	: 'Kustuta tabel',
		rows		: 'Read',
		columns		: 'Veerud',
		border		: 'Joone suurus',
		align		: 'Joondus',
		alignNotSet	: '<Määramata>',
		alignLeft	: 'Vasak',
		alignCenter	: 'Kesk',
		alignRight	: 'Parem',
		width		: 'Laius',
		widthPx		: 'pikslit',
		widthPc		: 'protsenti',
		height		: 'Kõrgus',
		cellSpace	: 'Lahtri vahe',
		cellPad		: 'Lahtri täidis',
		caption		: 'Tabeli tiitel',
		summary		: 'Kokkuvõte',
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
			menu			: 'Lahter',
			insertBefore	: 'Sisesta lahter enne',
			insertAfter		: 'Sisesta lahter peale',
			deleteCell		: 'Eemalda lahtrid',
			merge			: 'Ühenda lahtrid',
			mergeRight		: 'Ühenda paremale',
			mergeDown		: 'Ühenda alla',
			splitHorizontal	: 'Poolita lahter horisontaalselt',
			splitVertical	: 'Poolita lahter vertikaalselt',
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
			menu			: 'Rida',
			insertBefore	: 'Sisesta rida enne',
			insertAfter		: 'Sisesta rida peale',
			deleteRow		: 'Eemalda read'
		},

		column :
		{
			menu			: 'Veerg',
			insertBefore	: 'Sisesta veerg enne',
			insertAfter		: 'Sisesta veerg peale',
			deleteColumn	: 'Eemalda veerud'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Nupu omadused',
		text		: 'Tekst (väärtus)',
		type		: 'Tüüp',
		typeBtn		: 'Nupp',
		typeSbm		: 'Saada',
		typeRst		: 'Lähtesta'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Märkeruudu omadused',
		radioTitle	: 'Raadionupu omadused',
		value		: 'Väärtus',
		selected	: 'Valitud'
	},

	// Form Dialog.
	form :
	{
		title		: 'Vormi omadused',
		menu		: 'Vormi omadused',
		action		: 'Toiming',
		method		: 'Meetod',
		encoding	: 'Encoding', // MISSING
		target		: 'Sihtkoht',
		targetNotSet	: '<määramata>',
		targetNew	: 'Uus aken (_blank)',
		targetTop	: 'Pealmine aken (_top)',
		targetSelf	: 'Sama aken (_self)',
		targetParent	: 'Esivanem aken (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Valiklahtri omadused',
		selectInfo	: 'Info',
		opAvail		: 'Võimalikud valikud',
		value		: 'Väärtus',
		size		: 'Suurus',
		lines		: 'ridu',
		chkMulti	: 'Võimalda mitu valikut',
		opText		: 'Tekst',
		opValue		: 'Väärtus',
		btnAdd		: 'Lisa',
		btnModify	: 'Muuda',
		btnUp		: 'Üles',
		btnDown		: 'Alla',
		btnSetValue : 'Sea valitud olekuna',
		btnDelete	: 'Kustuta'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Tekstiala omadused',
		cols		: 'Veerge',
		rows		: 'Ridu'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Tekstilahtri omadused',
		name		: 'Nimi',
		value		: 'Väärtus',
		charWidth	: 'Laius (tähemärkides)',
		maxChars	: 'Maksimaalselt tähemärke',
		type		: 'Tüüp',
		typeText	: 'Tekst',
		typePass	: 'Parool'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Varjatud lahtri omadused',
		name	: 'Nimi',
		value	: 'Väärtus'
	},

	// Image Dialog.
	image :
	{
		title		: 'Pildi atribuudid',
		titleButton	: 'Piltnupu omadused',
		menu		: 'Pildi atribuudid',
		infoTab	: 'Pildi info',
		btnUpload	: 'Saada serverissee',
		url		: 'URL',
		upload	: 'Lae üles',
		alt		: 'Alternatiivne tekst',
		width		: 'Laius',
		height	: 'Kõrgus',
		lockRatio	: 'Lukusta kuvasuhe',
		resetSize	: 'Lähtesta suurus',
		border	: 'Joon',
		hSpace	: 'H. vaheruum',
		vSpace	: 'V. vaheruum',
		align		: 'Joondus',
		alignLeft	: 'Vasak',
		alignAbsBottom: 'Abs alla',
		alignAbsMiddle: 'Abs keskele',
		alignBaseline	: 'Baasjoonele',
		alignBottom	: 'Alla',
		alignMiddle	: 'Keskele',
		alignRight	: 'Paremale',
		alignTextTop	: 'Tekstit üles',
		alignTop	: 'Üles',
		preview	: 'Eelvaade',
		alertUrl	: 'Palun kirjuta pildi URL',
		linkTab	: 'Link',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Flash omadused',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Flash omadused',
		chkPlay		: 'Automaatne start ',
		chkLoop		: 'Korduv',
		chkMenu		: 'Võimalda flash menüü',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Mastaap',
		scaleAll		: 'Näita kõike',
		scaleNoBorder	: 'Äärist ei ole',
		scaleFit		: 'Täpne sobivus',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Joondus',
		alignLeft	: 'Vasak',
		alignAbsBottom: 'Abs alla',
		alignAbsMiddle: 'Abs keskele',
		alignBaseline	: 'Baasjoonele',
		alignBottom	: 'Alla',
		alignMiddle	: 'Keskele',
		alignRight	: 'Paremale',
		alignTextTop	: 'Tekstit üles',
		alignTop	: 'Üles',
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
		bgcolor	: 'Tausta värv',
		width	: 'Laius',
		height	: 'Kõrgus',
		hSpace	: 'H. vaheruum',
		vSpace	: 'V. vaheruum',
		validateSrc : 'Palun kirjuta lingi URL',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Kontrolli õigekirja',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Puudub sõnastikust',
		changeTo		: 'Muuda',
		btnIgnore		: 'Ignoreeri',
		btnIgnoreAll	: 'Ignoreeri kõiki',
		btnReplace		: 'Asenda',
		btnReplaceAll	: 'Asenda kõik',
		btnUndo			: 'Võta tagasi',
		noSuggestions	: '- Soovitused puuduvad -',
		progress		: 'Toimub õigekirja kontroll...',
		noMispell		: 'Õigekirja kontroll sooritatud: õigekirjuvigu ei leitud',
		noChanges		: 'Õigekirja kontroll sooritatud: ühtegi sõna ei muudetud',
		oneChange		: 'Õigekirja kontroll sooritatud: üks sõna muudeti',
		manyChanges		: 'Õigekirja kontroll sooritatud: %1 sõna muudetud',
		ieSpellDownload	: 'Õigekirja kontrollija ei ole installeeritud. Soovid sa selle alla laadida?'
	},

	smiley :
	{
		toolbar	: 'Emotikon',
		title	: 'Sisesta emotikon'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Nummerdatud loetelu',
	bulletedlist : 'Punktiseeritud loetelu',
	indent : 'Suurenda taanet',
	outdent : 'Vähenda taanet',

	justify :
	{
		left : 'Vasakjoondus',
		center : 'Keskjoondus',
		right : 'Paremjoondus',
		block : 'Rööpjoondus'
	},

	blockquote : 'Blokktsitaat',

	clipboard :
	{
		title		: 'Kleebi',
		cutError	: 'Sinu veebisirvija turvaseaded ei luba redaktoril automaatselt lõigata. Palun kasutage selleks klaviatuuri klahvikombinatsiooni (Ctrl+X).',
		copyError	: 'Sinu veebisirvija turvaseaded ei luba redaktoril automaatselt kopeerida. Palun kasutage selleks klaviatuuri klahvikombinatsiooni (Ctrl+C).',
		pasteMsg	: 'Palun kleebi järgnevasse kasti kasutades klaviatuuri klahvikombinatsiooni (<STRONG>Ctrl+V</STRONG>) ja vajuta seejärel <STRONG>OK</STRONG>.',
		securityMsg	: 'Sinu veebisirvija turvaseadete tõttu, ei oma redaktor otsest ligipääsu lõikelaua andmetele. Sa pead kleepima need uuesti siia aknasse.'
	},

	pastefromword :
	{
		toolbar : 'Kleebi Wordist',
		title : 'Kleebi Wordist',
		advice : 'Palun kleebi järgnevasse kasti kasutades klaviatuuri klahvikombinatsiooni (<STRONG>Ctrl+V</STRONG>) ja vajuta seejärel <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Ignoreeri kirja definitsioone',
		removeStyle : 'Eemalda stiilide definitsioonid'
	},

	pasteText :
	{
		button : 'Kleebi tavalise tekstina',
		title : 'Kleebi tavalise tekstina'
	},

	templates :
	{
		button : 'Šabloon',
		title : 'Sisu šabloonid',
		insertOption: 'Asenda tegelik sisu',
		selectPromptMsg: 'Palun vali šabloon, et avada see redaktoris<br />(praegune sisu läheb kaotsi):',
		emptyListMsg : '(Ühtegi šablooni ei ole defineeritud)'
	},

	showBlocks : 'Näita blokke',

	stylesCombo :
	{
		label : 'Laad',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'Vorming',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'Vorming',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'Tavaline',
		tag_pre : 'Vormindatud',
		tag_address : 'Aadress',
		tag_h1 : 'Pealkiri 1',
		tag_h2 : 'Pealkiri 2',
		tag_h3 : 'Pealkiri 3',
		tag_h4 : 'Pealkiri 4',
		tag_h5 : 'Pealkiri 5',
		tag_h6 : 'Pealkiri 6',
		tag_div : 'Tavaline (DIV)'
	},

	font :
	{
		label : 'Kiri',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'Kiri',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'Suurus',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Suurus',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Teksti värv',
		bgColorTitle : 'Tausta värv',
		auto : 'Automaatne',
		more : 'Rohkem värve...'
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
