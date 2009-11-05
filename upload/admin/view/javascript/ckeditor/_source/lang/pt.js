/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Portuguese language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['pt'] =
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
	source			: 'Fonte',
	newPage			: 'Nova Página',
	save			: 'Guardar',
	preview			: 'Pré-visualizar',
	cut				: 'Cortar',
	copy			: 'Copiar',
	paste			: 'Colar',
	print			: 'Imprimir',
	underline		: 'Sublinhado',
	bold			: 'Negrito',
	italic			: 'Itálico',
	selectAll		: 'Seleccionar Tudo',
	removeFormat	: 'Eliminar Formato',
	strike			: 'Rasurado',
	subscript		: 'Superior à Linha',
	superscript		: 'Inferior à Linha',
	horizontalrule	: 'Inserir Linha Horizontal',
	pagebreak		: 'Inserir Quebra de Página',
	unlink			: 'Eliminar Hiperligação',
	undo			: 'Anular',
	redo			: 'Repetir',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Navegar no Servidor',
		url				: 'URL',
		protocol		: 'Protocolo',
		upload			: 'Carregar',
		uploadSubmit	: 'Enviar para o Servidor',
		image			: 'Imagem',
		flash			: 'Flash',
		form			: 'Formulário',
		checkbox		: 'Caixa de Verificação',
		radio		: 'Botão de Opção',
		textField		: 'Campo de Texto',
		textarea		: 'Área de Texto',
		hiddenField		: 'Campo Escondido',
		button			: 'Botão',
		select	: 'Caixa de Combinação',
		imageButton		: 'Botão de Imagem',
		notSet			: '<Não definido>',
		id				: 'Id',
		name			: 'Nome',
		langDir			: 'Orientação de idioma',
		langDirLtr		: 'Esquerda à Direita (LTR)',
		langDirRtl		: 'Direita a Esquerda (RTL)',
		langCode		: 'Código de Idioma',
		longDescr		: 'Descrição Completa do URL',
		cssClass		: 'Classes de Estilo de Folhas Classes',
		advisoryTitle	: 'Título',
		cssStyle		: 'Estilo',
		ok				: 'OK',
		cancel			: 'Cancelar',
		generalTab		: 'General', // MISSING
		advancedTab		: 'Avançado',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Inserir Caracter Especial',
		title		: 'Seleccione um caracter especial'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Inserir/Editar Hiperligação',
		menu		: 'Editar Hiperligação',
		title		: 'Hiperligação',
		info		: 'Informação de Hiperligação',
		target		: 'Destino',
		upload		: 'Carregar',
		advanced	: 'Avançado',
		type		: 'Tipo de Hiperligação',
		toAnchor	: 'Referência a esta página',
		toEmail		: 'E-Mail',
		target		: 'Destino',
		targetNotSet	: '<Não definido>',
		targetFrame	: '<Frame>',
		targetPopup	: '<Janela de popup>',
		targetNew	: 'Nova Janela(_blank)',
		targetTop	: 'Janela primaria (_top)',
		targetSelf	: 'Mesma janela (_self)',
		targetParent	: 'Janela Pai (_parent)',
		targetFrameName	: 'Nome do Frame Destino',
		targetPopupName	: 'Nome da Janela de Popup',
		popupFeatures	: 'Características de Janela de Popup',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Barra de Estado',
		popupLocationBar	: 'Barra de localização',
		popupToolbar	: 'Barra de Ferramentas',
		popupMenuBar	: 'Barra de Menu',
		popupFullScreen	: 'Janela Completa (IE)',
		popupScrollBars	: 'Barras de deslocamento',
		popupDependent	: 'Dependente (Netscape)',
		popupWidth		: 'Largura',
		popupLeft		: 'Posição Esquerda',
		popupHeight		: 'Altura',
		popupTop		: 'Posição Direita',
		id				: 'Id', // MISSING
		langDir			: 'Orientação de idioma',
		langDirNotSet	: '<Não definido>',
		langDirLTR		: 'Esquerda à Direita (LTR)',
		langDirRTL		: 'Direita a Esquerda (RTL)',
		acccessKey		: 'Chave de Acesso',
		name			: 'Nome',
		langCode		: 'Orientação de idioma',
		tabIndex		: 'Índice de Tubulação',
		advisoryTitle	: 'Título',
		advisoryContentType	: 'Tipo de Conteúdo',
		cssClasses		: 'Classes de Estilo de Folhas Classes',
		charset			: 'Fonte de caracteres vinculado',
		styles			: 'Estilo',
		selectAnchor	: 'Seleccionar una referência',
		anchorName		: 'Por Nome de Referência',
		anchorId		: 'Por ID de elemento',
		emailAddress	: 'Endereço de E-Mail',
		emailSubject	: 'Título de Mensagem',
		emailBody		: 'Corpo da Mensagem',
		noAnchors		: '(Não há referências disponíveis no documento)',
		noUrl			: 'Por favor introduza a hiperligação URL',
		noEmail			: 'Por favor introduza o endereço de e-mail'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: ' Inserir/Editar Âncora',
		menu		: 'Propriedades da Âncora',
		title		: 'Propriedades da Âncora',
		name		: 'Nome da Âncora',
		errorName	: 'Por favor, introduza o nome da âncora'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Find and Replace', // MISSING
		find				: 'Procurar',
		replace				: 'Substituir',
		findWhat			: 'Texto a Procurar:',
		replaceWith			: 'Substituir por:',
		notFoundMsg			: 'O texto especificado não foi encontrado.',
		matchCase			: 'Maiúsculas/Minúsculas',
		matchWord			: 'Coincidir com toda a palavra',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Substituir Tudo',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Tabela',
		title		: 'Propriedades da Tabela',
		menu		: 'Propriedades da Tabela',
		deleteTable	: 'Eliminar Tabela',
		rows		: 'Linhas',
		columns		: 'Colunas',
		border		: 'Tamanho do Limite',
		align		: 'Alinhamento',
		alignNotSet	: '<Não definido>',
		alignLeft	: 'Esquerda',
		alignCenter	: 'Centrado',
		alignRight	: 'Direita',
		width		: 'Largura',
		widthPx		: 'pixeis',
		widthPc		: 'percentagem',
		height		: 'Altura',
		cellSpace	: 'Esp. e/células',
		cellPad		: 'Esp. interior',
		caption		: 'Título',
		summary		: 'Sumário',
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
			menu			: 'Célula',
			insertBefore	: 'Insert Cell Before', // MISSING
			insertAfter		: 'Insert Cell After', // MISSING
			deleteCell		: 'Eliminar Célula',
			merge			: 'Unir Células',
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
			menu			: 'Linha',
			insertBefore	: 'Insert Row Before', // MISSING
			insertAfter		: 'Insert Row After', // MISSING
			deleteRow		: 'Eliminar Linhas'
		},

		column :
		{
			menu			: 'Coluna',
			insertBefore	: 'Insert Column Before', // MISSING
			insertAfter		: 'Insert Column After', // MISSING
			deleteColumn	: 'Eliminar Coluna'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Propriedades do Botão',
		text		: 'Texto (Valor)',
		type		: 'Tipo',
		typeBtn		: 'Button', // MISSING
		typeSbm		: 'Submit', // MISSING
		typeRst		: 'Reset' // MISSING
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Propriedades da Caixa de Verificação',
		radioTitle	: 'Propriedades do Botão de Opção',
		value		: 'Valor',
		selected	: 'Seleccionado'
	},

	// Form Dialog.
	form :
	{
		title		: 'Propriedades do Formulário',
		menu		: 'Propriedades do Formulário',
		action		: 'Acção',
		method		: 'Método',
		encoding	: 'Encoding', // MISSING
		target		: 'Destino',
		targetNotSet	: '<Não definido>',
		targetNew	: 'Nova Janela(_blank)',
		targetTop	: 'Janela primaria (_top)',
		targetSelf	: 'Mesma janela (_self)',
		targetParent	: 'Janela Pai (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Propriedades da Caixa de Combinação',
		selectInfo	: 'Informação',
		opAvail		: 'Opções Possíveis',
		value		: 'Valor',
		size		: 'Tamanho',
		lines		: 'linhas',
		chkMulti	: 'Permitir selecções múltiplas',
		opText		: 'Texto',
		opValue		: 'Valor',
		btnAdd		: 'Adicionar',
		btnModify	: 'Modificar',
		btnUp		: 'Para cima',
		btnDown		: 'Para baixo',
		btnSetValue : 'Definir um valor por defeito',
		btnDelete	: 'Apagar'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Propriedades da Área de Texto',
		cols		: 'Colunas',
		rows		: 'Linhas'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Propriedades do Campo de Texto',
		name		: 'Nome',
		value		: 'Valor',
		charWidth	: 'Tamanho do caracter',
		maxChars	: 'Nr. Máximo de Caracteres',
		type		: 'Tipo',
		typeText	: 'Texto',
		typePass	: 'Palavra-chave'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Propriedades do Campo Escondido',
		name	: 'Nome',
		value	: 'Valor'
	},

	// Image Dialog.
	image :
	{
		title		: 'Propriedades da Imagem',
		titleButton	: 'Propriedades do Botão de imagens',
		menu		: 'Propriedades da Imagem',
		infoTab	: 'Informação da Imagem',
		btnUpload	: 'Enviar para o Servidor',
		url		: 'URL',
		upload	: 'Carregar',
		alt		: 'Texto Alternativo',
		width		: 'Largura',
		height	: 'Altura',
		lockRatio	: 'Proporcional',
		resetSize	: 'Tamanho Original',
		border	: 'Limite',
		hSpace	: 'Esp.Horiz',
		vSpace	: 'Esp.Vert',
		align		: 'Alinhamento',
		alignLeft	: 'Esquerda',
		alignAbsBottom: 'Abs inferior',
		alignAbsMiddle: 'Abs centro',
		alignBaseline	: 'Linha de base',
		alignBottom	: 'Fundo',
		alignMiddle	: 'Centro',
		alignRight	: 'Direita',
		alignTextTop	: 'Topo do texto',
		alignTop	: 'Topo',
		preview	: 'Pré-visualizar',
		alertUrl	: 'Por favor introduza o URL da imagem',
		linkTab	: 'Hiperligação',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Propriedades do Flash',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Propriedades do Flash',
		chkPlay		: 'Reproduzir automaticamente',
		chkLoop		: 'Loop',
		chkMenu		: 'Permitir Menu do Flash',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Escala',
		scaleAll		: 'Mostrar tudo',
		scaleNoBorder	: 'Sem Limites',
		scaleFit		: 'Tamanho Exacto',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Alinhamento',
		alignLeft	: 'Esquerda',
		alignAbsBottom: 'Abs inferior',
		alignAbsMiddle: 'Abs centro',
		alignBaseline	: 'Linha de base',
		alignBottom	: 'Fundo',
		alignMiddle	: 'Centro',
		alignRight	: 'Direita',
		alignTextTop	: 'Topo do texto',
		alignTop	: 'Topo',
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
		bgcolor	: 'Cor de Fundo',
		width	: 'Largura',
		height	: 'Altura',
		hSpace	: 'Esp.Horiz',
		vSpace	: 'Esp.Vert',
		validateSrc : 'Por favor introduza a hiperligação URL',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Verificação Ortográfica',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Não está num directório',
		changeTo		: 'Mudar para',
		btnIgnore		: 'Ignorar',
		btnIgnoreAll	: 'Ignorar Tudo',
		btnReplace		: 'Substituir',
		btnReplaceAll	: 'Substituir Tudo',
		btnUndo			: 'Anular',
		noSuggestions	: '- Sem sugestões -',
		progress		: 'Verificação ortográfica em progresso…',
		noMispell		: 'Verificação ortográfica completa: não foram encontrados erros',
		noChanges		: 'Verificação ortográfica completa: não houve alteração de palavras',
		oneChange		: 'Verificação ortográfica completa: uma palavra alterada',
		manyChanges		: 'Verificação ortográfica completa: %1 palavras alteradas',
		ieSpellDownload	: ' Verificação ortográfica não instalada. Quer descarregar agora?'
	},

	smiley :
	{
		toolbar	: 'Emoticons',
		title	: 'Inserir um Emoticon'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Numeração',
	bulletedlist : 'Marcas',
	indent : 'Aumentar Avanço',
	outdent : 'Diminuir Avanço',

	justify :
	{
		left : 'Alinhar à Esquerda',
		center : 'Alinhar ao Centro',
		right : 'Alinhar à Direita',
		block : 'Justificado'
	},

	blockquote : 'Blockquote', // MISSING

	clipboard :
	{
		title		: 'Colar',
		cutError	: 'A configuração de segurança do navegador não permite a execução automática de operações de cortar. Por favor use o teclado (Ctrl+X).',
		copyError	: 'A configuração de segurança do navegador não permite a execução automática de operações de copiar. Por favor use o teclado (Ctrl+C).',
		pasteMsg	: 'Por favor, cole dentro da seguinte caixa usando o teclado (<STRONG>Ctrl+V</STRONG>) e prima <STRONG>OK</STRONG>.',
		securityMsg	: 'Because of your browser security settings, the editor is not able to access your clipboard data directly. You are required to paste it again in this window.' // MISSING
	},

	pastefromword :
	{
		toolbar : 'Colar do Word',
		title : 'Colar do Word',
		advice : 'Por favor, cole dentro da seguinte caixa usando o teclado (<STRONG>Ctrl+V</STRONG>) e prima <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Ignorar da definições do Tipo de Letra ',
		removeStyle : 'Remover as definições de Estilos'
	},

	pasteText :
	{
		button : 'Colar como Texto Simples',
		title : 'Colar como Texto Simples'
	},

	templates :
	{
		button : 'Modelos',
		title : 'Modelo de Conteúdo',
		insertOption: 'Replace actual contents', // MISSING
		selectPromptMsg: 'Por favor, seleccione o modelo a abrir no editor<br>(o conteúdo actual será perdido):',
		emptyListMsg : '(Sem modelos definidos)'
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
		tag_pre : 'Formatado',
		tag_address : 'Endereço',
		tag_h1 : 'Título 1',
		tag_h2 : 'Título 2',
		tag_h3 : 'Título 3',
		tag_h4 : 'Título 4',
		tag_h5 : 'Título 5',
		tag_h6 : 'Título 6',
		tag_div : 'Normal (DIV)' // MISSING
	},

	font :
	{
		label : 'Tipo de Letra',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'Tipo de Letra',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'Tamanho',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Tamanho',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Cor do Texto',
		bgColorTitle : 'Cor de Fundo',
		auto : 'Automático',
		more : 'Mais Cores...'
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
