/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Slovenian language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['sl'] =
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
	source			: 'Izvorna koda',
	newPage			: 'Nova stran',
	save			: 'Shrani',
	preview			: 'Predogled',
	cut				: 'Izreži',
	copy			: 'Kopiraj',
	paste			: 'Prilepi',
	print			: 'Natisni',
	underline		: 'Podčrtano',
	bold			: 'Krepko',
	italic			: 'Ležeče',
	selectAll		: 'Izberi vse',
	removeFormat	: 'Odstrani oblikovanje',
	strike			: 'Prečrtano',
	subscript		: 'Podpisano',
	superscript		: 'Nadpisano',
	horizontalrule	: 'Vstavi vodoravno črto',
	pagebreak		: 'Vstavi prelom strani',
	unlink			: 'Odstrani povezavo',
	undo			: 'Razveljavi',
	redo			: 'Ponovi',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Prebrskaj na strežniku',
		url				: 'URL',
		protocol		: 'Protokol',
		upload			: 'Prenesi',
		uploadSubmit	: 'Pošlji na strežnik',
		image			: 'Slika',
		flash			: 'Flash',
		form			: 'Obrazec',
		checkbox		: 'Potrditveno polje',
		radio		: 'Izbirno polje',
		textField		: 'Vnosno polje',
		textarea		: 'Vnosno območje',
		hiddenField		: 'Skrito polje',
		button			: 'Gumb',
		select	: 'Spustni seznam',
		imageButton		: 'Gumb s sliko',
		notSet			: '<ni postavljen>',
		id				: 'Id',
		name			: 'Ime',
		langDir			: 'Smer jezika',
		langDirLtr		: 'Od leve proti desni (LTR)',
		langDirRtl		: 'Od desne proti levi (RTL)',
		langCode		: 'Oznaka jezika',
		longDescr		: 'Dolg opis URL-ja',
		cssClass		: 'Razred stilne predloge',
		advisoryTitle	: 'Predlagani naslov',
		cssStyle		: 'Slog',
		ok				: 'V redu',
		cancel			: 'Prekliči',
		generalTab		: 'General', // MISSING
		advancedTab		: 'Napredno',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Vstavi posebni znak',
		title		: 'Izberi posebni znak'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Vstavi/uredi povezavo',
		menu		: 'Uredi povezavo',
		title		: 'Povezava',
		info		: 'Podatki o povezavi',
		target		: 'Cilj',
		upload		: 'Prenesi',
		advanced	: 'Napredno',
		type		: 'Vrsta povezave',
		toAnchor	: 'Zaznamek na tej strani',
		toEmail		: 'Elektronski naslov',
		target		: 'Cilj',
		targetNotSet	: '<ni postavljen>',
		targetFrame	: '<okvir>',
		targetPopup	: '<pojavno okno>',
		targetNew	: 'Novo okno (_blank)',
		targetTop	: 'Najvišje okno (_top)',
		targetSelf	: 'Isto okno (_self)',
		targetParent	: 'Starševsko okno (_parent)',
		targetFrameName	: 'Ime ciljnega okvirja',
		targetPopupName	: 'Ime pojavnega okna',
		popupFeatures	: 'Značilnosti pojavnega okna',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Vrstica stanja',
		popupLocationBar	: 'Naslovna vrstica',
		popupToolbar	: 'Orodna vrstica',
		popupMenuBar	: 'Menijska vrstica',
		popupFullScreen	: 'Celozaslonska slika (IE)',
		popupScrollBars	: 'Drsniki',
		popupDependent	: 'Podokno (Netscape)',
		popupWidth		: 'Širina',
		popupLeft		: 'Lega levo',
		popupHeight		: 'Višina',
		popupTop		: 'Lega na vrhu',
		id				: 'Id', // MISSING
		langDir			: 'Smer jezika',
		langDirNotSet	: '<ni postavljen>',
		langDirLTR		: 'Od leve proti desni (LTR)',
		langDirRTL		: 'Od desne proti levi (RTL)',
		acccessKey		: 'Vstopno geslo',
		name			: 'Ime',
		langCode		: 'Smer jezika',
		tabIndex		: 'Številka tabulatorja',
		advisoryTitle	: 'Predlagani naslov',
		advisoryContentType	: 'Predlagani tip vsebine (content-type)',
		cssClasses		: 'Razred stilne predloge',
		charset			: 'Kodna tabela povezanega vira',
		styles			: 'Slog',
		selectAnchor	: 'Izberi zaznamek',
		anchorName		: 'Po imenu zaznamka',
		anchorId		: 'Po ID-ju elementa',
		emailAddress	: 'Elektronski naslov',
		emailSubject	: 'Predmet sporočila',
		emailBody		: 'Vsebina sporočila',
		noAnchors		: '(V tem dokumentu ni zaznamkov)',
		noUrl			: 'Vnesite URL povezave',
		noEmail			: 'Vnesite elektronski naslov'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Vstavi/uredi zaznamek',
		menu		: 'Lastnosti zaznamka',
		title		: 'Lastnosti zaznamka',
		name		: 'Ime zaznamka',
		errorName	: 'Prosim vnesite ime zaznamka'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Najdi in zamenjaj',
		find				: 'Najdi',
		replace				: 'Zamenjaj',
		findWhat			: 'Najdi:',
		replaceWith			: 'Zamenjaj z:',
		notFoundMsg			: 'Navedeno besedilo ni bilo najdeno.',
		matchCase			: 'Razlikuj velike in male črke',
		matchWord			: 'Samo cele besede',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Zamenjaj vse',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Tabela',
		title		: 'Lastnosti tabele',
		menu		: 'Lastnosti tabele',
		deleteTable	: 'Izbriši tabelo',
		rows		: 'Vrstice',
		columns		: 'Stolpci',
		border		: 'Velikost obrobe',
		align		: 'Poravnava',
		alignNotSet	: '<Ni nastavljeno>',
		alignLeft	: 'Levo',
		alignCenter	: 'Sredinsko',
		alignRight	: 'Desno',
		width		: 'Širina',
		widthPx		: 'pik',
		widthPc		: 'procentov',
		height		: 'Višina',
		cellSpace	: 'Razmik med celicami',
		cellPad		: 'Polnilo med celicami',
		caption		: 'Naslov',
		summary		: 'Povzetek',
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
			menu			: 'Celica',
			insertBefore	: 'Vstavi celico pred',
			insertAfter		: 'Vstavi celico za',
			deleteCell		: 'Izbriši celice',
			merge			: 'Združi celice',
			mergeRight		: 'Združi desno',
			mergeDown		: 'Druži navzdol',
			splitHorizontal	: 'Razdeli celico vodoravno',
			splitVertical	: 'Razdeli celico navpično',
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
			menu			: 'Vrstica',
			insertBefore	: 'Vstavi vrstico pred',
			insertAfter		: 'Vstavi vrstico za',
			deleteRow		: 'Izbriši vrstice'
		},

		column :
		{
			menu			: 'Stolpec',
			insertBefore	: 'Vstavi stolpec pred',
			insertAfter		: 'Vstavi stolpec za',
			deleteColumn	: 'Izbriši stolpce'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Lastnosti gumba',
		text		: 'Besedilo (Vrednost)',
		type		: 'Tip',
		typeBtn		: 'Gumb',
		typeSbm		: 'Potrdi',
		typeRst		: 'Ponastavi'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Lastnosti potrditvenega polja',
		radioTitle	: 'Lastnosti izbirnega polja',
		value		: 'Vrednost',
		selected	: 'Izbrano'
	},

	// Form Dialog.
	form :
	{
		title		: 'Lastnosti obrazca',
		menu		: 'Lastnosti obrazca',
		action		: 'Akcija',
		method		: 'Metoda',
		encoding	: 'Encoding', // MISSING
		target		: 'Cilj',
		targetNotSet	: '<ni postavljen>',
		targetNew	: 'Novo okno (_blank)',
		targetTop	: 'Najvišje okno (_top)',
		targetSelf	: 'Isto okno (_self)',
		targetParent	: 'Starševsko okno (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Lastnosti spustnega seznama',
		selectInfo	: 'Podatki',
		opAvail		: 'Razpoložljive izbire',
		value		: 'Vrednost',
		size		: 'Velikost',
		lines		: 'vrstic',
		chkMulti	: 'Dovoli izbor večih vrstic',
		opText		: 'Besedilo',
		opValue		: 'Vrednost',
		btnAdd		: 'Dodaj',
		btnModify	: 'Spremeni',
		btnUp		: 'Gor',
		btnDown		: 'Dol',
		btnSetValue : 'Postavi kot privzeto izbiro',
		btnDelete	: 'Izbriši'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Lastnosti vnosnega območja',
		cols		: 'Stolpcev',
		rows		: 'Vrstic'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Lastnosti vnosnega polja',
		name		: 'Ime',
		value		: 'Vrednost',
		charWidth	: 'Dolžina',
		maxChars	: 'Največje število znakov',
		type		: 'Tip',
		typeText	: 'Besedilo',
		typePass	: 'Geslo'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Lastnosti skritega polja',
		name	: 'Ime',
		value	: 'Vrednost'
	},

	// Image Dialog.
	image :
	{
		title		: 'Lastnosti slike',
		titleButton	: 'Lastnosti gumba s sliko',
		menu		: 'Lastnosti slike',
		infoTab	: 'Podatki o sliki',
		btnUpload	: 'Pošlji na strežnik',
		url		: 'URL',
		upload	: 'Pošlji',
		alt		: 'Nadomestno besedilo',
		width		: 'Širina',
		height	: 'Višina',
		lockRatio	: 'Zakleni razmerje',
		resetSize	: 'Ponastavi velikost',
		border	: 'Obroba',
		hSpace	: 'Vodoravni razmik',
		vSpace	: 'Navpični razmik',
		align		: 'Poravnava',
		alignLeft	: 'Levo',
		alignAbsBottom: 'Popolnoma na dno',
		alignAbsMiddle: 'Popolnoma v sredino',
		alignBaseline	: 'Na osnovno črto',
		alignBottom	: 'Na dno',
		alignMiddle	: 'V sredino',
		alignRight	: 'Desno',
		alignTextTop	: 'Besedilo na vrh',
		alignTop	: 'Na vrh',
		preview	: 'Predogled',
		alertUrl	: 'Vnesite URL slike',
		linkTab	: 'Povezava',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Lastnosti Flash',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Lastnosti Flash',
		chkPlay		: 'Samodejno predvajaj',
		chkLoop		: 'Ponavljanje',
		chkMenu		: 'Omogoči Flash Meni',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Povečava',
		scaleAll		: 'Pokaži vse',
		scaleNoBorder	: 'Brez obrobe',
		scaleFit		: 'Natančno prileganje',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Poravnava',
		alignLeft	: 'Levo',
		alignAbsBottom: 'Popolnoma na dno',
		alignAbsMiddle: 'Popolnoma v sredino',
		alignBaseline	: 'Na osnovno črto',
		alignBottom	: 'Na dno',
		alignMiddle	: 'V sredino',
		alignRight	: 'Desno',
		alignTextTop	: 'Besedilo na vrh',
		alignTop	: 'Na vrh',
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
		bgcolor	: 'Barva ozadja',
		width	: 'Širina',
		height	: 'Višina',
		hSpace	: 'Vodoravni razmik',
		vSpace	: 'Navpični razmik',
		validateSrc : 'Vnesite URL povezave',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Preveri črkovanje',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Ni v slovarju',
		changeTo		: 'Spremeni v',
		btnIgnore		: 'Prezri',
		btnIgnoreAll	: 'Prezri vse',
		btnReplace		: 'Zamenjaj',
		btnReplaceAll	: 'Zamenjaj vse',
		btnUndo			: 'Razveljavi',
		noSuggestions	: '- Ni predlogov -',
		progress		: 'Preverjanje črkovanja se izvaja...',
		noMispell		: 'Črkovanje je končano: Brez napak',
		noChanges		: 'Črkovanje je končano: Nobena beseda ni bila spremenjena',
		oneChange		: 'Črkovanje je končano: Spremenjena je bila ena beseda',
		manyChanges		: 'Črkovanje je končano: Spremenjenih je bilo %1 besed',
		ieSpellDownload	: 'Črkovalnik ni nameščen. Ali ga želite prenesti sedaj?'
	},

	smiley :
	{
		toolbar	: 'Smeško',
		title	: 'Vstavi smeška'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Oštevilčen seznam',
	bulletedlist : 'Označen seznam',
	indent : 'Povečaj zamik',
	outdent : 'Zmanjšaj zamik',

	justify :
	{
		left : 'Leva poravnava',
		center : 'Sredinska poravnava',
		right : 'Desna poravnava',
		block : 'Obojestranska poravnava'
	},

	blockquote : 'Citat',

	clipboard :
	{
		title		: 'Prilepi',
		cutError	: 'Varnostne nastavitve brskalnika ne dopuščajo samodejnega izrezovanja. Uporabite kombinacijo tipk na tipkovnici (Ctrl+X).',
		copyError	: 'Varnostne nastavitve brskalnika ne dopuščajo samodejnega kopiranja. Uporabite kombinacijo tipk na tipkovnici (Ctrl+C).',
		pasteMsg	: 'Prosim prilepite v sleči okvir s pomočjo tipkovnice (<STRONG>Ctrl+V</STRONG>) in pritisnite <STRONG>V redu</STRONG>.',
		securityMsg	: 'Zaradi varnostnih nastavitev vašega brskalnika urejevalnik ne more neposredno dostopati do odložišča. Vsebino odložišča ponovno prilepite v to okno.'
	},

	pastefromword :
	{
		toolbar : 'Prilepi iz Worda',
		title : 'Prilepi iz Worda',
		advice : 'Prosim prilepite v sleči okvir s pomočjo tipkovnice (<STRONG>Ctrl+V</STRONG>) in pritisnite <STRONG>V redu</STRONG>.',
		ignoreFontFace : 'Prezri obliko pisave',
		removeStyle : 'Odstrani nastavitve stila'
	},

	pasteText :
	{
		button : 'Prilepi kot golo besedilo',
		title : 'Prilepi kot golo besedilo'
	},

	templates :
	{
		button : 'Predloge',
		title : 'Vsebinske predloge',
		insertOption: 'Zamenjaj trenutno vsebino',
		selectPromptMsg: 'Izberite predlogo, ki jo želite odpreti v urejevalniku<br>(trenutna vsebina bo izgubljena):',
		emptyListMsg : '(Ni pripravljenih predlog)'
	},

	showBlocks : 'Prikaži ograde',

	stylesCombo :
	{
		label : 'Slog',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'Oblika',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'Oblika',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'Navaden',
		tag_pre : 'Oblikovan',
		tag_address : 'Napis',
		tag_h1 : 'Naslov 1',
		tag_h2 : 'Naslov 2',
		tag_h3 : 'Naslov 3',
		tag_h4 : 'Naslov 4',
		tag_h5 : 'Naslov 5',
		tag_h6 : 'Naslov 6',
		tag_div : 'Normal (DIV)' // MISSING
	},

	font :
	{
		label : 'Pisava',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'Pisava',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'Velikost',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Velikost',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Barva besedila',
		bgColorTitle : 'Barva ozadja',
		auto : 'Samodejno',
		more : 'Več barv...'
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
