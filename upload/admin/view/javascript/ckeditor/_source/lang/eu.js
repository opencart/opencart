/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Basque language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['eu'] =
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
	editorTitle		: 'Testu aberastuentzako editorea, %1',

	// Toolbar buttons without dialogs.
	source			: 'HTML Iturburua',
	newPage			: 'Orrialde Berria',
	save			: 'Gorde',
	preview			: 'Aurrebista',
	cut				: 'Ebaki',
	copy			: 'Kopiatu',
	paste			: 'Itsatsi',
	print			: 'Inprimatu',
	underline		: 'Azpimarratu',
	bold			: 'Lodia',
	italic			: 'Etzana',
	selectAll		: 'Hautatu dena',
	removeFormat	: 'Kendu Formatua',
	strike			: 'Marratua',
	subscript		: 'Azpi-indize',
	superscript		: 'Goi-indize',
	horizontalrule	: 'Txertatu Marra Horizontala',
	pagebreak		: 'Txertatu Orrialde-jauzia',
	unlink			: 'Kendu Esteka',
	undo			: 'Desegin',
	redo			: 'Berregin',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Zerbitzaria arakatu',
		url				: 'URL',
		protocol		: 'Protokoloa',
		upload			: 'Gora kargatu',
		uploadSubmit	: 'Zerbitzarira bidalia',
		image			: 'Irudia',
		flash			: 'Flasha',
		form			: 'Formularioa',
		checkbox		: 'Kontrol-laukia',
		radio		: 'Aukera-botoia',
		textField		: 'Testu Eremua',
		textarea		: 'Testu-area',
		hiddenField		: 'Ezkutuko Eremua',
		button			: 'Botoia',
		select	: 'Hautespen Eremua',
		imageButton		: 'Irudi Botoia',
		notSet			: '<Ezarri gabe>',
		id				: 'Id',
		name			: 'Izena',
		langDir			: 'Hizkuntzaren Norabidea',
		langDirLtr		: 'Ezkerretik Eskumara(LTR)',
		langDirRtl		: 'Eskumatik Ezkerrera (RTL)',
		langCode		: 'Hizkuntza Kodea',
		longDescr		: 'URL Deskribapen Luzea',
		cssClass		: 'Estilo-orriko Klaseak',
		advisoryTitle	: 'Izenburua',
		cssStyle		: 'Estiloa',
		ok				: 'Ados',
		cancel			: 'Utzi',
		generalTab		: 'Orokorra',
		advancedTab		: 'Aurreratua',
		validateNumberFailed	: 'Balio hau ez da zenbaki bat.',
		confirmNewPage	: 'Eduki honetan gorde gabe dauden aldaketak galduko dira. Ziur zaude orri berri bat kargatu nahi duzula?',
		confirmCancel	: 'Aukera batzuk aldatu egin dira. Ziur zaude elkarrizketa-koadroa itxi nahi duzula?',

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, erabilezina</span>'
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Txertatu Karaktere Berezia',
		title		: 'Karaktere Berezia Aukeratu'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Txertatu/Editatu Esteka',
		menu		: 'Aldatu Esteka',
		title		: 'Esteka',
		info		: 'Estekaren Informazioa',
		target		: 'Target (Helburua)',
		upload		: 'Gora kargatu',
		advanced	: 'Aurreratua',
		type		: 'Esteka Mota',
		toAnchor	: 'Aingura orrialde honetan',
		toEmail		: 'ePosta',
		target		: 'Target (Helburua)',
		targetNotSet	: '<Ezarri gabe>',
		targetFrame	: '<marko>',
		targetPopup	: '<popup leihoa>',
		targetNew	: 'Leiho Berria (_blank)',
		targetTop	: 'Goiko Leihoa (_top)',
		targetSelf	: 'Leiho Berdina (_self)',
		targetParent	: 'Leiho Gurasoa (_parent)',
		targetFrameName	: 'Marko Helburuaren Izena',
		targetPopupName	: 'Popup Leihoaren Izena',
		popupFeatures	: 'Popup Leihoaren Ezaugarriak',
		popupResizable	: 'Tamaina Aldakorra',
		popupStatusBar	: 'Egoera Barra',
		popupLocationBar	: 'Kokaleku Barra',
		popupToolbar	: 'Tresna Barra',
		popupMenuBar	: 'Menu Barra',
		popupFullScreen	: 'Pantaila Osoa (IE)',
		popupScrollBars	: 'Korritze Barrak',
		popupDependent	: 'Menpekoa (Netscape)',
		popupWidth		: 'Zabalera',
		popupLeft		: 'Ezkerreko  Posizioa',
		popupHeight		: 'Altuera',
		popupTop		: 'Goiko Posizioa',
		id				: 'Id',
		langDir			: 'Hizkuntzaren Norabidea',
		langDirNotSet	: '<Ezarri gabe>',
		langDirLTR		: 'Ezkerretik Eskumara(LTR)',
		langDirRTL		: 'Eskumatik Ezkerrera (RTL)',
		acccessKey		: 'Sarbide-gakoa',
		name			: 'Izena',
		langCode		: 'Hizkuntzaren Norabidea',
		tabIndex		: 'Tabulazio Indizea',
		advisoryTitle	: 'Izenburua',
		advisoryContentType	: 'Eduki Mota (Content Type)',
		cssClasses		: 'Estilo-orriko Klaseak',
		charset			: 'Estekatutako Karaktere Multzoa',
		styles			: 'Estiloa',
		selectAnchor	: 'Aingura bat hautatu',
		anchorName		: 'Aingura izenagatik',
		anchorId		: 'Elementuaren ID-gatik',
		emailAddress	: 'ePosta Helbidea',
		emailSubject	: 'Mezuaren Gaia',
		emailBody		: 'Mezuaren Gorputza',
		noAnchors		: '(Ez daude aingurak eskuragarri dokumentuan)',
		noUrl			: 'Mesedez URL esteka idatzi',
		noEmail			: 'Mesedez ePosta helbidea idatzi'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Aingura',
		menu		: 'Ainguraren Ezaugarriak',
		title		: 'Ainguraren Ezaugarriak',
		name		: 'Ainguraren Izena',
		errorName	: 'Idatzi ainguraren izena'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Bilatu eta Ordeztu',
		find				: 'Bilatu',
		replace				: 'Ordezkatu',
		findWhat			: 'Zer bilatu:',
		replaceWith			: 'Zerekin ordeztu:',
		notFoundMsg			: 'Idatzitako testua ez da topatu.',
		matchCase			: 'Maiuskula/minuskula',
		matchWord			: 'Esaldi osoa bilatu',
		matchCyclic			: 'Bilaketa ziklikoa',
		replaceAll			: 'Ordeztu Guztiak',
		replaceSuccessMsg	: 'Zenbat aldiz ordeztua: %1'
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Taula',
		title		: 'Taularen Ezaugarriak',
		menu		: 'Taularen Ezaugarriak',
		deleteTable	: 'Ezabatu Taula',
		rows		: 'Lerroak',
		columns		: 'Zutabeak',
		border		: 'Ertzaren Zabalera',
		align		: 'Lerrokatu',
		alignNotSet	: '<Ezarri gabe>',
		alignLeft	: 'Ezkerrean',
		alignCenter	: 'Erdian',
		alignRight	: 'Eskuman',
		width		: 'Zabalera',
		widthPx		: 'pixel',
		widthPc		: 'ehuneko',
		height		: 'Altuera',
		cellSpace	: 'Gelaxka arteko tartea',
		cellPad		: 'Gelaxken betegarria',
		caption		: 'Epigrafea',
		summary		: 'Laburpena',
		headers		: 'Goiburuak',
		headersNone		: 'Bat ere ez',
		headersColumn	: 'Lehen zutabea',
		headersRow		: 'Lehen lerroa',
		headersBoth		: 'Biak',
		invalidRows		: 'Lerro kopurua 0 baino handiagoa den zenbakia izan behar da.',
		invalidCols		: 'Zutabe kopurua 0 baino handiagoa den zenbakia izan behar da.',
		invalidBorder	: 'Ertzaren tamaina zenbaki bat izan behar da.',
		invalidWidth	: 'Taularen zabalera zenbaki bat izan behar da.',
		invalidHeight	: 'Taularen altuera zenbaki bat izan behar da.',
		invalidCellSpacing	: 'Gelaxka arteko tartea zenbaki bat izan behar da.',
		invalidCellPadding	: 'Gelaxken betegarria zenbaki bat izan behar da.',

		cell :
		{
			menu			: 'Gelaxka',
			insertBefore	: 'Txertatu Gelaxka Aurretik',
			insertAfter		: 'Txertatu Gelaxka Ostean',
			deleteCell		: 'Kendu Gelaxkak',
			merge			: 'Batu Gelaxkak',
			mergeRight		: 'Elkartu Eskumara',
			mergeDown		: 'Elkartu Behera',
			splitHorizontal	: 'Banatu Gelaxkak Horizontalki',
			splitVertical	: 'Banatu Gelaxkak Bertikalki',
			title			: 'Gelaxken Ezaugarriak',
			cellType		: 'Gelaxka Mota',
			rowSpan			: 'Hedatutako Lerroak',
			colSpan			: 'Hedatutako Zutabeak',
			wordWrap		: 'Itzulbira',
			hAlign			: 'Lerrokatze Horizontala',
			vAlign			: 'Lerrokatze Bertikala',
			alignTop		: 'Goian',
			alignMiddle		: 'Erdian',
			alignBottom		: 'Behean',
			alignBaseline	: 'Oinarri-lerroan',
			bgColor			: 'Fondoaren Kolorea',
			borderColor		: 'Ertzaren Kolorea',
			data			: 'Data',
			header			: 'Goiburua',
			yes				: 'Bai',
			no				: 'Ez',
			invalidWidth	: 'Gelaxkaren zabalera zenbaki bat izan behar da.',
			invalidHeight	: 'Gelaxkaren altuera zenbaki bat izan behar da.',
			invalidRowSpan	: 'Lerroen hedapena zenbaki osoa izan behar da.',
			invalidColSpan	: 'Zutabeen hedapena zenbaki osoa izan behar da.',
			chooseColor : 'Choose' // MISSING
		},

		row :
		{
			menu			: 'Lerroa',
			insertBefore	: 'Txertatu Lerroa Aurretik',
			insertAfter		: 'Txertatu Lerroa Ostean',
			deleteRow		: 'Ezabatu Lerroak'
		},

		column :
		{
			menu			: 'Zutabea',
			insertBefore	: 'Txertatu Zutabea Aurretik',
			insertAfter		: 'Txertatu Zutabea Ostean',
			deleteColumn	: 'Ezabatu Zutabeak'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Botoiaren Ezaugarriak',
		text		: 'Testua (Balorea)',
		type		: 'Mota',
		typeBtn		: 'Botoia',
		typeSbm		: 'Bidali',
		typeRst		: 'Garbitu'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Kontrol-laukiko Ezaugarriak',
		radioTitle	: 'Aukera-botoiaren Ezaugarriak',
		value		: 'Balorea',
		selected	: 'Hautatuta'
	},

	// Form Dialog.
	form :
	{
		title		: 'Formularioaren Ezaugarriak',
		menu		: 'Formularioaren Ezaugarriak',
		action		: 'Ekintza',
		method		: 'Metodoa',
		encoding	: 'Kodeketa',
		target		: 'Target (Helburua)',
		targetNotSet	: '<Ezarri gabe>',
		targetNew	: 'Leiho Berria (_blank)',
		targetTop	: 'Goiko Leihoa (_top)',
		targetSelf	: 'Leiho Berdina (_self)',
		targetParent	: 'Leiho Gurasoa (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Hautespen Eremuaren Ezaugarriak',
		selectInfo	: 'Informazioa',
		opAvail		: 'Aukera Eskuragarriak',
		value		: 'Balorea',
		size		: 'Tamaina',
		lines		: 'lerro kopurura',
		chkMulti	: 'Hautaketa anitzak baimendu',
		opText		: 'Testua',
		opValue		: 'Balorea',
		btnAdd		: 'Gehitu',
		btnModify	: 'Aldatu',
		btnUp		: 'Gora',
		btnDown		: 'Behera',
		btnSetValue : 'Aukeratutako balorea ezarri',
		btnDelete	: 'Ezabatu'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Testu-arearen Ezaugarriak',
		cols		: 'Zutabeak',
		rows		: 'Lerroak'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Testu Eremuaren Ezaugarriak',
		name		: 'Izena',
		value		: 'Balorea',
		charWidth	: 'Zabalera',
		maxChars	: 'Zenbat karaktere gehienez',
		type		: 'Mota',
		typeText	: 'Testua',
		typePass	: 'Pasahitza'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Ezkutuko Eremuaren Ezaugarriak',
		name	: 'Izena',
		value	: 'Balorea'
	},

	// Image Dialog.
	image :
	{
		title		: 'Irudi Ezaugarriak',
		titleButton	: 'Irudi Botoiaren Ezaugarriak',
		menu		: 'Irudi Ezaugarriak',
		infoTab	: 'Irudi informazioa',
		btnUpload	: 'Zerbitzarira bidalia',
		url		: 'URL',
		upload	: 'Gora Kargatu',
		alt		: 'Ordezko Testua',
		width		: 'Zabalera',
		height	: 'Altuera',
		lockRatio	: 'Erlazioa Blokeatu',
		resetSize	: 'Tamaina Berrezarri',
		border	: 'Ertza',
		hSpace	: 'HSpace',
		vSpace	: 'VSpace',
		align		: 'Lerrokatu',
		alignLeft	: 'Ezkerrera',
		alignAbsBottom: 'Abs Behean',
		alignAbsMiddle: 'Abs Erdian',
		alignBaseline	: 'Oinan',
		alignBottom	: 'Behean',
		alignMiddle	: 'Erdian',
		alignRight	: 'Eskuman',
		alignTextTop	: 'Testua Goian',
		alignTop	: 'Goian',
		preview	: 'Aurrebista',
		alertUrl	: 'Mesedez Irudiaren URLa idatzi',
		linkTab	: 'Esteka',
		button2Img	: 'Aukeratutako irudi botoia, irudi normal batean eraldatu nahi duzu?',
		img2Button	: 'Aukeratutako irudia, irudi botoi batean eraldatu nahi duzu?',
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Flasharen Ezaugarriak',
		propertiesTab	: 'Ezaugarriak',
		title		: 'Flasharen Ezaugarriak',
		chkPlay		: 'Automatikoki Erreproduzitu',
		chkLoop		: 'Begizta',
		chkMenu		: 'Flasharen Menua Gaitu',
		chkFull		: 'Onartu Pantaila osoa',
 		scale		: 'Eskalatu',
		scaleAll		: 'Dena erakutsi',
		scaleNoBorder	: 'Ertzik gabe',
		scaleFit		: 'Doitu',
		access			: 'Scriptak baimendu',
		accessAlways	: 'Beti',
		accessSameDomain	: 'Domeinu berdinekoak',
		accessNever	: 'Inoiz ere ez',
		align		: 'Lerrokatu',
		alignLeft	: 'Ezkerrera',
		alignAbsBottom: 'Abs Behean',
		alignAbsMiddle: 'Abs Erdian',
		alignBaseline	: 'Oinan',
		alignBottom	: 'Behean',
		alignMiddle	: 'Erdian',
		alignRight	: 'Eskuman',
		alignTextTop	: 'Testua Goian',
		alignTop	: 'Goian',
		quality		: 'Kalitatea',
		qualityBest		 : 'Hoberena',
		qualityHigh		 : 'Altua',
		qualityAutoHigh	 : 'Auto Altua',
		qualityMedium	 : 'Ertaina',
		qualityAutoLow	 : 'Auto Baxua',
		qualityLow		 : 'Baxua',
		windowModeWindow	 : 'Leihoa',
		windowModeOpaque	 : 'Opakoa',
		windowModeTransparent	 : 'Gardena',
		windowMode	: 'Leihoaren modua',
		flashvars	: 'Flash Aldagaiak',
		bgcolor	: 'Atzeko kolorea',
		width	: 'Zabalera',
		height	: 'Altuera',
		hSpace	: 'HSpace',
		vSpace	: 'VSpace',
		validateSrc : 'Mesedez URL esteka idatzi',
		validateWidth : 'Zabalera zenbaki bat izan behar da.',
		validateHeight : 'Altuera zenbaki bat izan behar da.',
		validateHSpace : 'HSpace zenbaki bat izan behar da.',
		validateVSpace : 'VSpace zenbaki bat izan behar da.'
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Ortografia',
		title			: 'Ortografia zuzenketa',
		notAvailable	: 'Barkatu baina momentu honetan zerbitzua ez dago erabilgarri.',
		errorLoading	: 'Errorea gertatu da aplikazioa zerbitzaritik kargatzean: %s.',
		notInDic		: 'Ez dago hiztegian',
		changeTo		: 'Honekin ordezkatu',
		btnIgnore		: 'Ezikusi',
		btnIgnoreAll	: 'Denak Ezikusi',
		btnReplace		: 'Ordezkatu',
		btnReplaceAll	: 'Denak Ordezkatu',
		btnUndo			: 'Desegin',
		noSuggestions	: '- Iradokizunik ez -',
		progress		: 'Zuzenketa ortografikoa martxan...',
		noMispell		: 'Zuzenketa ortografikoa bukatuta: Akatsik ez',
		noChanges		: 'Zuzenketa ortografikoa bukatuta: Ez da ezer aldatu',
		oneChange		: 'Zuzenketa ortografikoa bukatuta: Hitz bat aldatu da',
		manyChanges		: 'Zuzenketa ortografikoa bukatuta: %1 hitz aldatu dira',
		ieSpellDownload	: 'Zuzentzaile ortografikoa ez dago instalatuta. Deskargatu nahi duzu?'
	},

	smiley :
	{
		toolbar	: 'Aurpegierak',
		title	: 'Aurpegiera Sartu'
	},

	elementsPath :
	{
		eleTitle : '%1 elementua'
	},

	numberedlist : 'Zenbakidun Zerrenda',
	bulletedlist : 'Buletdun Zerrenda',
	indent : 'Handitu Koska',
	outdent : 'Txikitu Koska',

	justify :
	{
		left : 'Lerrokatu Ezkerrean',
		center : 'Lerrokatu Erdian',
		right : 'Lerrokatu Eskuman',
		block : 'Justifikatu'
	},

	blockquote : 'Aipamen blokea',

	clipboard :
	{
		title		: 'Itsatsi',
		cutError	: 'Zure web nabigatzailearen segurtasun ezarpenak testuak automatikoki moztea ez dute baimentzen. Mesedez teklatua erabili ezazu (Ctrl+X).',
		copyError	: 'Zure web nabigatzailearen segurtasun ezarpenak testuak automatikoki kopiatzea ez dute baimentzen. Mesedez teklatua erabili ezazu (Ctrl+C).',
		pasteMsg	: 'Mesedez teklatua erabilita (<STRONG>Ctrl+V</STRONG>) ondorego eremuan testua itsatsi eta <STRONG>OK</STRONG> sakatu.',
		securityMsg	: 'Nabigatzailearen segurtasun ezarpenak direla eta, editoreak ezin du arbela zuzenean erabili. Leiho honetan berriro itsatsi behar duzu.'
	},

	pastefromword :
	{
		toolbar : 'Itsatsi Word-etik',
		title : 'Itsatsi Word-etik',
		advice : 'Mesedez teklatua erabilita (<STRONG>Ctrl+V</STRONG>) ondorego eremuan testua itsatsi eta <STRONG>OK</STRONG> sakatu.',
		ignoreFontFace : 'Letra Motaren definizioa ezikusi',
		removeStyle : 'Estilo definizioak kendu'
	},

	pasteText :
	{
		button : 'Testu Arrunta bezala Itsatsi',
		title : 'Testu Arrunta bezala Itsatsi'
	},

	templates :
	{
		button : 'Txantiloiak',
		title : 'Eduki Txantiloiak',
		insertOption: 'Ordeztu oraingo edukiak',
		selectPromptMsg: 'Mesedez txantiloia aukeratu editorean kargatzeko<br>(orain dauden edukiak galduko dira):',
		emptyListMsg : '(Ez dago definitutako txantiloirik)'
	},

	showBlocks : 'Blokeak erakutsi',

	stylesCombo :
	{
		label : 'Estiloa',
		voiceLabel : 'Estiloak',
		panelVoiceLabel : 'Estilo bat aukeratu',
		panelTitle1 : 'Bloke Estiloak',
		panelTitle2 : 'Inline Estiloak',
		panelTitle3 : 'Objektu Estiloak'
	},

	format :
	{
		label : 'Formatua',
		voiceLabel : 'Formatua',
		panelTitle : 'Formatua',
		panelVoiceLabel : 'Aukeratu paragrafo formatu bat',

		tag_p : 'Arrunta',
		tag_pre : 'Formateatua',
		tag_address : 'Helbidea',
		tag_h1 : 'Izenburua 1',
		tag_h2 : 'Izenburua 2',
		tag_h3 : 'Izenburua 3',
		tag_h4 : 'Izenburua 4',
		tag_h5 : 'Izenburua 5',
		tag_h6 : 'Izenburua 6',
		tag_div : 'Paragrafoa (DIV)'
	},

	font :
	{
		label : 'Letra-tipoa',
		voiceLabel : 'Letra-tipoa',
		panelTitle : 'Letra-tipoa',
		panelVoiceLabel : 'Aukeratu letra-tipoa'
	},

	fontSize :
	{
		label : 'Tamaina',
		voiceLabel : 'Tamaina',
		panelTitle : 'Tamaina',
		panelVoiceLabel : 'Aukeratu letraren tamaina'
	},

	colorButton :
	{
		textColorTitle : 'Testu Kolorea',
		bgColorTitle : 'Atzeko kolorea',
		auto : 'Automatikoa',
		more : 'Kolore gehiago...'
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
		title : 'Ortografia Zuzenketa Idatzi Ahala (SCAYT)',
		enable : 'Gaitu SCAYT',
		disable : 'Desgaitu SCAYT',
		about : 'SCAYTi buruz',
		toggle : 'SCAYT aldatu',
		options : 'Aukerak',
		langs : 'Hizkuntzak',
		moreSuggestions : 'Iradokizun gehiago',
		ignore : 'Baztertu',
		ignoreAll : 'Denak baztertu',
		addWord : 'Hitza Gehitu',
		emptyDic : 'Hiztegiaren izena ezin da hutsik egon.',
		optionsTab : 'Aukerak',
		languagesTab : 'Hizkuntzak',
		dictionariesTab : 'Hiztegiak',
		aboutTab : 'Honi buruz'
	},

	about :
	{
		title : 'CKEditor(r)i buruz',
		dlgTitle : 'CKEditor(r)i buruz',
		moreInfo : 'Lizentziari buruzko informazioa gure webgunean:',
		copy : 'Copyright &copy; $1. Eskubide guztiak erreserbaturik.'
	},

	maximize : 'Maximizatu',
	minimize : 'Minimize', // MISSING

	fakeobjects :
	{
		anchor : 'Aingura',
		flash : 'Flash Animazioa',
		div : 'Orrialde Saltoa',
		unknown : 'Objektu ezezaguna'
	},

	resize : 'Arrastatu tamaina aldatzeko',

	colordialog :
	{
		title : 'Select color', // MISSING
		highlight : 'Highlight', // MISSING
		selected : 'Selected', // MISSING
		clear : 'Clear' // MISSING
	}
};
