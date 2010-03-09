/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Hungarian language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['hu'] =
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
	source			: 'Forráskód',
	newPage			: 'Új oldal',
	save			: 'Mentés',
	preview			: 'Előnézet',
	cut				: 'Kivágás',
	copy			: 'Másolás',
	paste			: 'Beillesztés',
	print			: 'Nyomtatás',
	underline		: 'Aláhúzott',
	bold			: 'Félkövér',
	italic			: 'Dőlt',
	selectAll		: 'Mindent kijelöl',
	removeFormat	: 'Formázás eltávolítása',
	strike			: 'Áthúzott',
	subscript		: 'Alsó index',
	superscript		: 'Felső index',
	horizontalrule	: 'Elválasztóvonal beillesztése',
	pagebreak		: 'Oldaltörés beillesztése',
	unlink			: 'Hivatkozás törlése',
	undo			: 'Visszavonás',
	redo			: 'Ismétlés',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Böngészés a szerveren',
		url				: 'Hivatkozás',
		protocol		: 'Protokoll',
		upload			: 'Feltöltés',
		uploadSubmit	: 'Küldés a szerverre',
		image			: 'Kép',
		flash			: 'Flash',
		form			: 'Űrlap',
		checkbox		: 'Jelölőnégyzet',
		radio		: 'Választógomb',
		textField		: 'Szövegmező',
		textarea		: 'Szövegterület',
		hiddenField		: 'Rejtettmező',
		button			: 'Gomb',
		select	: 'Legördülő lista',
		imageButton		: 'Képgomb',
		notSet			: '<nincs beállítva>',
		id				: 'Azonosító',
		name			: 'Név',
		langDir			: 'Írás iránya',
		langDirLtr		: 'Balról jobbra',
		langDirRtl		: 'Jobbról balra',
		langCode		: 'Nyelv kódja',
		longDescr		: 'Részletes leírás webcíme',
		cssClass		: 'Stíluskészlet',
		advisoryTitle	: 'Súgócimke',
		cssStyle		: 'Stílus',
		ok				: 'Rendben',
		cancel			: 'Mégsem',
		generalTab		: 'General', // MISSING
		advancedTab		: 'További opciók',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Speciális karakter beillesztése',
		title		: 'Speciális karakter választása'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Hivatkozás beillesztése/módosítása',
		menu		: 'Hivatkozás módosítása',
		title		: 'Hivatkozás tulajdonságai',
		info		: 'Alaptulajdonságok',
		target		: 'Tartalom megjelenítése',
		upload		: 'Feltöltés',
		advanced	: 'További opciók',
		type		: 'Hivatkozás típusa',
		toAnchor	: 'Horgony az oldalon',
		toEmail		: 'E-Mail',
		target		: 'Tartalom megjelenítése',
		targetNotSet	: '<nincs beállítva>',
		targetFrame	: '<keretben>',
		targetPopup	: '<felugró ablakban>',
		targetNew	: 'Új ablakban (_blank)',
		targetTop	: 'Legfelső ablakban (_top)',
		targetSelf	: 'Azonos ablakban (_self)',
		targetParent	: 'Szülő ablakban (_parent)',
		targetFrameName	: 'Keret neve',
		targetPopupName	: 'Felugró ablak neve',
		popupFeatures	: 'Felugró ablak jellemzői',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Állapotsor',
		popupLocationBar	: 'Címsor',
		popupToolbar	: 'Eszköztár',
		popupMenuBar	: 'Menü sor',
		popupFullScreen	: 'Teljes képernyő (csak IE)',
		popupScrollBars	: 'Gördítősáv',
		popupDependent	: 'Szülőhöz kapcsolt (csak Netscape)',
		popupWidth		: 'Szélesség',
		popupLeft		: 'Bal pozíció',
		popupHeight		: 'Magasság',
		popupTop		: 'Felső pozíció',
		id				: 'Id', // MISSING
		langDir			: 'Írás iránya',
		langDirNotSet	: '<nincs beállítva>',
		langDirLTR		: 'Balról jobbra',
		langDirRTL		: 'Jobbról balra',
		acccessKey		: 'Billentyűkombináció',
		name			: 'Név',
		langCode		: 'Írás iránya',
		tabIndex		: 'Tabulátor index',
		advisoryTitle	: 'Súgócimke',
		advisoryContentType	: 'Súgó tartalomtípusa',
		cssClasses		: 'Stíluskészlet',
		charset			: 'Hivatkozott tartalom kódlapja',
		styles			: 'Stílus',
		selectAnchor	: 'Horgony választása',
		anchorName		: 'Horgony név szerint',
		anchorId		: 'Azonosító szerint',
		emailAddress	: 'E-Mail cím',
		emailSubject	: 'Üzenet tárgya',
		emailBody		: 'Üzenet',
		noAnchors		: '(Nincs horgony a dokumentumban)',
		noUrl			: 'Adja meg a hivatkozás webcímét',
		noEmail			: 'Adja meg az E-Mail címet'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Horgony beillesztése/szerkesztése',
		menu		: 'Horgony tulajdonságai',
		title		: 'Horgony tulajdonságai',
		name		: 'Horgony neve',
		errorName	: 'Kérem adja meg a horgony nevét'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Keresés és csere',
		find				: 'Keresés',
		replace				: 'Csere',
		findWhat			: 'Keresett szöveg:',
		replaceWith			: 'Csere erre:',
		notFoundMsg			: 'A keresett szöveg nem található.',
		matchCase			: 'kis- és nagybetű megkülönböztetése',
		matchWord			: 'csak ha ez a teljes szó',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Az összes cseréje',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Táblázat',
		title		: 'Táblázat tulajdonságai',
		menu		: 'Táblázat tulajdonságai',
		deleteTable	: 'Táblázat törlése',
		rows		: 'Sorok',
		columns		: 'Oszlopok',
		border		: 'Szegélyméret',
		align		: 'Igazítás',
		alignNotSet	: '<Nincs beállítva>',
		alignLeft	: 'Balra',
		alignCenter	: 'Középre',
		alignRight	: 'Jobbra',
		width		: 'Szélesség',
		widthPx		: 'képpont',
		widthPc		: 'százalék',
		height		: 'Magasság',
		cellSpace	: 'Cella térköz',
		cellPad		: 'Cella belső margó',
		caption		: 'Felirat',
		summary		: 'Leírás',
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
			menu			: 'Cella',
			insertBefore	: 'Cella beillesztése az aktuális cella elé',
			insertAfter		: 'Cella beillesztése az aktuális cella mögé',
			deleteCell		: 'Cellák törlése',
			merge			: 'Cellák egyesítése',
			mergeRight		: 'Cellák egyesítése jobbra',
			mergeDown		: 'Cellák egyesítése lefelé',
			splitHorizontal	: 'Cellák szétválasztása vízszintesen',
			splitVertical	: 'Cellák szétválasztása függőlegesen',
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
			menu			: 'Sor',
			insertBefore	: 'Sor beillesztése az aktuális sor elé',
			insertAfter		: 'Sor beillesztése az aktuális sor mögé',
			deleteRow		: 'Sorok törlése'
		},

		column :
		{
			menu			: 'Oszlop',
			insertBefore	: 'Oszlop beillesztése az aktuális oszlop elé',
			insertAfter		: 'Oszlop beillesztése az aktuális oszlop mögé',
			deleteColumn	: 'Oszlopok törlése'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Gomb tulajdonságai',
		text		: 'Szöveg (Érték)',
		type		: 'Típus',
		typeBtn		: 'Gomb',
		typeSbm		: 'Küldés',
		typeRst		: 'Alaphelyzet'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Jelölőnégyzet tulajdonságai',
		radioTitle	: 'Választógomb tulajdonságai',
		value		: 'Érték',
		selected	: 'Kiválasztott'
	},

	// Form Dialog.
	form :
	{
		title		: 'Űrlap tulajdonságai',
		menu		: 'Űrlap tulajdonságai',
		action		: 'Adatfeldolgozást végző hivatkozás',
		method		: 'Adatküldés módja',
		encoding	: 'Encoding', // MISSING
		target		: 'Tartalom megjelenítése',
		targetNotSet	: '<nincs beállítva>',
		targetNew	: 'Új ablakban (_blank)',
		targetTop	: 'Legfelső ablakban (_top)',
		targetSelf	: 'Azonos ablakban (_self)',
		targetParent	: 'Szülő ablakban (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Legördülő lista tulajdonságai',
		selectInfo	: 'Alaptulajdonságok',
		opAvail		: 'Elérhető opciók',
		value		: 'Érték',
		size		: 'Méret',
		lines		: 'sor',
		chkMulti	: 'több sor is kiválasztható',
		opText		: 'Szöveg',
		opValue		: 'Érték',
		btnAdd		: 'Hozzáad',
		btnModify	: 'Módosít',
		btnUp		: 'Fel',
		btnDown		: 'Le',
		btnSetValue : 'Legyen az alapértelmezett érték',
		btnDelete	: 'Töröl'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Szövegterület tulajdonságai',
		cols		: 'Karakterek száma egy sorban',
		rows		: 'Sorok száma'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Szövegmező tulajdonságai',
		name		: 'Név',
		value		: 'Érték',
		charWidth	: 'Megjelenített karakterek száma',
		maxChars	: 'Maximális karakterszám',
		type		: 'Típus',
		typeText	: 'Szöveg',
		typePass	: 'Jelszó'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Rejtett mező tulajdonságai',
		name	: 'Név',
		value	: 'Érték'
	},

	// Image Dialog.
	image :
	{
		title		: 'Kép tulajdonságai',
		titleButton	: 'Képgomb tulajdonságai',
		menu		: 'Kép tulajdonságai',
		infoTab	: 'Alaptulajdonságok',
		btnUpload	: 'Küldés a szerverre',
		url		: 'Hivatkozás',
		upload	: 'Feltöltés',
		alt		: 'Buborék szöveg',
		width		: 'Szélesség',
		height	: 'Magasság',
		lockRatio	: 'Arány megtartása',
		resetSize	: 'Eredeti méret',
		border	: 'Keret',
		hSpace	: 'Vízsz. táv',
		vSpace	: 'Függ. táv',
		align		: 'Igazítás',
		alignLeft	: 'Bal',
		alignAbsBottom: 'Legaljára',
		alignAbsMiddle: 'Közepére',
		alignBaseline	: 'Alapvonalhoz',
		alignBottom	: 'Aljára',
		alignMiddle	: 'Középre',
		alignRight	: 'Jobbra',
		alignTextTop	: 'Szöveg tetejére',
		alignTop	: 'Tetejére',
		preview	: 'Előnézet',
		alertUrl	: 'Töltse ki a kép webcímét',
		linkTab	: 'Hivatkozás',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Flash tulajdonságai',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Flash tulajdonságai',
		chkPlay		: 'Automata lejátszás',
		chkLoop		: 'Folyamatosan',
		chkMenu		: 'Flash menü engedélyezése',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Méretezés',
		scaleAll		: 'Mindent mutat',
		scaleNoBorder	: 'Keret nélkül',
		scaleFit		: 'Teljes kitöltés',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Igazítás',
		alignLeft	: 'Bal',
		alignAbsBottom: 'Legaljára',
		alignAbsMiddle: 'Közepére',
		alignBaseline	: 'Alapvonalhoz',
		alignBottom	: 'Aljára',
		alignMiddle	: 'Középre',
		alignRight	: 'Jobbra',
		alignTextTop	: 'Szöveg tetejére',
		alignTop	: 'Tetejére',
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
		bgcolor	: 'Háttérszín',
		width	: 'Szélesség',
		height	: 'Magasság',
		hSpace	: 'Vízsz. táv',
		vSpace	: 'Függ. táv',
		validateSrc : 'Adja meg a hivatkozás webcímét',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Helyesírás-ellenőrzés',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Nincs a szótárban',
		changeTo		: 'Módosítás',
		btnIgnore		: 'Kihagyja',
		btnIgnoreAll	: 'Mindet kihagyja',
		btnReplace		: 'Csere',
		btnReplaceAll	: 'Összes cseréje',
		btnUndo			: 'Visszavonás',
		noSuggestions	: 'Nincs javaslat',
		progress		: 'Helyesírás-ellenőrzés folyamatban...',
		noMispell		: 'Helyesírás-ellenőrzés kész: Nem találtam hibát',
		noChanges		: 'Helyesírás-ellenőrzés kész: Nincs változtatott szó',
		oneChange		: 'Helyesírás-ellenőrzés kész: Egy szó cserélve',
		manyChanges		: 'Helyesírás-ellenőrzés kész: %1 szó cserélve',
		ieSpellDownload	: 'A helyesírás-ellenőrző nincs telepítve. Szeretné letölteni most?'
	},

	smiley :
	{
		toolbar	: 'Hangulatjelek',
		title	: 'Hangulatjel beszúrása'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Számozás',
	bulletedlist : 'Felsorolás',
	indent : 'Behúzás növelése',
	outdent : 'Behúzás csökkentése',

	justify :
	{
		left : 'Balra',
		center : 'Középre',
		right : 'Jobbra',
		block : 'Sorkizárt'
	},

	blockquote : 'Idézet blokk',

	clipboard :
	{
		title		: 'Beillesztés',
		cutError	: 'A böngésző biztonsági beállításai nem engedélyezik a szerkesztőnek, hogy végrehajtsa a kivágás műveletet. Használja az alábbi billentyűkombinációt (Ctrl+X).',
		copyError	: 'A böngésző biztonsági beállításai nem engedélyezik a szerkesztőnek, hogy végrehajtsa a másolás műveletet. Használja az alábbi billentyűkombinációt (Ctrl+X).',
		pasteMsg	: 'Másolja be az alábbi mezőbe a <STRONG>Ctrl+V</STRONG> billentyűk lenyomásával, majd nyomjon <STRONG>Rendben</STRONG>-t.',
		securityMsg	: 'A böngésző biztonsági beállításai miatt a szerkesztő nem képes hozzáférni a vágólap adataihoz. Illeszd be újra ebben az ablakban.'
	},

	pastefromword :
	{
		toolbar : 'Beillesztés Word-ből',
		title : 'Beillesztés Word-ből',
		advice : 'Másolja be az alábbi mezőbe a <STRONG>Ctrl+V</STRONG> billentyűk lenyomásával, majd nyomjon <STRONG>Rendben</STRONG>-t.',
		ignoreFontFace : 'Betű formázások megszüntetése',
		removeStyle : 'Stílusok eltávolítása'
	},

	pasteText :
	{
		button : 'Beillesztés formázatlan szövegként',
		title : 'Beillesztés formázatlan szövegként'
	},

	templates :
	{
		button : 'Sablonok',
		title : 'Elérhető sablonok',
		insertOption: 'Kicseréli a jelenlegi tartalmat',
		selectPromptMsg: 'Válassza ki melyik sablon nyíljon meg a szerkesztőben<br>(a jelenlegi tartalom elveszik):',
		emptyListMsg : '(Nincs sablon megadva)'
	},

	showBlocks : 'Blokkok megjelenítése',

	stylesCombo :
	{
		label : 'Stílus',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'Formátum',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'Formátum',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'Normál',
		tag_pre : 'Formázott',
		tag_address : 'Címsor',
		tag_h1 : 'Fejléc 1',
		tag_h2 : 'Fejléc 2',
		tag_h3 : 'Fejléc 3',
		tag_h4 : 'Fejléc 4',
		tag_h5 : 'Fejléc 5',
		tag_h6 : 'Fejléc 6',
		tag_div : 'Bekezdés (DIV)'
	},

	font :
	{
		label : 'Betűtípus',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'Betűtípus',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'Méret',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Méret',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Betűszín',
		bgColorTitle : 'Háttérszín',
		auto : 'Automatikus',
		more : 'További színek...'
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
