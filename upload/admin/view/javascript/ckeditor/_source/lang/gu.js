/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Gujarati language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['gu'] =
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
	source			: 'મૂળ કે પ્રાથમિક દસ્તાવેજ',
	newPage			: 'નવુ પાનું',
	save			: 'સેવ',
	preview			: 'પૂર્વદર્શન',
	cut				: 'કાપવું',
	copy			: 'નકલ',
	paste			: 'પેસ્ટ',
	print			: 'પ્રિન્ટ',
	underline		: 'અન્ડર્લાઇન, નીચે લીટી',
	bold			: 'બોલ્ડ/સ્પષ્ટ',
	italic			: 'ઇટેલિક, ત્રાંસા',
	selectAll		: 'બઘું પસંદ કરવું',
	removeFormat	: 'ફૉર્મટ કાઢવું',
	strike			: 'છેકી નાખવું',
	subscript		: 'એક ચિહ્નની નીચે કરેલું બીજું ચિહ્ન',
	superscript		: 'એક ચિહ્ન ઉપર કરેલું બીજું ચિહ્ન.',
	horizontalrule	: 'સમસ્તરીય રેખા ઇન્સર્ટ/દાખલ કરવી',
	pagebreak		: 'ઇન્સર્ટ પેજબ્રેક/પાનાને અલગ કરવું/દાખલ કરવું',
	unlink			: 'લિંક કાઢવી',
	undo			: 'રદ કરવું; પહેલાં હતી એવી સ્થિતિ પાછી લાવવી',
	redo			: 'રિડૂ; પછી હતી એવી સ્થિતિ પાછી લાવવી',

	// Common messages and labels.
	common :
	{
		browseServer	: 'સર્વર બ્રાઉઝ કરો',
		url				: 'URL',
		protocol		: 'પ્રોટોકૉલ',
		upload			: 'અપલોડ',
		uploadSubmit	: 'આ સર્વરને મોકલવું',
		image			: 'ચિત્ર',
		flash			: 'ફ્લૅશ',
		form			: 'ફૉર્મ/પત્રક',
		checkbox		: 'ચેક બોક્સ',
		radio		: 'રેડિઓ બટન',
		textField		: 'ટેક્સ્ટ ફીલ્ડ, શબ્દ ક્ષેત્ર',
		textarea		: 'ટેક્સ્ટ એરિઆ, શબ્દ વિસ્તાર',
		hiddenField		: 'ગુપ્ત ક્ષેત્ર',
		button			: 'બટન',
		select	: 'પસંદગી ક્ષેત્ર',
		imageButton		: 'ચિત્ર બટન',
		notSet			: '<સેટ નથી>',
		id				: 'Id',
		name			: 'નામ',
		langDir			: 'ભાષા લેખવાની પદ્ધતિ',
		langDirLtr		: 'ડાબે થી જમણે (LTR)',
		langDirRtl		: 'જમણે થી ડાબે (RTL)',
		langCode		: 'ભાષા કોડ',
		longDescr		: 'વધારે માહિતી માટે URL',
		cssClass		: 'સ્ટાઇલ-શીટ ક્લાસ',
		advisoryTitle	: 'મુખ્ય મથાળું',
		cssStyle		: 'સ્ટાઇલ',
		ok				: 'ઠીક છે',
		cancel			: 'રદ કરવું',
		generalTab		: 'General', // MISSING
		advancedTab		: 'અડ્વાન્સડ',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'વિશિષ્ટ અક્ષર ઇન્સર્ટ/દાખલ કરવું',
		title		: 'સ્પેશિઅલ વિશિષ્ટ અક્ષર પસંદ કરો'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'લિંક ઇન્સર્ટ/દાખલ કરવી',
		menu		: ' લિંક એડિટ/માં ફેરફાર કરવો',
		title		: 'લિંક',
		info		: 'લિંક ઇન્ફૉ ટૅબ',
		target		: 'ટાર્ગેટ/લક્ષ્ય',
		upload		: 'અપલોડ',
		advanced	: 'અડ્વાન્સડ',
		type		: 'લિંક પ્રકાર',
		toAnchor	: 'આ પેજનો ઍંકર',
		toEmail		: 'ઈ-મેલ',
		target		: 'ટાર્ગેટ/લક્ષ્ય',
		targetNotSet	: '<સેટ નથી>',
		targetFrame	: '<ફ્રેમ>',
		targetPopup	: '<પૉપ-અપ વિન્ડો>',
		targetNew	: 'નવી  વિન્ડો (_blank)',
		targetTop	: 'ઉપરની વિન્ડો (_top)',
		targetSelf	: 'આજ વિન્ડો (_self)',
		targetParent	: 'મૂળ વિન્ડો (_parent)',
		targetFrameName	: 'ટાર્ગેટ ફ્રેમ નું નામ',
		targetPopupName	: 'પૉપ-અપ વિન્ડો નું નામ',
		popupFeatures	: 'પૉપ-અપ વિન્ડો ફીચરસૅ',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'સ્ટૅટસ બાર',
		popupLocationBar	: 'લોકેશન બાર',
		popupToolbar	: 'ટૂલ બાર',
		popupMenuBar	: 'મેન્યૂ બાર',
		popupFullScreen	: 'ફુલ સ્ક્રીન (IE)',
		popupScrollBars	: 'સ્ક્રોલ બાર',
		popupDependent	: 'ડિપેન્ડન્ટ (Netscape)',
		popupWidth		: 'પહોળાઈ',
		popupLeft		: 'ડાબી બાજુ',
		popupHeight		: 'ઊંચાઈ',
		popupTop		: 'જમણી બાજુ',
		id				: 'Id', // MISSING
		langDir			: 'ભાષા લેખવાની પદ્ધતિ',
		langDirNotSet	: '<સેટ નથી>',
		langDirLTR		: 'ડાબે થી જમણે (LTR)',
		langDirRTL		: 'જમણે થી ડાબે (RTL)',
		acccessKey		: 'ઍક્સેસ કી',
		name			: 'નામ',
		langCode		: 'ભાષા લેખવાની પદ્ધતિ',
		tabIndex		: 'ટૅબ ઇન્ડેક્સ',
		advisoryTitle	: 'મુખ્ય મથાળું',
		advisoryContentType	: 'મુખ્ય કન્ટેન્ટ પ્રકાર',
		cssClasses		: 'સ્ટાઇલ-શીટ ક્લાસ',
		charset			: 'લિંક રિસૉર્સ કૅરિક્ટર સેટ',
		styles			: 'સ્ટાઇલ',
		selectAnchor	: 'ઍંકર પસંદ કરો',
		anchorName		: 'ઍંકર નામથી પસંદ કરો',
		anchorId		: 'ઍંકર એલિમન્ટ Id થી પસંદ કરો',
		emailAddress	: 'ઈ-મેલ સરનામું',
		emailSubject	: 'ઈ-મેલ વિષય',
		emailBody		: 'સંદેશ',
		noAnchors		: '(ડૉક્યુમન્ટમાં ઍંકરની સંખ્યા)',
		noUrl			: 'લિંક  URL ટાઇપ કરો',
		noEmail			: 'ઈ-મેલ સરનામું ટાઇપ કરો'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'ઍંકર ઇન્સર્ટ/દાખલ કરવી',
		menu		: 'ઍંકરના ગુણ',
		title		: 'ઍંકરના ગુણ',
		name		: 'ઍંકરનું નામ',
		errorName	: 'ઍંકરનું નામ ટાઈપ કરો'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'શોધવું અને બદલવું',
		find				: 'શોધવું',
		replace				: 'રિપ્લેસ/બદલવું',
		findWhat			: 'આ શોધો',
		replaceWith			: 'આનાથી બદલો',
		notFoundMsg			: 'તમે શોધેલી ટેક્સ્ટ નથી મળી',
		matchCase			: 'કેસ સરખા રાખો',
		matchWord			: 'બઘા શબ્દ સરખા રાખો',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'બઘા બદલી ',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'ટેબલ, કોઠો',
		title		: 'ટેબલ, કોઠાનું મથાળું',
		menu		: 'ટેબલ, કોઠાનું મથાળું',
		deleteTable	: 'કોઠો ડિલીટ/કાઢી નાખવું',
		rows		: 'પંક્તિના ખાના',
		columns		: 'કૉલમ/ઊભી કટાર',
		border		: 'કોઠાની બાજુ(બોર્ડર) સાઇઝ',
		align		: 'અલાઇનમન્ટ/ગોઠવાયેલું ',
		alignNotSet	: '<સેટ નથી>',
		alignLeft	: 'ડાબી બાજુ',
		alignCenter	: 'મધ્ય સેન્ટર',
		alignRight	: 'જમણી બાજુ',
		width		: 'પહોળાઈ',
		widthPx		: 'પિકસલ',
		widthPc		: 'પ્રતિશત',
		height		: 'ઊંચાઈ',
		cellSpace	: 'સેલ અંતર',
		cellPad		: 'સેલ પૅડિંગ',
		caption		: 'મથાળું/કૅપ્શન ',
		summary		: 'ટૂંકો એહેવાલ',
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
			menu			: 'કોષના ખાના',
			insertBefore	: 'પહેલાં કોષ ઉમેરવો',
			insertAfter		: 'પછી કોષ ઉમેરવો',
			deleteCell		: 'કોષ ડિલીટ/કાઢી નાખવો',
			merge			: 'કોષ ભેગા કરવા',
			mergeRight		: 'જમણી બાજુ ભેગા કરવા',
			mergeDown		: 'નીચે ભેગા કરવા',
			splitHorizontal	: 'કોષને સમસ્તરીય વિભાજન કરવું',
			splitVertical	: 'કોષને સીધું ને ઊભું વિભાજન કરવું',
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
			menu			: 'પંક્તિના ખાના',
			insertBefore	: 'પહેલાં પંક્તિ ઉમેરવી',
			insertAfter		: 'પછી પંક્તિ ઉમેરવી',
			deleteRow		: 'પંક્તિઓ ડિલીટ/કાઢી નાખવી'
		},

		column :
		{
			menu			: 'કૉલમ/ઊભી કટાર',
			insertBefore	: 'પહેલાં કૉલમ/ઊભી કટાર ઉમેરવી',
			insertAfter		: 'પછી કૉલમ/ઊભી કટાર ઉમેરવી',
			deleteColumn	: 'કૉલમ/ઊભી કટાર ડિલીટ/કાઢી નાખવી'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'બટનના ગુણ',
		text		: 'ટેક્સ્ટ (વૅલ્યૂ)',
		type		: 'પ્રકાર',
		typeBtn		: 'બટન',
		typeSbm		: 'સબ્મિટ',
		typeRst		: 'રિસેટ'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'ચેક બોક્સ ગુણ',
		radioTitle	: 'રેડિઓ બટનના ગુણ',
		value		: 'વૅલ્યૂ',
		selected	: 'સિલેક્ટેડ'
	},

	// Form Dialog.
	form :
	{
		title		: 'ફૉર્મ/પત્રકના ગુણ',
		menu		: 'ફૉર્મ/પત્રકના ગુણ',
		action		: 'ક્રિયા',
		method		: 'પદ્ધતિ',
		encoding	: 'Encoding', // MISSING
		target		: 'ટાર્ગેટ/લક્ષ્ય',
		targetNotSet	: '<સેટ નથી>',
		targetNew	: 'નવી  વિન્ડો (_blank)',
		targetTop	: 'ઉપરની વિન્ડો (_top)',
		targetSelf	: 'આજ વિન્ડો (_self)',
		targetParent	: 'મૂળ વિન્ડો (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'પસંદગી ક્ષેત્રના ગુણ',
		selectInfo	: 'સૂચના',
		opAvail		: 'ઉપલબ્ધ વિકલ્પ',
		value		: 'વૅલ્યૂ',
		size		: 'સાઇઝ',
		lines		: 'લીટીઓ',
		chkMulti	: 'એકથી વધારે પસંદ કરી શકો',
		opText		: 'ટેક્સ્ટ',
		opValue		: 'વૅલ્યૂ',
		btnAdd		: 'ઉમેરવું',
		btnModify	: 'બદલવું',
		btnUp		: 'ઉપર',
		btnDown		: 'નીચે',
		btnSetValue : 'પસંદ કરલી વૅલ્યૂ સેટ કરો',
		btnDelete	: 'રદ કરવું'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'ટેક્સ્ટ એઅરિઆ, શબ્દ વિસ્તારના ગુણ',
		cols		: 'કૉલમ/ઊભી કટાર',
		rows		: 'પંક્તિઓ'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'ટેક્સ્ટ ફીલ્ડ, શબ્દ ક્ષેત્રના ગુણ',
		name		: 'નામ',
		value		: 'વૅલ્યૂ',
		charWidth	: 'કેરેક્ટરની પહોળાઈ',
		maxChars	: 'અધિકતમ કેરેક્ટર',
		type		: 'ટાઇપ',
		typeText	: 'ટેક્સ્ટ',
		typePass	: 'પાસવર્ડ'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'ગુપ્ત ક્ષેત્રના ગુણ',
		name	: 'નામ',
		value	: 'વૅલ્યૂ'
	},

	// Image Dialog.
	image :
	{
		title		: 'ચિત્રના ગુણ',
		titleButton	: 'ચિત્ર બટનના ગુણ',
		menu		: 'ચિત્રના ગુણ',
		infoTab	: 'ચિત્ર ની જાણકારી',
		btnUpload	: 'આ સર્વરને મોકલવું',
		url		: 'URL',
		upload	: 'અપલોડ',
		alt		: 'ઑલ્ટર્નટ ટેક્સ્ટ',
		width		: 'પહોળાઈ',
		height	: 'ઊંચાઈ',
		lockRatio	: 'લૉક ગુણોત્તર',
		resetSize	: 'રીસેટ સાઇઝ',
		border	: 'બોર્ડર',
		hSpace	: 'સમસ્તરીય જગ્યા',
		vSpace	: 'લંબરૂપ જગ્યા',
		align		: 'લાઇનદોરીમાં ગોઠવવું',
		alignLeft	: 'ડાબી બાજુ ગોઠવવું',
		alignAbsBottom: 'Abs નીચે',
		alignAbsMiddle: 'Abs ઉપર',
		alignBaseline	: 'આધાર લીટી',
		alignBottom	: 'નીચે',
		alignMiddle	: 'વચ્ચે',
		alignRight	: 'જમણી',
		alignTextTop	: 'ટેક્સ્ટ ઉપર',
		alignTop	: 'ઉપર',
		preview	: 'પૂર્વદર્શન',
		alertUrl	: 'ચિત્રની URL ટાઇપ કરો',
		linkTab	: 'લિંક',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'ફ્લૅશના ગુણ',
		propertiesTab	: 'Properties', // MISSING
		title		: 'ફ્લૅશ ગુણ',
		chkPlay		: 'ઑટો/સ્વયં પ્લે',
		chkLoop		: 'લૂપ',
		chkMenu		: 'ફ્લૅશ મેન્યૂ નો પ્રયોગ કરો',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'સ્કેલ',
		scaleAll		: 'સ્કેલ ઓલ/બધુ બતાવો',
		scaleNoBorder	: 'સ્કેલ બોર્ડર વગર',
		scaleFit		: 'સ્કેલ એકદમ ફીટ',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'લાઇનદોરીમાં ગોઠવવું',
		alignLeft	: 'ડાબી બાજુ ગોઠવવું',
		alignAbsBottom: 'Abs નીચે',
		alignAbsMiddle: 'Abs ઉપર',
		alignBaseline	: 'આધાર લીટી',
		alignBottom	: 'નીચે',
		alignMiddle	: 'વચ્ચે',
		alignRight	: 'જમણી',
		alignTextTop	: 'ટેક્સ્ટ ઉપર',
		alignTop	: 'ઉપર',
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
		bgcolor	: 'બૅકગ્રાઉન્ડ રંગ,',
		width	: 'પહોળાઈ',
		height	: 'ઊંચાઈ',
		hSpace	: 'સમસ્તરીય જગ્યા',
		vSpace	: 'લંબરૂપ જગ્યા',
		validateSrc : 'લિંક  URL ટાઇપ કરો',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'જોડણી (સ્પેલિંગ) તપાસવી',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'શબ્દકોશમાં નથી',
		changeTo		: 'આનાથી બદલવું',
		btnIgnore		: 'ઇગ્નોર/અવગણના કરવી',
		btnIgnoreAll	: 'બધાની ઇગ્નોર/અવગણના કરવી',
		btnReplace		: 'બદલવું',
		btnReplaceAll	: 'બધા બદલી કરો',
		btnUndo			: 'અન્ડૂ',
		noSuggestions	: '- કઇ સજેશન નથી -',
		progress		: 'શબ્દની જોડણી/સ્પેલ ચેક ચાલુ છે...',
		noMispell		: 'શબ્દની જોડણી/સ્પેલ ચેક પૂર્ણ: ખોટી જોડણી મળી નથી',
		noChanges		: 'શબ્દની જોડણી/સ્પેલ ચેક પૂર્ણ: એકપણ શબ્દ બદલયો નથી',
		oneChange		: 'શબ્દની જોડણી/સ્પેલ ચેક પૂર્ણ: એક શબ્દ બદલયો છે',
		manyChanges		: 'શબ્દની જોડણી/સ્પેલ ચેક પૂર્ણ: %1 શબ્દ બદલયા છે',
		ieSpellDownload	: 'સ્પેલ-ચેકર ઇન્સ્ટોલ નથી. શું તમે ડાઉનલોડ કરવા માંગો છો?'
	},

	smiley :
	{
		toolbar	: 'સ્માઇલી',
		title	: 'સ્માઇલી  પસંદ કરો'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'સંખ્યાંકન સૂચિ',
	bulletedlist : 'બુલેટ સૂચિ',
	indent : 'ઇન્ડેન્ટ, લીટીના આરંભમાં જગ્યા વધારવી',
	outdent : 'ઇન્ડેન્ટ લીટીના આરંભમાં જગ્યા ઘટાડવી',

	justify :
	{
		left : 'ડાબી બાજુએ/બાજુ તરફ',
		center : 'સંકેંદ્રણ/સેંટરિંગ',
		right : 'જમણી બાજુએ/બાજુ તરફ',
		block : 'બ્લૉક, અંતરાય જસ્ટિફાઇ'
	},

	blockquote : 'બ્લૉક-કોટ, અવતરણચિહ્નો',

	clipboard :
	{
		title		: 'પેસ્ટ',
		cutError	: 'તમારા બ્રાઉઝર ની સુરક્ષિત સેટિંગસ કટ કરવાની પરવાનગી નથી આપતી. (Ctrl+X) નો ઉપયોગ કરો.',
		copyError	: 'તમારા બ્રાઉઝર ની સુરક્ષિત સેટિંગસ કોપી કરવાની પરવાનગી નથી આપતી.  (Ctrl+C) का प्रयोग करें।',
		pasteMsg	: 'Ctrl+V નો પ્રયોગ કરી પેસ્ટ કરો',
		securityMsg	: 'તમારા બ્રાઉઝર ની સુરક્ષિત સેટિંગસના કારણે,એડિટર તમારા કિલ્પબોર્ડ ડેટા ને કોપી નથી કરી શકતો. તમારે આ વિન્ડોમાં ફરીથી પેસ્ટ કરવું પડશે.'
	},

	pastefromword :
	{
		toolbar : 'પેસ્ટ (વડૅ ટેક્સ્ટ)',
		title : 'પેસ્ટ (વડૅ ટેક્સ્ટ)',
		advice : 'Ctrl+V નો પ્રયોગ કરી પેસ્ટ કરો',
		ignoreFontFace : 'ફૉન્ટફેસ વ્યાખ્યાની અવગણના',
		removeStyle : 'સ્ટાઇલ વ્યાખ્યા કાઢી નાખવી'
	},

	pasteText :
	{
		button : 'પેસ્ટ (ટેક્સ્ટ)',
		title : 'પેસ્ટ (ટેક્સ્ટ)'
	},

	templates :
	{
		button : 'ટેમ્પ્લેટ',
		title : 'કન્ટેન્ટ ટેમ્પ્લેટ',
		insertOption: 'મૂળ શબ્દને બદલો',
		selectPromptMsg: 'એડિટરમાં ઓપન કરવા ટેમ્પ્લેટ પસંદ કરો (વર્તમાન કન્ટેન્ટ સેવ નહીં થાય):',
		emptyListMsg : '(કોઈ ટેમ્પ્લેટ ડિફાઇન નથી)'
	},

	showBlocks : 'બ્લૉક બતાવવું',

	stylesCombo :
	{
		label : 'શૈલી/રીત',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'ફૉન્ટ ફૉર્મટ, રચનાની શૈલી',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'ફૉન્ટ ફૉર્મટ, રચનાની શૈલી',
		panelVoiceLabel : 'Select a paragraph format', // MISSING

		tag_p : 'સામાન્ય',
		tag_pre : 'ફૉર્મટેડ',
		tag_address : 'સરનામું',
		tag_h1 : 'શીર્ષક 1',
		tag_h2 : 'શીર્ષક 2',
		tag_h3 : 'શીર્ષક 3',
		tag_h4 : 'શીર્ષક 4',
		tag_h5 : 'શીર્ષક 5',
		tag_h6 : 'શીર્ષક 6',
		tag_div : 'શીર્ષક (DIV)'
	},

	font :
	{
		label : 'ફૉન્ટ',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'ફૉન્ટ',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'ફૉન્ટ સાઇઝ/કદ',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'ફૉન્ટ સાઇઝ/કદ',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'શબ્દનો રંગ',
		bgColorTitle : 'બૅકગ્રાઉન્ડ રંગ,',
		auto : 'સ્વચાલિત',
		more : 'ઔર રંગ...'
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
