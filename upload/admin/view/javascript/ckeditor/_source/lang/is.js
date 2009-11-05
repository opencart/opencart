/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Icelandic language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['is'] =
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
	source			: 'Kóði',
	newPage			: 'Ný síða',
	save			: 'Vista',
	preview			: 'Forskoða',
	cut				: 'Klippa',
	copy			: 'Afrita',
	paste			: 'Líma',
	print			: 'Prenta',
	underline		: 'Undirstrikað',
	bold			: 'Feitletrað',
	italic			: 'Skáletrað',
	selectAll		: 'Velja allt',
	removeFormat	: 'Fjarlægja snið',
	strike			: 'Yfirstrikað',
	subscript		: 'Niðurskrifað',
	superscript		: 'Uppskrifað',
	horizontalrule	: 'Lóðrétt lína',
	pagebreak		: 'Setja inn síðuskil',
	unlink			: 'Fjarlægja stiklu',
	undo			: 'Afturkalla',
	redo			: 'Hætta við afturköllun',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Fletta í skjalasafni',
		url				: 'Vefslóð',
		protocol		: 'Samskiptastaðall',
		upload			: 'Senda upp',
		uploadSubmit	: 'Hlaða upp',
		image			: 'Setja inn mynd',
		flash			: 'Flash',
		form			: 'Setja inn innsláttarform',
		checkbox		: 'Setja inn hökunarreit',
		radio		: 'Setja inn valhnapp',
		textField		: 'Setja inn textareit',
		textarea		: 'Setja inn textasvæði',
		hiddenField		: 'Setja inn falið svæði',
		button			: 'Setja inn hnapp',
		select	: 'Setja inn lista',
		imageButton		: 'Setja inn myndahnapp',
		notSet			: '<ekkert valið>',
		id				: 'Auðkenni',
		name			: 'Nafn',
		langDir			: 'Lesstefna',
		langDirLtr		: 'Frá vinstri til hægri (LTR)',
		langDirRtl		: 'Frá hægri til vinstri (RTL)',
		langCode		: 'Tungumálakóði',
		longDescr		: 'Nánari lýsing',
		cssClass		: 'Stílsniðsflokkur',
		advisoryTitle	: 'Titill',
		cssStyle		: 'Stíll',
		ok				: 'Í lagi',
		cancel			: 'Hætta við',
		generalTab		: 'Almennt',
		advancedTab		: 'Tæknilegt',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Setja inn merki',
		title		: 'Velja tákn'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Stofna/breyta stiklu',
		menu		: 'Breyta stiklu',
		title		: 'Stikla',
		info		: 'Almennt',
		target		: 'Mark',
		upload		: 'Senda upp',
		advanced	: 'Tæknilegt',
		type		: 'Stikluflokkur',
		toAnchor	: 'Bókamerki á þessari síðu',
		toEmail		: 'Netfang',
		target		: 'Mark',
		targetNotSet	: '<ekkert valið>',
		targetFrame	: '<rammi>',
		targetPopup	: '<sprettigluggi>',
		targetNew	: 'Nýr gluggi (_blank)',
		targetTop	: 'Allur glugginn (_top)',
		targetSelf	: 'Sami gluggi (_self)',
		targetParent	: 'Yfirsettur rammi (_parent)',
		targetFrameName	: 'Nafn markglugga',
		targetPopupName	: 'Nafn sprettiglugga',
		popupFeatures	: 'Eigindi sprettiglugga',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Stöðustika',
		popupLocationBar	: 'Fanglína',
		popupToolbar	: 'Verkfærastika',
		popupMenuBar	: 'Vallína',
		popupFullScreen	: 'Heilskjár (IE)',
		popupScrollBars	: 'Skrunstikur',
		popupDependent	: 'Háð venslum (Netscape)',
		popupWidth		: 'Breidd',
		popupLeft		: 'Fjarlægð frá vinstri',
		popupHeight		: 'Hæð',
		popupTop		: 'Fjarlægð frá efri brún',
		id				: 'Id', // MISSING
		langDir			: 'Lesstefna',
		langDirNotSet	: '<ekkert valið>',
		langDirLTR		: 'Frá vinstri til hægri (LTR)',
		langDirRTL		: 'Frá hægri til vinstri (RTL)',
		acccessKey		: 'Skammvalshnappur',
		name			: 'Nafn',
		langCode		: 'Lesstefna',
		tabIndex		: 'Raðnúmer innsláttarreits',
		advisoryTitle	: 'Titill',
		advisoryContentType	: 'Tegund innihalds',
		cssClasses		: 'Stílsniðsflokkur',
		charset			: 'Táknróf',
		styles			: 'Stíll',
		selectAnchor	: 'Veldu akkeri',
		anchorName		: 'Eftir akkerisnafni',
		anchorId		: 'Eftir auðkenni einingar',
		emailAddress	: 'Netfang',
		emailSubject	: 'Efni',
		emailBody		: 'Meginmál',
		noAnchors		: '<Engin bókamerki á skrá>',
		noUrl			: 'Sláðu inn veffang stiklunnar!',
		noEmail			: 'Sláðu inn netfang!'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Stofna/breyta kaflamerki',
		menu		: 'Eigindi kaflamerkis',
		title		: 'Eigindi kaflamerkis',
		name		: 'Nafn bókamerkis',
		errorName	: 'Sláðu inn nafn bókamerkis!'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Finna og skipta',
		find				: 'Leita',
		replace				: 'Skipta út',
		findWhat			: 'Leita að:',
		replaceWith			: 'Skipta út fyrir:',
		notFoundMsg			: 'Leitartexti fannst ekki!',
		matchCase			: 'Gera greinarmun á¡ há¡- og lágstöfum',
		matchWord			: 'Aðeins heil orð',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Skipta út allsstaðar',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Tafla',
		title		: 'Eigindi töflu',
		menu		: 'Eigindi töflu',
		deleteTable	: 'Fella töflu',
		rows		: 'Raðir',
		columns		: 'Dálkar',
		border		: 'Breidd ramma',
		align		: 'Jöfnun',
		alignNotSet	: '<ekkert valið>',
		alignLeft	: 'Vinstrijafnað',
		alignCenter	: 'Miðjað',
		alignRight	: 'Hægrijafnað',
		width		: 'Breidd',
		widthPx		: 'myndeindir',
		widthPc		: 'prósent',
		height		: 'Hæð',
		cellSpace	: 'Bil milli reita',
		cellPad		: 'Reitaspássía',
		caption		: 'Titill',
		summary		: 'Áfram',
		headers		: 'Fyrirsagnir',
		headersNone		: 'Engar',
		headersColumn	: 'Fyrsti dálkur',
		headersRow		: 'Fyrsta röð',
		headersBoth		: 'Hvort tveggja',
		invalidRows		: 'Number of rows must be a number greater than 0.', // MISSING
		invalidCols		: 'Number of columns must be a number greater than 0.', // MISSING
		invalidBorder	: 'Border size must be a number.', // MISSING
		invalidWidth	: 'Table width must be a number.', // MISSING
		invalidHeight	: 'Table height must be a number.', // MISSING
		invalidCellSpacing	: 'Cell spacing must be a number.', // MISSING
		invalidCellPadding	: 'Cell padding must be a number.', // MISSING

		cell :
		{
			menu			: 'Reitur',
			insertBefore	: 'Skjóta inn reiti fyrir aftan',
			insertAfter		: 'Skjóta inn reiti fyrir framan',
			deleteCell		: 'Fella reit',
			merge			: 'Sameina reiti',
			mergeRight		: 'Sameina til hægri',
			mergeDown		: 'Sameina niður á við',
			splitHorizontal	: 'Kljúfa reit lárétt',
			splitVertical	: 'Kljúfa reit lóðrétt',
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
			menu			: 'Röð',
			insertBefore	: 'Skjóta inn röð fyrir ofan',
			insertAfter		: 'Skjóta inn röð fyrir neðan',
			deleteRow		: 'Eyða röð'
		},

		column :
		{
			menu			: 'Dálkur',
			insertBefore	: 'Skjóta inn dálki vinstra megin',
			insertAfter		: 'Skjóta inn dálki hægra megin',
			deleteColumn	: 'Fella dálk'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Eigindi hnapps',
		text		: 'Texti',
		type		: 'Gerð',
		typeBtn		: 'Hnappur',
		typeSbm		: 'Staðfesta',
		typeRst		: 'Hreinsa'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Eigindi markreits',
		radioTitle	: 'Eigindi valhnapps',
		value		: 'Gildi',
		selected	: 'Valið'
	},

	// Form Dialog.
	form :
	{
		title		: 'Eigindi innsláttarforms',
		menu		: 'Eigindi innsláttarforms',
		action		: 'Aðgerð',
		method		: 'Aðferð',
		encoding	: 'Encoding', // MISSING
		target		: 'Mark',
		targetNotSet	: '<ekkert valið>',
		targetNew	: 'Nýr gluggi (_blank)',
		targetTop	: 'Allur glugginn (_top)',
		targetSelf	: 'Sami gluggi (_self)',
		targetParent	: 'Yfirsettur rammi (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Eigindi lista',
		selectInfo	: 'Upplýsingar',
		opAvail		: 'Kostir',
		value		: 'Gildi',
		size		: 'Stærð',
		lines		: 'línur',
		chkMulti	: 'Leyfa fleiri kosti',
		opText		: 'Texti',
		opValue		: 'Gildi',
		btnAdd		: 'Bæta við',
		btnModify	: 'Breyta',
		btnUp		: 'Upp',
		btnDown		: 'Niður',
		btnSetValue : 'Merkja sem valið',
		btnDelete	: 'Eyða'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Eigindi textasvæðis',
		cols		: 'Dálkar',
		rows		: 'Línur'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Eigindi textareits',
		name		: 'Nafn',
		value		: 'Gildi',
		charWidth	: 'Breidd (leturtákn)',
		maxChars	: 'Hámarksfjöldi leturtákna',
		type		: 'Gerð',
		typeText	: 'Texti',
		typePass	: 'Lykilorð'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Eigindi falins svæðis',
		name	: 'Nafn',
		value	: 'Gildi'
	},

	// Image Dialog.
	image :
	{
		title		: 'Eigindi myndar',
		titleButton	: 'Eigindi myndahnapps',
		menu		: 'Eigindi myndar',
		infoTab	: 'Almennt',
		btnUpload	: 'Hlaða upp',
		url		: 'Vefslóð',
		upload	: 'Hlaða upp',
		alt		: 'Baklægur texti',
		width		: 'Breidd',
		height	: 'Hæð',
		lockRatio	: 'Festa stærðarhlutfall',
		resetSize	: 'Reikna stærð',
		border	: 'Rammi',
		hSpace	: 'Vinstri bil',
		vSpace	: 'Hægri bil',
		align		: 'Jöfnun',
		alignLeft	: 'Vinstri',
		alignAbsBottom: 'Abs neðst',
		alignAbsMiddle: 'Abs miðjuð',
		alignBaseline	: 'Grunnlína',
		alignBottom	: 'Neðst',
		alignMiddle	: 'Miðjuð',
		alignRight	: 'Hægri',
		alignTextTop	: 'Efri brún texta',
		alignTop	: 'Efst',
		preview	: 'Sýna dæmi',
		alertUrl	: 'Sláðu inn slóðina að myndinni',
		linkTab	: 'Stikla',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Eigindi Flash',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Eigindi Flash',
		chkPlay		: 'Sjálfvirk spilun',
		chkLoop		: 'Endurtekning',
		chkMenu		: 'Sýna Flash-valmynd',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Skali',
		scaleAll		: 'Sýna allt',
		scaleNoBorder	: 'Án ramma',
		scaleFit		: 'Fella skala að stærð',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Jöfnun',
		alignLeft	: 'Vinstri',
		alignAbsBottom: 'Abs neðst',
		alignAbsMiddle: 'Abs miðjuð',
		alignBaseline	: 'Grunnlína',
		alignBottom	: 'Neðst',
		alignMiddle	: 'Miðjuð',
		alignRight	: 'Hægri',
		alignTextTop	: 'Efri brún texta',
		alignTop	: 'Efst',
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
		bgcolor	: 'Bakgrunnslitur',
		width	: 'Breidd',
		height	: 'Hæð',
		hSpace	: 'Vinstri bil',
		vSpace	: 'Hægri bil',
		validateSrc : 'Sláðu inn veffang stiklunnar!',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Villuleit',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Ekki í orðabókinni',
		changeTo		: 'Tillaga',
		btnIgnore		: 'Hunsa',
		btnIgnoreAll	: 'Hunsa allt',
		btnReplace		: 'Skipta',
		btnReplaceAll	: 'Skipta öllu',
		btnUndo			: 'Til baka',
		noSuggestions	: '- engar tillögur -',
		progress		: 'Villuleit í gangi...',
		noMispell		: 'Villuleit lokið: Engin villa fannst',
		noChanges		: 'Villuleit lokið: Engu orði breytt',
		oneChange		: 'Villuleit lokið: Einu orði breytt',
		manyChanges		: 'Villuleit lokið: %1 orðum breytt',
		ieSpellDownload	: 'Villuleit ekki sett upp.<br>Viltu setja hana upp?'
	},

	smiley :
	{
		toolbar	: 'Svipur',
		title	: 'Velja svip'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Númeraður listi',
	bulletedlist : 'Punktalisti',
	indent : 'Minnka inndrátt',
	outdent : 'Auka inndrátt',

	justify :
	{
		left : 'Vinstrijöfnun',
		center : 'Miðja texta',
		right : 'Hægrijöfnun',
		block : 'Jafna báðum megin'
	},

	blockquote : 'Inndráttur',

	clipboard :
	{
		title		: 'Líma',
		cutError	: 'Öryggisstillingar vafrans þíns leyfa ekki klippingu texta með músaraðgerð. Notaðu lyklaborðið í klippa (Ctrl+X).',
		copyError	: 'Öryggisstillingar vafrans þíns leyfa ekki afritun texta með músaraðgerð. Notaðu lyklaborðið í afrita (Ctrl+C).',
		pasteMsg	: 'Límdu í svæðið hér að neðan og (<STRONG>Ctrl+V</STRONG>) og smelltu á <STRONG>OK</STRONG>.',
		securityMsg	: 'Vegna öryggisstillinga í vafranum þínum fær ritillinn ekki beinan aðgang að klippuborðinu. Þú verður að líma innihaldið aftur inn í þennan glugga.'
	},

	pastefromword :
	{
		toolbar : 'Líma úr Word',
		title : 'Líma úr Word',
		advice : 'Límdu í svæðið hér að neðan og (<STRONG>Ctrl+V</STRONG>) og smelltu á <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Hunsa leturskilgreiningar',
		removeStyle : 'Hunsa letureigindi'
	},

	pasteText :
	{
		button : 'Líma sem ósniðinn texta',
		title : 'Líma sem ósniðinn texta'
	},

	templates :
	{
		button : 'Sniðmát',
		title : 'Innihaldssniðmát',
		insertOption: 'Skipta út raunverulegu innihaldi',
		selectPromptMsg: 'Veldu sniðmát til að opna í ritlinum.<br>(Núverandi innihald víkur fyrir því!):',
		emptyListMsg : '(Ekkert sniðmát er skilgreint!)'
	},

	showBlocks : 'Sýna blokkir',

	stylesCombo :
	{
		label : 'Stílflokkur',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'Stílsnið',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'Stílsnið',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'Venjulegt letur',
		tag_pre : 'Forsniðið',
		tag_address : 'Vistfang',
		tag_h1 : 'Fyrirsögn 1',
		tag_h2 : 'Fyrirsögn 2',
		tag_h3 : 'Fyrirsögn 3',
		tag_h4 : 'Fyrirsögn 4',
		tag_h5 : 'Fyrirsögn 5',
		tag_h6 : 'Fyrirsögn 6',
		tag_div : 'Venjulegt (DIV)'
	},

	font :
	{
		label : 'Leturgerð ',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'Leturgerð ',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'Leturstærð ',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Leturstærð ',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Litur texta',
		bgColorTitle : 'Bakgrunnslitur',
		auto : 'Sjálfval',
		more : 'Fleiri liti...'
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
