/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Galician language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['gl'] =
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
	source			: 'Código Fonte',
	newPage			: 'Nova Páxina',
	save			: 'Gardar',
	preview			: 'Vista Previa',
	cut				: 'Cortar',
	copy			: 'Copiar',
	paste			: 'Pegar',
	print			: 'Imprimir',
	underline		: 'Sub-raiado',
	bold			: 'Negrita',
	italic			: 'Cursiva',
	selectAll		: 'Seleccionar todo',
	removeFormat	: 'Eliminar Formato',
	strike			: 'Tachado',
	subscript		: 'Subíndice',
	superscript		: 'Superíndice',
	horizontalrule	: 'Inserir Liña Horizontal',
	pagebreak		: 'Inserir Salto de Páxina',
	unlink			: 'Eliminar Ligazón',
	undo			: 'Desfacer',
	redo			: 'Refacer',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Navegar no Servidor',
		url				: 'URL',
		protocol		: 'Protocolo',
		upload			: 'Carregar',
		uploadSubmit	: 'Enviar ó Servidor',
		image			: 'Imaxe',
		flash			: 'Flash',
		form			: 'Formulario',
		checkbox		: 'Cadro de Verificación',
		radio		: 'Botón de Radio',
		textField		: 'Campo de Texto',
		textarea		: 'Área de Texto',
		hiddenField		: 'Campo Oculto',
		button			: 'Botón',
		select	: 'Campo de Selección',
		imageButton		: 'Botón de Imaxe',
		notSet			: '<non definido>',
		id				: 'Id',
		name			: 'Nome',
		langDir			: 'Orientación do Idioma',
		langDirLtr		: 'Esquerda a Dereita (LTR)',
		langDirRtl		: 'Dereita a Esquerda (RTL)',
		langCode		: 'Código do Idioma',
		longDescr		: 'Descrición Completa da URL',
		cssClass		: 'Clases da Folla de Estilos',
		advisoryTitle	: 'Título',
		cssStyle		: 'Estilo',
		ok				: 'OK',
		cancel			: 'Cancelar',
		generalTab		: 'General', // MISSING
		advancedTab		: 'Advanzado',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Inserir Carácter Especial',
		title		: 'Seleccione Caracter Especial'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Inserir/Editar Ligazón',
		menu		: 'Editar Ligazón',
		title		: 'Ligazón',
		info		: 'Información da Ligazón',
		target		: 'Destino',
		upload		: 'Carregar',
		advanced	: 'Advanzado',
		type		: 'Tipo de Ligazón',
		toAnchor	: 'Referencia nesta páxina',
		toEmail		: 'E-Mail',
		target		: 'Destino',
		targetNotSet	: '<non definido>',
		targetFrame	: '<frame>',
		targetPopup	: '<Xanela Emerxente>',
		targetNew	: 'Nova Xanela (_blank)',
		targetTop	: 'Xanela Primaria (_top)',
		targetSelf	: 'Mesma Xanela (_self)',
		targetParent	: 'Xanela Pai (_parent)',
		targetFrameName	: 'Nome do Marco Destino',
		targetPopupName	: 'Nome da Xanela Emerxente',
		popupFeatures	: 'Características da Xanela Emerxente',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Barra de Estado',
		popupLocationBar	: 'Barra de Localización',
		popupToolbar	: 'Barra de Ferramentas',
		popupMenuBar	: 'Barra de Menú',
		popupFullScreen	: 'A Toda Pantalla (IE)',
		popupScrollBars	: 'Barras de Desplazamento',
		popupDependent	: 'Dependente (Netscape)',
		popupWidth		: 'Largura',
		popupLeft		: 'Posición Esquerda',
		popupHeight		: 'Altura',
		popupTop		: 'Posición dende Arriba',
		id				: 'Id', // MISSING
		langDir			: 'Orientación do Idioma',
		langDirNotSet	: '<non definido>',
		langDirLTR		: 'Esquerda a Dereita (LTR)',
		langDirRTL		: 'Dereita a Esquerda (RTL)',
		acccessKey		: 'Chave de Acceso',
		name			: 'Nome',
		langCode		: 'Orientación do Idioma',
		tabIndex		: 'Índice de Tabulación',
		advisoryTitle	: 'Título',
		advisoryContentType	: 'Tipo de Contido',
		cssClasses		: 'Clases da Folla de Estilos',
		charset			: 'Fonte de Caracteres Vinculado',
		styles			: 'Estilo',
		selectAnchor	: 'Seleccionar unha Referencia',
		anchorName		: 'Por Nome de Referencia',
		anchorId		: 'Por Element Id',
		emailAddress	: 'Enderezo de E-Mail',
		emailSubject	: 'Asunto do Mensaxe',
		emailBody		: 'Corpo do Mensaxe',
		noAnchors		: '(Non hai referencias disponibles no documento)',
		noUrl			: 'Por favor, escriba a ligazón URL',
		noEmail			: 'Por favor, escriba o enderezo de e-mail'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Inserir/Editar Referencia',
		menu		: 'Propriedades da Referencia',
		title		: 'Propriedades da Referencia',
		name		: 'Nome da Referencia',
		errorName	: 'Por favor, escriba o nome da referencia'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Find and Replace', // MISSING
		find				: 'Procurar',
		replace				: 'Substituir',
		findWhat			: 'Texto a procurar:',
		replaceWith			: 'Substituir con:',
		notFoundMsg			: 'Non te atopou o texto indicado.',
		matchCase			: 'Coincidir Mai./min.',
		matchWord			: 'Coincidir con toda a palabra',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Substitiur Todo',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Tabla',
		title		: 'Propiedades da Táboa',
		menu		: 'Propiedades da Táboa',
		deleteTable	: 'Borrar Táboa',
		rows		: 'Filas',
		columns		: 'Columnas',
		border		: 'Tamaño do Borde',
		align		: 'Aliñamento',
		alignNotSet	: '<Non Definido>',
		alignLeft	: 'Esquerda',
		alignCenter	: 'Centro',
		alignRight	: 'Ereita',
		width		: 'Largura',
		widthPx		: 'pixels',
		widthPc		: 'percent',
		height		: 'Altura',
		cellSpace	: 'Marxe entre Celas',
		cellPad		: 'Marxe interior',
		caption		: 'Título',
		summary		: 'Sumario',
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
			menu			: 'Cela',
			insertBefore	: 'Insert Cell Before', // MISSING
			insertAfter		: 'Insert Cell After', // MISSING
			deleteCell		: 'Borrar Cela',
			merge			: 'Unir Celas',
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
			menu			: 'Fila',
			insertBefore	: 'Insert Row Before', // MISSING
			insertAfter		: 'Insert Row After', // MISSING
			deleteRow		: 'Borrar Filas'
		},

		column :
		{
			menu			: 'Columna',
			insertBefore	: 'Insert Column Before', // MISSING
			insertAfter		: 'Insert Column After', // MISSING
			deleteColumn	: 'Borrar Columnas'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Propriedades do Botón',
		text		: 'Texto (Valor)',
		type		: 'Tipo',
		typeBtn		: 'Button', // MISSING
		typeSbm		: 'Submit', // MISSING
		typeRst		: 'Reset' // MISSING
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Propriedades do Cadro de Verificación',
		radioTitle	: 'Propriedades do Botón de Radio',
		value		: 'Valor',
		selected	: 'Seleccionado'
	},

	// Form Dialog.
	form :
	{
		title		: 'Propriedades do Formulario',
		menu		: 'Propriedades do Formulario',
		action		: 'Acción',
		method		: 'Método',
		encoding	: 'Encoding', // MISSING
		target		: 'Destino',
		targetNotSet	: '<non definido>',
		targetNew	: 'Nova Xanela (_blank)',
		targetTop	: 'Xanela Primaria (_top)',
		targetSelf	: 'Mesma Xanela (_self)',
		targetParent	: 'Xanela Pai (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Propriedades do Campo de Selección',
		selectInfo	: 'Info',
		opAvail		: 'Opcións Disponibles',
		value		: 'Valor',
		size		: 'Tamaño',
		lines		: 'liñas',
		chkMulti	: 'Permitir múltiples seleccións',
		opText		: 'Texto',
		opValue		: 'Valor',
		btnAdd		: 'Engadir',
		btnModify	: 'Modificar',
		btnUp		: 'Subir',
		btnDown		: 'Baixar',
		btnSetValue : 'Definir como valor por defecto',
		btnDelete	: 'Borrar'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Propriedades da Área de Texto',
		cols		: 'Columnas',
		rows		: 'Filas'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Propriedades do Campo de Texto',
		name		: 'Nome',
		value		: 'Valor',
		charWidth	: 'Tamaño do Caracter',
		maxChars	: 'Máximo de Caracteres',
		type		: 'Tipo',
		typeText	: 'Texto',
		typePass	: 'Chave'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Propriedades do Campo Oculto',
		name	: 'Nome',
		value	: 'Valor'
	},

	// Image Dialog.
	image :
	{
		title		: 'Propriedades da Imaxe',
		titleButton	: 'Propriedades do Botón de Imaxe',
		menu		: 'Propriedades da Imaxe',
		infoTab	: 'Información da Imaxe',
		btnUpload	: 'Enviar ó Servidor',
		url		: 'URL',
		upload	: 'Carregar',
		alt		: 'Texto Alternativo',
		width		: 'Largura',
		height	: 'Altura',
		lockRatio	: 'Proporcional',
		resetSize	: 'Tamaño Orixinal',
		border	: 'Límite',
		hSpace	: 'Esp. Horiz.',
		vSpace	: 'Esp. Vert.',
		align		: 'Aliñamento',
		alignLeft	: 'Esquerda',
		alignAbsBottom: 'Abs Inferior',
		alignAbsMiddle: 'Abs Centro',
		alignBaseline	: 'Liña Base',
		alignBottom	: 'Pé',
		alignMiddle	: 'Centro',
		alignRight	: 'Dereita',
		alignTextTop	: 'Tope do Texto',
		alignTop	: 'Tope',
		preview	: 'Vista Previa',
		alertUrl	: 'Por favor, escriba a URL da imaxe',
		linkTab	: 'Ligazón',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Propriedades Flash',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Propriedades Flash',
		chkPlay		: 'Auto Execución',
		chkLoop		: 'Bucle',
		chkMenu		: 'Activar Menú Flash',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Escalar',
		scaleAll		: 'Amosar Todo',
		scaleNoBorder	: 'Sen Borde',
		scaleFit		: 'Encaixar axustando',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Aliñamento',
		alignLeft	: 'Esquerda',
		alignAbsBottom: 'Abs Inferior',
		alignAbsMiddle: 'Abs Centro',
		alignBaseline	: 'Liña Base',
		alignBottom	: 'Pé',
		alignMiddle	: 'Centro',
		alignRight	: 'Dereita',
		alignTextTop	: 'Tope do Texto',
		alignTop	: 'Tope',
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
		bgcolor	: 'Cor do Fondo',
		width	: 'Largura',
		height	: 'Altura',
		hSpace	: 'Esp. Horiz.',
		vSpace	: 'Esp. Vert.',
		validateSrc : 'Por favor, escriba a ligazón URL',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Corrección Ortográfica',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Non está no diccionario',
		changeTo		: 'Cambiar a',
		btnIgnore		: 'Ignorar',
		btnIgnoreAll	: 'Ignorar Todas',
		btnReplace		: 'Substituir',
		btnReplaceAll	: 'Substituir Todas',
		btnUndo			: 'Desfacer',
		noSuggestions	: '- Sen candidatos -',
		progress		: 'Corrección ortográfica en progreso...',
		noMispell		: 'Corrección ortográfica rematada: Non se atoparon erros',
		noChanges		: 'Corrección ortográfica rematada: Non se substituiu nengunha verba',
		oneChange		: 'Corrección ortográfica rematada: Unha verba substituida',
		manyChanges		: 'Corrección ortográfica rematada: %1 verbas substituidas',
		ieSpellDownload	: 'O corrector ortográfico non está instalado. ¿Quere descargalo agora?'
	},

	smiley :
	{
		toolbar	: 'Smiley',
		title	: 'Inserte un Smiley'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Lista Numerada',
	bulletedlist : 'Marcas',
	indent : 'Aumentar Sangría',
	outdent : 'Disminuir Sangría',

	justify :
	{
		left : 'Aliñar á Esquerda',
		center : 'Centrado',
		right : 'Aliñar á Dereita',
		block : 'Xustificado'
	},

	blockquote : 'Blockquote', // MISSING

	clipboard :
	{
		title		: 'Pegar',
		cutError	: 'Os axustes de seguridade do seu navegador non permiten que o editor realice automáticamente as tarefas de corte. Por favor, use o teclado para iso (Ctrl+X).',
		copyError	: 'Os axustes de seguridade do seu navegador non permiten que o editor realice automáticamente as tarefas de copia. Por favor, use o teclado para iso (Ctrl+C).',
		pasteMsg	: 'Por favor, pegue dentro do seguinte cadro usando o teclado (<STRONG>Ctrl+V</STRONG>) e pulse <STRONG>OK</STRONG>.',
		securityMsg	: 'Because of your browser security settings, the editor is not able to access your clipboard data directly. You are required to paste it again in this window.' // MISSING
	},

	pastefromword :
	{
		toolbar : 'Pegar dende Word',
		title : 'Pegar dende Word',
		advice : 'Por favor, pegue dentro do seguinte cadro usando o teclado (<STRONG>Ctrl+V</STRONG>) e pulse <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Ignorar as definicións de Tipografía',
		removeStyle : 'Eliminar as definicións de Estilos'
	},

	pasteText :
	{
		button : 'Pegar como texto plano',
		title : 'Pegar como texto plano'
	},

	templates :
	{
		button : 'Plantillas',
		title : 'Plantillas de Contido',
		insertOption: 'Replace actual contents', // MISSING
		selectPromptMsg: 'Por favor, seleccione a plantilla a abrir no editor<br>(o contido actual perderase):',
		emptyListMsg : '(Non hai plantillas definidas)'
	},

	showBlocks : 'Show Blocks', // MISSING

	stylesCombo :
	{
		label : 'Estilo',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'Formato',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'Formato',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'Normal',
		tag_pre : 'Formateado',
		tag_address : 'Enderezo',
		tag_h1 : 'Enacabezado 1',
		tag_h2 : 'Encabezado 2',
		tag_h3 : 'Encabezado 3',
		tag_h4 : 'Encabezado 4',
		tag_h5 : 'Encabezado 5',
		tag_h6 : 'Encabezado 6',
		tag_div : 'Paragraph (DIV)'
	},

	font :
	{
		label : 'Tipo',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'Tipo',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'Tamaño',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Tamaño',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Cor do Texto',
		bgColorTitle : 'Cor do Fondo',
		auto : 'Automático',
		more : 'Máis Cores...'
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
