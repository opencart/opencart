/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @fileOverview Defines the {@link CKEDITOR.lang} object, for the
 * Korean language.
 */

/**#@+
   @type String
   @example
*/

/**
 * Constains the dictionary of language entries.
 * @namespace
 */
CKEDITOR.lang['ko'] =
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
	source			: '소스',
	newPage			: '새 문서',
	save			: '저장하기',
	preview			: '미리보기',
	cut				: '잘라내기',
	copy			: '복사하기',
	paste			: '붙여넣기',
	print			: '인쇄하기',
	underline		: '밑줄',
	bold			: '진하게',
	italic			: '이텔릭',
	selectAll		: '전체선택',
	removeFormat	: '포맷 지우기',
	strike			: '취소선',
	subscript		: '아래 첨자',
	superscript		: '위 첨자',
	horizontalrule	: '수평선 삽입',
	pagebreak		: 'Insert Page Break for Printing', // MISSING
	unlink			: '링크 삭제',
	undo			: '취소',
	redo			: '재실행',

	// Common messages and labels.
	common :
	{
		browseServer	: '서버 보기',
		url				: 'URL',
		protocol		: '프로토콜',
		upload			: '업로드',
		uploadSubmit	: '서버로 전송',
		image			: '이미지',
		flash			: '플래쉬',
		form			: '폼',
		checkbox		: '체크박스',
		radio		: '라디오버튼',
		textField		: '입력필드',
		textarea		: '입력영역',
		hiddenField		: '숨김필드',
		button			: '버튼',
		select	: '펼침목록',
		imageButton		: '이미지버튼',
		notSet			: '<설정되지 않음>',
		id				: 'ID',
		name			: 'Name',
		langDir			: '쓰기 방향',
		langDirLtr		: '왼쪽에서 오른쪽 (LTR)',
		langDirRtl		: '오른쪽에서 왼쪽 (RTL)',
		langCode		: '언어 코드',
		longDescr		: 'URL 설명',
		cssClass		: 'Stylesheet Classes',
		advisoryTitle	: 'Advisory Title',
		cssStyle		: 'Style',
		ok				: '예',
		cancel			: '아니오',
		generalTab		: 'General', // MISSING
		advancedTab		: '자세히',
		validateNumberFailed	: 'This value is not a number.', // MISSING
		confirmNewPage	: 'Any unsaved changes to this content will be lost. Are you sure you want to load new page?', // MISSING
		confirmCancel	: 'Some of the options have been changed. Are you sure to close the dialog?', // MISSING

		// Put the voice-only part of the label in the span.
		unavailable		: '%1<span class="cke_accessibility">, unavailable</span>' // MISSING
	},

	// Special char dialog.
	specialChar		:
	{
		toolbar		: '특수문자 삽입',
		title		: '특수문자 선택'
	},

	// Link dialog.
	link :
	{
		toolbar		: '링크 삽입/변경',
		menu		: '링크 수정',
		title		: '링크',
		info		: '링크 정보',
		target		: '타겟',
		upload		: '업로드',
		advanced	: '자세히',
		type		: '링크 종류',
		toAnchor	: '책갈피',
		toEmail		: '이메일',
		target		: '타겟',
		targetNotSet	: '<설정되지 않음>',
		targetFrame	: '<프레임>',
		targetPopup	: '<팝업창>',
		targetNew	: '새 창 (_blank)',
		targetTop	: '최 상위 창 (_top)',
		targetSelf	: '현재 창 (_self)',
		targetParent	: '부모 창 (_parent)',
		targetFrameName	: '타겟 프레임 이름',
		targetPopupName	: '팝업창 이름',
		popupFeatures	: '팝업창 설정',
		popupResizable	: 'Resizable', // MISSING
		popupStatusBar	: '상태바',
		popupLocationBar	: '주소표시줄',
		popupToolbar	: '툴바',
		popupMenuBar	: '메뉴바',
		popupFullScreen	: '전체화면 (IE)',
		popupScrollBars	: '스크롤바',
		popupDependent	: 'Dependent (Netscape)',
		popupWidth		: '너비',
		popupLeft		: '왼쪽 위치',
		popupHeight		: '높이',
		popupTop		: '윗쪽 위치',
		id				: 'Id', // MISSING
		langDir			: '쓰기 방향',
		langDirNotSet	: '<설정되지 않음>',
		langDirLTR		: '왼쪽에서 오른쪽 (LTR)',
		langDirRTL		: '오른쪽에서 왼쪽 (RTL)',
		acccessKey		: '엑세스 키',
		name			: 'Name',
		langCode		: '쓰기 방향',
		tabIndex		: '탭 순서',
		advisoryTitle	: 'Advisory Title',
		advisoryContentType	: 'Advisory Content Type',
		cssClasses		: 'Stylesheet Classes',
		charset			: 'Linked Resource Charset',
		styles			: 'Style',
		selectAnchor	: '책갈피 선택',
		anchorName		: '책갈피 이름',
		anchorId		: '책갈피 ID',
		emailAddress	: '이메일 주소',
		emailSubject	: '제목',
		emailBody		: '내용',
		noAnchors		: '(문서에 책갈피가 없습니다.)',
		noUrl			: '링크 URL을 입력하십시요.',
		noEmail			: '이메일주소를 입력하십시요.'
	},

	// Anchor dialog
	anchor :
	{
		toolbar		: '책갈피 삽입/변경',
		menu		: '책갈피 속성',
		title		: '책갈피 속성',
		name		: '책갈피 이름',
		errorName	: '책갈피 이름을 입력하십시요.'
	},

	// Find And Replace Dialog
	findAndReplace :
	{
		title				: '찾기 & 바꾸기',
		find				: '찾기',
		replace				: '바꾸기',
		findWhat			: '찾을 문자열:',
		replaceWith			: '바꿀 문자열:',
		notFoundMsg			: '문자열을 찾을 수 없습니다.',
		matchCase			: '대소문자 구분',
		matchWord			: '온전한 단어',
		matchCyclic			: 'Match cyclic', // MISSING
		replaceAll			: '모두 바꾸기',
		replaceSuccessMsg	: '%1 occurrence(s) replaced.' // MISSING
	},

	// Table Dialog
	table :
	{
		toolbar		: '표',
		title		: '표 설정',
		menu		: '표 설정',
		deleteTable	: '표 삭제',
		rows		: '가로줄',
		columns		: '세로줄',
		border		: '테두리 크기',
		align		: '정렬',
		alignNotSet	: '<설정되지 않음>',
		alignLeft	: '왼쪽',
		alignCenter	: '가운데',
		alignRight	: '오른쪽',
		width		: '너비',
		widthPx		: '픽셀',
		widthPc		: '퍼센트',
		height		: '높이',
		cellSpace	: '셀 간격',
		cellPad		: '셀 여백',
		caption		: '캡션',
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
			menu			: '셀/칸(Cell)',
			insertBefore	: '앞에 셀/칸 삽입',
			insertAfter		: '뒤에 셀/칸 삽입',
			deleteCell		: '셀 삭제',
			merge			: '셀 합치기',
			mergeRight		: '오른쪽 뭉치기',
			mergeDown		: '왼쪽 뭉치기',
			splitHorizontal	: '수평 나누기',
			splitVertical	: '수직 나누기',
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
			menu			: '행(Row)',
			insertBefore	: '앞에 행 삽입',
			insertAfter		: '뒤에 행 삽입',
			deleteRow		: '가로줄 삭제'
		},

		column :
		{
			menu			: '열(Column)',
			insertBefore	: '앞에 열 삽입',
			insertAfter		: '뒤에 열 삽입',
			deleteColumn	: '세로줄 삭제'
		}
	},

	// Button Dialog.
	button :
	{
		title		: '버튼 속성',
		text		: '버튼글자(값)',
		type		: '버튼종류',
		typeBtn		: 'Button', // MISSING
		typeSbm		: 'Submit', // MISSING
		typeRst		: 'Reset' // MISSING
	},

	// Checkbox and Radio Button Dialogs.
	checkboxAndRadio :
	{
		checkboxTitle : '체크박스 속성',
		radioTitle	: '라디오버튼 속성',
		value		: '값',
		selected	: '선택됨'
	},

	// Form Dialog.
	form :
	{
		title		: '폼 속성',
		menu		: '폼 속성',
		action		: '실행경로(Action)',
		method		: '방법(Method)',
		encoding	: 'Encoding', // MISSING
		target		: '타겟',
		targetNotSet	: '<설정되지 않음>',
		targetNew	: '새 창 (_blank)',
		targetTop	: '최 상위 창 (_top)',
		targetSelf	: '현재 창 (_self)',
		targetParent	: '부모 창 (_parent)'
	},

	// Select Field Dialog.
	select :
	{
		title		: '펼침목록 속성',
		selectInfo	: '정보',
		opAvail		: '선택옵션',
		value		: '값',
		size		: '세로크기',
		lines		: '줄',
		chkMulti	: '여러항목 선택 허용',
		opText		: '이름',
		opValue		: '값',
		btnAdd		: '추가',
		btnModify	: '변경',
		btnUp		: '위로',
		btnDown		: '아래로',
		btnSetValue : '선택된것으로 설정',
		btnDelete	: '삭제'
	},

	// Textarea Dialog.
	textarea :
	{
		title		: '입력영역 속성',
		cols		: '칸수',
		rows		: '줄수'
	},

	// Text Field Dialog.
	textfield :
	{
		title		: '입력필드 속성',
		name		: '이름',
		value		: '값',
		charWidth	: '글자 너비',
		maxChars	: '최대 글자수',
		type		: '종류',
		typeText	: '문자열',
		typePass	: '비밀번호'
	},

	// Hidden Field Dialog.
	hidden :
	{
		title	: '숨김필드 속성',
		name	: '이름',
		value	: '값'
	},

	// Image Dialog.
	image :
	{
		title		: '이미지 설정',
		titleButton	: '이미지버튼 속성',
		menu		: '이미지 설정',
		infoTab	: '이미지 정보',
		btnUpload	: '서버로 전송',
		url		: 'URL',
		upload	: '업로드',
		alt		: '이미지 설명',
		width		: '너비',
		height	: '높이',
		lockRatio	: '비율 유지',
		resetSize	: '원래 크기로',
		border	: '테두리',
		hSpace	: '수평여백',
		vSpace	: '수직여백',
		align		: '정렬',
		alignLeft	: '왼쪽',
		alignAbsBottom: '줄아래(Abs Bottom)',
		alignAbsMiddle: '줄중간(Abs Middle)',
		alignBaseline	: '기준선',
		alignBottom	: '아래',
		alignMiddle	: '중간',
		alignRight	: '오른쪽',
		alignTextTop	: '글자상단',
		alignTop	: '위',
		preview	: '미리보기',
		alertUrl	: '이미지 URL을 입력하십시요',
		linkTab	: '링크',
		button2Img	: 'Do you want to transform the selected image button on a simple image?', // MISSING
		img2Button	: 'Do you want to transform the selected image on a image button?', // MISSING
		urlMissing : 'Image source URL is missing.' // MISSING
	},

	// Flash Dialog
	flash :
	{
		properties		: '플래쉬 속성',
		propertiesTab	: 'Properties', // MISSING
		title		: '플래쉬 등록정보',
		chkPlay		: '자동재생',
		chkLoop		: '반복',
		chkMenu		: '플래쉬메뉴 가능',
		chkFull		: 'Allow Fullscreen', // MISSING
 		scale		: '영역',
		scaleAll		: '모두보기',
		scaleNoBorder	: '경계선없음',
		scaleFit		: '영역자동조절',
		access			: 'Script Access', // MISSING
		accessAlways	: 'Always', // MISSING
		accessSameDomain	: 'Same domain', // MISSING
		accessNever	: 'Never', // MISSING
		align		: '정렬',
		alignLeft	: '왼쪽',
		alignAbsBottom: '줄아래(Abs Bottom)',
		alignAbsMiddle: '줄중간(Abs Middle)',
		alignBaseline	: '기준선',
		alignBottom	: '아래',
		alignMiddle	: '중간',
		alignRight	: '오른쪽',
		alignTextTop	: '글자상단',
		alignTop	: '위',
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
		bgcolor	: '배경 색상',
		width	: '너비',
		height	: '높이',
		hSpace	: '수평여백',
		vSpace	: '수직여백',
		validateSrc : '링크 URL을 입력하십시요.',
		validateWidth : 'Width must be a number.', // MISSING
		validateHeight : 'Height must be a number.', // MISSING
		validateHSpace : 'HSpace must be a number.', // MISSING
		validateVSpace : 'VSpace must be a number.' // MISSING
	},

	// Speller Pages Dialog
	spellCheck :
	{
		toolbar			: '철자검사',
		title			: 'Spell Check', // MISSING
		notAvailable	: 'Sorry, but service is unavailable now.', // MISSING
		errorLoading	: 'Error loading application service host: %s.', // MISSING
		notInDic		: '사전에 없는 단어',
		changeTo		: '변경할 단어',
		btnIgnore		: '건너뜀',
		btnIgnoreAll	: '모두 건너뜀',
		btnReplace		: '변경',
		btnReplaceAll	: '모두 변경',
		btnUndo			: '취소',
		noSuggestions	: '- 추천단어 없음 -',
		progress		: '철자검사를 진행중입니다...',
		noMispell		: '철자검사 완료: 잘못된 철자가 없습니다.',
		noChanges		: '철자검사 완료: 변경된 단어가 없습니다.',
		oneChange		: '철자검사 완료: 단어가 변경되었습니다.',
		manyChanges		: '철자검사 완료: %1 단어가 변경되었습니다.',
		ieSpellDownload	: '철자 검사기가 철치되지 않았습니다. 지금 다운로드하시겠습니까?'
	},

	smiley :
	{
		toolbar	: '아이콘',
		title	: '아이콘 삽입'
	},

	elementsPath :
	{
		eleTitle : '%1 element' // MISSING
	},

	numberedlist : '순서있는 목록',
	bulletedlist : '순서없는 목록',
	indent : '들여쓰기',
	outdent : '내어쓰기',

	justify :
	{
		left : '왼쪽 정렬',
		center : '가운데 정렬',
		right : '오른쪽 정렬',
		block : '양쪽 맞춤'
	},

	blockquote : 'Blockquote', // MISSING

	clipboard :
	{
		title		: '붙여넣기',
		cutError	: '브라우저의 보안설정때문에 잘라내기 기능을 실행할 수 없습니다. 키보드 명령을 사용하십시요. (Ctrl+X).',
		copyError	: '브라우저의 보안설정때문에 복사하기 기능을 실행할 수 없습니다. 키보드 명령을 사용하십시요.  (Ctrl+C).',
		pasteMsg	: '키보드의 (<STRONG>Ctrl+V</STRONG>) 를 이용해서 상자안에 붙여넣고 <STRONG>OK</STRONG> 를 누르세요.',
		securityMsg	: '브러우저 보안 설정으로 인해, 클립보드의 자료를 직접 접근할 수 없습니다. 이 창에 다시 붙여넣기 하십시오.'
	},

	pastefromword :
	{
		toolbar : 'MS Word 형식에서 붙여넣기',
		title : 'MS Word 형식에서 붙여넣기',
		advice : '키보드의 (<STRONG>Ctrl+V</STRONG>) 를 이용해서 상자안에 붙여넣고 <STRONG>OK</STRONG> 를 누르세요.',
		ignoreFontFace : '폰트 설정 무시',
		removeStyle : '스타일 정의 제거'
	},

	pasteText :
	{
		button : '텍스트로 붙여넣기',
		title : '텍스트로 붙여넣기'
	},

	templates :
	{
		button : '템플릿',
		title : '내용 템플릿',
		insertOption: '현재 내용 바꾸기',
		selectPromptMsg: '에디터에서 사용할 템플릿을 선택하십시요.<br>(지금까지 작성된 내용은 사라집니다.):',
		emptyListMsg : '(템플릿이 없습니다.)'
	},

	showBlocks : 'Show Blocks', // MISSING

	stylesCombo :
	{
		label : '스타일',
		voiceLabel : 'Styles', // MISSING
		panelVoiceLabel : 'Select a style', // MISSING
		panelTitle1 : 'Block Styles', // MISSING
		panelTitle2 : 'Inline Styles', // MISSING
		panelTitle3 : 'Object Styles' // MISSING
	},

	format :
	{
		label : '포맷',
		voiceLabel : 'Format', // MISSING
		panelTitle : '포맷',
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
		tag_div : 'Normal (DIV)' // MISSING
	},

	font :
	{
		label : '폰트',
		voiceLabel : 'Font', // MISSING
		panelTitle : '폰트',
		panelVoiceLabel : 'Select a font' // MISSING
	},

	fontSize :
	{
		label : '글자 크기',
		voiceLabel : 'Font Size', // MISSING
		panelTitle : '글자 크기',
		panelVoiceLabel : 'Select a font size' // MISSING
	},

	colorButton :
	{
		textColorTitle : '글자 색상',
		bgColorTitle : '배경 색상',
		auto : '기본색상',
		more : '색상선택...'
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
