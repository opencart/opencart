/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Brazilian Portuguese language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['pt-br'] =
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
	editorTitle		: 'Editor de texto formatado, %1',

	// Toolbar buttons without dialogs.
	source			: 'Código-Fonte',
	newPage			: 'Novo',
	save			: 'Salvar',
	preview			: 'Visualizar',
	cut				: 'Recortar',
	copy			: 'Copiar',
	paste			: 'Colar',
	print			: 'Imprimir',
	underline		: 'Sublinhado',
	bold			: 'Negrito',
	italic			: 'Itálico',
	selectAll		: 'Selecionar Tudo',
	removeFormat	: 'Remover Formatação',
	strike			: 'Tachado',
	subscript		: 'Subscrito',
	superscript		: 'Sobrescrito',
	horizontalrule	: 'Inserir Linha Horizontal',
	pagebreak		: 'Inserir Quebra de Página',
	unlink			: 'Remover Hiperlink',
	undo			: 'Desfazer',
	redo			: 'Refazer',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Localizar no Servidor',
		url				: 'URL',
		protocol		: 'Protocolo',
		upload			: 'Enviar ao Servidor',
		uploadSubmit	: 'Enviar para o Servidor',
		image			: 'Figura',
		flash			: 'Flash',
		form			: 'Formulário',
		checkbox		: 'Caixa de Seleção',
		radio		: 'Botão de Opção',
		textField		: 'Caixa de Texto',
		textarea		: 'Área de Texto',
		hiddenField		: 'Campo Oculto',
		button			: 'Botão',
		select	: 'Caixa de Listagem',
		imageButton		: 'Botão de Imagem',
		notSet			: '<não ajustado>',
		id				: 'Id',
		name			: 'Nome',
		langDir			: 'Direção do idioma',
		langDirLtr		: 'Esquerda para Direita (LTR)',
		langDirRtl		: 'Direita para Esquerda (RTL)',
		langCode		: 'Idioma',
		longDescr		: 'Descrição da URL',
		cssClass		: 'Classe de Folhas de Estilo',
		advisoryTitle	: 'Título',
		cssStyle		: 'Estilos',
		ok				: 'OK',
		cancel			: 'Cancelar',
		generalTab		: 'Geral',
		advancedTab		: 'Avançado',
		validateNumberFailed	: 'Este valor não é um número.',
		confirmNewPage	: 'Todas as mudanças não salvas serão perdidas. Tem certeza de que quer carregar outra página?',
		confirmCancel	: 'Algumas opções foram alteradas. Tem certeza de que quer fechar a caixa de diálogo?',

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, indisponível</span>'
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Inserir Caractere Especial',
		title		: 'Selecione um Caractere Especial'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Inserir/Editar Hiperlink',
		menu		: 'Editar Hiperlink',
		title		: 'Hiperlink',
		info		: 'Informações',
		target		: 'Destino',
		upload		: 'Enviar ao Servidor',
		advanced	: 'Avançado',
		type		: 'Tipo de hiperlink',
		toAnchor	: 'Âncora nesta página',
		toEmail		: 'E-Mail',
		target		: 'Destino',
		targetNotSet	: '<não ajustado>',
		targetFrame	: '<frame>',
		targetPopup	: '<janela popup>',
		targetNew	: 'Nova Janela (_blank)',
		targetTop	: 'Janela Superior (_top)',
		targetSelf	: 'Mesma Janela (_self)',
		targetParent	: 'Janela Pai (_parent)',
		targetFrameName	: 'Nome do Frame de Destino',
		targetPopupName	: 'Nome da Janela Pop-up',
		popupFeatures	: 'Atributos da Janela Pop-up',
		popupResizable	: 'Redimensionável',
		popupStatusBar	: 'Barra de Status',
		popupLocationBar	: 'Barra de Endereços',
		popupToolbar	: 'Barra de Ferramentas',
		popupMenuBar	: 'Barra de Menus',
		popupFullScreen	: 'Modo Tela Cheia (IE)',
		popupScrollBars	: 'Barras de Rolagem',
		popupDependent	: 'Dependente (Netscape)',
		popupWidth		: 'Largura',
		popupLeft		: 'Esquerda',
		popupHeight		: 'Altura',
		popupTop		: 'Superior',
		id				: 'Id',
		langDir			: 'Direção do idioma',
		langDirNotSet	: '<não ajustado>',
		langDirLTR		: 'Esquerda para Direita (LTR)',
		langDirRTL		: 'Direita para Esquerda (RTL)',
		acccessKey		: 'Chave de Acesso',
		name			: 'Nome',
		langCode		: 'Direção do idioma',
		tabIndex		: 'Índice de Tabulação',
		advisoryTitle	: 'Título',
		advisoryContentType	: 'Tipo de Conteúdo',
		cssClasses		: 'Classe de Folhas de Estilo',
		charset			: 'Conjunto de Caracteres do Hiperlink',
		styles			: 'Estilos',
		selectAnchor	: 'Selecione uma âncora',
		anchorName		: 'Pelo Nome da âncora',
		anchorId		: 'Pelo Id do Elemento',
		emailAddress	: 'Endereço E-Mail',
		emailSubject	: 'Assunto da Mensagem',
		emailBody		: 'Corpo da Mensagem',
		noAnchors		: '(Não há âncoras disponíveis neste documento)',
		noUrl			: 'Por favor, digite o endereço do Hiperlink',
		noEmail			: 'Por favor, digite o endereço de e-mail'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Inserir/Editar Âncora',
		menu		: 'Formatar Âncora',
		title		: 'Formatar Âncora',
		name		: 'Nome da Âncora',
		errorName	: 'Por favor, digite o nome da âncora'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Localizar e Substituir',
		find				: 'Localizar',
		replace				: 'Substituir',
		findWhat			: 'Procurar por:',
		replaceWith			: 'Substituir por:',
		notFoundMsg			: 'O texto especificado não foi encontrado.',
		matchCase			: 'Coincidir Maiúsculas/Minúsculas',
		matchWord			: 'Coincidir a palavra inteira',
		matchCyclic			: 'Coincidir cíclico',
		replaceAll			: 'Substituir Tudo',
		replaceSuccessMsg	: '%1 ocorrência(s) substituída(s).'
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Tabela',
		title		: 'Formatar Tabela',
		menu		: 'Formatar Tabela',
		deleteTable	: 'Apagar Tabela',
		rows		: 'Linhas',
		columns		: 'Colunas',
		border		: 'Borda',
		align		: 'Alinhamento',
		alignNotSet	: '<Não ajustado>',
		alignLeft	: 'Esquerda',
		alignCenter	: 'Centralizado',
		alignRight	: 'Direita',
		width		: 'Largura',
		widthPx		: 'pixels',
		widthPc		: '%',
		height		: 'Altura',
		cellSpace	: 'Espaçamento',
		cellPad		: 'Enchimento',
		caption		: 'Legenda',
		summary		: 'Resumo',
		headers		: 'Cabeçalho',
		headersNone		: 'Nenhum',
		headersColumn	: 'Primeira coluna',
		headersRow		: 'Primeira linha',
		headersBoth		: 'Ambos',
		invalidRows		: '"Número de linhas" tem que ser um número maior que 0.',
		invalidCols		: '"Número de colunas" tem que ser um número maior que 0.',
		invalidBorder	: '"Tamanho da borda" tem que ser um número.',
		invalidWidth	: '"Largura da tabela" tem que ser um número.',
		invalidHeight	: '"Altura da tabela" tem que ser um número.',
		invalidCellSpacing	: '"Espaçamento das células" tem que ser um número.',
		invalidCellPadding	: '"Margem interna das células" tem que ser um número.',

		cell :
		{
			menu			: 'Célula',
			insertBefore	: 'Inserir célula à esquerda',
			insertAfter		: 'Inserir célula à direita',
			deleteCell		: 'Remover Células',
			merge			: 'Mesclar Células',
			mergeRight		: 'Mesclar com célula à direita',
			mergeDown		: 'Mesclar com célula abaixo',
			splitHorizontal	: 'Dividir célula horizontalmente',
			splitVertical	: 'Dividir célula verticalmente',
			title			: 'Propriedades da célula',
			cellType		: 'Tipo de célula',
			rowSpan			: 'Linhas cobertas',
			colSpan			: 'Colunas cobertas',
			wordWrap		: 'Quebra de palavra',
			hAlign			: 'Alinhamento horizontal',
			vAlign			: 'Alinhamento vertical',
			alignTop		: 'Alinhar no topo',
			alignMiddle		: 'Centralizado verticalmente',
			alignBottom		: 'Alinhar na base',
			alignBaseline	: 'Patamar de alinhamento',
			bgColor			: 'Cor de fundo',
			borderColor		: 'Cor das bordas',
			data			: 'Dados',
			header			: 'Cabeçalho',
			yes				: 'Sim',
			no				: 'Não',
			invalidWidth	: 'A largura da célula tem que ser um número.',
			invalidHeight	: 'A altura da célula tem que ser um número.',
			invalidRowSpan	: '"Linhas cobertas" tem que ser um número inteiro.',
			invalidColSpan	: '"Colunas cobertas" tem que ser um número inteiro.',
			chooseColor : 'Choose' // MISSING
		},

		row :
		{
			menu			: 'Linha',
			insertBefore	: 'Inserir linha acima',
			insertAfter		: 'Inserir linha abaixo',
			deleteRow		: 'Remover Linhas'
		},

		column :
		{
			menu			: 'Coluna',
			insertBefore	: 'Inserir coluna à esquerda',
			insertAfter		: 'Inserir coluna à direita',
			deleteColumn	: 'Remover Colunas'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Formatar Botão',
		text		: 'Texto (Valor)',
		type		: 'Tipo',
		typeBtn		: 'Botão',
		typeSbm		: 'Enviar',
		typeRst		: 'Limpar'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Formatar Caixa de Seleção',
		radioTitle	: 'Formatar Botão de Opção',
		value		: 'Valor',
		selected	: 'Selecionado'
	},

	// Form Dialog.
	form :
	{
		title		: 'Formatar Formulário',
		menu		: 'Formatar Formulário',
		action		: 'Action',
		method		: 'Método',
		encoding	: 'Codificação',
		target		: 'Destino',
		targetNotSet	: '<não ajustado>',
		targetNew	: 'Nova Janela (_blank)',
		targetTop	: 'Janela Superior (_top)',
		targetSelf	: 'Mesma Janela (_self)',
		targetParent	: 'Janela Pai (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Formatar Caixa de Listagem',
		selectInfo	: 'Info',
		opAvail		: 'Opções disponíveis',
		value		: 'Valor',
		size		: 'Tamanho',
		lines		: 'linhas',
		chkMulti	: 'Permitir múltiplas seleções',
		opText		: 'Texto',
		opValue		: 'Valor',
		btnAdd		: 'Adicionar',
		btnModify	: 'Modificar',
		btnUp		: 'Para cima',
		btnDown		: 'Para baixo',
		btnSetValue : 'Definir como selecionado',
		btnDelete	: 'Remover'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Formatar Área de Texto',
		cols		: 'Colunas',
		rows		: 'Linhas'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Formatar Caixa de Texto',
		name		: 'Nome',
		value		: 'Valor',
		charWidth	: 'Comprimento (em caracteres)',
		maxChars	: 'Número Máximo de Caracteres',
		type		: 'Tipo',
		typeText	: 'Texto',
		typePass	: 'Senha'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Formatar Campo Oculto',
		name	: 'Nome',
		value	: 'Valor'
	},

	// Image Dialog.
	image :
	{
		title		: 'Formatar Figura',
		titleButton	: 'Formatar Botão de Imagem',
		menu		: 'Formatar Figura',
		infoTab	: 'Informações da Figura',
		btnUpload	: 'Enviar para o Servidor',
		url		: 'URL',
		upload	: 'Submeter',
		alt		: 'Texto Alternativo',
		width		: 'Largura',
		height	: 'Altura',
		lockRatio	: 'Manter proporções',
		resetSize	: 'Redefinir para o Tamanho Original',
		border	: 'Borda',
		hSpace	: 'Horizontal',
		vSpace	: 'Vertical',
		align		: 'Alinhamento',
		alignLeft	: 'Esquerda',
		alignAbsBottom: 'Inferior Absoluto',
		alignAbsMiddle: 'Centralizado Absoluto',
		alignBaseline	: 'Baseline',
		alignBottom	: 'Inferior',
		alignMiddle	: 'Centralizado',
		alignRight	: 'Direita',
		alignTextTop	: 'Superior Absoluto',
		alignTop	: 'Superior',
		preview	: 'Visualização',
		alertUrl	: 'Por favor, digite o URL da figura.',
		linkTab	: 'Hiperlink',
		button2Img	: 'Você deseja transformar o botão de imagem selecionado em uma imagem comum?',
		img2Button	: 'Você deseja transformar a imagem selecionada em um botão de imagem?',
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Propriedades do Flash',
		propertiesTab	: 'Propriedades',
		title		: 'Propriedades do Flash',
		chkPlay		: 'Tocar Automaticamente',
		chkLoop		: 'Loop',
		chkMenu		: 'Habilita Menu Flash',
		chkFull		: 'Permitir tela cheia',
 		scale		: 'Escala',
		scaleAll		: 'Mostrar tudo',
		scaleNoBorder	: 'Sem Borda',
		scaleFit		: 'Escala Exata',
		access			: 'Acesso ao script',
		accessAlways	: 'Sempre',
		accessSameDomain	: 'Mesmo domínio',
		accessNever	: 'Nunca',
		align		: 'Alinhamento',
		alignLeft	: 'Esquerda',
		alignAbsBottom: 'Inferior Absoluto',
		alignAbsMiddle: 'Centralizado Absoluto',
		alignBaseline	: 'Baseline',
		alignBottom	: 'Inferior',
		alignMiddle	: 'Centralizado',
		alignRight	: 'Direita',
		alignTextTop	: 'Superior Absoluto',
		alignTop	: 'Superior',
		quality		: 'Qualidade',
		qualityBest		 : 'Melhor',
		qualityHigh		 : 'Alta',
		qualityAutoHigh	 : 'Alta automático',
		qualityMedium	 : 'Média',
		qualityAutoLow	 : 'Média automático',
		qualityLow		 : 'Baixa',
		windowModeWindow	 : 'Janela',
		windowModeOpaque	 : 'Opaca',
		windowModeTransparent	 : 'Transparente',
		windowMode	: 'Modo da janela',
		flashvars	: 'Variáveis do Flash',
		bgcolor	: 'Cor do Plano de Fundo',
		width	: 'Largura',
		height	: 'Altura',
		hSpace	: 'Horizontal',
		vSpace	: 'Vertical',
		validateSrc : 'Por favor, digite o endereço do Hiperlink',
		validateWidth : '"Largura" tem que ser um número.',
		validateHeight : '"Altura" tem que ser um número',
		validateHSpace : '"HSpace" tem que ser um número',
		validateVSpace : '"VSpace" tem que ser um número.'
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Verificar Ortografia',
		title			: 'Corretor gramatical',
		notAvailable	: 'Desculpe, o serviço não está disponível no momento.',
		errorLoading	: 'Erro carregando servidor de aplicação: %s.',
		notInDic		: 'Não encontrada',
		changeTo		: 'Alterar para',
		btnIgnore		: 'Ignorar uma vez',
		btnIgnoreAll	: 'Ignorar Todas',
		btnReplace		: 'Alterar',
		btnReplaceAll	: 'Alterar Todas',
		btnUndo			: 'Desfazer',
		noSuggestions	: '-sem sugestões de ortografia-',
		progress		: 'Verificação ortográfica em andamento...',
		noMispell		: 'Verificação encerrada: Não foram encontrados erros de ortografia',
		noChanges		: 'Verificação ortográfica encerrada: Não houve alterações',
		oneChange		: 'Verificação ortográfica encerrada: Uma palavra foi alterada',
		manyChanges		: 'Verificação ortográfica encerrada: %1 foram alteradas',
		ieSpellDownload	: 'A verificação ortográfica não foi instalada. Você gostaria de realizar o download agora?'
	},

	smiley :
	{
		toolbar	: 'Emoticon',
		title	: 'Inserir Emoticon'
	},

	elementsPath :
	{
		eleTitle : 'Elemento %1'
	},

	numberedlist : 'Numeração',
	bulletedlist : 'Marcadores',
	indent : 'Aumentar Recuo',
	outdent : 'Diminuir Recuo',

	justify :
	{
		left : 'Alinhar Esquerda',
		center : 'Centralizar',
		right : 'Alinhar Direita',
		block : 'Justificado'
	},

	blockquote : 'Recuo',

	clipboard :
	{
		title		: 'Colar',
		cutError	: 'As configurações de segurança do seu navegador não permitem que o editor execute operações de recortar automaticamente. Por favor, utilize o teclado para recortar (Ctrl+X).',
		copyError	: 'As configurações de segurança do seu navegador não permitem que o editor execute operações de copiar automaticamente. Por favor, utilize o teclado para copiar (Ctrl+C).',
		pasteMsg	: 'Transfira o link usado no box usando o teclado com (<STRONG>Ctrl+V</STRONG>) e <STRONG>OK</STRONG>.',
		securityMsg	: 'As configurações de segurança do seu navegador não permitem que o editor acesse os dados da área de transferência diretamente. Por favor cole o conteúdo novamente nesta janela.'
	},

	pastefromword :
	{
		toolbar : 'Colar do Word',
		title : 'Colar do Word',
		advice : 'Transfira o link usado no box usando o teclado com (<STRONG>Ctrl+V</STRONG>) e <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Ignorar definições de fonte',
		removeStyle : 'Remove definições de estilo'
	},

	pasteText :
	{
		button : 'Colar como Texto sem Formatação',
		title : 'Colar como Texto sem Formatação'
	},

	templates :
	{
		button : 'Modelos de layout',
		title : 'Modelo de layout do conteúdo',
		insertOption: 'Substituir o conteúdo atual',
		selectPromptMsg: 'Selecione um modelo de layout para ser aberto no editor<br>(o conteúdo atual será perdido):',
		emptyListMsg : '(Não foram definidos modelos de layout)'
	},

	showBlocks : 'Mostrar blocos',

	stylesCombo :
	{
		label : 'Estilo',
		voiceLabel : 'Estilo',
		panelVoiceLabel : 'Selecione um estilo',
		panelTitle1 : 'Estilos de bloco',
		panelTitle2 : 'Estilos em texto corrido',
		panelTitle3 : 'Estilos de objeto'
	},

	format :
	{
		label : 'Formatação',
		voiceLabel : 'Formatação',
		panelTitle : 'Formatação',
		panelVoiceLabel : 'Selecione uma formatação de parágrafo',

		tag_p : 'Normal',
		tag_pre : 'Formatado',
		tag_address : 'Endereço',
		tag_h1 : 'Título 1',
		tag_h2 : 'Título 2',
		tag_h3 : 'Título 3',
		tag_h4 : 'Título 4',
		tag_h5 : 'Título 5',
		tag_h6 : 'Título 6',
		tag_div : 'Normal (DIV)'
	},

	font :
	{
		label : 'Fonte',
		voiceLabel : 'Fonte',
		panelTitle : 'Fonte',
		panelVoiceLabel : 'Selecione uma fonte'
	},

	fontSize :
	{
		label : 'Tamanho',
		voiceLabel : 'Tamanho da fonte',
		panelTitle : 'Tamanho',
		panelVoiceLabel : 'Selecione um tamanho de fonte'
	},

	colorButton :
	{
		textColorTitle : 'Cor do Texto',
		bgColorTitle : 'Cor do Plano de Fundo',
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
		title : 'Correção gramatical durante a digitação',
		enable : 'Habilitar SCAYT',
		disable : 'Desabilitar SCAYT',
		about : 'Sobre o SCAYT',
		toggle : 'Ativar/desativar SCAYT',
		options : 'Opções',
		langs : 'Línguas',
		moreSuggestions : 'Mais sugestões',
		ignore : 'Ignorar',
		ignoreAll : 'Ignorar todas',
		addWord : 'Adicionar palavra',
		emptyDic : 'O nome do dicionário não deveria estar vazio.',
		optionsTab : 'Opções',
		languagesTab : 'Línguas',
		dictionariesTab : 'Dicionários',
		aboutTab : 'Sobre'
	},

	about :
	{
		title : 'Sobre o CKEditor',
		dlgTitle : 'About CKEditor', // MISSING
		moreInfo : 'Para informações sobre a licença, por favor, visite o nosso site na Internet:',
		copy : 'Direito de reprodução &copy; $1. Todos os direitos reservados.'
	},

	maximize : 'Maximizar',
	minimize : 'Minimize', // MISSING

	fakeobjects :
	{
		anchor : 'Âncora',
		flash : 'Animação em Flash',
		div : 'Quebra de página',
		unknown : 'Objeto desconhecido'
	},

	resize : 'Arraste para redimensionar',

	colordialog :
	{
		title : 'Select color', // MISSING
		highlight : 'Highlight', // MISSING
		selected : 'Selected', // MISSING
		clear : 'Clear' // MISSING
	}
};
