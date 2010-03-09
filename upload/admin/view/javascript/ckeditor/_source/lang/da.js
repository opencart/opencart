/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Danish language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['da'] =
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
	editorTitle		: 'Editor, %1',

	// Toolbar buttons without dialogs.
	source			: 'Kilde',
	newPage			: 'Ny side',
	save			: 'Gem',
	preview			: 'Vis eksempel',
	cut				: 'Klip',
	copy			: 'Kopiér',
	paste			: 'Indsæt',
	print			: 'Udskriv',
	underline		: 'Understreget',
	bold			: 'Fed',
	italic			: 'Kursiv',
	selectAll		: 'Vælg alt',
	removeFormat	: 'Fjern formatering',
	strike			: 'Gennemstreget',
	subscript		: 'Sænket skrift',
	superscript		: 'Hævet skrift',
	horizontalrule	: 'Indsæt vandret streg',
	pagebreak		: 'Indsæt sideskift',
	unlink			: 'Fjern hyperlink',
	undo			: 'Fortryd',
	redo			: 'Annullér fortryd',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Gennemse...',
		url				: 'URL',
		protocol		: 'Protokol',
		upload			: 'Upload',
		uploadSubmit	: 'Upload',
		image			: 'Indsæt billede',
		flash			: 'Indsæt Flash',
		form			: 'Indsæt formular',
		checkbox		: 'Indsæt afkrydsningsfelt',
		radio		: 'Indsæt alternativknap',
		textField		: 'Indsæt tekstfelt',
		textarea		: 'Indsæt tekstboks',
		hiddenField		: 'Indsæt skjult felt',
		button			: 'Indsæt knap',
		select	: 'Indsæt liste',
		imageButton		: 'Indsæt billedknap',
		notSet			: '<intet valgt>',
		id				: 'Id',
		name			: 'Navn',
		langDir			: 'Tekstretning',
		langDirLtr		: 'Fra venstre mod højre (LTR)',
		langDirRtl		: 'Fra højre mod venstre (RTL)',
		langCode		: 'Sprogkode',
		longDescr		: 'Udvidet beskrivelse',
		cssClass		: 'Typografiark (CSS)',
		advisoryTitle	: 'Titel',
		cssStyle		: 'Typografi (CSS)',
		ok				: 'OK',
		cancel			: 'Annullér',
		generalTab		: 'Generelt',
		advancedTab		: 'Avanceret',
		validateNumberFailed	: 'Værdien er ikke et tal.',
		confirmNewPage	: 'Alt indhold, der ikke er blevet gemt, vil gå tabt. Er du sikker på, at du vil indlæse en ny side?',
		confirmCancel	: 'Nogle af indstillingerne er blevet ændret. Er du sikker på, at du vil lukke vinduet?',

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, ikke tilgængelig</span>'
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Indsæt symbol',
		title		: 'Vælg symbol'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Indsæt/redigér hyperlink',
		menu		: 'Redigér hyperlink',
		title		: 'Egenskaber for hyperlink',
		info		: 'Generelt',
		target		: 'Mål',
		upload		: 'Upload',
		advanced	: 'Avanceret',
		type		: 'Type',
		toAnchor	: 'Bogmærke på denne side',
		toEmail		: 'E-mail',
		target		: 'Mål',
		targetNotSet	: '<intet valgt>',
		targetFrame	: '<ramme>',
		targetPopup	: '<popup vindue>',
		targetNew	: 'Nyt vindue (_blank)',
		targetTop	: 'Hele vinduet (_top)',
		targetSelf	: 'Samme vindue/ramme (_self)',
		targetParent	: 'Overordnet vindue/ramme (_parent)',
		targetFrameName	: 'Destinationsvinduets navn',
		targetPopupName	: 'Popup vinduets navn',
		popupFeatures	: 'Egenskaber for popup',
		popupResizable	: 'Justérbar',
		popupStatusBar	: 'Statuslinje',
		popupLocationBar	: 'Adresselinje',
		popupToolbar	: 'Værktøjslinje',
		popupMenuBar	: 'Menulinje',
		popupFullScreen	: 'Fuld skærm (IE)',
		popupScrollBars	: 'Scrollbar',
		popupDependent	: 'Koblet/dependent (Netscape)',
		popupWidth		: 'Bredde',
		popupLeft		: 'Position fra venstre',
		popupHeight		: 'Højde',
		popupTop		: 'Position fra toppen',
		id				: 'Id',
		langDir			: 'Tekstretning',
		langDirNotSet	: '<intet valgt>',
		langDirLTR		: 'Fra venstre mod højre (LTR)',
		langDirRTL		: 'Fra højre mod venstre (RTL)',
		acccessKey		: 'Genvejstast',
		name			: 'Navn',
		langCode		: 'Tekstretning',
		tabIndex		: 'Tabulator indeks',
		advisoryTitle	: 'Titel',
		advisoryContentType	: 'Indholdstype',
		cssClasses		: 'Typografiark',
		charset			: 'Tegnsæt',
		styles			: 'Typografi',
		selectAnchor	: 'Vælg et anker',
		anchorName		: 'Efter anker navn',
		anchorId		: 'Efter element Id',
		emailAddress	: 'E-mail adresse',
		emailSubject	: 'Emne',
		emailBody		: 'Besked',
		noAnchors		: '(Ingen bogmærker i dokumentet)',
		noUrl			: 'Indtast hyperlink URL!',
		noEmail			: 'Indtast e-mail adresse!'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Indsæt/redigér bogmærke',
		menu		: 'Egenskaber for bogmærke',
		title		: 'Egenskaber for bogmærke',
		name		: 'Bogmærke navn',
		errorName	: 'Indtast bogmærke navn'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Søg og erstat',
		find				: 'Søg',
		replace				: 'Erstat',
		findWhat			: 'Søg efter:',
		replaceWith			: 'Erstat med:',
		notFoundMsg			: 'Søgeteksten blev ikke fundet',
		matchCase			: 'Forskel på store og små bogstaver',
		matchWord			: 'Kun hele ord',
		matchCyclic			: 'Match cyklisk',
		replaceAll			: 'Erstat alle',
		replaceSuccessMsg	: '%1 forekomst(er) erstattet.'
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Tabel',
		title		: 'Egenskaber for tabel',
		menu		: 'Egenskaber for tabel',
		deleteTable	: 'Slet tabel',
		rows		: 'Rækker',
		columns		: 'Kolonner',
		border		: 'Rammebredde',
		align		: 'Justering',
		alignNotSet	: '<intet valgt>',
		alignLeft	: 'Venstrestillet',
		alignCenter	: 'Centreret',
		alignRight	: 'Højrestillet',
		width		: 'Bredde',
		widthPx		: 'pixels',
		widthPc		: 'procent',
		height		: 'Højde',
		cellSpace	: 'Celleafstand',
		cellPad		: 'Cellemargen',
		caption		: 'Titel',
		summary		: 'Resumé',
		headers		: 'Header',
		headersNone		: 'Ingen',
		headersColumn	: 'Første kolonne',
		headersRow		: 'Første række',
		headersBoth		: 'Begge',
		invalidRows		: 'Antallet af rækker skal være større end 0.',
		invalidCols		: 'Antallet af kolonner skal være større end 0.',
		invalidBorder	: 'Rammetykkelse skal være et tal.',
		invalidWidth	: 'Tabelbredde skal være et tal.',
		invalidHeight	: 'Tabelhøjde skal være et tal.',
		invalidCellSpacing	: 'Celleafstand skal være et tal.',
		invalidCellPadding	: 'Cellemargen skal være et tal.',

		cell :
		{
			menu			: 'Celle',
			insertBefore	: 'Indsæt celle før',
			insertAfter		: 'Indsæt celle efter',
			deleteCell		: 'Slet celle',
			merge			: 'Flet celler',
			mergeRight		: 'Flet til højre',
			mergeDown		: 'Flet nedad',
			splitHorizontal	: 'Del celle vandret',
			splitVertical	: 'Del celle lodret',
			title			: 'Celleegenskaber',
			cellType		: 'Celletype',
			rowSpan			: 'Række span (rows span)',
			colSpan			: 'Kolonne span (columns span)',
			wordWrap		: 'Tekstombrydning',
			hAlign			: 'Vandret justering',
			vAlign			: 'Lodret justering',
			alignTop		: 'Top',
			alignMiddle		: 'Midt',
			alignBottom		: 'Bund',
			alignBaseline	: 'Grundlinje',
			bgColor			: 'Baggrundsfarve',
			borderColor		: 'Rammefarve',
			data			: 'Data',
			header			: 'Header',
			yes				: 'Ja',
			no				: 'Nej',
			invalidWidth	: 'Cellebredde skal være et tal.',
			invalidHeight	: 'Cellehøjde skal være et tal.',
			invalidRowSpan	: 'Række span skal være et heltal.',
			invalidColSpan	: 'Kolonne span skal være et heltal.',
			chooseColor : 'Choose' // MISSING
		},

		row :
		{
			menu			: 'Række',
			insertBefore	: 'Indsæt række før',
			insertAfter		: 'Indsæt række efter',
			deleteRow		: 'Slet række'
		},

		column :
		{
			menu			: 'Kolonne',
			insertBefore	: 'Indsæt kolonne før',
			insertAfter		: 'Indsæt kolonne efter',
			deleteColumn	: 'Slet kolonne'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Egenskaber for knap',
		text		: 'Tekst',
		type		: 'Type',
		typeBtn		: 'Knap',
		typeSbm		: 'Send',
		typeRst		: 'Nulstil'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Egenskaber for afkrydsningsfelt',
		radioTitle	: 'Egenskaber for alternativknap',
		value		: 'Værdi',
		selected	: 'Valgt'
	},

	// Form Dialog.
	form :
	{
		title		: 'Egenskaber for formular',
		menu		: 'Egenskaber for formular',
		action		: 'Handling',
		method		: 'Metode',
		encoding	: 'Kodning (encoding)',
		target		: 'Mål',
		targetNotSet	: '<intet valgt>',
		targetNew	: 'Nyt vindue (_blank)',
		targetTop	: 'Hele vinduet (_top)',
		targetSelf	: 'Samme vindue/ramme (_self)',
		targetParent	: 'Overordnet vindue/ramme (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Egenskaber for liste',
		selectInfo	: 'Generelt',
		opAvail		: 'Valgmuligheder',
		value		: 'Værdi',
		size		: 'Størrelse',
		lines		: 'Linjer',
		chkMulti	: 'Tillad flere valg',
		opText		: 'Tekst',
		opValue		: 'Værdi',
		btnAdd		: 'Tilføj',
		btnModify	: 'Redigér',
		btnUp		: 'Op',
		btnDown		: 'Ned',
		btnSetValue : 'Sæt som valgt',
		btnDelete	: 'Slet'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Egenskaber for tekstboks',
		cols		: 'Kolonner',
		rows		: 'Rækker'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Egenskaber for tekstfelt',
		name		: 'Navn',
		value		: 'Værdi',
		charWidth	: 'Bredde (tegn)',
		maxChars	: 'Max. antal tegn',
		type		: 'Type',
		typeText	: 'Tekst',
		typePass	: 'Adgangskode'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Egenskaber for skjult felt',
		name	: 'Navn',
		value	: 'Værdi'
	},

	// Image Dialog.
	image :
	{
		title		: 'Egenskaber for billede',
		titleButton	: 'Egenskaber for billedknap',
		menu		: 'Egenskaber for billede',
		infoTab	: 'Generelt',
		btnUpload	: 'Upload',
		url		: 'URL',
		upload	: 'Upload',
		alt		: 'Alternativ tekst',
		width		: 'Bredde',
		height	: 'Højde',
		lockRatio	: 'Lås størrelsesforhold',
		resetSize	: 'Nulstil størrelse',
		border	: 'Ramme',
		hSpace	: 'Vandret margen',
		vSpace	: 'Lodret margen',
		align		: 'Justering',
		alignLeft	: 'Venstre',
		alignAbsBottom: 'Absolut nederst',
		alignAbsMiddle: 'Absolut centreret',
		alignBaseline	: 'Grundlinje',
		alignBottom	: 'Nederst',
		alignMiddle	: 'Centreret',
		alignRight	: 'Højre',
		alignTextTop	: 'Toppen af teksten',
		alignTop	: 'Øverst',
		preview	: 'Vis eksempel',
		alertUrl	: 'Indtast stien til billedet',
		linkTab	: 'Hyperlink',
		button2Img	: 'Vil du lave billedknappen om til et almindeligt billede?',
		img2Button	: 'Vil du lave billedet om til en billedknap?',
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Egenskaber for Flash',
		propertiesTab	: 'Egenskaber',
		title		: 'Egenskaber for Flash',
		chkPlay		: 'Automatisk afspilning',
		chkLoop		: 'Gentagelse',
		chkMenu		: 'Vis Flash menu',
		chkFull		: 'Tillad fuldskærm',
 		scale		: 'Skalér',
		scaleAll		: 'Vis alt',
		scaleNoBorder	: 'Ingen ramme',
		scaleFit		: 'Tilpas størrelse',
		access			: 'Script adgang',
		accessAlways	: 'Altid',
		accessSameDomain	: 'Samme domæne',
		accessNever	: 'Aldrig',
		align		: 'Justering',
		alignLeft	: 'Venstre',
		alignAbsBottom: 'Absolut nederst',
		alignAbsMiddle: 'Absolut centreret',
		alignBaseline	: 'Grundlinje',
		alignBottom	: 'Nederst',
		alignMiddle	: 'Centreret',
		alignRight	: 'Højre',
		alignTextTop	: 'Toppen af teksten',
		alignTop	: 'Øverst',
		quality		: 'Kvalitet',
		qualityBest		 : 'Bedste',
		qualityHigh		 : 'Høj',
		qualityAutoHigh	 : 'Auto høj',
		qualityMedium	 : 'Medium',
		qualityAutoLow	 : 'Auto lav',
		qualityLow		 : 'Lav',
		windowModeWindow	 : 'Vindue',
		windowModeOpaque	 : 'Gennemsigtig (opaque)',
		windowModeTransparent	 : 'Transparent',
		windowMode	: 'Vinduestilstand',
		flashvars	: 'Variabler for Flash',
		bgcolor	: 'Baggrundsfarve',
		width	: 'Bredde',
		height	: 'Højde',
		hSpace	: 'Vandret margen',
		vSpace	: 'Lodret margen',
		validateSrc : 'Indtast hyperlink URL!',
		validateWidth : 'Bredde skal være et tal.',
		validateHeight : 'Højde skal være et tal.',
		validateHSpace : 'Vandret margen skal være et tal.',
		validateVSpace : 'Lodret margen skal være et tal.'
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Stavekontrol',
		title			: 'Stavekontrol',
		notAvailable	: 'Stavekontrol er desværre ikke tilgængelig.',
		errorLoading	: 'Fejl ved indlæsning af host: %s.',
		notInDic		: 'Ikke i ordbogen',
		changeTo		: 'Forslag',
		btnIgnore		: 'Ignorér',
		btnIgnoreAll	: 'Ignorér alle',
		btnReplace		: 'Erstat',
		btnReplaceAll	: 'Erstat alle',
		btnUndo			: 'Tilbage',
		noSuggestions	: '(ingen forslag)',
		progress		: 'Stavekontrollen arbejder...',
		noMispell		: 'Stavekontrol færdig: Ingen fejl fundet',
		noChanges		: 'Stavekontrol færdig: Ingen ord ændret',
		oneChange		: 'Stavekontrol færdig: Et ord ændret',
		manyChanges		: 'Stavekontrol færdig: %1 ord ændret',
		ieSpellDownload	: 'Stavekontrol ikke installeret. Vil du installere den nu?'
	},

	smiley :
	{
		toolbar	: 'Smiley',
		title	: 'Vælg smiley'
	},

	elementsPath :
	{
		eleTitle : '%1 element'
	},

	numberedlist : 'Talopstilling',
	bulletedlist : 'Punktopstilling',
	indent : 'Forøg indrykning',
	outdent : 'Formindsk indrykning',

	justify :
	{
		left : 'Venstrestillet',
		center : 'Centreret',
		right : 'Højrestillet',
		block : 'Lige margener'
	},

	blockquote : 'Blokcitat',

	clipboard :
	{
		title		: 'Indsæt',
		cutError	: 'Din browsers sikkerhedsindstillinger tillader ikke editoren at få automatisk adgang til udklipsholderen.<br><br>Brug i stedet tastaturet til at klippe teksten (Ctrl+X).',
		copyError	: 'Din browsers sikkerhedsindstillinger tillader ikke editoren at få automatisk adgang til udklipsholderen.<br><br>Brug i stedet tastaturet til at kopiere teksten (Ctrl+C).',
		pasteMsg	: 'Indsæt i feltet herunder (<STRONG>Ctrl+V</STRONG>) og klik på <STRONG>OK</STRONG>.',
		securityMsg	: 'Din browsers sikkerhedsindstillinger tillader ikke editoren at få automatisk adgang til udklipsholderen.<br><br>Du skal indsætte udklipsholderens indhold i dette vindue igen.'
	},

	pastefromword :
	{
		toolbar : 'Indsæt fra Word',
		title : 'Indsæt fra Word',
		advice : 'Indsæt i feltet herunder (<STRONG>Ctrl+V</STRONG>) og klik på <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Ignorér skrifttypedefinitioner',
		removeStyle : 'Ignorér typografi'
	},

	pasteText :
	{
		button : 'Indsæt som ikke-formateret tekst',
		title : 'Indsæt som ikke-formateret tekst'
	},

	templates :
	{
		button : 'Skabeloner',
		title : 'Indholdsskabeloner',
		insertOption: 'Erstat det faktiske indhold',
		selectPromptMsg: 'Vælg den skabelon, som skal åbnes i editoren (nuværende indhold vil blive overskrevet):',
		emptyListMsg : '(Der er ikke defineret nogen skabelon)'
	},

	showBlocks : 'Vis afsnitsmærker',

	stylesCombo :
	{
		label : 'Typografi',
		voiceLabel : 'Typografi',
		panelVoiceLabel : 'Vælg typografi',
		panelTitle1 : 'Block typografi',
		panelTitle2 : 'Inline typografi',
		panelTitle3 : 'Object typografi'
	},

	format :
	{
		label : 'Formatering',
		voiceLabel : 'Formatering',
		panelTitle : 'Formatering',
		panelVoiceLabel : 'Vælg afsnitsformatering',

		tag_p : 'Normal',
		tag_pre : 'Formateret',
		tag_address : 'Adresse',
		tag_h1 : 'Overskrift 1',
		tag_h2 : 'Overskrift 2',
		tag_h3 : 'Overskrift 3',
		tag_h4 : 'Overskrift 4',
		tag_h5 : 'Overskrift 5',
		tag_h6 : 'Overskrift 6',
		tag_div : 'Normal (DIV)'
	},

	font :
	{
		label : 'Skrifttype',
		voiceLabel : 'Skrifttype',
		panelTitle : 'Skrifttype',
		panelVoiceLabel : 'Vælg skrifttype'
	},

	fontSize :
	{
		label : 'Skriftstørrelse',
		voiceLabel : 'Skriftstørrelse',
		panelTitle : 'Skriftstørrelse',
		panelVoiceLabel : 'Vælg skriftstørrelse'
	},

	colorButton :
	{
		textColorTitle : 'Tekstfarve',
		bgColorTitle : 'Baggrundsfarve',
		auto : 'Automatisk',
		more : 'Flere farver...'
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
		title : 'Stavekontrol mens du skriver',
		enable : 'Aktivér SCAYT',
		disable : 'Deaktivér SCAYT',
		about : 'Om SCAYT',
		toggle : 'Skift/toggle SCAYT',
		options : 'Indstillinger',
		langs : 'Sprog',
		moreSuggestions : 'Flere forslag',
		ignore : 'Ignorér',
		ignoreAll : 'Ignorér alle',
		addWord : 'Tilføj ord',
		emptyDic : 'Ordbogsnavn må ikke være tom.',
		optionsTab : 'Indstillinger',
		languagesTab : 'Sprog',
		dictionariesTab : 'Ordbøger',
		aboutTab : 'Om'
	},

	about :
	{
		title : 'Om CKEditor',
		dlgTitle : 'Om CKEditor',
		moreInfo : 'For informationer omkring licens, se venligst vores hjemmeside (på engelsk):',
		copy : 'Copyright &copy; $1. Alle rettigheder forbeholdes.'
	},

	maximize : 'Maximér',
	minimize : 'Minimize', // MISSING

	fakeobjects :
	{
		anchor : 'Anker',
		flash : 'Flashanimation',
		div : 'Sideskift',
		unknown : 'Ukendt objekt'
	},

	resize : 'Træk for at skalere',

	colordialog :
	{
		title : 'Select color', // MISSING
		highlight : 'Highlight', // MISSING
		selected : 'Selected', // MISSING
		clear : 'Clear' // MISSING
	}
};
