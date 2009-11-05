/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Lithuanian language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['lt'] =
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
	source			: 'Šaltinis',
	newPage			: 'Naujas puslapis',
	save			: 'Išsaugoti',
	preview			: 'Peržiūra',
	cut				: 'Iškirpti',
	copy			: 'Kopijuoti',
	paste			: 'Įdėti',
	print			: 'Spausdinti',
	underline		: 'Pabrauktas',
	bold			: 'Pusjuodis',
	italic			: 'Kursyvas',
	selectAll		: 'Pažymėti viską',
	removeFormat	: 'Panaikinti formatą',
	strike			: 'Perbrauktas',
	subscript		: 'Apatinis indeksas',
	superscript		: 'Viršutinis indeksas',
	horizontalrule	: 'Įterpti horizontalią liniją',
	pagebreak		: 'Įterpti puslapių skirtuką',
	unlink			: 'Panaikinti nuorodą',
	undo			: 'Atšaukti',
	redo			: 'Atstatyti',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Naršyti po serverį',
		url				: 'URL',
		protocol		: 'Protokolas',
		upload			: 'Siųsti',
		uploadSubmit	: 'Siųsti į serverį',
		image			: 'Vaizdas',
		flash			: 'Flash',
		form			: 'Forma',
		checkbox		: 'Žymimasis langelis',
		radio		: 'Žymimoji akutė',
		textField		: 'Teksto laukas',
		textarea		: 'Teksto sritis',
		hiddenField		: 'Nerodomas laukas',
		button			: 'Mygtukas',
		select	: 'Atrankos laukas',
		imageButton		: 'Vaizdinis mygtukas',
		notSet			: '<nėra nustatyta>',
		id				: 'Id',
		name			: 'Vardas',
		langDir			: 'Teksto kryptis',
		langDirLtr		: 'Iš kairės į dešinę (LTR)',
		langDirRtl		: 'Iš dešinės į kairę (RTL)',
		langCode		: 'Kalbos kodas',
		longDescr		: 'Ilgas aprašymas URL',
		cssClass		: 'Stilių lentelės klasės',
		advisoryTitle	: 'Konsultacinė antraštė',
		cssStyle		: 'Stilius',
		ok				: 'OK',
		cancel			: 'Nutraukti',
		generalTab		: 'Bendros savybės',
		advancedTab		: 'Papildomas',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Įterpti specialų simbolį',
		title		: 'Pasirinkite specialų simbolį'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Įterpti/taisyti nuorodą',
		menu		: 'Taisyti nuorodą',
		title		: 'Nuoroda',
		info		: 'Nuorodos informacija',
		target		: 'Paskirties vieta',
		upload		: 'Siųsti',
		advanced	: 'Papildomas',
		type		: 'Nuorodos tipas',
		toAnchor	: 'Žymė šiame puslapyje',
		toEmail		: 'El.paštas',
		target		: 'Paskirties vieta',
		targetNotSet	: '<nėra nustatyta>',
		targetFrame	: '<kadras>',
		targetPopup	: '<išskleidžiamas langas>',
		targetNew	: 'Naujas langas (_blank)',
		targetTop	: 'Svarbiausias langas (_top)',
		targetSelf	: 'Tas pats langas (_self)',
		targetParent	: 'Pirminis langas (_parent)',
		targetFrameName	: 'Paskirties kadro vardas',
		targetPopupName	: 'Paskirties lango vardas',
		popupFeatures	: 'Išskleidžiamo lango savybės',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Būsenos juosta',
		popupLocationBar	: 'Adreso juosta',
		popupToolbar	: 'Mygtukų juosta',
		popupMenuBar	: 'Meniu juosta',
		popupFullScreen	: 'Visas ekranas (IE)',
		popupScrollBars	: 'Slinkties juostos',
		popupDependent	: 'Priklausomas (Netscape)',
		popupWidth		: 'Plotis',
		popupLeft		: 'Kairė pozicija',
		popupHeight		: 'Aukštis',
		popupTop		: 'Viršutinė pozicija',
		id				: 'Id', // MISSING
		langDir			: 'Teksto kryptis',
		langDirNotSet	: '<nėra nustatyta>',
		langDirLTR		: 'Iš kairės į dešinę (LTR)',
		langDirRTL		: 'Iš dešinės į kairę (RTL)',
		acccessKey		: 'Prieigos raktas',
		name			: 'Vardas',
		langCode		: 'Teksto kryptis',
		tabIndex		: 'Tabuliavimo indeksas',
		advisoryTitle	: 'Konsultacinė antraštė',
		advisoryContentType	: 'Konsultacinio turinio tipas',
		cssClasses		: 'Stilių lentelės klasės',
		charset			: 'Susietų išteklių simbolių lentelė',
		styles			: 'Stilius',
		selectAnchor	: 'Pasirinkite žymę',
		anchorName		: 'Pagal žymės vardą',
		anchorId		: 'Pagal žymės Id',
		emailAddress	: 'El.pašto adresas',
		emailSubject	: 'Žinutės tema',
		emailBody		: 'Žinutės turinys',
		noAnchors		: '(Šiame dokumente žymių nėra)',
		noUrl			: 'Prašome įvesti nuorodos URL',
		noEmail			: 'Prašome įvesti el.pašto adresą'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Įterpti/modifikuoti žymę',
		menu		: 'Žymės savybės',
		title		: 'Žymės savybės',
		name		: 'Žymės vardas',
		errorName	: 'Prašome įvesti žymės vardą'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Surasti ir pakeisti',
		find				: 'Rasti',
		replace				: 'Pakeisti',
		findWhat			: 'Surasti tekstą:',
		replaceWith			: 'Pakeisti tekstu:',
		notFoundMsg			: 'Nurodytas tekstas nerastas.',
		matchCase			: 'Skirti didžiąsias ir mažąsias raides',
		matchWord			: 'Atitikti pilną žodį',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Pakeisti viską',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Lentelė',
		title		: 'Lentelės savybės',
		menu		: 'Lentelės savybės',
		deleteTable	: 'Šalinti lentelę',
		rows		: 'Eilutės',
		columns		: 'Stulpeliai',
		border		: 'Rėmelio dydis',
		align		: 'Lygiuoti',
		alignNotSet	: '<Nenustatyta>',
		alignLeft	: 'Kairę',
		alignCenter	: 'Centrą',
		alignRight	: 'Dešinę',
		width		: 'Plotis',
		widthPx		: 'taškais',
		widthPc		: 'procentais',
		height		: 'Aukštis',
		cellSpace	: 'Tarpas tarp langelių',
		cellPad		: 'Trapas nuo langelio rėmo iki teksto',
		caption		: 'Antraštė',
		summary		: 'Santrauka',
		headers		: 'Antraštės',
		headersNone		: 'Nėra',
		headersColumn	: 'Pirmas stulpelis',
		headersRow		: 'Pirma eilutė',
		headersBoth		: 'Abu',
		invalidRows		: 'Number of rows must be a number greater than 0.', // MISSING
		invalidCols		: 'Number of columns must be a number greater than 0.', // MISSING
		invalidBorder	: 'Border size must be a number.', // MISSING
		invalidWidth	: 'Table width must be a number.', // MISSING
		invalidHeight	: 'Table height must be a number.', // MISSING
		invalidCellSpacing	: 'Cell spacing must be a number.', // MISSING
		invalidCellPadding	: 'Cell padding must be a number.', // MISSING

		cell :
		{
			menu			: 'Langelis',
			insertBefore	: 'Įterpti langelį prieš',
			insertAfter		: 'Įterpti langelį po',
			deleteCell		: 'Šalinti langelius',
			merge			: 'Sujungti langelius',
			mergeRight		: 'Sujungti su dešine',
			mergeDown		: 'Sujungti su apačia',
			splitHorizontal	: 'Skaidyti langelį horizontaliai',
			splitVertical	: 'Skaidyti langelį vertikaliai',
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
			menu			: 'Eilutė',
			insertBefore	: 'Įterpti eilutę prieš',
			insertAfter		: 'Įterpti eilutę po',
			deleteRow		: 'Šalinti eilutes'
		},

		column :
		{
			menu			: 'Stulpelis',
			insertBefore	: 'Įterpti stulpelį prieš',
			insertAfter		: 'Įterpti stulpelį po',
			deleteColumn	: 'Šalinti stulpelius'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Mygtuko savybės',
		text		: 'Tekstas (Reikšmė)',
		type		: 'Tipas',
		typeBtn		: 'Mygtukas',
		typeSbm		: 'Siųsti',
		typeRst		: 'Išvalyti'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Žymimojo langelio savybės',
		radioTitle	: 'Žymimosios akutės savybės',
		value		: 'Reikšmė',
		selected	: 'Pažymėtas'
	},

	// Form Dialog.
	form :
	{
		title		: 'Formos savybės',
		menu		: 'Formos savybės',
		action		: 'Veiksmas',
		method		: 'Metodas',
		encoding	: 'Encoding', // MISSING
		target		: 'Paskirties vieta',
		targetNotSet	: '<nėra nustatyta>',
		targetNew	: 'Naujas langas (_blank)',
		targetTop	: 'Svarbiausias langas (_top)',
		targetSelf	: 'Tas pats langas (_self)',
		targetParent	: 'Pirminis langas (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Atrankos lauko savybės',
		selectInfo	: 'Informacija',
		opAvail		: 'Galimos parinktys',
		value		: 'Reikšmė',
		size		: 'Dydis',
		lines		: 'eilučių',
		chkMulti	: 'Leisti daugeriopą atranką',
		opText		: 'Tekstas',
		opValue		: 'Reikšmė',
		btnAdd		: 'Įtraukti',
		btnModify	: 'Modifikuoti',
		btnUp		: 'Aukštyn',
		btnDown		: 'Žemyn',
		btnSetValue : 'Laikyti pažymėta reikšme',
		btnDelete	: 'Trinti'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Teksto srities savybės',
		cols		: 'Ilgis',
		rows		: 'Plotis'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Teksto lauko savybės',
		name		: 'Vardas',
		value		: 'Reikšmė',
		charWidth	: 'Ilgis simboliais',
		maxChars	: 'Maksimalus simbolių skaičius',
		type		: 'Tipas',
		typeText	: 'Tekstas',
		typePass	: 'Slaptažodis'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Nerodomo lauko savybės',
		name	: 'Vardas',
		value	: 'Reikšmė'
	},

	// Image Dialog.
	image :
	{
		title		: 'Vaizdo savybės',
		titleButton	: 'Vaizdinio mygtuko savybės',
		menu		: 'Vaizdo savybės',
		infoTab	: 'Vaizdo informacija',
		btnUpload	: 'Siųsti į serverį',
		url		: 'URL',
		upload	: 'Nusiųsti',
		alt		: 'Alternatyvus Tekstas',
		width		: 'Plotis',
		height	: 'Aukštis',
		lockRatio	: 'Išlaikyti proporciją',
		resetSize	: 'Atstatyti dydį',
		border	: 'Rėmelis',
		hSpace	: 'Hor.Erdvė',
		vSpace	: 'Vert.Erdvė',
		align		: 'Lygiuoti',
		alignLeft	: 'Kairę',
		alignAbsBottom: 'Absoliučią apačią',
		alignAbsMiddle: 'Absoliutų vidurį',
		alignBaseline	: 'Apatinę liniją',
		alignBottom	: 'Apačią',
		alignMiddle	: 'Vidurį',
		alignRight	: 'Dešinę',
		alignTextTop	: 'Teksto viršūnę',
		alignTop	: 'Viršūnę',
		preview	: 'Peržiūra',
		alertUrl	: 'Prašome įvesti vaizdo URL',
		linkTab	: 'Nuoroda',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Flash savybės',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Flash savybės',
		chkPlay		: 'Automatinis paleidimas',
		chkLoop		: 'Ciklas',
		chkMenu		: 'Leisti Flash meniu',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Mastelis',
		scaleAll		: 'Rodyti visą',
		scaleNoBorder	: 'Be rėmelio',
		scaleFit		: 'Tikslus atitikimas',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Lygiuoti',
		alignLeft	: 'Kairę',
		alignAbsBottom: 'Absoliučią apačią',
		alignAbsMiddle: 'Absoliutų vidurį',
		alignBaseline	: 'Apatinę liniją',
		alignBottom	: 'Apačią',
		alignMiddle	: 'Vidurį',
		alignRight	: 'Dešinę',
		alignTextTop	: 'Teksto viršūnę',
		alignTop	: 'Viršūnę',
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
		bgcolor	: 'Fono spalva',
		width	: 'Plotis',
		height	: 'Aukštis',
		hSpace	: 'Hor.Erdvė',
		vSpace	: 'Vert.Erdvė',
		validateSrc : 'Prašome įvesti nuorodos URL',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Rašybos tikrinimas',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Žodyne nerastas',
		changeTo		: 'Pakeisti į',
		btnIgnore		: 'Ignoruoti',
		btnIgnoreAll	: 'Ignoruoti visus',
		btnReplace		: 'Pakeisti',
		btnReplaceAll	: 'Pakeisti visus',
		btnUndo			: 'Atšaukti',
		noSuggestions	: '- Nėra pasiūlymų -',
		progress		: 'Vyksta rašybos tikrinimas...',
		noMispell		: 'Rašybos tikrinimas baigtas: Nerasta rašybos klaidų',
		noChanges		: 'Rašybos tikrinimas baigtas: Nėra pakeistų žodžių',
		oneChange		: 'Rašybos tikrinimas baigtas: Vienas žodis pakeistas',
		manyChanges		: 'Rašybos tikrinimas baigtas: Pakeista %1 žodžių',
		ieSpellDownload	: 'Rašybos tikrinimas neinstaliuotas. Ar Jūs norite jį dabar atsisiųsti?'
	},

	smiley :
	{
		toolbar	: 'Veideliai',
		title	: 'Įterpti veidelį'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Numeruotas sąrašas',
	bulletedlist : 'Suženklintas sąrašas',
	indent : 'Padidinti įtrauką',
	outdent : 'Sumažinti įtrauką',

	justify :
	{
		left : 'Lygiuoti kairę',
		center : 'Centruoti',
		right : 'Lygiuoti dešinę',
		block : 'Lygiuoti abi puses'
	},

	blockquote : 'Citata',

	clipboard :
	{
		title		: 'Įdėti',
		cutError	: 'Jūsų naršyklės saugumo nustatymai neleidžia redaktoriui automatiškai įvykdyti iškirpimo operacijų. Tam prašome naudoti klaviatūrą (Ctrl+X).',
		copyError	: 'Jūsų naršyklės saugumo nustatymai neleidžia redaktoriui automatiškai įvykdyti kopijavimo operacijų. Tam prašome naudoti klaviatūrą (Ctrl+C).',
		pasteMsg	: 'Žemiau esančiame įvedimo lauke įdėkite tekstą, naudodami klaviatūrą (<STRONG>Ctrl+V</STRONG>) ir paspauskite mygtuką <STRONG>OK</STRONG>.',
		securityMsg	: 'Dėl jūsų naršyklės saugumo nustatymų, redaktorius negali tiesiogiai pasiekti laikinosios atminties. Jums reikia nukopijuoti dar kartą į šį langą.'
	},

	pastefromword :
	{
		toolbar : 'Įdėti iš Word',
		title : 'Įdėti iš Word',
		advice : 'Žemiau esančiame įvedimo lauke įdėkite tekstą, naudodami klaviatūrą (<STRONG>Ctrl+V</STRONG>) ir paspauskite mygtuką <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Ignoruoti šriftų nustatymus',
		removeStyle : 'Pašalinti stilių nustatymus'
	},

	pasteText :
	{
		button : 'Įdėti kaip gryną tekstą',
		title : 'Įdėti kaip gryną tekstą'
	},

	templates :
	{
		button : 'Šablonai',
		title : 'Turinio šablonai',
		insertOption: 'Pakeisti dabartinį turinį pasirinktu šablonu',
		selectPromptMsg: 'Pasirinkite norimą šabloną<br>(<b>Dėmesio!</b> esamas turinys bus prarastas):',
		emptyListMsg : '(Šablonų sąrašas tuščias)'
	},

	showBlocks : 'Rodyti blokus',

	stylesCombo :
	{
		label : 'Stilius',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'Šrifto formatas',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'Šrifto formatas',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'Normalus',
		tag_pre : 'Formuotas',
		tag_address : 'Kreipinio',
		tag_h1 : 'Antraštinis 1',
		tag_h2 : 'Antraštinis 2',
		tag_h3 : 'Antraštinis 3',
		tag_h4 : 'Antraštinis 4',
		tag_h5 : 'Antraštinis 5',
		tag_h6 : 'Antraštinis 6',
		tag_div : 'Normal (DIV)' // MISSING
	},

	font :
	{
		label : 'Šriftas',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'Šriftas',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'Šrifto dydis',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Šrifto dydis',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Teksto spalva',
		bgColorTitle : 'Fono spalva',
		auto : 'Automatinis',
		more : 'Daugiau spalvų...'
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
