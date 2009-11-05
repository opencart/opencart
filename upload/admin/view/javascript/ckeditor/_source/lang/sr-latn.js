/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Serbian (Latin) language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['sr-latn'] =
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
	source			: 'Kôd',
	newPage			: 'Nova stranica',
	save			: 'Sačuvaj',
	preview			: 'Izgled stranice',
	cut				: 'Iseci',
	copy			: 'Kopiraj',
	paste			: 'Zalepi',
	print			: 'Štampa',
	underline		: 'Podvučeno',
	bold			: 'Podebljano',
	italic			: 'Kurziv',
	selectAll		: 'Označi sve',
	removeFormat	: 'Ukloni formatiranje',
	strike			: 'Precrtano',
	subscript		: 'Indeks',
	superscript		: 'Stepen',
	horizontalrule	: 'Unesi horizontalnu liniju',
	pagebreak		: 'Insert Page Break for Printing', // MISSING
	unlink			: 'Ukloni link',
	undo			: 'Poni�ti akciju',
	redo			: 'Ponovi akciju',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Pretraži server',
		url				: 'URL',
		protocol		: 'Protokol',
		upload			: 'Pošalji',
		uploadSubmit	: 'Pošalji na server',
		image			: 'Slika',
		flash			: 'Fleš',
		form			: 'Forma',
		checkbox		: 'Polje za potvrdu',
		radio		: 'Radio-dugme',
		textField		: 'Tekstualno polje',
		textarea		: 'Zona teksta',
		hiddenField		: 'Skriveno polje',
		button			: 'Dugme',
		select	: 'Izborno polje',
		imageButton		: 'Dugme sa slikom',
		notSet			: '<nije postavljeno>',
		id				: 'Id',
		name			: 'Naziv',
		langDir			: 'Smer jezika',
		langDirLtr		: 'S leva na desno (LTR)',
		langDirRtl		: 'S desna na levo (RTL)',
		langCode		: 'Kôd jezika',
		longDescr		: 'Pun opis URL',
		cssClass		: 'Stylesheet klase',
		advisoryTitle	: 'Advisory naslov',
		cssStyle		: 'Stil',
		ok				: 'OK',
		cancel			: 'Otkaži',
		generalTab		: 'General', // MISSING
		advancedTab		: 'Napredni tagovi',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Unesi specijalni karakter',
		title		: 'Odaberite specijalni karakter'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Unesi/izmeni link',
		menu		: 'Izmeni link',
		title		: 'Link',
		info		: 'Link Info',
		target		: 'Meta',
		upload		: 'Pošalji',
		advanced	: 'Napredni tagovi',
		type		: 'Vrsta linka',
		toAnchor	: 'Sidro na ovoj stranici',
		toEmail		: 'E-Mail',
		target		: 'Meta',
		targetNotSet	: '<nije postavljeno>',
		targetFrame	: '<okvir>',
		targetPopup	: '<popup prozor>',
		targetNew	: 'Novi prozor (_blank)',
		targetTop	: 'Prozor na vrhu (_top)',
		targetSelf	: 'Isti prozor (_self)',
		targetParent	: 'Roditeljski prozor (_parent)',
		targetFrameName	: 'Naziv odredišnog frejma',
		targetPopupName	: 'Naziv popup prozora',
		popupFeatures	: 'Mogućnosti popup prozora',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Statusna linija',
		popupLocationBar	: 'Lokacija',
		popupToolbar	: 'Toolbar',
		popupMenuBar	: 'Kontekstni meni',
		popupFullScreen	: 'Prikaz preko celog ekrana (IE)',
		popupScrollBars	: 'Scroll bar',
		popupDependent	: 'Zavisno (Netscape)',
		popupWidth		: 'Širina',
		popupLeft		: 'Od leve ivice ekrana (px)',
		popupHeight		: 'Visina',
		popupTop		: 'Od vrha ekrana (px)',
		id				: 'Id', // MISSING
		langDir			: 'Smer jezika',
		langDirNotSet	: '<nije postavljeno>',
		langDirLTR		: 'S leva na desno (LTR)',
		langDirRTL		: 'S desna na levo (RTL)',
		acccessKey		: 'Pristupni taster',
		name			: 'Naziv',
		langCode		: 'Smer jezika',
		tabIndex		: 'Tab indeks',
		advisoryTitle	: 'Advisory naslov',
		advisoryContentType	: 'Advisory vrsta sadržaja',
		cssClasses		: 'Stylesheet klase',
		charset			: 'Linked Resource Charset',
		styles			: 'Stil',
		selectAnchor	: 'Odaberi sidro',
		anchorName		: 'Po nazivu sidra',
		anchorId		: 'Po Id-ju elementa',
		emailAddress	: 'E-Mail adresa',
		emailSubject	: 'Naslov',
		emailBody		: 'Sadržaj poruke',
		noAnchors		: '(Nema dostupnih sidra)',
		noUrl			: 'Unesite URL linka',
		noEmail			: 'Otkucajte adresu elektronske pote'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Unesi/izmeni sidro',
		menu		: 'Osobine sidra',
		title		: 'Osobine sidra',
		name		: 'Ime sidra',
		errorName	: 'Unesite ime sidra'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Find and Replace', // MISSING
		find				: 'Pretraga',
		replace				: 'Zamena',
		findWhat			: 'Pronadi:',
		replaceWith			: 'Zameni sa:',
		notFoundMsg			: 'Traženi tekst nije pronađen.',
		matchCase			: 'Razlikuj mala i velika slova',
		matchWord			: 'Uporedi cele reci',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Zameni sve',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Tabela',
		title		: 'Osobine tabele',
		menu		: 'Osobine tabele',
		deleteTable	: 'Delete Table', // MISSING
		rows		: 'Redova',
		columns		: 'Kolona',
		border		: 'Veličina okvira',
		align		: 'Ravnanje',
		alignNotSet	: '<nije postavljeno>',
		alignLeft	: 'Levo',
		alignCenter	: 'Sredina',
		alignRight	: 'Desno',
		width		: 'Širina',
		widthPx		: 'piksela',
		widthPc		: 'procenata',
		height		: 'Visina',
		cellSpace	: 'Ćelijski prostor',
		cellPad		: 'Razmak ćelija',
		caption		: 'Naslov tabele',
		summary		: 'Summary', // MISSING
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
			deleteCell		: 'Obriši ćelije',
			merge			: 'Spoj celije',
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
			deleteRow		: 'Obriši redove'
		},

		column :
		{
			menu			: 'Column', // MISSING
			insertBefore	: 'Insert Column Before', // MISSING
			insertAfter		: 'Insert Column After', // MISSING
			deleteColumn	: 'Obriši kolone'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Osobine dugmeta',
		text		: 'Tekst (vrednost)',
		type		: 'Tip',
		typeBtn		: 'Button', // MISSING
		typeSbm		: 'Submit', // MISSING
		typeRst		: 'Reset' // MISSING
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Osobine polja za potvrdu',
		radioTitle	: 'Osobine radio-dugmeta',
		value		: 'Vrednost',
		selected	: 'Označeno'
	},

	// Form Dialog.
	form :
	{
		title		: 'Osobine forme',
		menu		: 'Osobine forme',
		action		: 'Akcija',
		method		: 'Metoda',
		encoding	: 'Encoding', // MISSING
		target		: 'Meta',
		targetNotSet	: '<nije postavljeno>',
		targetNew	: 'Novi prozor (_blank)',
		targetTop	: 'Prozor na vrhu (_top)',
		targetSelf	: 'Isti prozor (_self)',
		targetParent	: 'Roditeljski prozor (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Osobine izbornog polja',
		selectInfo	: 'Info',
		opAvail		: 'Dostupne opcije',
		value		: 'Vrednost',
		size		: 'Veličina',
		lines		: 'linija',
		chkMulti	: 'Dozvoli višestruku selekciju',
		opText		: 'Tekst',
		opValue		: 'Vrednost',
		btnAdd		: 'Dodaj',
		btnModify	: 'Izmeni',
		btnUp		: 'Gore',
		btnDown		: 'Dole',
		btnSetValue : 'Podesi kao označenu vrednost',
		btnDelete	: 'Obriši'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Osobine zone teksta',
		cols		: 'Broj kolona',
		rows		: 'Broj redova'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Osobine tekstualnog polja',
		name		: 'Naziv',
		value		: 'Vrednost',
		charWidth	: 'Širina (karaktera)',
		maxChars	: 'Maksimalno karaktera',
		type		: 'Tip',
		typeText	: 'Tekst',
		typePass	: 'Lozinka'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Osobine skrivenog polja',
		name	: 'Naziv',
		value	: 'Vrednost'
	},

	// Image Dialog.
	image :
	{
		title		: 'Osobine slika',
		titleButton	: 'Osobine dugmeta sa slikom',
		menu		: 'Osobine slika',
		infoTab	: 'Info slike',
		btnUpload	: 'Pošalji na server',
		url		: 'URL',
		upload	: 'Pošalji',
		alt		: 'Alternativni tekst',
		width		: 'Širina',
		height	: 'Visina',
		lockRatio	: 'Zaključaj odnos',
		resetSize	: 'Resetuj veličinu',
		border	: 'Okvir',
		hSpace	: 'HSpace',
		vSpace	: 'VSpace',
		align		: 'Ravnanje',
		alignLeft	: 'Levo',
		alignAbsBottom: 'Abs dole',
		alignAbsMiddle: 'Abs sredina',
		alignBaseline	: 'Bazno',
		alignBottom	: 'Dole',
		alignMiddle	: 'Sredina',
		alignRight	: 'Desno',
		alignTextTop	: 'Vrh teksta',
		alignTop	: 'Vrh',
		preview	: 'Izgled',
		alertUrl	: 'Unesite URL slike',
		linkTab	: 'Link',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Osobine fleša',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Osobine fleša',
		chkPlay		: 'Automatski start',
		chkLoop		: 'Ponavljaj',
		chkMenu		: 'Uključi fleš meni',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Skaliraj',
		scaleAll		: 'Prikaži sve',
		scaleNoBorder	: 'Bez ivice',
		scaleFit		: 'Popuni površinu',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Ravnanje',
		alignLeft	: 'Levo',
		alignAbsBottom: 'Abs dole',
		alignAbsMiddle: 'Abs sredina',
		alignBaseline	: 'Bazno',
		alignBottom	: 'Dole',
		alignMiddle	: 'Sredina',
		alignRight	: 'Desno',
		alignTextTop	: 'Vrh teksta',
		alignTop	: 'Vrh',
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
		bgcolor	: 'Boja pozadine',
		width	: 'Širina',
		height	: 'Visina',
		hSpace	: 'HSpace',
		vSpace	: 'VSpace',
		validateSrc : 'Unesite URL linka',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Proveri spelovanje',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Nije u rečniku',
		changeTo		: 'Izmeni',
		btnIgnore		: 'Ignoriši',
		btnIgnoreAll	: 'Ignoriši sve',
		btnReplace		: 'Zameni',
		btnReplaceAll	: 'Zameni sve',
		btnUndo			: 'Vrati akciju',
		noSuggestions	: '- Bez sugestija -',
		progress		: 'Provera spelovanja u toku...',
		noMispell		: 'Provera spelovanja završena: greške nisu pronadene',
		noChanges		: 'Provera spelovanja završena: Nije izmenjena nijedna rec',
		oneChange		: 'Provera spelovanja završena: Izmenjena je jedna reč',
		manyChanges		: 'Provera spelovanja završena: %1 reč(i) je izmenjeno',
		ieSpellDownload	: 'Provera spelovanja nije instalirana. Da li želite da je skinete sa Interneta?'
	},

	smiley :
	{
		toolbar	: 'Smajli',
		title	: 'Unesi smajlija'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Nabrojiva lista',
	bulletedlist : 'Nenabrojiva lista',
	indent : 'Uvećaj levu marginu',
	outdent : 'Smanji levu marginu',

	justify :
	{
		left : 'Levo ravnanje',
		center : 'Centriran tekst',
		right : 'Desno ravnanje',
		block : 'Obostrano ravnanje'
	},

	blockquote : 'Blockquote', // MISSING

	clipboard :
	{
		title		: 'Zalepi',
		cutError	: 'Sigurnosna podešavanja Vašeg pretraživača ne dozvoljavaju operacije automatskog isecanja teksta. Molimo Vas da koristite prečicu sa tastature (Ctrl+X).',
		copyError	: 'Sigurnosna podešavanja Vašeg pretraživača ne dozvoljavaju operacije automatskog kopiranja teksta. Molimo Vas da koristite prečicu sa tastature (Ctrl+C).',
		pasteMsg	: 'Molimo Vas da zalepite unutar donje povrine koristeći tastaturnu prečicu (<STRONG>Ctrl+V</STRONG>) i da pritisnete <STRONG>OK</STRONG>.',
		securityMsg	: 'Because of your browser security settings, the editor is not able to access your clipboard data directly. You are required to paste it again in this window.' // MISSING
	},

	pastefromword :
	{
		toolbar : 'Zalepi iz Worda',
		title : 'Zalepi iz Worda',
		advice : 'Molimo Vas da zalepite unutar donje povrine koristeći tastaturnu prečicu (<STRONG>Ctrl+V</STRONG>) i da pritisnete <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Ignoriši definicije fontova',
		removeStyle : 'Ukloni definicije stilova'
	},

	pasteText :
	{
		button : 'Zalepi kao čist tekst',
		title : 'Zalepi kao čist tekst'
	},

	templates :
	{
		button : 'Obrasci',
		title : 'Obrasci za sadržaj',
		insertOption: 'Replace actual contents', // MISSING
		selectPromptMsg: 'Molimo Vas da odaberete obrazac koji ce biti primenjen na stranicu (trenutni sadržaj ce biti obrisan):',
		emptyListMsg : '(Nema definisanih obrazaca)'
	},

	showBlocks : 'Show Blocks', // MISSING

	stylesCombo :
	{
		label : 'Stil',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'Format',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'Format',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'Normal',
		tag_pre : 'Formatirano',
		tag_address : 'Adresa',
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
		label : 'Font',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'Font',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'Veličina fonta',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Veličina fonta',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Boja teksta',
		bgColorTitle : 'Boja pozadine',
		auto : 'Automatski',
		more : 'Više boja...'
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
