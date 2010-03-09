/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Malay language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['ms'] =
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
	source			: 'Sumber',
	newPage			: 'Helaian Baru',
	save			: 'Simpan',
	preview			: 'Prebiu',
	cut				: 'Potong',
	copy			: 'Salin',
	paste			: 'Tampal',
	print			: 'Cetak',
	underline		: 'Underline',
	bold			: 'Bold',
	italic			: 'Italic',
	selectAll		: 'Pilih Semua',
	removeFormat	: 'Buang Format',
	strike			: 'Strike Through',
	subscript		: 'Subscript',
	superscript		: 'Superscript',
	horizontalrule	: 'Masukkan Garisan Membujur',
	pagebreak		: 'Insert Page Break for Printing', // MISSING
	unlink			: 'Buang Sambungan',
	undo			: 'Batalkan',
	redo			: 'Ulangkan',

	// Common messages and labels.
	common :
	{
		browseServer	: 'Browse Server',
		url				: 'URL',
		protocol		: 'Protokol',
		upload			: 'Muat Naik',
		uploadSubmit	: 'Hantar ke Server',
		image			: 'Gambar',
		flash			: 'Flash', // MISSING
		form			: 'Borang',
		checkbox		: 'Checkbox',
		radio		: 'Butang Radio',
		textField		: 'Text Field',
		textarea		: 'Textarea',
		hiddenField		: 'Field Tersembunyi',
		button			: 'Butang',
		select	: 'Field Pilihan',
		imageButton		: 'Butang Bergambar',
		notSet			: '<tidak di set>',
		id				: 'Id',
		name			: 'Nama',
		langDir			: 'Arah Tulisan',
		langDirLtr		: 'Kiri ke Kanan (LTR)',
		langDirRtl		: 'Kanan ke Kiri (RTL)',
		langCode		: 'Kod Bahasa',
		longDescr		: 'Butiran Panjang URL',
		cssClass		: 'Kelas-kelas Stylesheet',
		advisoryTitle	: 'Tajuk Makluman',
		cssStyle		: 'Stail',
		ok				: 'OK',
		cancel			: 'Batal',
		generalTab		: 'General', // MISSING
		advancedTab		: 'Advanced',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'Masukkan Huruf Istimewa',
		title		: 'Sila pilih huruf istimewa'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'Masukkan/Sunting Sambungan',
		menu		: 'Sunting Sambungan',
		title		: 'Sambungan',
		info		: 'Butiran Sambungan',
		target		: 'Sasaran',
		upload		: 'Muat Naik',
		advanced	: 'Advanced',
		type		: 'Jenis Sambungan',
		toAnchor	: 'Pautan dalam muka surat ini',
		toEmail		: 'E-Mail',
		target		: 'Sasaran',
		targetNotSet	: '<tidak di set>',
		targetFrame	: '<bingkai>',
		targetPopup	: '<tetingkap popup>',
		targetNew	: 'Tetingkap Baru (_blank)',
		targetTop	: 'Tetingkap yang paling atas (_top)',
		targetSelf	: 'Tetingkap yang Sama (_self)',
		targetParent	: 'Tetingkap Parent (_parent)',
		targetFrameName	: 'Nama Bingkai Sasaran',
		targetPopupName	: 'Nama Tetingkap Popup',
		popupFeatures	: 'Ciri Tetingkap Popup',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'Bar Status',
		popupLocationBar	: 'Bar Lokasi',
		popupToolbar	: 'Toolbar',
		popupMenuBar	: 'Bar Menu',
		popupFullScreen	: 'Skrin Penuh (IE)',
		popupScrollBars	: 'Bar-bar skrol',
		popupDependent	: 'Bergantungan (Netscape)',
		popupWidth		: 'Lebar',
		popupLeft		: 'Posisi Kiri',
		popupHeight		: 'Tinggi',
		popupTop		: 'Posisi Atas',
		id				: 'Id', // MISSING
		langDir			: 'Arah Tulisan',
		langDirNotSet	: '<tidak di set>',
		langDirLTR		: 'Kiri ke Kanan (LTR)',
		langDirRTL		: 'Kanan ke Kiri (RTL)',
		acccessKey		: 'Kunci Akses',
		name			: 'Nama',
		langCode		: 'Arah Tulisan',
		tabIndex		: 'Indeks Tab ',
		advisoryTitle	: 'Tajuk Makluman',
		advisoryContentType	: 'Jenis Kandungan Makluman',
		cssClasses		: 'Kelas-kelas Stylesheet',
		charset			: 'Linked Resource Charset',
		styles			: 'Stail',
		selectAnchor	: 'Sila pilih pautan',
		anchorName		: 'dengan menggunakan nama pautan',
		anchorId		: 'dengan menggunakan ID elemen',
		emailAddress	: 'Alamat E-Mail',
		emailSubject	: 'Subjek Mesej',
		emailBody		: 'Isi Kandungan Mesej',
		noAnchors		: '(Tiada pautan terdapat dalam dokumen ini)',
		noUrl			: 'Sila taip sambungan URL',
		noEmail			: 'Sila taip alamat e-mail'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'Masukkan/Sunting Pautan',
		menu		: 'Ciri-ciri Pautan',
		title		: 'Ciri-ciri Pautan',
		name		: 'Nama Pautan',
		errorName	: 'Sila taip nama pautan'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Find and Replace', // MISSING
		find				: 'Cari',
		replace				: 'Ganti',
		findWhat			: 'Perkataan yang dicari:',
		replaceWith			: 'Diganti dengan:',
		notFoundMsg			: 'Text yang dicari tidak dijumpai.',
		matchCase			: 'Padanan case huruf',
		matchWord			: 'Padana Keseluruhan perkataan',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'Ganti semua',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'Jadual',
		title		: 'Ciri-ciri Jadual',
		menu		: 'Ciri-ciri Jadual',
		deleteTable	: 'Delete Table', // MISSING
		rows		: 'Barisan',
		columns		: 'Jaluran',
		border		: 'Saiz Border',
		align		: 'Penjajaran',
		alignNotSet	: '<Tidak diset>',
		alignLeft	: 'Kiri',
		alignCenter	: 'Tengah',
		alignRight	: 'Kanan',
		width		: 'Lebar',
		widthPx		: 'piksel-piksel',
		widthPc		: 'peratus',
		height		: 'Tinggi',
		cellSpace	: 'Ruangan Antara Sel',
		cellPad		: 'Tambahan Ruang Sel',
		caption		: 'Keterangan',
		summary		: 'Summary', // MISSING
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
			menu			: 'Cell', // MISSING
			insertBefore	: 'Insert Cell Before', // MISSING
			insertAfter		: 'Insert Cell After', // MISSING
			deleteCell		: 'Buangkan Sel-sel',
			merge			: 'Cantumkan Sel-sel',
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
			menu			: 'Row', // MISSING
			insertBefore	: 'Insert Row Before', // MISSING
			insertAfter		: 'Insert Row After', // MISSING
			deleteRow		: 'Buangkan Baris'
		},

		column :
		{
			menu			: 'Column', // MISSING
			insertBefore	: 'Insert Column Before', // MISSING
			insertAfter		: 'Insert Column After', // MISSING
			deleteColumn	: 'Buangkan Lajur'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'Ciri-ciri Butang',
		text		: 'Teks (Nilai)',
		type		: 'Jenis',
		typeBtn		: 'Button', // MISSING
		typeSbm		: 'Submit', // MISSING
		typeRst		: 'Reset' // MISSING
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'Ciri-ciri Checkbox',
		radioTitle	: 'Ciri-ciri Butang Radio',
		value		: 'Nilai',
		selected	: 'Dipilih'
	},

	// Form Dialog.
	form :
	{
		title		: 'Ciri-ciri Borang',
		menu		: 'Ciri-ciri Borang',
		action		: 'Tindakan borang',
		method		: 'Cara borang dihantar',
		encoding	: 'Encoding', // MISSING
		target		: 'Sasaran',
		targetNotSet	: '<tidak di set>',
		targetNew	: 'Tetingkap Baru (_blank)',
		targetTop	: 'Tetingkap yang paling atas (_top)',
		targetSelf	: 'Tetingkap yang Sama (_self)',
		targetParent	: 'Tetingkap Parent (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'Ciri-ciri Selection Field',
		selectInfo	: 'Select Info', // MISSING
		opAvail		: 'Pilihan sediada',
		value		: 'Nilai',
		size		: 'Saiz',
		lines		: 'garisan',
		chkMulti	: 'Benarkan pilihan pelbagai',
		opText		: 'Teks',
		opValue		: 'Nilai',
		btnAdd		: 'Tambah Pilihan',
		btnModify	: 'Ubah Pilihan',
		btnUp		: 'Naik ke atas',
		btnDown		: 'Turun ke bawah',
		btnSetValue : 'Set sebagai nilai terpilih',
		btnDelete	: 'Padam'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'Ciri-ciri Textarea',
		cols		: 'Lajur',
		rows		: 'Baris'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'Ciri-ciri Text Field',
		name		: 'Nama',
		value		: 'Nilai',
		charWidth	: 'Lebar isian',
		maxChars	: 'Isian Maksimum',
		type		: 'Jenis',
		typeText	: 'Teks',
		typePass	: 'Kata Laluan'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'Ciri-ciri Field Tersembunyi',
		name	: 'Nama',
		value	: 'Nilai'
	},

	// Image Dialog.
	image :
	{
		title		: 'Ciri-ciri Imej',
		titleButton	: 'Ciri-ciri Butang Bergambar',
		menu		: 'Ciri-ciri Imej',
		infoTab	: 'Info Imej',
		btnUpload	: 'Hantar ke Server',
		url		: 'URL',
		upload	: 'Muat Naik',
		alt		: 'Text Alternatif',
		width		: 'Lebar',
		height	: 'Tinggi',
		lockRatio	: 'Tetapkan Nisbah',
		resetSize	: 'Saiz Set Semula',
		border	: 'Border',
		hSpace	: 'Ruang Melintang',
		vSpace	: 'Ruang Menegak',
		align		: 'Jajaran',
		alignLeft	: 'Kiri',
		alignAbsBottom: 'Bawah Mutlak',
		alignAbsMiddle: 'Pertengahan Mutlak',
		alignBaseline	: 'Garis Dasar',
		alignBottom	: 'Bawah',
		alignMiddle	: 'Pertengahan',
		alignRight	: 'Kanan',
		alignTextTop	: 'Atas Text',
		alignTop	: 'Atas',
		preview	: 'Prebiu',
		alertUrl	: 'Sila taip URL untuk fail gambar',
		linkTab	: 'Sambungan',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'Flash Properties', // MISSING
		propertiesTab	: 'Properties', // MISSING
		title		: 'Flash Properties', // MISSING
		chkPlay		: 'Auto Play', // MISSING
		chkLoop		: 'Loop', // MISSING
		chkMenu		: 'Enable Flash Menu', // MISSING
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'Scale', // MISSING
		scaleAll		: 'Show all', // MISSING
		scaleNoBorder	: 'No Border', // MISSING
		scaleFit		: 'Exact Fit', // MISSING
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'Jajaran',
		alignLeft	: 'Kiri',
		alignAbsBottom: 'Bawah Mutlak',
		alignAbsMiddle: 'Pertengahan Mutlak',
		alignBaseline	: 'Garis Dasar',
		alignBottom	: 'Bawah',
		alignMiddle	: 'Pertengahan',
		alignRight	: 'Kanan',
		alignTextTop	: 'Atas Text',
		alignTop	: 'Atas',
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
		bgcolor	: 'Warna Latarbelakang',
		width	: 'Lebar',
		height	: 'Tinggi',
		hSpace	: 'Ruang Melintang',
		vSpace	: 'Ruang Menegak',
		validateSrc : 'Sila taip sambungan URL',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'Semak Ejaan',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'Tidak terdapat didalam kamus',
		changeTo		: 'Tukarkan kepada',
		btnIgnore		: 'Biar',
		btnIgnoreAll	: 'Biarkan semua',
		btnReplace		: 'Ganti',
		btnReplaceAll	: 'Gantikan Semua',
		btnUndo			: 'Batalkan',
		noSuggestions	: '- Tiada cadangan -',
		progress		: 'Pemeriksaan ejaan sedang diproses...',
		noMispell		: 'Pemeriksaan ejaan siap: Tiada salah ejaan',
		noChanges		: 'Pemeriksaan ejaan siap: Tiada perkataan diubah',
		oneChange		: 'Pemeriksaan ejaan siap: Satu perkataan telah diubah',
		manyChanges		: 'Pemeriksaan ejaan siap: %1 perkataan diubah',
		ieSpellDownload	: 'Pemeriksa ejaan tidak dipasang. Adakah anda mahu muat turun sekarang?'
	},

	smiley :
	{
		toolbar	: 'Smiley',
		title	: 'Masukkan Smiley'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'Senarai bernombor',
	bulletedlist : 'Senarai tidak bernombor',
	indent : 'Tambahkan Inden',
	outdent : 'Kurangkan Inden',

	justify :
	{
		left : 'Jajaran Kiri',
		center : 'Jajaran Tengah',
		right : 'Jajaran Kanan',
		block : 'Jajaran Blok'
	},

	blockquote : 'Blockquote', // MISSING

	clipboard :
	{
		title		: 'Tampal',
		cutError	: 'Keselamatan perisian browser anda tidak membenarkan operasi suntingan text/imej. Sila gunakan papan kekunci (Ctrl+X).',
		copyError	: 'Keselamatan perisian browser anda tidak membenarkan operasi salinan text/imej. Sila gunakan papan kekunci (Ctrl+C).',
		pasteMsg	: 'Please paste inside the following box using the keyboard (<strong>Ctrl+V</strong>) and hit OK', // MISSING
		securityMsg	: 'Because of your browser security settings, the editor is not able to access your clipboard data directly. You are required to paste it again in this window.' // MISSING
	},

	pastefromword :
	{
		toolbar : 'Tampal dari Word',
		title : 'Tampal dari Word',
		advice : 'Please paste inside the following box using the keyboard (<strong>Ctrl+V</strong>) and hit <strong>OK</strong>.', // MISSING
		ignoreFontFace : 'Ignore Font Face definitions', // MISSING
		removeStyle : 'Remove Styles definitions' // MISSING
	},

	pasteText :
	{
		button : 'Tampal sebagai text biasa',
		title : 'Tampal sebagai text biasa'
	},

	templates :
	{
		button : 'Templat',
		title : 'Templat Kandungan',
		insertOption: 'Replace actual contents', // MISSING
		selectPromptMsg: 'Sila pilih templat untuk dibuka oleh editor<br>(kandungan sebenar akan hilang):',
		emptyListMsg : '(Tiada Templat Disimpan)'
	},

	showBlocks : 'Show Blocks', // MISSING

	stylesCombo :
	{
		label : 'Stail',
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
		tag_pre : 'Telah Diformat',
		tag_address : 'Alamat',
		tag_h1 : 'Heading 1',
		tag_h2 : 'Heading 2',
		tag_h3 : 'Heading 3',
		tag_h4 : 'Heading 4',
		tag_h5 : 'Heading 5',
		tag_h6 : 'Heading 6',
		tag_div : 'Perenggan (DIV)'
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
		label : 'Saiz',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'Saiz',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'Warna Text',
		bgColorTitle : 'Warna Latarbelakang',
		auto : 'Otomatik',
		more : 'Warna lain-lain...'
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
