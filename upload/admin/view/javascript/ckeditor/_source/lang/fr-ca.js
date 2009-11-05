/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Canadian French language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['fr-ca'] =
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
	source			: 'Source',
	newPage			: 'Nouvelle page',
	save			: 'Sauvegarder',
	preview			: 'Previsualiser',
	cut				: 'Couper',
	copy			: 'Copier',
	paste			: 'Coller',
	print			: 'Imprimer',
	underline		: 'Souligné',
	bold			: 'Gras',
	italic			: 'Italique',
	selectAll		: 'Tout sélectionner',
	removeFormat	: 'Supprimer le formatage',
	strike			: 'Barrer',
	subscript		: 'Indice',
	superscript		: 'Exposant',
	horizontalrule	: 'Insérer un séparateur',
	pagebreak		: 'Insérer un saut de page',
	unlink			: 'Supprimer le lien',
	undo			: 'Annuler',
	redo			: 'Refaire',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Parcourir le serveur',
		url				: 'URL',
		protocol		: 'Protocole',
		upload			: 'Télécharger',
		uploadSubmit	: 'Envoyer sur le serveur',
		image			: 'Image',
		flash			: 'Animation Flash',
		form			: 'Formulaire',
		checkbox		: 'Case à cocher',
		radio		: 'Bouton radio',
		textField		: 'Champ texte',
		textarea		: 'Zone de texte',
		hiddenField		: 'Champ caché',
		button			: 'Bouton',
		select	: 'Champ de sélection',
		imageButton		: 'Bouton image',
		notSet			: '<Par défaut>',
		id				: 'Id',
		name			: 'Nom',
		langDir			: 'Sens d\'écriture',
		langDirLtr		: 'De gauche à droite (LTR)',
		langDirRtl		: 'De droite à gauche (RTL)',
		langCode		: 'Code langue',
		longDescr		: 'URL de description longue',
		cssClass		: 'Classes de feuilles de style',
		advisoryTitle	: 'Titre',
		cssStyle		: 'Style',
		ok				: 'OK',
		cancel			: 'Annuler',
		generalTab		: 'Général',
		advancedTab		: 'Avancée',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Insérer un caractère spécial',
		title		: 'Insérer un caractère spécial'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Insérer/modifier le lien',
		menu		: 'Modifier le lien',
		title		: 'Propriétés du lien',
		info		: 'Informations sur le lien',
		target		: 'Destination',
		upload		: 'Télécharger',
		advanced	: 'Avancée',
		type		: 'Type de lien',
		toAnchor	: 'Ancre dans cette page',
		toEmail		: 'E-Mail',
		target		: 'Destination',
		targetNotSet	: '<Par défaut>',
		targetFrame	: '<Cadre>',
		targetPopup	: '<fenêtre popup>',
		targetNew	: 'Nouvelle fenêtre (_blank)',
		targetTop	: 'Fenêtre supérieure (_top)',
		targetSelf	: 'Même fenêtre (_self)',
		targetParent	: 'Fenêtre mère (_parent)',
		targetFrameName	: 'Nom du cadre de destination',
		targetPopupName	: 'Nom de la fenêtre popup',
		popupFeatures	: 'Caractéristiques de la fenêtre popup',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Barre d\'état',
		popupLocationBar	: 'Barre d\'adresses',
		popupToolbar	: 'Barre d\'outils',
		popupMenuBar	: 'Barre de menu',
		popupFullScreen	: 'Plein écran (IE)',
		popupScrollBars	: 'Barres de défilement',
		popupDependent	: 'Dépendante (Netscape)',
		popupWidth		: 'Largeur',
		popupLeft		: 'Position à partir de la gauche',
		popupHeight		: 'Hauteur',
		popupTop		: 'Position à partir du haut',
		id				: 'Id', // MISSING
		langDir			: 'Sens d\'écriture',
		langDirNotSet	: '<Par défaut>',
		langDirLTR		: 'De gauche à droite (LTR)',
		langDirRTL		: 'De droite à gauche (RTL)',
		acccessKey		: 'Équivalent clavier',
		name			: 'Nom',
		langCode		: 'Sens d\'écriture',
		tabIndex		: 'Ordre de tabulation',
		advisoryTitle	: 'Titre',
		advisoryContentType	: 'Type de contenu',
		cssClasses		: 'Classes de feuilles de style',
		charset			: 'Encodage de caractère',
		styles			: 'Style',
		selectAnchor	: 'Sélectionner une ancre',
		anchorName		: 'Par nom',
		anchorId		: 'Par id',
		emailAddress	: 'Adresse E-Mail',
		emailSubject	: 'Sujet du message',
		emailBody		: 'Corps du message',
		noAnchors		: '(Pas d\'ancre disponible dans le document)',
		noUrl			: 'Veuillez saisir l\'URL',
		noEmail			: 'Veuillez saisir l\'adresse e-mail'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Insérer/modifier l\'ancre',
		menu		: 'Propriétés de l\'ancre',
		title		: 'Propriétés de l\'ancre',
		name		: 'Nom de l\'ancre',
		errorName	: 'Veuillez saisir le nom de l\'ancre'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Chercher et Remplacer',
		find				: 'Chercher',
		replace				: 'Remplacer',
		findWhat			: 'Rechercher:',
		replaceWith			: 'Remplacer par:',
		notFoundMsg			: 'Le texte indiqué est introuvable.',
		matchCase			: 'Respecter la casse',
		matchWord			: 'Mot entier',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Tout remplacer',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Tableau',
		title		: 'Propriétés du tableau',
		menu		: 'Propriétés du tableau',
		deleteTable	: 'Supprimer le tableau',
		rows		: 'Lignes',
		columns		: 'Colonnes',
		border		: 'Taille de la bordure',
		align		: 'Alignement',
		alignNotSet	: '<Par défaut>',
		alignLeft	: 'Gauche',
		alignCenter	: 'Centré',
		alignRight	: 'Droite',
		width		: 'Largeur',
		widthPx		: 'pixels',
		widthPc		: 'pourcentage',
		height		: 'Hauteur',
		cellSpace	: 'Espacement',
		cellPad		: 'Contour',
		caption		: 'Titre',
		summary		: 'Résumé',
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
			menu			: 'Cellule',
			insertBefore	: 'Insérer une cellule avant',
			insertAfter		: 'Insérer une cellule après',
			deleteCell		: 'Supprimer des cellules',
			merge			: 'Fusionner les cellules',
			mergeRight		: 'Fusionner à droite',
			mergeDown		: 'Fusionner en bas',
			splitHorizontal	: 'Scinder la cellule horizontalement',
			splitVertical	: 'Scinder la cellule verticalement',
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
			menu			: 'Ligne',
			insertBefore	: 'Insérer une ligne avant',
			insertAfter		: 'Insérer une ligne après',
			deleteRow		: 'Supprimer des lignes'
		},

		column :
		{
			menu			: 'Colonne',
			insertBefore	: 'Insérer une colonne avant',
			insertAfter		: 'Insérer une colonne après',
			deleteColumn	: 'Supprimer des colonnes'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Propriétés du bouton',
		text		: 'Texte (Valeur)',
		type		: 'Type',
		typeBtn		: 'Bouton',
		typeSbm		: 'Soumettre',
		typeRst		: 'Réinitialiser'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Propriétés de la case à cocher',
		radioTitle	: 'Propriétés du bouton radio',
		value		: 'Valeur',
		selected	: 'Sélectionné'
	},

	// Form Dialog.
	form :
	{
		title		: 'Propriétés du formulaire',
		menu		: 'Propriétés du formulaire',
		action		: 'Action',
		method		: 'Méthode',
		encoding	: 'Encoding', // MISSING
		target		: 'Destination',
		targetNotSet	: '<Par défaut>',
		targetNew	: 'Nouvelle fenêtre (_blank)',
		targetTop	: 'Fenêtre supérieure (_top)',
		targetSelf	: 'Même fenêtre (_self)',
		targetParent	: 'Fenêtre mère (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Propriétés de la liste/du menu',
		selectInfo	: 'Info',
		opAvail		: 'Options disponibles',
		value		: 'Valeur',
		size		: 'Taille',
		lines		: 'lignes',
		chkMulti	: 'Sélection multiple',
		opText		: 'Texte',
		opValue		: 'Valeur',
		btnAdd		: 'Ajouter',
		btnModify	: 'Modifier',
		btnUp		: 'Monter',
		btnDown		: 'Descendre',
		btnSetValue : 'Valeur sélectionnée',
		btnDelete	: 'Supprimer'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Propriétés de la zone de texte',
		cols		: 'Colonnes',
		rows		: 'Lignes'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Propriétés du champ texte',
		name		: 'Nom',
		value		: 'Valeur',
		charWidth	: 'Largeur en caractères',
		maxChars	: 'Nombre maximum de caractères',
		type		: 'Type',
		typeText	: 'Texte',
		typePass	: 'Mot de passe'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Propriétés du champ caché',
		name	: 'Nom',
		value	: 'Valeur'
	},

	// Image Dialog.
	image :
	{
		title		: 'Propriétés de l\'image',
		titleButton	: 'Propriétés du bouton image',
		menu		: 'Propriétés de l\'image',
		infoTab	: 'Informations sur l\'image',
		btnUpload	: 'Envoyer sur le serveur',
		url		: 'URL',
		upload	: 'Télécharger',
		alt		: 'Texte de remplacement',
		width		: 'Largeur',
		height	: 'Hauteur',
		lockRatio	: 'Garder les proportions',
		resetSize	: 'Taille originale',
		border	: 'Bordure',
		hSpace	: 'Espacement horizontal',
		vSpace	: 'Espacement vertical',
		align		: 'Alignement',
		alignLeft	: 'Gauche',
		alignAbsBottom: 'Abs Bas',
		alignAbsMiddle: 'Abs Milieu',
		alignBaseline	: 'Bas du texte',
		alignBottom	: 'Bas',
		alignMiddle	: 'Milieu',
		alignRight	: 'Droite',
		alignTextTop	: 'Haut du texte',
		alignTop	: 'Haut',
		preview	: 'Prévisualisation',
		alertUrl	: 'Veuillez saisir l\'URL de l\'image',
		linkTab	: 'Lien',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Propriétés de l\'animation Flash',
		propertiesTab	: 'Properties', // MISSING
		title		: 'Propriétés de l\'animation Flash',
		chkPlay		: 'Lecture automatique',
		chkLoop		: 'Boucle',
		chkMenu		: 'Activer le menu Flash',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Affichage',
		scaleAll		: 'Par défaut (tout montrer)',
		scaleNoBorder	: 'Sans bordure',
		scaleFit		: 'Ajuster aux dimensions',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Alignement',
		alignLeft	: 'Gauche',
		alignAbsBottom: 'Abs Bas',
		alignAbsMiddle: 'Abs Milieu',
		alignBaseline	: 'Bas du texte',
		alignBottom	: 'Bas',
		alignMiddle	: 'Milieu',
		alignRight	: 'Droite',
		alignTextTop	: 'Haut du texte',
		alignTop	: 'Haut',
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
		bgcolor	: 'Couleur de fond',
		width	: 'Largeur',
		height	: 'Hauteur',
		hSpace	: 'Espacement horizontal',
		vSpace	: 'Espacement vertical',
		validateSrc : 'Veuillez saisir l\'URL',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Orthographe',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Pas dans le dictionnaire',
		changeTo		: 'Changer en',
		btnIgnore		: 'Ignorer',
		btnIgnoreAll	: 'Ignorer tout',
		btnReplace		: 'Remplacer',
		btnReplaceAll	: 'Remplacer tout',
		btnUndo			: 'Annuler',
		noSuggestions	: '- Pas de suggestion -',
		progress		: 'Vérification d\'orthographe en cours...',
		noMispell		: 'Vérification d\'orthographe terminée: pas d\'erreur trouvée',
		noChanges		: 'Vérification d\'orthographe terminée: Pas de modifications',
		oneChange		: 'Vérification d\'orthographe terminée: Un mot modifié',
		manyChanges		: 'Vérification d\'orthographe terminée: %1 mots modifiés',
		ieSpellDownload	: 'Le Correcteur d\'orthographe n\'est pas installé. Souhaitez-vous le télécharger maintenant?'
	},

	smiley :
	{
		toolbar	: 'Emoticon',
		title	: 'Insérer un Emoticon'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Liste numérotée',
	bulletedlist : 'Liste à puces',
	indent : 'Augmenter le retrait',
	outdent : 'Diminuer le retrait',

	justify :
	{
		left : 'Aligner à gauche',
		center : 'Centrer',
		right : 'Aligner à Droite',
		block : 'Texte justifié'
	},

	blockquote : 'Citation',

	clipboard :
	{
		title		: 'Coller',
		cutError	: 'Les paramètres de sécurité de votre navigateur empêchent l\'éditeur de couper automatiquement vos données. Veuillez utiliser les équivalents claviers (Ctrl+X).',
		copyError	: 'Les paramètres de sécurité de votre navigateur empêchent l\'éditeur de copier automatiquement vos données. Veuillez utiliser les équivalents claviers (Ctrl+C).',
		pasteMsg	: 'Veuillez coller dans la zone ci-dessous en utilisant le clavier (<STRONG>Ctrl+V</STRONG>) et appuyer sur <STRONG>OK</STRONG>.',
		securityMsg	: 'A cause des paramètres de sécurité de votre navigateur, l\'éditeur ne peut accéder au presse-papier directement. Vous devez coller à nouveau le contenu dans cette fenêtre.'
	},

	pastefromword :
	{
		toolbar : 'Coller en tant que Word (formaté)',
		title : 'Coller en tant que Word (formaté)',
		advice : 'Veuillez coller dans la zone ci-dessous en utilisant le clavier (<STRONG>Ctrl+V</STRONG>) et appuyer sur <STRONG>OK</STRONG>.',
		ignoreFontFace : 'Ignorer les polices de caractères',
		removeStyle : 'Supprimer les styles'
	},

	pasteText :
	{
		button : 'Coller comme texte',
		title : 'Coller comme texte'
	},

	templates :
	{
		button : 'Modèles',
		title : 'Modèles de contenu',
		insertOption: 'Remplacer tout le contenu actuel',
		selectPromptMsg: 'Sélectionner le modèle à ouvrir dans l\'éditeur<br>(le contenu actuel sera remplacé):',
		emptyListMsg : '(Aucun modèle disponible)'
	},

	showBlocks : 'Afficher les blocs',

	stylesCombo :
	{
		label : 'Style',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'Format',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'Format',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'Normal',
		tag_pre : 'Formaté',
		tag_address : 'Adresse',
		tag_h1 : 'En-tête 1',
		tag_h2 : 'En-tête 2',
		tag_h3 : 'En-tête 3',
		tag_h4 : 'En-tête 4',
		tag_h5 : 'En-tête 5',
		tag_h6 : 'En-tête 6',
		tag_div : 'Normal (DIV)'
	},

	font :
	{
		label : 'Police',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'Police',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'Taille',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Taille',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Couleur de caractère',
		bgColorTitle : 'Couleur de fond',
		auto : 'Automatique',
		more : 'Plus de couleurs...'
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
