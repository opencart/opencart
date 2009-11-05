/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Latvian language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['lv'] =
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
	source			: 'HTML kods',
	newPage			: 'Jauna lapa',
	save			: 'Saglabāt',
	preview			: 'Pārskatīt',
	cut				: 'Izgriezt',
	copy			: 'Kopēt',
	paste			: 'Ievietot',
	print			: 'Drukāt',
	underline		: 'Apakšsvītra',
	bold			: 'Treknu šriftu',
	italic			: 'Slīprakstā',
	selectAll		: 'Iezīmēt visu',
	removeFormat	: 'Noņemt stilus',
	strike			: 'Pārsvītrots',
	subscript		: 'Zemrakstā',
	superscript		: 'Augšrakstā',
	horizontalrule	: 'Ievietot horizontālu Atdalītājsvītru',
	pagebreak		: 'Ievietot lapas pārtraukumu',
	unlink			: 'Noņemt hipersaiti',
	undo			: 'Atcelt',
	redo			: 'Atkārtot',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Skatīt servera saturu',
		url				: 'URL',
		protocol		: 'Protokols',
		upload			: 'Augšupielādēt',
		uploadSubmit	: 'Nosūtīt serverim',
		image			: 'Attēls',
		flash			: 'Flash',
		form			: 'Forma',
		checkbox		: 'Atzīmēšanas kastīte',
		radio		: 'Izvēles poga',
		textField		: 'Teksta rinda',
		textarea		: 'Teksta laukums',
		hiddenField		: 'Paslēpta teksta rinda',
		button			: 'Poga',
		select	: 'Iezīmēšanas lauks',
		imageButton		: 'Attēlpoga',
		notSet			: '<nav iestatīts>',
		id				: 'Id',
		name			: 'Nosaukums',
		langDir			: 'Valodas lasīšanas virziens',
		langDirLtr		: 'No kreisās uz labo (LTR)',
		langDirRtl		: 'No labās uz kreiso (RTL)',
		langCode		: 'Valodas kods',
		longDescr		: 'Gara apraksta Hipersaite',
		cssClass		: 'Stilu saraksta klases',
		advisoryTitle	: 'Konsultatīvs virsraksts',
		cssStyle		: 'Stils',
		ok				: 'Darīts!',
		cancel			: 'Atcelt',
		generalTab		: 'General', // MISSING
		advancedTab		: 'Izvērstais',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Ievietot speciālo simbolu',
		title		: 'Ievietot īpašu simbolu'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Ievietot/Labot hipersaiti',
		menu		: 'Labot hipersaiti',
		title		: 'Hipersaite',
		info		: 'Hipersaites informācija',
		target		: 'Mērķis',
		upload		: 'Augšupielādēt',
		advanced	: 'Izvērstais',
		type		: 'Hipersaites tips',
		toAnchor	: 'Iezīme šajā lapā',
		toEmail		: 'E-pasts',
		target		: 'Mērķis',
		targetNotSet	: '<nav iestatīts>',
		targetFrame	: '<ietvars>',
		targetPopup	: '<uznirstošā logā>',
		targetNew	: 'Jaunā logā (_blank)',
		targetTop	: 'Visredzamākajā logā (_top)',
		targetSelf	: 'Tajā pašā logā (_self)',
		targetParent	: 'Esošajā logā (_parent)',
		targetFrameName	: 'Mērķa ietvara nosaukums',
		targetPopupName	: 'Uznirstošā loga nosaukums',
		popupFeatures	: 'Uznirstošā loga nosaukums īpašības',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Statusa josla',
		popupLocationBar	: 'Atrašanās vietas josla',
		popupToolbar	: 'Rīku josla',
		popupMenuBar	: 'Izvēlnes josla',
		popupFullScreen	: 'Pilnā ekrānā (IE)',
		popupScrollBars	: 'Ritjoslas',
		popupDependent	: 'Atkarīgs (Netscape)',
		popupWidth		: 'Platums',
		popupLeft		: 'Kreisā koordināte',
		popupHeight		: 'Augstums',
		popupTop		: 'Augšējā koordināte',
		id				: 'Id', // MISSING
		langDir			: 'Valodas lasīšanas virziens',
		langDirNotSet	: '<nav iestatīts>',
		langDirLTR		: 'No kreisās uz labo (LTR)',
		langDirRTL		: 'No labās uz kreiso (RTL)',
		acccessKey		: 'Pieejas kods',
		name			: 'Nosaukums',
		langCode		: 'Valodas lasīšanas virziens',
		tabIndex		: 'Ciļņu indekss',
		advisoryTitle	: 'Konsultatīvs virsraksts',
		advisoryContentType	: 'Konsultatīvs satura tips',
		cssClasses		: 'Stilu saraksta klases',
		charset			: 'Pievienotā resursa kodu tabula',
		styles			: 'Stils',
		selectAnchor	: 'Izvēlēties iezīmi',
		anchorName		: 'Pēc iezīmes nosaukuma',
		anchorId		: 'Pēc elementa ID',
		emailAddress	: 'E-pasta adrese',
		emailSubject	: 'Ziņas tēma',
		emailBody		: 'Ziņas saturs',
		noAnchors		: '(Šajā dokumentā nav iezīmju)',
		noUrl			: 'Lūdzu norādi hipersaiti',
		noEmail			: 'Lūdzu norādi e-pasta adresi'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Ievietot/Labot iezīmi',
		menu		: 'Iezīmes īpašības',
		title		: 'Iezīmes īpašības',
		name		: 'Iezīmes nosaukums',
		errorName	: 'Lūdzu norādiet iezīmes nosaukumu'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Find and Replace', // MISSING
		find				: 'Meklēt',
		replace				: 'Nomainīt',
		findWhat			: 'Meklēt:',
		replaceWith			: 'Nomainīt uz:',
		notFoundMsg			: 'Norādītā frāze netika atrasta.',
		matchCase			: 'Reģistrjūtīgs',
		matchWord			: 'Jāsakrīt pilnībā',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Aizvietot visu',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Tabula',
		title		: 'Tabulas īpašības',
		menu		: 'Tabulas īpašības',
		deleteTable	: 'Dzēst tabulu',
		rows		: 'Rindas',
		columns		: 'Kolonnas',
		border		: 'Rāmja izmērs',
		align		: 'Novietojums',
		alignNotSet	: '<nav norādīts>',
		alignLeft	: 'Pa kreisi',
		alignCenter	: 'Centrēti',
		alignRight	: 'Pa labi',
		width		: 'Platums',
		widthPx		: 'pikseļos',
		widthPc		: 'procentuāli',
		height		: 'Augstums',
		cellSpace	: 'Rūtiņu atstatums',
		cellPad		: 'Rūtiņu nobīde',
		caption		: 'Leģenda',
		summary		: 'Anotācija',
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
			menu			: 'Šūna',
			insertBefore	: 'Insert Cell Before', // MISSING
			insertAfter		: 'Insert Cell After', // MISSING
			deleteCell		: 'Dzēst rūtiņas',
			merge			: 'Apvienot rūtiņas',
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
			menu			: 'Rinda',
			insertBefore	: 'Insert Row Before', // MISSING
			insertAfter		: 'Insert Row After', // MISSING
			deleteRow		: 'Dzēst rindas'
		},

		column :
		{
			menu			: 'Kolonna',
			insertBefore	: 'Insert Column Before', // MISSING
			insertAfter		: 'Insert Column After', // MISSING
			deleteColumn	: 'Dzēst kolonnas'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Pogas īpašības',
		text		: 'Teksts (vērtība)',
		type		: 'Tips',
		typeBtn		: 'Button', // MISSING
		typeSbm		: 'Submit', // MISSING
		typeRst		: 'Reset' // MISSING
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Atzīmēšanas kastītes īpašības',
		radioTitle	: 'Izvēles poga īpašības',
		value		: 'Vērtība',
		selected	: 'Iezīmēts'
	},

	// Form Dialog.
	form :
	{
		title		: 'Formas īpašības',
		menu		: 'Formas īpašības',
		action		: 'Darbība',
		method		: 'Metode',
		encoding	: 'Encoding', // MISSING
		target		: 'Mērķis',
		targetNotSet	: '<nav iestatīts>',
		targetNew	: 'Jaunā logā (_blank)',
		targetTop	: 'Visredzamākajā logā (_top)',
		targetSelf	: 'Tajā pašā logā (_self)',
		targetParent	: 'Esošajā logā (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Iezīmēšanas lauka īpašības',
		selectInfo	: 'Informācija',
		opAvail		: 'Pieejamās iespējas',
		value		: 'Vērtība',
		size		: 'Izmērs',
		lines		: 'rindas',
		chkMulti	: 'Atļaut vairākus iezīmējumus',
		opText		: 'Teksts',
		opValue		: 'Vērtība',
		btnAdd		: 'Pievienot',
		btnModify	: 'Veikt izmaiņas',
		btnUp		: 'Augšup',
		btnDown		: 'Lejup',
		btnSetValue : 'Noteikt kā iezīmēto vērtību',
		btnDelete	: 'Dzēst'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Teksta laukuma īpašības',
		cols		: 'Kolonnas',
		rows		: 'Rindas'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Teksta rindas  īpašības',
		name		: 'Nosaukums',
		value		: 'Vērtība',
		charWidth	: 'Simbolu platums',
		maxChars	: 'Simbolu maksimālais daudzums',
		type		: 'Tips',
		typeText	: 'Teksts',
		typePass	: 'Parole'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Paslēptās teksta rindas īpašības',
		name	: 'Nosaukums',
		value	: 'Vērtība'
	},

	// Image Dialog.
	image :
	{
		title		: 'Attēla īpašības',
		titleButton	: 'Attēlpogas īpašības',
		menu		: 'Attēla īpašības',
		infoTab	: 'Informācija par attēlu',
		btnUpload	: 'Nosūtīt serverim',
		url		: 'URL',
		upload	: 'Augšupielādēt',
		alt		: 'Alternatīvais teksts',
		width		: 'Platums',
		height	: 'Augstums',
		lockRatio	: 'Nemainīga Augstuma/Platuma attiecība',
		resetSize	: 'Atjaunot sākotnējo izmēru',
		border	: 'Rāmis',
		hSpace	: 'Horizontālā telpa',
		vSpace	: 'Vertikālā telpa',
		align		: 'Nolīdzināt',
		alignLeft	: 'Pa kreisi',
		alignAbsBottom: 'Absolūti apakšā',
		alignAbsMiddle: 'Absolūti vertikāli centrēts',
		alignBaseline	: 'Pamatrindā',
		alignBottom	: 'Apakšā',
		alignMiddle	: 'Vertikāli centrēts',
		alignRight	: 'Pa labi',
		alignTextTop	: 'Teksta augšā',
		alignTop	: 'Augšā',
		preview	: 'Pārskats',
		alertUrl	: 'Lūdzu norādīt attēla hipersaiti',
		linkTab	: 'Hipersaite',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Flash īpašības',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Flash īpašības',
		chkPlay		: 'Automātiska atskaņošana',
		chkLoop		: 'Nepārtraukti',
		chkMenu		: 'Atļaut Flash izvēlni',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Mainīt izmēru',
		scaleAll		: 'Rādīt visu',
		scaleNoBorder	: 'Bez rāmja',
		scaleFit		: 'Precīzs izmērs',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Nolīdzināt',
		alignLeft	: 'Pa kreisi',
		alignAbsBottom: 'Absolūti apakšā',
		alignAbsMiddle: 'Absolūti vertikāli centrēts',
		alignBaseline	: 'Pamatrindā',
		alignBottom	: 'Apakšā',
		alignMiddle	: 'Vertikāli centrēts',
		alignRight	: 'Pa labi',
		alignTextTop	: 'Teksta augšā',
		alignTop	: 'Augšā',
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
		bgcolor	: 'Fona krāsa',
		width	: 'Platums',
		height	: 'Augstums',
		hSpace	: 'Horizontālā telpa',
		vSpace	: 'Vertikālā telpa',
		validateSrc : 'Lūdzu norādi hipersaiti',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Pareizrakstības pārbaude',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Netika atrasts vārdnīcā',
		changeTo		: 'Nomainīt uz',
		btnIgnore		: 'Ignorēt',
		btnIgnoreAll	: 'Ignorēt visu',
		btnReplace		: 'Aizvietot',
		btnReplaceAll	: 'Aizvietot visu',
		btnUndo			: 'Atcelt',
		noSuggestions	: '- Nav ieteikumu -',
		progress		: 'Notiek pareizrakstības pārbaude...',
		noMispell		: 'Pareizrakstības pārbaude pabeigta: kļūdas netika atrastas',
		noChanges		: 'Pareizrakstības pārbaude pabeigta: nekas netika labots',
		oneChange		: 'Pareizrakstības pārbaude pabeigta: 1 vārds izmainīts',
		manyChanges		: 'Pareizrakstības pārbaude pabeigta: %1 vārdi tika mainīti',
		ieSpellDownload	: 'Pareizrakstības pārbaudītājs nav pievienots. Vai vēlaties to lejupielādēt tagad?'
	},

	smiley :
	{
		toolbar	: 'Smaidiņi',
		title	: 'Ievietot smaidiņu'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Numurēts saraksts',
	bulletedlist : 'Izcelts saraksts',
	indent : 'Palielināt atkāpi',
	outdent : 'Samazināt atkāpi',

	justify :
	{
		left : 'Izlīdzināt pa kreisi',
		center : 'Izlīdzināt pret centru',
		right : 'Izlīdzināt pa labi',
		block : 'Izlīdzināt malas'
	},

	blockquote : 'Blockquote', // MISSING

	clipboard :
	{
		title		: 'Ievietot',
		cutError	: 'Jūsu pārlūkprogrammas drošības iestatījumi nepieļauj editoram automātiski veikt izgriešanas darbību.  Lūdzu, izmantojiet (Ctrl+X, lai veiktu šo darbību.',
		copyError	: 'Jūsu pārlūkprogrammas drošības iestatījumi nepieļauj editoram automātiski veikt kopēšanas darbību.  Lūdzu, izmantojiet (Ctrl+C), lai veiktu šo darbību.',
		pasteMsg	: 'Lūdzu, ievietojiet tekstu šajā laukumā, izmantojot klaviatūru (<STRONG>Ctrl+V</STRONG>) un apstipriniet ar <STRONG>Darīts!</STRONG>.',
		securityMsg	: 'Because of your browser security settings, the editor is not able to access your clipboard data directly. You are required to paste it again in this window.' // MISSING
	},

	pastefromword :
	{
		toolbar : 'Ievietot no Worda',
		title : 'Ievietot no Worda',
		advice : 'Lūdzu, ievietojiet tekstu šajā laukumā, izmantojot klaviatūru (<STRONG>Ctrl+V</STRONG>) un apstipriniet ar <STRONG>Darīts!</STRONG>.',
		ignoreFontFace : 'Ignorēt iepriekš norādītos fontus',
		removeStyle : 'Noņemt norādītos stilus'
	},

	pasteText :
	{
		button : 'Ievietot kā vienkāršu tekstu',
		title : 'Ievietot kā vienkāršu tekstu'
	},

	templates :
	{
		button : 'Sagataves',
		title : 'Satura sagataves',
		insertOption: 'Replace actual contents', // MISSING
		selectPromptMsg: 'Lūdzu, norādiet sagatavi, ko atvērt editorā<br>(patreizējie dati tiks zaudēti):',
		emptyListMsg : '(Nav norādītas sagataves)'
	},

	showBlocks : 'Show Blocks', // MISSING

	stylesCombo :
	{
		label : 'Stils',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'Formāts',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'Formāts',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'Normāls teksts',
		tag_pre : 'Formatēts teksts',
		tag_address : 'Adrese',
		tag_h1 : 'Virsraksts 1',
		tag_h2 : 'Virsraksts 2',
		tag_h3 : 'Virsraksts 3',
		tag_h4 : 'Virsraksts 4',
		tag_h5 : 'Virsraksts 5',
		tag_h6 : 'Virsraksts 6',
		tag_div : 'Rindkopa (DIV)'
	},

	font :
	{
		label : 'Šrifts',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'Šrifts',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'Izmērs',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Izmērs',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Teksta krāsa',
		bgColorTitle : 'Fona krāsa',
		auto : 'Automātiska',
		more : 'Plašāka palete...'
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
