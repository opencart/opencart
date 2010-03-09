/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Czech language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['cs'] =
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
	editorTitle		: 'Editor formátovaného textu, %1',

	// Toolbar buttons without dialogs.
	source			: 'Zdroj',
	newPage			: 'Nová stránka',
	save			: 'Uložit',
	preview			: 'Náhled',
	cut				: 'Vyjmout',
	copy			: 'Kopírovat',
	paste			: 'Vložit',
	print			: 'Tisk',
	underline		: 'Podtržené',
	bold			: 'Tučné',
	italic			: 'Kurzíva',
	selectAll		: 'Vybrat vše',
	removeFormat	: 'Odstranit formátování',
	strike			: 'Přeškrtnuté',
	subscript		: 'Dolní index',
	superscript		: 'Horní index',
	horizontalrule	: 'Vložit vodorovnou linku',
	pagebreak		: 'Vložit konec stránky',
	unlink			: 'Odstranit odkaz',
	undo			: 'Zpět',
	redo			: 'Znovu',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Vybrat na serveru',
		url				: 'URL',
		protocol		: 'Protokol',
		upload			: 'Odeslat',
		uploadSubmit	: 'Odeslat na server',
		image			: 'Obrázek',
		flash			: 'Flash',
		form			: 'Formulář',
		checkbox		: 'Zaškrtávací políčko',
		radio		: 'Přepínač',
		textField		: 'Textové pole',
		textarea		: 'Textová oblast',
		hiddenField		: 'Skryté pole',
		button			: 'Tlačítko',
		select	: 'Seznam',
		imageButton		: 'Obrázkové tlačítko',
		notSet			: '<nenastaveno>',
		id				: 'Id',
		name			: 'Jméno',
		langDir			: 'Orientace jazyka',
		langDirLtr		: 'Zleva do prava (LTR)',
		langDirRtl		: 'Zprava do leva (RTL)',
		langCode		: 'Kód jazyka',
		longDescr		: 'Dlouhý popis URL',
		cssClass		: 'Třída stylu',
		advisoryTitle	: 'Pomocný titulek',
		cssStyle		: 'Styl',
		ok				: 'OK',
		cancel			: 'Storno',
		generalTab		: 'Obecné',
		advancedTab		: 'Rozšířené',
		validateNumberFailed	: 'Zadaná hodnota není číselná.',
		confirmNewPage	: 'Jakékoliv neuložené změny obsahu budou ztraceny. Skutečně chete otevrít novou stránku?',
		confirmCancel	: 'Některá z nastavení byla změněna. Skutečně chete zavřít dialogové okno?',

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, nedostupné</span>'
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Vložit speciální znaky',
		title		: 'Výběr speciálního znaku'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Vložit/změnit odkaz',
		menu		: 'Změnit odkaz',
		title		: 'Odkaz',
		info		: 'Informace o odkazu',
		target		: 'Cíl',
		upload		: 'Odeslat',
		advanced	: 'Rozšířené',
		type		: 'Typ odkazu',
		toAnchor	: 'Kotva v této stránce',
		toEmail		: 'E-Mail',
		target		: 'Cíl',
		targetNotSet	: '<nenastaveno>',
		targetFrame	: '<rámec>',
		targetPopup	: '<vyskakovací okno>',
		targetNew	: 'Nové okno (_blank)',
		targetTop	: 'Hlavní okno (_top)',
		targetSelf	: 'Stejné okno (_self)',
		targetParent	: 'Rodičovské okno (_parent)',
		targetFrameName	: 'Název cílového rámu',
		targetPopupName	: 'Název vyskakovacího okna',
		popupFeatures	: 'Vlastnosti vyskakovacího okna',
		popupResizable	: 'Umožňující měnit velikost',
		popupStatusBar	: 'Stavový řádek',
		popupLocationBar	: 'Panel umístění',
		popupToolbar	: 'Panel nástrojů',
		popupMenuBar	: 'Panel nabídky',
		popupFullScreen	: 'Celá obrazovka (IE)',
		popupScrollBars	: 'Posuvníky',
		popupDependent	: 'Závislost (Netscape)',
		popupWidth		: 'Šířka',
		popupLeft		: 'Levý okraj',
		popupHeight		: 'Výška',
		popupTop		: 'Horní okraj',
		id				: 'Id',
		langDir			: 'Orientace jazyka',
		langDirNotSet	: '<nenastaveno>',
		langDirLTR		: 'Zleva do prava (LTR)',
		langDirRTL		: 'Zprava do leva (RTL)',
		acccessKey		: 'Přístupový klíč',
		name			: 'Jméno',
		langCode		: 'Orientace jazyka',
		tabIndex		: 'Pořadí prvku',
		advisoryTitle	: 'Pomocný titulek',
		advisoryContentType	: 'Pomocný typ obsahu',
		cssClasses		: 'Třída stylu',
		charset			: 'Přiřazená znaková sada',
		styles			: 'Styl',
		selectAnchor	: 'Vybrat kotvu',
		anchorName		: 'Podle jména kotvy',
		anchorId		: 'Podle Id objektu',
		emailAddress	: 'E-Mailová adresa',
		emailSubject	: 'Předmět zprávy',
		emailBody		: 'Tělo zprávy',
		noAnchors		: '(Ve stránce není definována žádná kotva!)',
		noUrl			: 'Zadejte prosím URL odkazu',
		noEmail			: 'Zadejte prosím e-mailovou adresu'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Vložít/změnit záložku',
		menu		: 'Vlastnosti záložky',
		title		: 'Vlastnosti záložky',
		name		: 'Název záložky',
		errorName	: 'Zadejte prosím název záložky'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Najít a nahradit',
		find				: 'Hledat',
		replace				: 'Nahradit',
		findWhat			: 'Co hledat:',
		replaceWith			: 'Čím nahradit:',
		notFoundMsg			: 'Hledaný text nebyl nalezen.',
		matchCase			: 'Rozlišovat velikost písma',
		matchWord			: 'Pouze celá slova',
		matchCyclic			: 'Procházet opakovaně',
		replaceAll			: 'Nahradit vše',
		replaceSuccessMsg	: '%1 nahrazení.'
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Tabulka',
		title		: 'Vlastnosti tabulky',
		menu		: 'Vlastnosti tabulky',
		deleteTable	: 'Smazat tabulku',
		rows		: 'Řádky',
		columns		: 'Sloupce',
		border		: 'Ohraničení',
		align		: 'Zarovnání',
		alignNotSet	: '<nenastaveno>',
		alignLeft	: 'Vlevo',
		alignCenter	: 'Na střed',
		alignRight	: 'Vpravo',
		width		: 'Šířka',
		widthPx		: 'bodů',
		widthPc		: 'procent',
		height		: 'Výška',
		cellSpace	: 'Vzdálenost buněk',
		cellPad		: 'Odsazení obsahu v buňce',
		caption		: 'Popis',
		summary		: 'Souhrn',
		headers		: 'Záhlaví',
		headersNone		: 'Žádné',
		headersColumn	: 'První sloupec',
		headersRow		: 'První řádek',
		headersBoth		: 'Obojí',
		invalidRows		: 'Počet řádků musí být číslo větší než 0.',
		invalidCols		: 'Počet sloupců musí být číslo větší než 0.',
		invalidBorder	: 'Zdaná velikost okraje musí být číselná.',
		invalidWidth	: 'Zadaná šířka tabulky musí být číselná.',
		invalidHeight	: 'zadaná výška tabulky musí být číselná.',
		invalidCellSpacing	: 'Zadaná vzdálenost buněk musí být číselná.',
		invalidCellPadding	: 'Zadané odsazení obsahu v buňce musí být číselné.',

		cell :
		{
			menu			: 'Buňka',
			insertBefore	: 'Vložit buňku před',
			insertAfter		: 'Vložit buňku za',
			deleteCell		: 'Smazat buňky',
			merge			: 'Sloučit buňky',
			mergeRight		: 'Sloučit doprava',
			mergeDown		: 'Sloučit dolů',
			splitHorizontal	: 'Rozdělit buňky vodorovně',
			splitVertical	: 'Rozdělit buňky svisle',
			title			: 'Vlastnosti buňky',
			cellType		: 'Typ buňky',
			rowSpan			: 'Spojit řádky',
			colSpan			: 'Spojit sloupce',
			wordWrap		: 'Zalamování',
			hAlign			: 'Vodorovné zarovnání',
			vAlign			: 'Svislé zarovnání',
			alignTop		: 'Nahoru',
			alignMiddle		: 'Doprostřed',
			alignBottom		: 'Dolů',
			alignBaseline	: 'Na účaří',
			bgColor			: 'Barva pozadí',
			borderColor		: 'Barva okraje',
			data			: 'Data',
			header			: 'Hlavička',
			yes				: 'Ano',
			no				: 'Ne',
			invalidWidth	: 'Zadaná šířka buňky musí být číslená.',
			invalidHeight	: 'Zadaná výška buňky musí být číslená.',
			invalidRowSpan	: 'Zadaný počet sloučených řádků musí být celé číslo.',
			invalidColSpan	: 'Zadaný počet sloučených sloupců musí být celé číslo.',
			chooseColor : 'Výběr'
		},

		row :
		{
			menu			: 'Řádek',
			insertBefore	: 'Vložit řádek před',
			insertAfter		: 'Vložit řádek za',
			deleteRow		: 'Smazat řádky'
		},

		column :
		{
			menu			: 'Sloupec',
			insertBefore	: 'Vložit sloupec před',
			insertAfter		: 'Vložit sloupec za',
			deleteColumn	: 'Smazat sloupec'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Vlastnosti tlačítka',
		text		: 'Popisek',
		type		: 'Typ',
		typeBtn		: 'Tlačítko',
		typeSbm		: 'Odeslat',
		typeRst		: 'Obnovit'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Vlastnosti zaškrtávacího políčka',
		radioTitle	: 'Vlastnosti přepínače',
		value		: 'Hodnota',
		selected	: 'Zaškrtnuto'
	},

	// Form Dialog.
	form :
	{
		title		: 'Vlastnosti formuláře',
		menu		: 'Vlastnosti formuláře',
		action		: 'Akce',
		method		: 'Metoda',
		encoding	: 'Kódování',
		target		: 'Cíl',
		targetNotSet	: '<nenastaveno>',
		targetNew	: 'Nové okno (_blank)',
		targetTop	: 'Hlavní okno (_top)',
		targetSelf	: 'Stejné okno (_self)',
		targetParent	: 'Rodičovské okno (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Vlastnosti seznamu',
		selectInfo	: 'Info',
		opAvail		: 'Dostupná nastavení',
		value		: 'Hodnota',
		size		: 'Velikost',
		lines		: 'Řádků',
		chkMulti	: 'Povolit mnohonásobné výběry',
		opText		: 'Text',
		opValue		: 'Hodnota',
		btnAdd		: 'Přidat',
		btnModify	: 'Změnit',
		btnUp		: 'Nahoru',
		btnDown		: 'Dolů',
		btnSetValue : 'Nastavit jako vybranou hodnotu',
		btnDelete	: 'Smazat'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Vlastnosti textové oblasti',
		cols		: 'Sloupců',
		rows		: 'Řádků'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Vlastnosti textového pole',
		name		: 'Název',
		value		: 'Hodnota',
		charWidth	: 'Šířka ve znacích',
		maxChars	: 'Maximální počet znaků',
		type		: 'Typ',
		typeText	: 'Text',
		typePass	: 'Heslo'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Vlastnosti skrytého pole',
		name	: 'Název',
		value	: 'Hodnota'
	},

	// Image Dialog.
	image :
	{
		title		: 'Vlastnosti obrázku',
		titleButton	: 'Vlastností obrázkového tlačítka',
		menu		: 'Vlastnosti obrázku',
		infoTab	: 'Informace o obrázku',
		btnUpload	: 'Odeslat na server',
		url		: 'URL',
		upload	: 'Odeslat',
		alt		: 'Alternativní text',
		width		: 'Šířka',
		height	: 'Výška',
		lockRatio	: 'Zámek',
		resetSize	: 'Původní velikost',
		border	: 'Okraje',
		hSpace	: 'H-mezera',
		vSpace	: 'V-mezera',
		align		: 'Zarovnání',
		alignLeft	: 'Vlevo',
		alignAbsBottom: 'Zcela dolů',
		alignAbsMiddle: 'Doprostřed',
		alignBaseline	: 'Na účaří',
		alignBottom	: 'Dolů',
		alignMiddle	: 'Na střed',
		alignRight	: 'Vpravo',
		alignTextTop	: 'Na horní okraj textu',
		alignTop	: 'Nahoru',
		preview	: 'Náhled',
		alertUrl	: 'Zadejte prosím URL obrázku',
		linkTab	: 'Odkaz',
		button2Img	: 'Skutečně chcete převést zvolené obrázkové tlačítko na obyčejný obrázek?',
		img2Button	: 'Skutečně chcete převést zvolený obrázek na obrázkové tlačítko?',
		urlMissing : 'Zadané URL zdroje obrázku nebylo nalezeno.'
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Vlastnosti Flashe',
		propertiesTab	: 'Vlastnosti',
		title		: 'Vlastnosti Flashe',
		chkPlay		: 'Automatické spuštění',
		chkLoop		: 'Opakování',
		chkMenu		: 'Nabídka Flash',
		chkFull		: 'Povolit celoobrazovkový režim',
 		scale		: 'Zobrazit',
		scaleAll		: 'Zobrazit vše',
		scaleNoBorder	: 'Bez okraje',
		scaleFit		: 'Přizpůsobit',
		access			: 'Přístup ke skriptu',
		accessAlways	: 'Vždy',
		accessSameDomain	: 'Ve stejné doméně',
		accessNever	: 'Nikdy',
		align		: 'Zarovnání',
		alignLeft	: 'Vlevo',
		alignAbsBottom: 'Zcela dolů',
		alignAbsMiddle: 'Doprostřed',
		alignBaseline	: 'Na účaří',
		alignBottom	: 'Dolů',
		alignMiddle	: 'Na střed',
		alignRight	: 'Vpravo',
		alignTextTop	: 'Na horní okraj textu',
		alignTop	: 'Nahoru',
		quality		: 'Kvalita',
		qualityBest		 : 'Nejlepší',
		qualityHigh		 : 'Vysoká',
		qualityAutoHigh	 : 'Vysoká - auto',
		qualityMedium	 : 'Střední',
		qualityAutoLow	 : 'Nízká - auto',
		qualityLow		 : 'Nejnižší',
		windowModeWindow	 : 'Okno',
		windowModeOpaque	 : 'Neprůhledné',
		windowModeTransparent	 : 'Průhledné',
		windowMode	: 'Režim okna',
		flashvars	: 'Proměnné pro Flash',
		bgcolor	: 'Barva pozadí',
		width	: 'Šířka',
		height	: 'Výška',
		hSpace	: 'H-mezera',
		vSpace	: 'V-mezera',
		validateSrc : 'Zadejte prosím URL odkazu',
		validateWidth : 'Zadaná šířka musí být číslo.',
		validateHeight : 'Zadaná výška musí být číslo.',
		validateHSpace : 'Zadaná H-mezera musí být číslo.',
		validateVSpace : 'Zadaná V-mezera musí být číslo.'
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Zkontrolovat pravopis',
		title			: 'Kontrola pravopisu',
		notAvailable	: 'Omlouváme se, ale služba nyní není dostupná.',
		errorLoading	: 'Chyba nahrávání služby aplikace z: %s.',
		notInDic		: 'Není ve slovníku',
		changeTo		: 'Změnit na',
		btnIgnore		: 'Přeskočit',
		btnIgnoreAll	: 'Přeskakovat vše',
		btnReplace		: 'Zaměnit',
		btnReplaceAll	: 'Zaměňovat vše',
		btnUndo			: 'Zpět',
		noSuggestions	: '- žádné návrhy -',
		progress		: 'Probíhá kontrola pravopisu...',
		noMispell		: 'Kontrola pravopisu dokončena: Žádné pravopisné chyby nenalezeny',
		noChanges		: 'Kontrola pravopisu dokončena: Beze změn',
		oneChange		: 'Kontrola pravopisu dokončena: Jedno slovo změněno',
		manyChanges		: 'Kontrola pravopisu dokončena: %1 slov změněno',
		ieSpellDownload	: 'Kontrola pravopisu není nainstalována. Chcete ji nyní stáhnout?'
	},

	smiley :
	{
		toolbar	: 'Smajlíky',
		title	: 'Vkládání smajlíků'
	},

	elementsPath :
	{
		eleTitle : '%1 objekt'
	},

	numberedlist : 'Číslování',
	bulletedlist : 'Odrážky',
	indent : 'Zvětšit odsazení',
	outdent : 'Zmenšit odsazení',

	justify :
	{
		left : 'Zarovnat vlevo',
		center : 'Zarovnat na střed',
		right : 'Zarovnat vpravo',
		block : 'Zarovnat do bloku'
	},

	blockquote : 'Citace',

	clipboard :
	{
		title		: 'Vložit',
		cutError	: 'Bezpečnostní nastavení Vašeho prohlížeče nedovolují editoru spustit funkci pro vyjmutí zvoleného textu do schránky. Prosím vyjměte zvolený text do schránky pomocí klávesnice (Ctrl+X).',
		copyError	: 'Bezpečnostní nastavení Vašeho prohlížeče nedovolují editoru spustit funkci pro kopírování zvoleného textu do schránky. Prosím zkopírujte zvolený text do schránky pomocí klávesnice (Ctrl+C).',
		pasteMsg	: 'Do následujícího pole vložte požadovaný obsah pomocí klávesnice (<STRONG>Ctrl+V</STRONG>) a stiskněte <STRONG>OK</STRONG>.',
		securityMsg	: 'Z důvodů nastavení bezpečnosti Vašeho prohlížeče nemůže editor přistupovat přímo do schránky. Obsah schránky prosím vložte znovu do tohoto okna.'
	},

	pastefromword :
	{
		toolbar : 'Vložit z Wordu',
		title : 'Vložit z Wordu',
		advice : 'Do následujícího pole vložte požadovaný obsah pomocí klávesnice (<STRONG>Ctrl+V</STRONG>) a stiskněte <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Ignorovat písmo',
		removeStyle : 'Odstranit styly'
	},

	pasteText :
	{
		button : 'Vložit jako čistý text',
		title : 'Vložit jako čistý text'
	},

	templates :
	{
		button : 'Šablony',
		title : 'Šablony obsahu',
		insertOption: 'Nahradit aktuální obsah',
		selectPromptMsg: 'Prosím zvolte šablonu pro otevření v editoru<br>(aktuální obsah editoru bude ztracen):',
		emptyListMsg : '(Není definována žádná šablona)'
	},

	showBlocks : 'Ukázat bloky',

	stylesCombo :
	{
		label : 'Styl',
		voiceLabel : 'Styly',
		panelVoiceLabel : 'Výběr stylu',
		panelTitle1 : 'Blokové styly',
		panelTitle2 : 'Řádkové styly',
		panelTitle3 : 'Objektové styly'
	},

	format :
	{
		label : 'Formát',
		voiceLabel : 'Formátování',
		panelTitle : 'Formát',
		panelVoiceLabel : 'Volba formátu odstavce',

		tag_p : 'Normální',
		tag_pre : 'Naformátováno',
		tag_address : 'Adresa',
		tag_h1 : 'Nadpis 1',
		tag_h2 : 'Nadpis 2',
		tag_h3 : 'Nadpis 3',
		tag_h4 : 'Nadpis 4',
		tag_h5 : 'Nadpis 5',
		tag_h6 : 'Nadpis 6',
		tag_div : 'Normální (DIV)'
	},

	font :
	{
		label : 'Písmo',
		voiceLabel : 'Písmo',
		panelTitle : 'Písmo',
		panelVoiceLabel : 'Volba písma'
	},

	fontSize :
	{
		label : 'Velikost',
		voiceLabel : 'Velikost písma',
		panelTitle : 'Velikost',
		panelVoiceLabel : 'Volba velikosti písma'
	},

	colorButton :
	{
		textColorTitle : 'Barva textu',
		bgColorTitle : 'Barva pozadí',
		auto : 'Automaticky',
		more : 'Více barev...'
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
		title : 'Kontrola pravopisu během psaní (SCAYT)',
		enable : 'Zapnout SCAYT',
		disable : 'Vypnout SCAYT',
		about : 'O aplikaci SCAYT',
		toggle : 'Vypínač SCAYT',
		options : 'Nastavení',
		langs : 'Jazyky',
		moreSuggestions : 'Více návrhů',
		ignore : 'Přeskočit',
		ignoreAll : 'Přeskočit vše',
		addWord : 'Přidat slovo',
		emptyDic : 'Název slovníku nesmí být prázdný.',
		optionsTab : 'Nastavení',
		languagesTab : 'Jazyky',
		dictionariesTab : 'Slovníky',
		aboutTab : 'O aplikaci'
	},

	about :
	{
		title : 'O aplikaci CKEditor',
		dlgTitle : 'O aplikaci CKEditor',
		moreInfo : 'Pro informace o lincenci navštivte naši webovou stránku:',
		copy : 'Copyright &copy; $1. All rights reserved.'
	},

	maximize : 'Maximalizovat',
	minimize : 'Minimalizovat',

	fakeobjects :
	{
		anchor : 'Záložka',
		flash : 'Flash animace',
		div : 'Zalomení stránky',
		unknown : 'Neznámý objekt'
	},

	resize : 'Uchopit pro změnu velikosti',

	colordialog :
	{
		title : 'Výběr barvy',
		highlight : 'Zvýraznit',
		selected : 'Vybráno',
		clear : 'Vyčistit'
	}
};
