/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Romanian language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['ro'] =
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
	source			: 'Sursa',
	newPage			: 'Pagină nouă',
	save			: 'Salvează',
	preview			: 'Previzualizare',
	cut				: 'Taie',
	copy			: 'Copiază',
	paste			: 'Adaugă',
	print			: 'Printează',
	underline		: 'Subliniat (underline)',
	bold			: 'Îngroşat (bold)',
	italic			: 'Înclinat (italic)',
	selectAll		: 'Selectează tot',
	removeFormat	: 'Înlătură formatarea',
	strike			: 'Tăiat (strike through)',
	subscript		: 'Indice (subscript)',
	superscript		: 'Putere (superscript)',
	horizontalrule	: 'Inserează linie orizontă',
	pagebreak		: 'Inserează separator de pagină (Page Break)',
	unlink			: 'Înlătură link (legătură web)',
	undo			: 'Starea anterioară (undo)',
	redo			: 'Starea ulterioară (redo)',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Răsfoieşte server',
		url				: 'URL',
		protocol		: 'Protocol',
		upload			: 'Încarcă',
		uploadSubmit	: 'Trimite la server',
		image			: 'Imagine',
		flash			: 'Flash',
		form			: 'Formular (Form)',
		checkbox		: 'Bifă (Checkbox)',
		radio		: 'Buton radio (RadioButton)',
		textField		: 'Câmp text (TextField)',
		textarea		: 'Suprafaţă text (Textarea)',
		hiddenField		: 'Câmp ascuns (HiddenField)',
		button			: 'Buton',
		select	: 'Câmp selecţie (SelectionField)',
		imageButton		: 'Buton imagine (ImageButton)',
		notSet			: '<nesetat>',
		id				: 'Id',
		name			: 'Nume',
		langDir			: 'Direcţia cuvintelor',
		langDirLtr		: 'stânga-dreapta (LTR)',
		langDirRtl		: 'dreapta-stânga (RTL)',
		langCode		: 'Codul limbii',
		longDescr		: 'Descrierea lungă URL',
		cssClass		: 'Clasele cu stilul paginii (CSS)',
		advisoryTitle	: 'Titlul consultativ',
		cssStyle		: 'Stil',
		ok				: 'Bine',
		cancel			: 'Anulare',
		generalTab		: 'General', // MISSING
		advancedTab		: 'Avansat',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Inserează caracter special',
		title		: 'Selectează caracter special'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Inserează/Editează link (legătură web)',
		menu		: 'Editează Link',
		title		: 'Link (Legătură web)',
		info		: 'Informaţii despre link (Legătură web)',
		target		: 'Ţintă (Target)',
		upload		: 'Încarcă',
		advanced	: 'Avansat',
		type		: 'Tipul link-ului (al legăturii web)',
		toAnchor	: 'Ancoră în această pagină',
		toEmail		: 'E-Mail',
		target		: 'Ţintă (Target)',
		targetNotSet	: '<nesetat>',
		targetFrame	: '<frame>',
		targetPopup	: '<fereastra popup>',
		targetNew	: 'Fereastră nouă (_blank)',
		targetTop	: 'Fereastra din topul ierarhiei (_top)',
		targetSelf	: 'Aceeaşi fereastră (_self)',
		targetParent	: 'Fereastra părinte (_parent)',
		targetFrameName	: 'Numele frame-ului ţintă',
		targetPopupName	: 'Numele ferestrei popup',
		popupFeatures	: 'Proprietăţile ferestrei popup',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Bara de status',
		popupLocationBar	: 'Bara de locaţie',
		popupToolbar	: 'Bara de opţiuni',
		popupMenuBar	: 'Bara de meniu',
		popupFullScreen	: 'Tot ecranul (Full Screen)(IE)',
		popupScrollBars	: 'Scroll Bars',
		popupDependent	: 'Dependent (Netscape)',
		popupWidth		: 'Lăţime',
		popupLeft		: 'Poziţia la stânga',
		popupHeight		: 'Înălţime',
		popupTop		: 'Poziţia la dreapta',
		id				: 'Id', // MISSING
		langDir			: 'Direcţia cuvintelor',
		langDirNotSet	: '<nesetat>',
		langDirLTR		: 'stânga-dreapta (LTR)',
		langDirRTL		: 'dreapta-stânga (RTL)',
		acccessKey		: 'Tasta de acces',
		name			: 'Nume',
		langCode		: 'Direcţia cuvintelor',
		tabIndex		: 'Indexul tabului',
		advisoryTitle	: 'Titlul consultativ',
		advisoryContentType	: 'Tipul consultativ al titlului',
		cssClasses		: 'Clasele cu stilul paginii (CSS)',
		charset			: 'Setul de caractere al resursei legate',
		styles			: 'Stil',
		selectAnchor	: 'Selectaţi o ancoră',
		anchorName		: 'după numele ancorei',
		anchorId		: 'după Id-ul elementului',
		emailAddress	: 'Adresă de e-mail',
		emailSubject	: 'Subiectul mesajului',
		emailBody		: 'Conţinutul mesajului',
		noAnchors		: '(Nicio ancoră disponibilă în document)',
		noUrl			: 'Vă rugăm să scrieţi URL-ul',
		noEmail			: 'Vă rugăm să scrieţi adresa de e-mail'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Inserează/Editează ancoră',
		menu		: 'Proprietăţi ancoră',
		title		: 'Proprietăţi ancoră',
		name		: 'Numele ancorei',
		errorName	: 'Vă rugăm scrieţi numele ancorei'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Găseşte şi înlocuieşte',
		find				: 'Găseşte',
		replace				: 'Înlocuieşte',
		findWhat			: 'Găseşte:',
		replaceWith			: 'Înlocuieşte cu:',
		notFoundMsg			: 'Textul specificat nu a fost găsit.',
		matchCase			: 'Deosebeşte majuscule de minuscule (Match case)',
		matchWord			: 'Doar cuvintele întregi',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Înlocuieşte tot',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Tabel',
		title		: 'Proprietăţile tabelului',
		menu		: 'Proprietăţile tabelului',
		deleteTable	: 'Şterge tabel',
		rows		: 'Linii',
		columns		: 'Coloane',
		border		: 'Mărimea marginii',
		align		: 'Aliniament',
		alignNotSet	: '<Nesetat>',
		alignLeft	: 'Stânga',
		alignCenter	: 'Centru',
		alignRight	: 'Dreapta',
		width		: 'Lăţime',
		widthPx		: 'pixeli',
		widthPc		: 'procente',
		height		: 'Înălţime',
		cellSpace	: 'Spaţiu între celule',
		cellPad		: 'Spaţiu în cadrul celulei',
		caption		: 'Titlu (Caption)',
		summary		: 'Rezumat',
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
			menu			: 'Celulă',
			insertBefore	: 'Inserează celulă înainte',
			insertAfter		: 'Inserează celulă după',
			deleteCell		: 'Şterge celule',
			merge			: 'Uneşte celule',
			mergeRight		: 'Uneşte la dreapta',
			mergeDown		: 'Uneşte jos',
			splitHorizontal	: 'Împarte celula pe orizontală',
			splitVertical	: 'Împarte celula pe verticală',
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
			menu			: 'Linie',
			insertBefore	: 'Inserează linie înainte',
			insertAfter		: 'Inserează linie după',
			deleteRow		: 'Şterge linii'
		},

		column :
		{
			menu			: 'Coloană',
			insertBefore	: 'Inserează coloană înainte',
			insertAfter		: 'Inserează coloană după',
			deleteColumn	: 'Şterge celule'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Proprietăţi buton',
		text		: 'Text (Valoare)',
		type		: 'Tip',
		typeBtn		: 'Button',
		typeSbm		: 'Submit',
		typeRst		: 'Reset'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Proprietăţi bifă (Checkbox)',
		radioTitle	: 'Proprietăţi buton radio (Radio Button)',
		value		: 'Valoare',
		selected	: 'Selectat'
	},

	// Form Dialog.
	form :
	{
		title		: 'Proprietăţi formular (Form)',
		menu		: 'Proprietăţi formular (Form)',
		action		: 'Acţiune',
		method		: 'Metodă',
		encoding	: 'Encoding', // MISSING
		target		: 'Ţintă (Target)',
		targetNotSet	: '<nesetat>',
		targetNew	: 'Fereastră nouă (_blank)',
		targetTop	: 'Fereastra din topul ierarhiei (_top)',
		targetSelf	: 'Aceeaşi fereastră (_self)',
		targetParent	: 'Fereastra părinte (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Proprietăţi câmp selecţie (Selection Field)',
		selectInfo	: 'Informaţii',
		opAvail		: 'Opţiuni disponibile',
		value		: 'Valoare',
		size		: 'Mărime',
		lines		: 'linii',
		chkMulti	: 'Permite selecţii multiple',
		opText		: 'Text',
		opValue		: 'Valoare',
		btnAdd		: 'Adaugă',
		btnModify	: 'Modifică',
		btnUp		: 'Sus',
		btnDown		: 'Jos',
		btnSetValue : 'Setează ca valoare selectată',
		btnDelete	: 'Şterge'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Proprietăţi suprafaţă text (Textarea)',
		cols		: 'Coloane',
		rows		: 'Linii'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Proprietăţi câmp text (Text Field)',
		name		: 'Nume',
		value		: 'Valoare',
		charWidth	: 'Lărgimea caracterului',
		maxChars	: 'Caractere maxime',
		type		: 'Tip',
		typeText	: 'Text',
		typePass	: 'Parolă'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Proprietăţi câmp ascuns (Hidden Field)',
		name	: 'Nume',
		value	: 'Valoare'
	},

	// Image Dialog.
	image :
	{
		title		: 'Proprietăţile imaginii',
		titleButton	: 'Proprietăţi buton imagine (Image Button)',
		menu		: 'Proprietăţile imaginii',
		infoTab	: 'Informaţii despre imagine',
		btnUpload	: 'Trimite la server',
		url		: 'URL',
		upload	: 'Încarcă',
		alt		: 'Text alternativ',
		width		: 'Lăţime',
		height	: 'Înălţime',
		lockRatio	: 'Păstrează proporţiile',
		resetSize	: 'Resetează mărimea',
		border	: 'Margine',
		hSpace	: 'HSpace',
		vSpace	: 'VSpace',
		align		: 'Aliniere',
		alignLeft	: 'Stânga',
		alignAbsBottom: 'Jos absolut (Abs Bottom)',
		alignAbsMiddle: 'Mijloc absolut (Abs Middle)',
		alignBaseline	: 'Linia de jos (Baseline)',
		alignBottom	: 'Jos',
		alignMiddle	: 'Mijloc',
		alignRight	: 'Dreapta',
		alignTextTop	: 'Text sus',
		alignTop	: 'Sus',
		preview	: 'Previzualizare',
		alertUrl	: 'Vă rugăm să scrieţi URL-ul imaginii',
		linkTab	: 'Link (Legătură web)',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Proprietăţile flash-ului',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Proprietăţile flash-ului',
		chkPlay		: 'Rulează automat',
		chkLoop		: 'Repetă (Loop)',
		chkMenu		: 'Activează meniul flash',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Scală',
		scaleAll		: 'Arată tot',
		scaleNoBorder	: 'Fără margini (No border)',
		scaleFit		: 'Potriveşte',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Aliniere',
		alignLeft	: 'Stânga',
		alignAbsBottom: 'Jos absolut (Abs Bottom)',
		alignAbsMiddle: 'Mijloc absolut (Abs Middle)',
		alignBaseline	: 'Linia de jos (Baseline)',
		alignBottom	: 'Jos',
		alignMiddle	: 'Mijloc',
		alignRight	: 'Dreapta',
		alignTextTop	: 'Text sus',
		alignTop	: 'Sus',
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
		bgcolor	: 'Coloarea fundalului',
		width	: 'Lăţime',
		height	: 'Înălţime',
		hSpace	: 'HSpace',
		vSpace	: 'VSpace',
		validateSrc : 'Vă rugăm să scrieţi URL-ul',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Verifică text',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Nu e în dicţionar',
		changeTo		: 'Schimbă în',
		btnIgnore		: 'Ignoră',
		btnIgnoreAll	: 'Ignoră toate',
		btnReplace		: 'Înlocuieşte',
		btnReplaceAll	: 'Înlocuieşte tot',
		btnUndo			: 'Starea anterioară (undo)',
		noSuggestions	: '- Fără sugestii -',
		progress		: 'Verificarea textului în desfăşurare...',
		noMispell		: 'Verificarea textului terminată: Nicio greşeală găsită',
		noChanges		: 'Verificarea textului terminată: Niciun cuvânt modificat',
		oneChange		: 'Verificarea textului terminată: Un cuvânt modificat',
		manyChanges		: 'Verificarea textului terminată: 1% cuvinte modificate',
		ieSpellDownload	: 'Unealta pentru verificat textul (Spell checker) neinstalată. Doriţi să o descărcaţi acum?'
	},

	smiley :
	{
		toolbar	: 'Figură expresivă (Emoticon)',
		title	: 'Inserează o figură expresivă (Emoticon)'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Listă numerotată',
	bulletedlist : 'Listă cu puncte',
	indent : 'Creşte indentarea',
	outdent : 'Scade indentarea',

	justify :
	{
		left : 'Aliniere la stânga',
		center : 'Aliniere centrală',
		right : 'Aliniere la dreapta',
		block : 'Aliniere în bloc (Block Justify)'
	},

	blockquote : 'Citat',

	clipboard :
	{
		title		: 'Adaugă',
		cutError	: 'Setările de securitate ale navigatorului (browser) pe care îl folosiţi nu permit editorului să execute automat operaţiunea de tăiere. Vă rugăm folosiţi tastatura (Ctrl+X).',
		copyError	: 'Setările de securitate ale navigatorului (browser) pe care îl folosiţi nu permit editorului să execute automat operaţiunea de copiere. Vă rugăm folosiţi tastatura (Ctrl+C).',
		pasteMsg	: 'Vă rugăm adăugaţi în căsuţa următoare folosind tastatura (<STRONG>Ctrl+V</STRONG>) şi apăsaţi <STRONG>OK</STRONG>.',
		securityMsg	: 'Din cauza setărilor de securitate ale programului dvs. cu care navigaţi pe internet (browser), editorul nu poate accesa direct datele din clipboard. Va trebui să adăugaţi din nou datele în această fereastră.'
	},

	pastefromword :
	{
		toolbar : 'Adaugă din Word',
		title : 'Adaugă din Word',
		advice : 'Vă rugăm adăugaţi în căsuţa următoare folosind tastatura (<STRONG>Ctrl+V</STRONG>) şi apăsaţi <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Ignoră definiţiile Font Face',
		removeStyle : 'Şterge definiţiile stilurilor'
	},

	pasteText :
	{
		button : 'Adaugă ca text simplu (Plain Text)',
		title : 'Adaugă ca text simplu (Plain Text)'
	},

	templates :
	{
		button : 'Template-uri (şabloane)',
		title : 'Template-uri (şabloane) de conţinut',
		insertOption: 'Înlocuieşte cuprinsul actual',
		selectPromptMsg: 'Vă rugăm selectaţi template-ul (şablonul) ce se va deschide în editor<br>(conţinutul actual va fi pierdut):',
		emptyListMsg : '(Niciun template (şablon) definit)'
	},

	showBlocks : 'Arată blocurile',

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
		label : 'Formatare',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'Formatare',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'Normal',
		tag_pre : 'Formatted',
		tag_address : 'Address',
		tag_h1 : 'Heading 1',
		tag_h2 : 'Heading 2',
		tag_h3 : 'Heading 3',
		tag_h4 : 'Heading 4',
		tag_h5 : 'Heading 5',
		tag_h6 : 'Heading 6',
		tag_div : 'Normal (DIV)'
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
		label : 'Mărime',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Mărime',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Culoarea textului',
		bgColorTitle : 'Coloarea fundalului',
		auto : 'Automatic',
		more : 'Mai multe culori...'
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
