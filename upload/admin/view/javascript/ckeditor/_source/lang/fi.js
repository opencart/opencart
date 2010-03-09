/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Finnish language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['fi'] =
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
	source			: 'Koodi',
	newPage			: 'Tyhjennä',
	save			: 'Tallenna',
	preview			: 'Esikatsele',
	cut				: 'Leikkaa',
	copy			: 'Kopioi',
	paste			: 'Liitä',
	print			: 'Tulosta',
	underline		: 'Alleviivattu',
	bold			: 'Lihavoitu',
	italic			: 'Kursivoitu',
	selectAll		: 'Valitse kaikki',
	removeFormat	: 'Poista muotoilu',
	strike			: 'Yliviivattu',
	subscript		: 'Alaindeksi',
	superscript		: 'Yläindeksi',
	horizontalrule	: 'Lisää murtoviiva',
	pagebreak		: 'Lisää sivun vaihto',
	unlink			: 'Poista linkki',
	undo			: 'Kumoa',
	redo			: 'Toista',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Selaa palvelinta',
		url				: 'Osoite',
		protocol		: 'Protokolla',
		upload			: 'Lisää tiedosto',
		uploadSubmit	: 'Lähetä palvelimelle',
		image			: 'Kuva',
		flash			: 'Flash',
		form			: 'Lomake',
		checkbox		: 'Valintaruutu',
		radio		: 'Radiopainike',
		textField		: 'Tekstikenttä',
		textarea		: 'Tekstilaatikko',
		hiddenField		: 'Piilokenttä',
		button			: 'Painike',
		select	: 'Valintakenttä',
		imageButton		: 'Kuvapainike',
		notSet			: '<ei asetettu>',
		id				: 'Tunniste',
		name			: 'Nimi',
		langDir			: 'Kielen suunta',
		langDirLtr		: 'Vasemmalta oikealle (LTR)',
		langDirRtl		: 'Oikealta vasemmalle (RTL)',
		langCode		: 'Kielikoodi',
		longDescr		: 'Pitkän kuvauksen URL',
		cssClass		: 'Tyyliluokat',
		advisoryTitle	: 'Avustava otsikko',
		cssStyle		: 'Tyyli',
		ok				: 'OK',
		cancel			: 'Peruuta',
		generalTab		: 'General', // MISSING
		advancedTab		: 'Lisäominaisuudet',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Lisää erikoismerkki',
		title		: 'Valitse erikoismerkki'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Lisää linkki/muokkaa linkkiä',
		menu		: 'Muokkaa linkkiä',
		title		: 'Linkki',
		info		: 'Linkin tiedot',
		target		: 'Kohde',
		upload		: 'Lisää tiedosto',
		advanced	: 'Lisäominaisuudet',
		type		: 'Linkkityyppi',
		toAnchor	: 'Ankkuri tässä sivussa',
		toEmail		: 'Sähköposti',
		target		: 'Kohde',
		targetNotSet	: '<ei asetettu>',
		targetFrame	: '<kehys>',
		targetPopup	: '<popup ikkuna>',
		targetNew	: 'Uusi ikkuna (_blank)',
		targetTop	: 'Päällimmäisin ikkuna (_top)',
		targetSelf	: 'Sama ikkuna (_self)',
		targetParent	: 'Emoikkuna (_parent)',
		targetFrameName	: 'Kohdekehyksen nimi',
		targetPopupName	: 'Popup ikkunan nimi',
		popupFeatures	: 'Popup ikkunan ominaisuudet',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Tilarivi',
		popupLocationBar	: 'Osoiterivi',
		popupToolbar	: 'Vakiopainikkeet',
		popupMenuBar	: 'Valikkorivi',
		popupFullScreen	: 'Täysi ikkuna (IE)',
		popupScrollBars	: 'Vierityspalkit',
		popupDependent	: 'Riippuva (Netscape)',
		popupWidth		: 'Leveys',
		popupLeft		: 'Vasemmalta (px)',
		popupHeight		: 'Korkeus',
		popupTop		: 'Ylhäältä (px)',
		id				: 'Id', // MISSING
		langDir			: 'Kielen suunta',
		langDirNotSet	: '<ei asetettu>',
		langDirLTR		: 'Vasemmalta oikealle (LTR)',
		langDirRTL		: 'Oikealta vasemmalle (RTL)',
		acccessKey		: 'Pikanäppäin',
		name			: 'Nimi',
		langCode		: 'Kielen suunta',
		tabIndex		: 'Tabulaattori indeksi',
		advisoryTitle	: 'Avustava otsikko',
		advisoryContentType	: 'Avustava sisällön tyyppi',
		cssClasses		: 'Tyyliluokat',
		charset			: 'Linkitetty kirjaimisto',
		styles			: 'Tyyli',
		selectAnchor	: 'Valitse ankkuri',
		anchorName		: 'Ankkurin nimen mukaan',
		anchorId		: 'Ankkurin ID:n mukaan',
		emailAddress	: 'Sähköpostiosoite',
		emailSubject	: 'Aihe',
		emailBody		: 'Viesti',
		noAnchors		: '(Ei ankkureita tässä dokumentissa)',
		noUrl			: 'Linkille on kirjoitettava URL',
		noEmail			: 'Kirjoita sähköpostiosoite'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Lisää ankkuri/muokkaa ankkuria',
		menu		: 'Ankkurin ominaisuudet',
		title		: 'Ankkurin ominaisuudet',
		name		: 'Nimi',
		errorName	: 'Ankkurille on kirjoitettava nimi'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Etsi ja korvaa',
		find				: 'Etsi',
		replace				: 'Korvaa',
		findWhat			: 'Etsi mitä:',
		replaceWith			: 'Korvaa tällä:',
		notFoundMsg			: 'Etsittyä tekstiä ei löytynyt.',
		matchCase			: 'Sama kirjainkoko',
		matchWord			: 'Koko sana',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Korvaa kaikki',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Taulu',
		title		: 'Taulun ominaisuudet',
		menu		: 'Taulun ominaisuudet',
		deleteTable	: 'Poista taulu',
		rows		: 'Rivit',
		columns		: 'Sarakkeet',
		border		: 'Rajan paksuus',
		align		: 'Kohdistus',
		alignNotSet	: '<ei asetettu>',
		alignLeft	: 'Vasemmalle',
		alignCenter	: 'Keskelle',
		alignRight	: 'Oikealle',
		width		: 'Leveys',
		widthPx		: 'pikseliä',
		widthPc		: 'prosenttia',
		height		: 'Korkeus',
		cellSpace	: 'Solujen väli',
		cellPad		: 'Solujen sisennys',
		caption		: 'Otsikko',
		summary		: 'Yhteenveto',
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
			menu			: 'Solu',
			insertBefore	: 'Lisää solu eteen',
			insertAfter		: 'Lisää solu perään',
			deleteCell		: 'Poista solut',
			merge			: 'Yhdistä solut',
			mergeRight		: 'Yhdistä oikealla olevan kanssa',
			mergeDown		: 'Yhdistä alla olevan kanssa',
			splitHorizontal	: 'Jaa solu vaakasuunnassa',
			splitVertical	: 'Jaa solu pystysuunnassa',
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
			menu			: 'Rivi',
			insertBefore	: 'Lisää rivi yläpuolelle',
			insertAfter		: 'Lisää rivi alapuolelle',
			deleteRow		: 'Poista rivit'
		},

		column :
		{
			menu			: 'Sarake',
			insertBefore	: 'Lisää sarake vasemmalle',
			insertAfter		: 'Lisää sarake oikealle',
			deleteColumn	: 'Poista sarakkeet'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Painikkeen ominaisuudet',
		text		: 'Teksti (arvo)',
		type		: 'Tyyppi',
		typeBtn		: 'Painike',
		typeSbm		: 'Lähetä',
		typeRst		: 'Tyhjennä'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Valintaruudun ominaisuudet',
		radioTitle	: 'Radiopainikkeen ominaisuudet',
		value		: 'Arvo',
		selected	: 'Valittu'
	},

	// Form Dialog.
	form :
	{
		title		: 'Lomakkeen ominaisuudet',
		menu		: 'Lomakkeen ominaisuudet',
		action		: 'Toiminto',
		method		: 'Tapa',
		encoding	: 'Encoding', // MISSING
		target		: 'Kohde',
		targetNotSet	: '<ei asetettu>',
		targetNew	: 'Uusi ikkuna (_blank)',
		targetTop	: 'Päällimmäisin ikkuna (_top)',
		targetSelf	: 'Sama ikkuna (_self)',
		targetParent	: 'Emoikkuna (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Valintakentän ominaisuudet',
		selectInfo	: 'Info',
		opAvail		: 'Ominaisuudet',
		value		: 'Arvo',
		size		: 'Koko',
		lines		: 'Rivit',
		chkMulti	: 'Salli usea valinta',
		opText		: 'Teksti',
		opValue		: 'Arvo',
		btnAdd		: 'Lisää',
		btnModify	: 'Muuta',
		btnUp		: 'Ylös',
		btnDown		: 'Alas',
		btnSetValue : 'Aseta valituksi',
		btnDelete	: 'Poista'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Tekstilaatikon ominaisuudet',
		cols		: 'Sarakkeita',
		rows		: 'Rivejä'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Tekstikentän ominaisuudet',
		name		: 'Nimi',
		value		: 'Arvo',
		charWidth	: 'Leveys',
		maxChars	: 'Maksimi merkkimäärä',
		type		: 'Tyyppi',
		typeText	: 'Teksti',
		typePass	: 'Salasana'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Piilokentän ominaisuudet',
		name	: 'Nimi',
		value	: 'Arvo'
	},

	// Image Dialog.
	image :
	{
		title		: 'Kuvan ominaisuudet',
		titleButton	: 'Kuvapainikkeen ominaisuudet',
		menu		: 'Kuvan ominaisuudet',
		infoTab	: 'Kuvan tiedot',
		btnUpload	: 'Lähetä palvelimelle',
		url		: 'Osoite',
		upload	: 'Lisää kuva',
		alt		: 'Vaihtoehtoinen teksti',
		width		: 'Leveys',
		height	: 'Korkeus',
		lockRatio	: 'Lukitse suhteet',
		resetSize	: 'Alkuperäinen koko',
		border	: 'Raja',
		hSpace	: 'Vaakatila',
		vSpace	: 'Pystytila',
		align		: 'Kohdistus',
		alignLeft	: 'Vasemmalle',
		alignAbsBottom: 'Aivan alas',
		alignAbsMiddle: 'Aivan keskelle',
		alignBaseline	: 'Alas (teksti)',
		alignBottom	: 'Alas',
		alignMiddle	: 'Keskelle',
		alignRight	: 'Oikealle',
		alignTextTop	: 'Ylös (teksti)',
		alignTop	: 'Ylös',
		preview	: 'Esikatselu',
		alertUrl	: 'Kirjoita kuvan osoite (URL)',
		linkTab	: 'Linkki',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Flash ominaisuudet',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Flash ominaisuudet',
		chkPlay		: 'Automaattinen käynnistys',
		chkLoop		: 'Toisto',
		chkMenu		: 'Näytä Flash-valikko',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Levitä',
		scaleAll		: 'Näytä kaikki',
		scaleNoBorder	: 'Ei rajaa',
		scaleFit		: 'Tarkka koko',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Kohdistus',
		alignLeft	: 'Vasemmalle',
		alignAbsBottom: 'Aivan alas',
		alignAbsMiddle: 'Aivan keskelle',
		alignBaseline	: 'Alas (teksti)',
		alignBottom	: 'Alas',
		alignMiddle	: 'Keskelle',
		alignRight	: 'Oikealle',
		alignTextTop	: 'Ylös (teksti)',
		alignTop	: 'Ylös',
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
		bgcolor	: 'Taustaväri',
		width	: 'Leveys',
		height	: 'Korkeus',
		hSpace	: 'Vaakatila',
		vSpace	: 'Pystytila',
		validateSrc : 'Linkille on kirjoitettava URL',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Tarkista oikeinkirjoitus',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Ei sanakirjassa',
		changeTo		: 'Vaihda',
		btnIgnore		: 'Jätä huomioimatta',
		btnIgnoreAll	: 'Jätä kaikki huomioimatta',
		btnReplace		: 'Korvaa',
		btnReplaceAll	: 'Korvaa kaikki',
		btnUndo			: 'Kumoa',
		noSuggestions	: 'Ei ehdotuksia',
		progress		: 'Tarkistus käynnissä...',
		noMispell		: 'Tarkistus valmis: Ei virheitä',
		noChanges		: 'Tarkistus valmis: Yhtään sanaa ei muutettu',
		oneChange		: 'Tarkistus valmis: Yksi sana muutettiin',
		manyChanges		: 'Tarkistus valmis: %1 sanaa muutettiin',
		ieSpellDownload	: 'Oikeinkirjoituksen tarkistusta ei ole asennettu. Haluatko ladata sen nyt?'
	},

	smiley :
	{
		toolbar	: 'Hymiö',
		title	: 'Lisää hymiö'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Numerointi',
	bulletedlist : 'Luottelomerkit',
	indent : 'Suurenna sisennystä',
	outdent : 'Pienennä sisennystä',

	justify :
	{
		left : 'Tasaa vasemmat reunat',
		center : 'Keskitä',
		right : 'Tasaa oikeat reunat',
		block : 'Tasaa molemmat reunat'
	},

	blockquote : 'Lainaus',

	clipboard :
	{
		title		: 'Liitä',
		cutError	: 'Selaimesi turva-asetukset eivät salli editorin toteuttaa leikkaamista. Käytä näppäimistöä leikkaamiseen (Ctrl+X).',
		copyError	: 'Selaimesi turva-asetukset eivät salli editorin toteuttaa kopioimista. Käytä näppäimistöä kopioimiseen (Ctrl+C).',
		pasteMsg	: 'Liitä painamalla (<STRONG>Ctrl+V</STRONG>) ja painamalla <STRONG>OK</STRONG>.',
		securityMsg	: 'Selaimesi turva-asetukset eivät salli editorin käyttää leikepöytää suoraan. Sinun pitää suorittaa liittäminen tässä ikkunassa.'
	},

	pastefromword :
	{
		toolbar : 'Liitä Wordista',
		title : 'Liitä Wordista',
		advice : 'Liitä painamalla (<STRONG>Ctrl+V</STRONG>) ja painamalla <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Jätä huomioimatta fonttimääritykset',
		removeStyle : 'Poista tyylimääritykset'
	},

	pasteText :
	{
		button : 'Liitä tekstinä',
		title : 'Liitä tekstinä'
	},

	templates :
	{
		button : 'Pohjat',
		title : 'Sisältöpohjat',
		insertOption: 'Korvaa editorin koko sisältö',
		selectPromptMsg: 'Valitse pohja editoriin<br>(aiempi sisältö menetetään):',
		emptyListMsg : '(Ei määriteltyjä pohjia)'
	},

	showBlocks : 'Näytä elementit',

	stylesCombo :
	{
		label : 'Tyyli',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'Muotoilu',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'Muotoilu',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'Normaali',
		tag_pre : 'Muotoiltu',
		tag_address : 'Osoite',
		tag_h1 : 'Otsikko 1',
		tag_h2 : 'Otsikko 2',
		tag_h3 : 'Otsikko 3',
		tag_h4 : 'Otsikko 4',
		tag_h5 : 'Otsikko 5',
		tag_h6 : 'Otsikko 6',
		tag_div : 'Normal (DIV)' // MISSING
	},

	font :
	{
		label : 'Fontti',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'Fontti',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'Koko',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Koko',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Tekstiväri',
		bgColorTitle : 'Taustaväri',
		auto : 'Automaattinen',
		more : 'Lisää värejä...'
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
