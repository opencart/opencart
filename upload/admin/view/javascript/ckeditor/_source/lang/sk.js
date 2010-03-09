/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Slovak language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['sk'] =
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
	source			: 'Zdroj',
	newPage			: 'Nová stránka',
	save			: 'Uložiť',
	preview			: 'Náhľad',
	cut				: 'Vystrihnúť',
	copy			: 'Kopírovať',
	paste			: 'Vložiť',
	print			: 'Tlač',
	underline		: 'Podčiarknuté',
	bold			: 'Tučné',
	italic			: 'Kurzíva',
	selectAll		: 'Vybrať všetko',
	removeFormat	: 'Odstrániť formátovanie',
	strike			: 'Prečiarknuté',
	subscript		: 'Dolný index',
	superscript		: 'Horný index',
	horizontalrule	: 'Vložiť vodorovnú čiaru',
	pagebreak		: 'Vložiť oddeľovač stránky',
	unlink			: 'Odstrániť odkaz',
	undo			: 'Späť',
	redo			: 'Znovu',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Prechádzať server',
		url				: 'URL',
		protocol		: 'Protokol',
		upload			: 'Odoslať',
		uploadSubmit	: 'Odoslať na server',
		image			: 'Obrázok',
		flash			: 'Flash',
		form			: 'Formulár',
		checkbox		: 'Zaškrtávacie políčko',
		radio		: 'Prepínač',
		textField		: 'Textové pole',
		textarea		: 'Textová oblasť',
		hiddenField		: 'Skryté pole',
		button			: 'Tlačidlo',
		select	: 'Rozbaľovací zoznam',
		imageButton		: 'Obrázkové tlačidlo',
		notSet			: '<nenastavené>',
		id				: 'Id',
		name			: 'Meno',
		langDir			: 'Orientácia jazyka',
		langDirLtr		: 'Zľava doprava (LTR)',
		langDirRtl		: 'Sprava doľava (RTL)',
		langCode		: 'Kód jazyka',
		longDescr		: 'Dlhý popis URL',
		cssClass		: 'Trieda štýlu',
		advisoryTitle	: 'Pomocný titulok',
		cssStyle		: 'Štýl',
		ok				: 'OK',
		cancel			: 'Zrušiť',
		generalTab		: 'Hlavné',
		advancedTab		: 'Rozšírené',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Vložiť špeciálne znaky',
		title		: 'Výber špeciálneho znaku'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Vložiť/zmeniť odkaz',
		menu		: 'Zmeniť odkaz',
		title		: 'Odkaz',
		info		: 'Informácie o odkaze',
		target		: 'Cieľ',
		upload		: 'Odoslať',
		advanced	: 'Rozšírené',
		type		: 'Typ odkazu',
		toAnchor	: 'Kotva v tejto stránke',
		toEmail		: 'E-Mail',
		target		: 'Cieľ',
		targetNotSet	: '<nenastavené>',
		targetFrame	: '<rámec>',
		targetPopup	: '<vyskakovacie okno>',
		targetNew	: 'Nové okno (_blank)',
		targetTop	: 'Hlavné okno (_top)',
		targetSelf	: 'Rovnaké okno (_self)',
		targetParent	: 'Rodičovské okno (_parent)',
		targetFrameName	: 'Meno rámu cieľa',
		targetPopupName	: 'Názov vyskakovacieho okna',
		popupFeatures	: 'Vlastnosti vyskakovacieho okna',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Stavový riadok',
		popupLocationBar	: 'Panel umiestnenia',
		popupToolbar	: 'Panel nástrojov',
		popupMenuBar	: 'Panel ponuky',
		popupFullScreen	: 'Celá obrazovka (IE)',
		popupScrollBars	: 'Posuvníky',
		popupDependent	: 'Závislosť (Netscape)',
		popupWidth		: 'Šírka',
		popupLeft		: 'Ľavý okraj',
		popupHeight		: 'Výška',
		popupTop		: 'Horný okraj',
		id				: 'Id', // MISSING
		langDir			: 'Orientácia jazyka',
		langDirNotSet	: '<nenastavené>',
		langDirLTR		: 'Zľava doprava (LTR)',
		langDirRTL		: 'Sprava doľava (RTL)',
		acccessKey		: 'Prístupový kľúč',
		name			: 'Meno',
		langCode		: 'Orientácia jazyka',
		tabIndex		: 'Poradie prvku',
		advisoryTitle	: 'Pomocný titulok',
		advisoryContentType	: 'Pomocný typ obsahu',
		cssClasses		: 'Trieda štýlu',
		charset			: 'Priradená znaková sada',
		styles			: 'Štýl',
		selectAnchor	: 'Vybrať kotvu',
		anchorName		: 'Podľa mena kotvy',
		anchorId		: 'Podľa Id objektu',
		emailAddress	: 'E-Mailová adresa',
		emailSubject	: 'Predmet správy',
		emailBody		: 'Telo správy',
		noAnchors		: '(V stránke nie je definovaná žiadna kotva)',
		noUrl			: 'Zadajte prosím URL odkazu',
		noEmail			: 'Zadajte prosím e-mailovú adresu'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Vložiť/zmeniť kotvu',
		menu		: 'Vlastnosti kotvy',
		title		: 'Vlastnosti kotvy',
		name		: 'Meno kotvy',
		errorName	: 'Zadajte prosím meno kotvy'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Nájsť a nahradiť',
		find				: 'Hľadať',
		replace				: 'Nahradiť',
		findWhat			: 'Čo hľadať:',
		replaceWith			: 'Čím nahradiť:',
		notFoundMsg			: 'Hľadaný text nebol nájdený.',
		matchCase			: 'Rozlišovať malé/veľké písmená',
		matchWord			: 'Len celé slová',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Nahradiť všetko',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Tabuľka',
		title		: 'Vlastnosti tabuľky',
		menu		: 'Vlastnosti tabuľky',
		deleteTable	: 'Vymazať tabuľku',
		rows		: 'Riadky',
		columns		: 'Stĺpce',
		border		: 'Ohraničenie',
		align		: 'Zarovnanie',
		alignNotSet	: '<nenastavené>',
		alignLeft	: 'Vľavo',
		alignCenter	: 'Na stred',
		alignRight	: 'Vpravo',
		width		: 'Šírka',
		widthPx		: 'pixelov',
		widthPc		: 'percent',
		height		: 'Výška',
		cellSpace	: 'Vzdialenosť buniek',
		cellPad		: 'Odsadenie obsahu',
		caption		: 'Popis',
		summary		: 'Prehľad',
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
			menu			: 'Bunka',
			insertBefore	: 'Vložiť bunku pred',
			insertAfter		: 'Vložiť bunku za',
			deleteCell		: 'Vymazať bunky',
			merge			: 'Zlúčiť bunky',
			mergeRight		: 'Zlúčiť doprava',
			mergeDown		: 'Zlúčiť dole',
			splitHorizontal	: 'Rozdeliť bunky horizontálne',
			splitVertical	: 'Rozdeliť bunky vertikálne',
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
			menu			: 'Riadok',
			insertBefore	: 'Vložiť riadok za',
			insertAfter		: 'Vložiť riadok pred',
			deleteRow		: 'Vymazať riadok'
		},

		column :
		{
			menu			: 'Stĺpec',
			insertBefore	: 'Vložiť stĺpec za',
			insertAfter		: 'Vložiť stĺpec pred',
			deleteColumn	: 'Zmazať stĺpec'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Vlastnosti tlačidla',
		text		: 'Text',
		type		: 'Typ',
		typeBtn		: 'Tlačidlo',
		typeSbm		: 'Odoslať',
		typeRst		: 'Vymazať'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Vlastnosti zaškrtávacieho políčka',
		radioTitle	: 'Vlastnosti prepínača',
		value		: 'Hodnota',
		selected	: 'Vybrané'
	},

	// Form Dialog.
	form :
	{
		title		: 'Vlastnosti formulára',
		menu		: 'Vlastnosti formulára',
		action		: 'Akcie',
		method		: 'Metóda',
		encoding	: 'Encoding', // MISSING
		target		: 'Cieľ',
		targetNotSet	: '<nenastavené>',
		targetNew	: 'Nové okno (_blank)',
		targetTop	: 'Hlavné okno (_top)',
		targetSelf	: 'Rovnaké okno (_self)',
		targetParent	: 'Rodičovské okno (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Vlastnosti rozbaľovacieho zoznamu',
		selectInfo	: 'Info',
		opAvail		: 'Dostupné možnosti',
		value		: 'Hodnota',
		size		: 'Veľkosť',
		lines		: 'riadkov',
		chkMulti	: 'Povoliť viacnásobný výber',
		opText		: 'Text',
		opValue		: 'Hodnota',
		btnAdd		: 'Pridať',
		btnModify	: 'Zmeniť',
		btnUp		: 'Hore',
		btnDown		: 'Dole',
		btnSetValue : 'Nastaviť ako vybranú hodnotu',
		btnDelete	: 'Zmazať'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Vlastnosti textovej oblasti',
		cols		: 'Stĺpce',
		rows		: 'Riadky'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Vlastnosti textového poľa',
		name		: 'Názov',
		value		: 'Hodnota',
		charWidth	: 'Šírka pola (znakov)',
		maxChars	: 'Maximálny počet znakov',
		type		: 'Typ',
		typeText	: 'Text',
		typePass	: 'Heslo'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Vlastnosti skrytého poľa',
		name	: 'Názov',
		value	: 'Hodnota'
	},

	// Image Dialog.
	image :
	{
		title		: 'Vlastnosti obrázku',
		titleButton	: 'Vlastnosti obrázkového tlačidla',
		menu		: 'Vlastnosti obrázku',
		infoTab	: 'Informácie o obrázku',
		btnUpload	: 'Odoslať na server',
		url		: 'URL',
		upload	: 'Odoslať',
		alt		: 'Alternatívny text',
		width		: 'Šírka',
		height	: 'Výška',
		lockRatio	: 'Zámok',
		resetSize	: 'Pôvodná veľkosť',
		border	: 'Okraje',
		hSpace	: 'H-medzera',
		vSpace	: 'V-medzera',
		align		: 'Zarovnanie',
		alignLeft	: 'Vľavo',
		alignAbsBottom: 'Úplne dole',
		alignAbsMiddle: 'Do stredu',
		alignBaseline	: 'Na základňu',
		alignBottom	: 'Dole',
		alignMiddle	: 'Na stred',
		alignRight	: 'Vpravo',
		alignTextTop	: 'Na horný okraj textu',
		alignTop	: 'Nahor',
		preview	: 'Náhľad',
		alertUrl	: 'Zadajte prosím URL obrázku',
		linkTab	: 'Odkaz',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Vlastnosti Flashu',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Vlastnosti Flashu',
		chkPlay		: 'Automatické prehrávanie',
		chkLoop		: 'Opakovanie',
		chkMenu		: 'Povoliť Flash Menu',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Mierka',
		scaleAll		: 'Zobraziť mierku',
		scaleNoBorder	: 'Bez okrajov',
		scaleFit		: 'Roztiahnuť na celé',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Zarovnanie',
		alignLeft	: 'Vľavo',
		alignAbsBottom: 'Úplne dole',
		alignAbsMiddle: 'Do stredu',
		alignBaseline	: 'Na základňu',
		alignBottom	: 'Dole',
		alignMiddle	: 'Na stred',
		alignRight	: 'Vpravo',
		alignTextTop	: 'Na horný okraj textu',
		alignTop	: 'Nahor',
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
		bgcolor	: 'Farba pozadia',
		width	: 'Šírka',
		height	: 'Výška',
		hSpace	: 'H-medzera',
		vSpace	: 'V-medzera',
		validateSrc : 'Zadajte prosím URL odkazu',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Kontrola pravopisu',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Nie je v slovníku',
		changeTo		: 'Zmeniť na',
		btnIgnore		: 'Ignorovať',
		btnIgnoreAll	: 'Ignorovať všetko',
		btnReplace		: 'Prepísat',
		btnReplaceAll	: 'Prepísat všetko',
		btnUndo			: 'Späť',
		noSuggestions	: '- Žiadny návrh -',
		progress		: 'Prebieha kontrola pravopisu...',
		noMispell		: 'Kontrola pravopisu dokončená: bez chýb',
		noChanges		: 'Kontrola pravopisu dokončená: žiadne slová nezmenené',
		oneChange		: 'Kontrola pravopisu dokončená: zmenené jedno slovo',
		manyChanges		: 'Kontrola pravopisu dokončená: zmenených %1 slov',
		ieSpellDownload	: 'Kontrola pravopisu nie je naištalovaná. Chcete ju hneď stiahnuť?'
	},

	smiley :
	{
		toolbar	: 'Smajlíky',
		title	: 'Vkladanie smajlíkov'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Číslovanie',
	bulletedlist : 'Odrážky',
	indent : 'Zväčšiť odsadenie',
	outdent : 'Zmenšiť odsadenie',

	justify :
	{
		left : 'Zarovnať vľavo',
		center : 'Zarovnať na stred',
		right : 'Zarovnať vpravo',
		block : 'Zarovnať do bloku'
	},

	blockquote : 'Citácia',

	clipboard :
	{
		title		: 'Vložiť',
		cutError	: 'Bezpečnostné nastavenia Vášho prehliadača nedovoľujú editoru spustiť funkciu pre vystrihnutie zvoleného textu do schránky. Prosím vystrihnite zvolený text do schránky pomocou klávesnice (Ctrl+X).',
		copyError	: 'Bezpečnostné nastavenia Vášho prehliadača nedovoľujú editoru spustiť funkciu pre kopírovanie zvoleného textu do schránky. Prosím skopírujte zvolený text do schránky pomocou klávesnice (Ctrl+C).',
		pasteMsg	: 'Prosím vložte nasledovný rámček použitím klávesnice (<STRONG>Ctrl+V</STRONG>) a stlačte <STRONG>OK</STRONG>.',
		securityMsg	: 'Bezpečnostné nastavenia Vášho prehliadača nedovoľujú editoru pristupovať priamo k datám v schránke. Musíte ich vložiť znovu do tohto okna.'
	},

	pastefromword :
	{
		toolbar : 'Vložiť z Wordu',
		title : 'Vložiť z Wordu',
		advice : 'Prosím vložte nasledovný rámček použitím klávesnice (<STRONG>Ctrl+V</STRONG>) a stlačte <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Ignorovať nastavenia typu písma',
		removeStyle : 'Odstrániť formátovanie'
	},

	pasteText :
	{
		button : 'Vložiť ako čistý text',
		title : 'Vložiť ako čistý text'
	},

	templates :
	{
		button : 'Šablóny',
		title : 'Šablóny obsahu',
		insertOption: 'Nahradiť aktuálny obsah',
		selectPromptMsg: 'Prosím vyberte šablóny na otvorenie v editore<br>(súšasný obsah bude stratený):',
		emptyListMsg : '(žiadne šablóny nenájdené)'
	},

	showBlocks : 'Ukázať bloky',

	stylesCombo :
	{
		label : 'Štýl',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'Formát',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'Formát',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'Normálny',
		tag_pre : 'Formátovaný',
		tag_address : 'Adresa',
		tag_h1 : 'Nadpis 1',
		tag_h2 : 'Nadpis 2',
		tag_h3 : 'Nadpis 3',
		tag_h4 : 'Nadpis 4',
		tag_h5 : 'Nadpis 5',
		tag_h6 : 'Nadpis 6',
		tag_div : 'Odsek (DIV)'
	},

	font :
	{
		label : 'Písmo',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'Písmo',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'Veľkosť',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Veľkosť',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Farba textu',
		bgColorTitle : 'Farba pozadia',
		auto : 'Automaticky',
		more : 'Viac farieb...'
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
