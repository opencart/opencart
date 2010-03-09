/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Thai language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['th'] =
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
	source			: 'ดูรหัส HTML',
	newPage			: 'สร้างหน้าเอกสารใหม่',
	save			: 'บันทึก',
	preview			: 'ดูหน้าเอกสารตัวอย่าง',
	cut				: 'ตัด',
	copy			: 'สำเนา',
	paste			: 'วาง',
	print			: 'สั่งพิมพ์',
	underline		: 'ตัวขีดเส้นใต้',
	bold			: 'ตัวหนา',
	italic			: 'ตัวเอียง',
	selectAll		: 'เลือกทั้งหมด',
	removeFormat	: 'ล้างรูปแบบ',
	strike			: 'ตัวขีดเส้นทับ',
	subscript		: 'ตัวห้อย',
	superscript		: 'ตัวยก',
	horizontalrule	: 'แทรกเส้นคั่นบรรทัด',
	pagebreak		: 'แทรกตัวแบ่งหน้า Page Break',
	unlink			: 'ลบ ลิงค์',
	undo			: 'ยกเลิกคำสั่ง',
	redo			: 'ทำซ้ำคำสั่ง',

	// Common messages and labels.
	common :
	{
		browseServer	: 'เปิดหน้าต่างจัดการไฟล์อัพโหลด',
		url				: 'ที่อยู่อ้างอิง URL',
		protocol		: 'โปรโตคอล',
		upload			: 'อัพโหลดไฟล์',
		uploadSubmit	: 'อัพโหลดไฟล์ไปเก็บไว้ที่เครื่องแม่ข่าย (เซิร์ฟเวอร์)',
		image			: 'รูปภาพ',
		flash			: 'ไฟล์ Flash',
		form			: 'แบบฟอร์ม',
		checkbox		: 'เช็คบ๊อก',
		radio		: 'เรดิโอบัตตอน',
		textField		: 'เท็กซ์ฟิลด์',
		textarea		: 'เท็กซ์แอเรีย',
		hiddenField		: 'ฮิดเดนฟิลด์',
		button			: 'ปุ่ม',
		select	: 'แถบตัวเลือก',
		imageButton		: 'ปุ่มแบบรูปภาพ',
		notSet			: '<ไม่ระบุ>',
		id				: 'ไอดี',
		name			: 'ชื่อ',
		langDir			: 'การเขียน-อ่านภาษา',
		langDirLtr		: 'จากซ้ายไปขวา (LTR)',
		langDirRtl		: 'จากขวามาซ้าย (RTL)',
		langCode		: 'รหัสภาษา',
		longDescr		: 'คำอธิบายประกอบ URL',
		cssClass		: 'คลาสของไฟล์กำหนดลักษณะการแสดงผล',
		advisoryTitle	: 'คำเกริ่นนำ',
		cssStyle		: 'ลักษณะการแสดงผล',
		ok				: 'ตกลง',
		cancel			: 'ยกเลิก',
		generalTab		: 'General', // MISSING
		advancedTab		: 'ขั้นสูง',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: 'แทรกตัวอักษรพิเศษ',
		title		: 'แทรกตัวอักษรพิเศษ'
	},

	// Link dialog.
	link :
	{
		toolbar		: 'แทรก/แก้ไข ลิงค์',
		menu		: 'แก้ไข ลิงค์',
		title		: 'ลิงค์เชื่อมโยงเว็บ อีเมล์ รูปภาพ หรือไฟล์อื่นๆ',
		info		: 'รายละเอียด',
		target		: 'การเปิดหน้าลิงค์',
		upload		: 'อัพโหลดไฟล์',
		advanced	: 'ขั้นสูง',
		type		: 'ประเภทของลิงค์',
		toAnchor	: 'จุดเชื่อมโยง (Anchor)',
		toEmail		: 'ส่งอีเมล์ (E-Mail)',
		target		: 'การเปิดหน้าลิงค์',
		targetNotSet	: '<ไม่ระบุ>',
		targetFrame	: '<เปิดในเฟรม>',
		targetPopup	: '<เปิดหน้าจอเล็ก (Pop-up)>',
		targetNew	: 'เปิดหน้าจอใหม่ (_blank)',
		targetTop	: 'เปิดในหน้าบนสุด (_top)',
		targetSelf	: 'เปิดในหน้าปัจจุบัน (_self)',
		targetParent	: 'เปิดในหน้าหลัก (_parent)',
		targetFrameName	: 'ชื่อทาร์เก็ตเฟรม',
		targetPopupName	: 'ระบุชื่อหน้าจอเล็ก (Pop-up)',
		popupFeatures	: 'คุณสมบัติของหน้าจอเล็ก (Pop-up)',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: 'แสดงแถบสถานะ',
		popupLocationBar	: 'แสดงที่อยู่ของไฟล์',
		popupToolbar	: 'แสดงแถบเครื่องมือ',
		popupMenuBar	: 'แสดงแถบเมนู',
		popupFullScreen	: 'แสดงเต็มหน้าจอ (IE5.5++ เท่านั้น)',
		popupScrollBars	: 'แสดงแถบเลื่อน',
		popupDependent	: 'แสดงเต็มหน้าจอ (Netscape)',
		popupWidth		: 'กว้าง',
		popupLeft		: 'พิกัดซ้าย (Left Position)',
		popupHeight		: 'สูง',
		popupTop		: 'พิกัดบน (Top Position)',
		id				: 'Id', // MISSING
		langDir			: 'การเขียน-อ่านภาษา',
		langDirNotSet	: '<ไม่ระบุ>',
		langDirLTR		: 'จากซ้ายไปขวา (LTR)',
		langDirRTL		: 'จากขวามาซ้าย (RTL)',
		acccessKey		: 'แอคเซส คีย์',
		name			: 'ชื่อ',
		langCode		: 'การเขียน-อ่านภาษา',
		tabIndex		: 'ลำดับของ แท็บ',
		advisoryTitle	: 'คำเกริ่นนำ',
		advisoryContentType	: 'ชนิดของคำเกริ่นนำ',
		cssClasses		: 'คลาสของไฟล์กำหนดลักษณะการแสดงผล',
		charset			: 'ลิงค์เชื่อมโยงไปยังชุดตัวอักษร',
		styles			: 'ลักษณะการแสดงผล',
		selectAnchor	: 'ระบุข้อมูลของจุดเชื่อมโยง (Anchor)',
		anchorName		: 'ชื่อ',
		anchorId		: 'ไอดี',
		emailAddress	: 'อีเมล์ (E-Mail)',
		emailSubject	: 'หัวเรื่อง',
		emailBody		: 'ข้อความ',
		noAnchors		: '(ยังไม่มีจุดเชื่อมโยงภายในหน้าเอกสารนี้)',
		noUrl			: 'กรุณาระบุที่อยู่อ้างอิงออนไลน์ (URL)',
		noEmail			: 'กรุณาระบุอีเมล์ (E-mail)'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: 'แทรก/แก้ไข Anchor',
		menu		: 'รายละเอียด Anchor',
		title		: 'รายละเอียด Anchor',
		name		: 'ชื่อ Anchor',
		errorName	: 'กรุณาระบุชื่อของ Anchor'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: 'Find and Replace', // MISSING
		find				: 'ค้นหา',
		replace				: 'ค้นหาและแทนที่',
		findWhat			: 'ค้นหาคำว่า:',
		replaceWith			: 'แทนที่ด้วย:',
		notFoundMsg			: 'ไม่พบคำที่ค้นหา.',
		matchCase			: 'ตัวโหญ่-เล็ก ต้องตรงกัน',
		matchWord			: 'ต้องตรงกันทุกคำ',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: 'แทนที่ทั้งหมดที่พบ',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: 'ตาราง',
		title		: 'คุณสมบัติของ ตาราง',
		menu		: 'คุณสมบัติของ ตาราง',
		deleteTable	: 'ลบตาราง',
		rows		: 'แถว',
		columns		: 'สดมน์',
		border		: 'ขนาดเส้นขอบ',
		align		: 'การจัดตำแหน่ง',
		alignNotSet	: '<ไม่ระบุ>',
		alignLeft	: 'ชิดซ้าย',
		alignCenter	: 'กึ่งกลาง',
		alignRight	: 'ชิดขวา',
		width		: 'กว้าง',
		widthPx		: 'จุดสี',
		widthPc		: 'เปอร์เซ็น',
		height		: 'สูง',
		cellSpace	: 'ระยะแนวนอนน',
		cellPad		: 'ระยะแนวตั้ง',
		caption		: 'หัวเรื่องของตาราง',
		summary		: 'สรุปความ',
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
			menu			: 'ช่องตาราง',
			insertBefore	: 'Insert Cell Before', // MISSING
			insertAfter		: 'Insert Cell After', // MISSING
			deleteCell		: 'ลบช่อง',
			merge			: 'ผสานช่อง',
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
			menu			: 'แถว',
			insertBefore	: 'Insert Row Before', // MISSING
			insertAfter		: 'Insert Row After', // MISSING
			deleteRow		: 'ลบแถว'
		},

		column :
		{
			menu			: 'คอลัมน์',
			insertBefore	: 'Insert Column Before', // MISSING
			insertAfter		: 'Insert Column After', // MISSING
			deleteColumn	: 'ลบสดมน์'
		}
	},

	// Button Dialog.
	button :
	{
		title		: 'รายละเอียดของ ปุ่ม',
		text		: 'ข้อความ (ค่าตัวแปร)',
		type		: 'ข้อความ',
		typeBtn		: 'Button',
		typeSbm		: 'Submit',
		typeRst		: 'Reset'
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : 'คุณสมบัติของ เช็คบ๊อก',
		radioTitle	: 'คุณสมบัติของ เรดิโอบัตตอน',
		value		: 'ค่าตัวแปร',
		selected	: 'เลือกเป็นค่าเริ่มต้น'
	},

	// Form Dialog.
	form :
	{
		title		: 'คุณสมบัติของ แบบฟอร์ม',
		menu		: 'คุณสมบัติของ แบบฟอร์ม',
		action		: 'แอคชั่น',
		method		: 'เมธอด',
		encoding	: 'Encoding', // MISSING
		target		: 'การเปิดหน้าลิงค์',
		targetNotSet	: '<ไม่ระบุ>',
		targetNew	: 'เปิดหน้าจอใหม่ (_blank)',
		targetTop	: 'เปิดในหน้าบนสุด (_top)',
		targetSelf	: 'เปิดในหน้าปัจจุบัน (_self)',
		targetParent	: 'เปิดในหน้าหลัก (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: 'คุณสมบัติของ แถบตัวเลือก',
		selectInfo	: 'อินโฟ',
		opAvail		: 'รายการตัวเลือก',
		value		: 'ค่าตัวแปร',
		size		: 'ขนาด',
		lines		: 'บรรทัด',
		chkMulti	: 'เลือกหลายค่าได้',
		opText		: 'ข้อความ',
		opValue		: 'ค่าตัวแปร',
		btnAdd		: 'เพิ่ม',
		btnModify	: 'แก้ไข',
		btnUp		: 'บน',
		btnDown		: 'ล่าง',
		btnSetValue : 'เลือกเป็นค่าเริ่มต้น',
		btnDelete	: 'ลบ'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: 'คุณสมบัติของ เท็กแอเรีย',
		cols		: 'สดมภ์',
		rows		: 'แถว'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: 'คุณสมบัติของ เท็กซ์ฟิลด์',
		name		: 'ชื่อ',
		value		: 'ค่าตัวแปร',
		charWidth	: 'ความกว้าง',
		maxChars	: 'จำนวนตัวอักษรสูงสุด',
		type		: 'ชนิด',
		typeText	: 'ข้อความ',
		typePass	: 'รหัสผ่าน'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: 'คุณสมบัติของ ฮิดเดนฟิลด์',
		name	: 'ชื่อ',
		value	: 'ค่าตัวแปร'
	},

	// Image Dialog.
	image :
	{
		title		: 'คุณสมบัติของ รูปภาพ',
		titleButton	: 'คุณสมบัติของ ปุ่มแบบรูปภาพ',
		menu		: 'คุณสมบัติของ รูปภาพ',
		infoTab	: 'ข้อมูลของรูปภาพ',
		btnUpload	: 'อัพโหลดไฟล์ไปเก็บไว้ที่เครื่องแม่ข่าย (เซิร์ฟเวอร์)',
		url		: 'ที่อยู่อ้างอิง URL',
		upload	: 'อัพโหลดไฟล์',
		alt		: 'คำประกอบรูปภาพ',
		width		: 'ความกว้าง',
		height	: 'ความสูง',
		lockRatio	: 'กำหนดอัตราส่วน กว้าง-สูง แบบคงที่',
		resetSize	: 'กำหนดรูปเท่าขนาดจริง',
		border	: 'ขนาดขอบรูป',
		hSpace	: 'ระยะแนวนอน',
		vSpace	: 'ระยะแนวตั้ง',
		align		: 'การจัดวาง',
		alignLeft	: 'ชิดซ้าย',
		alignAbsBottom: 'ชิดด้านล่างสุด',
		alignAbsMiddle: 'กึ่งกลาง',
		alignBaseline	: 'ชิดบรรทัด',
		alignBottom	: 'ชิดด้านล่าง',
		alignMiddle	: 'กึ่งกลางแนวตั้ง',
		alignRight	: 'ชิดขวา',
		alignTextTop	: 'ใต้ตัวอักษร',
		alignTop	: 'บนสุด',
		preview	: 'หน้าเอกสารตัวอย่าง',
		alertUrl	: 'กรุณาระบุที่อยู่อ้างอิงออนไลน์ของไฟล์รูปภาพ (URL)',
		linkTab	: 'ลิ้งค์',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: 'คุณสมบัติของไฟล์ Flash',
		propertiesTab	: 'Properties', // MISSING
		title		: 'คุณสมบัติของไฟล์ Flash',
		chkPlay		: 'เล่นอัตโนมัติ Auto Play',
		chkLoop		: 'เล่นวนรอบ Loop',
		chkMenu		: 'ให้ใช้งานเมนูของ Flash',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: 'อัตราส่วน Scale',
		scaleAll		: 'แสดงให้เห็นทั้งหมด Show all',
		scaleNoBorder	: 'ไม่แสดงเส้นขอบ No Border',
		scaleFit		: 'แสดงให้พอดีกับพื้นที่ Exact Fit',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: 'การจัดวาง',
		alignLeft	: 'ชิดซ้าย',
		alignAbsBottom: 'ชิดด้านล่างสุด',
		alignAbsMiddle: 'กึ่งกลาง',
		alignBaseline	: 'ชิดบรรทัด',
		alignBottom	: 'ชิดด้านล่าง',
		alignMiddle	: 'กึ่งกลางแนวตั้ง',
		alignRight	: 'ชิดขวา',
		alignTextTop	: 'ใต้ตัวอักษร',
		alignTop	: 'บนสุด',
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
		bgcolor	: 'สีพื้นหลัง',
		width	: 'ความกว้าง',
		height	: 'ความสูง',
		hSpace	: 'ระยะแนวนอน',
		vSpace	: 'ระยะแนวตั้ง',
		validateSrc : 'กรุณาระบุที่อยู่อ้างอิงออนไลน์ (URL)',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: 'ตรวจการสะกดคำ',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: 'ไม่พบในดิกชันนารี',
		changeTo		: 'แก้ไขเป็น',
		btnIgnore		: 'ยกเว้น',
		btnIgnoreAll	: 'ยกเว้นทั้งหมด',
		btnReplace		: 'แทนที่',
		btnReplaceAll	: 'แทนที่ทั้งหมด',
		btnUndo			: 'ยกเลิก',
		noSuggestions	: '- ไม่มีคำแนะนำใดๆ -',
		progress		: 'กำลังตรวจสอบคำสะกด...',
		noMispell		: 'ตรวจสอบคำสะกดเสร็จสิ้น: ไม่พบคำสะกดผิด',
		noChanges		: 'ตรวจสอบคำสะกดเสร็จสิ้น: ไม่มีการแก้คำใดๆ',
		oneChange		: 'ตรวจสอบคำสะกดเสร็จสิ้น: แก้ไข1คำ',
		manyChanges		: 'ตรวจสอบคำสะกดเสร็จสิ้น:: แก้ไข %1 คำ',
		ieSpellDownload	: 'ไม่ได้ติดตั้งระบบตรวจสอบคำสะกด. ต้องการติดตั้งไหมครับ?'
	},

	smiley :
	{
		toolbar	: 'รูปสื่ออารมณ์',
		title	: 'แทรกสัญลักษณ์สื่ออารมณ์'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : 'ลำดับรายการแบบตัวเลข',
	bulletedlist : 'ลำดับรายการแบบสัญลักษณ์',
	indent : 'เพิ่มระยะย่อหน้า',
	outdent : 'ลดระยะย่อหน้า',

	justify :
	{
		left : 'จัดชิดซ้าย',
		center : 'จัดกึ่งกลาง',
		right : 'จัดชิดขวา',
		block : 'จัดพอดีหน้ากระดาษ'
	},

	blockquote : 'Blockquote', // MISSING

	clipboard :
	{
		title		: 'วาง',
		cutError	: 'ไม่สามารถตัดข้อความที่เลือกไว้ได้เนื่องจากการกำหนดค่าระดับความปลอดภัย. กรุณาใช้ปุ่มลัดเพื่อวางข้อความแทน (กดปุ่ม Ctrl และตัว X พร้อมกัน).',
		copyError	: 'ไม่สามารถสำเนาข้อความที่เลือกไว้ได้เนื่องจากการกำหนดค่าระดับความปลอดภัย. กรุณาใช้ปุ่มลัดเพื่อวางข้อความแทน (กดปุ่ม Ctrl และตัว C พร้อมกัน).',
		pasteMsg	: 'กรุณาใช้คีย์บอร์ดเท่านั้น โดยกดปุ๋ม (<strong>Ctrl และ V</strong>)พร้อมๆกัน และกด <strong>OK</strong>.',
		securityMsg	: 'Because of your browser security settings, the editor is not able to access your clipboard data directly. You are required to paste it again in this window.' // MISSING
	},

	pastefromword :
	{
		toolbar : 'วางสำเนาจากตัวอักษรเวิร์ด',
		title : 'วางสำเนาจากตัวอักษรเวิร์ด',
		advice : 'กรุณาใช้คีย์บอร์ดเท่านั้น โดยกดปุ๋ม (<strong>Ctrl และ V</strong>)พร้อมๆกัน และกด <strong>OK</strong>.',
		ignoreFontFace : 'ไม่สนใจ Font Face definitions',
		removeStyle : 'ลบ Styles definitions'
	},

	pasteText :
	{
		button : 'วางแบบตัวอักษรธรรมดา',
		title : 'วางแบบตัวอักษรธรรมดา'
	},

	templates :
	{
		button : 'เทมเพลต',
		title : 'เทมเพลตของส่วนเนื้อหาเว็บไซต์',
		insertOption: 'แทนที่เนื้อหาเว็บไซต์ที่เลือก',
		selectPromptMsg: 'กรุณาเลือก เทมเพลต เพื่อนำไปแก้ไขในอีดิตเตอร์<br />(เนื้อหาส่วนนี้จะหายไป):',
		emptyListMsg : '(ยังไม่มีการกำหนดเทมเพลต)'
	},

	showBlocks : 'Show Blocks', // MISSING

	stylesCombo :
	{
		label : 'ลักษณะ',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : 'รูปแบบ',
		voiceLabel : 'Format', // MISSING
		panelTitle : 'รูปแบบ',
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
		tag_div : 'Paragraph (DIV)'
	},

	font :
	{
		label : 'แบบอักษร',
		voiceLabel : 'Font', // MISSING
		panelTitle : 'แบบอักษร',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : 'ขนาด',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : 'ขนาด',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : 'สีตัวอักษร',
		bgColorTitle : 'สีพื้นหลัง',
		auto : 'สีอัตโนมัติ',
		more : 'เลือกสีอื่นๆ...'
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
