/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	function guardDomWalkerNonEmptyTextNode( node )
	{
		return ( node.type == CKEDITOR.NODE_TEXT && node.getLength() > 0 );
	}

	/**
	 * Elements which break characters been considered as sequence.
	*/
	function checkCharactersBoundary ( node )
	{
		var dtd = CKEDITOR.dtd;
		return node.isBlockBoundary(
			CKEDITOR.tools.extend( {}, dtd.$empty, dtd.$nonEditable ) );
	}

	/**
	 * Get the cursor object which represent both current character and it's dom
	 * position thing.
	 */
	var cursorStep = function()
	{
		return {
			textNode : this.textNode,
			offset : this.offset,
			character : this.textNode ?
				this.textNode.getText().charAt( this.offset ) : null,
			hitMatchBoundary : this._.matchBoundary
		};
	};

	var pages = [ 'find', 'replace' ],
		fieldsMapping = [
		[ 'txtFindFind', 'txtFindReplace' ],
		[ 'txtFindCaseChk', 'txtReplaceCaseChk' ],
		[ 'txtFindWordChk', 'txtReplaceWordChk' ],
		[ 'txtFindCyclic', 'txtReplaceCyclic' ] ];

	/**
	 * Synchronize corresponding filed values between 'replace' and 'find' pages.
	 * @param {String} currentPageId	The page id which receive values.
	 */
	function syncFieldsBetweenTabs( currentPageId )
	{
		var sourceIndex, targetIndex,
			sourceField, targetField;

		sourceIndex = currentPageId === 'find' ? 1 : 0;
		targetIndex = 1 - sourceIndex;
		var i, l = fieldsMapping.length;
		for ( i = 0 ; i < l ; i++ )
		{
			sourceField = this.getContentElement( pages[ sourceIndex ],
					fieldsMapping[ i ][ sourceIndex ] );
			targetField = this.getContentElement( pages[ targetIndex ],
					fieldsMapping[ i ][ targetIndex ] );

			targetField.setValue( sourceField.getValue() );
		}
	}

	var findDialog = function( editor, startupPage )
	{
		// Style object for highlights.
		var highlightStyle = new CKEDITOR.style( editor.config.find_highlight );

		/**
		 * Iterator which walk through the specified range char by char. By
		 * default the walking will not stop at the character boundaries, until
		 * the end of the range is encountered.
		 * @param { CKEDITOR.dom.range } range
		 * @param {Boolean} matchWord Whether the walking will stop at character boundary.
		 */
		var characterWalker = function( range , matchWord )
		{
			var walker =
				new CKEDITOR.dom.walker( range );
			walker[ matchWord ? 'guard' : 'evaluator' ] =
				guardDomWalkerNonEmptyTextNode;
			walker.breakOnFalse = true;

			this._ = {
				matchWord : matchWord,
				walker : walker,
				matchBoundary : false
			};
		};

		characterWalker.prototype = {
			next : function()
			{
				return this.move();
			},

			back : function()
			{
				return this.move( true );
			},

			move : function( rtl )
			{
				var currentTextNode = this.textNode;
				// Already at the end of document, no more character available.
				if(  currentTextNode === null )
					return cursorStep.call( this );

				this._.matchBoundary = false;

				// There are more characters in the text node, step forward.
				if( currentTextNode
				    && rtl
					&& this.offset > 0 )
				{
					this.offset--;
					return cursorStep.call( this );
				}
				else if( currentTextNode
					&& this.offset < currentTextNode.getLength() - 1 )
				{
					this.offset++;
					return cursorStep.call( this );
				}
				else
				{
					currentTextNode = null;
					// At the end of the text node, walking foward for the next.
					while ( !currentTextNode )
					{
						currentTextNode =
							this._.walker[ rtl ? 'previous' : 'next' ].call( this._.walker );

						// Stop searching if we're need full word match OR
						// already reach document end.
						if ( this._.matchWord && !currentTextNode
							 ||this._.walker._.end )
							break;

						// Marking as match character boundaries.
						if( !currentTextNode
						   && checkCharactersBoundary( this._.walker.current ) )
							this._.matchBoundary = true;

					}
					// Found a fresh text node.
					this.textNode = currentTextNode;
					if ( currentTextNode )
						this.offset = rtl ? currentTextNode.getLength() - 1 : 0;
					else
						this.offset = 0;
				}

				return cursorStep.call( this );
			}

		};

		/**
		 * A range of cursors which represent a trunk of characters which try to
		 * match, it has the same length as the pattern  string.
		 */
		var characterRange = function( characterWalker, rangeLength )
		{
			this._ = {
				walker : characterWalker,
				cursors : [],
				rangeLength : rangeLength,
				highlightRange : null,
				isMatched : false
			};
		};

		characterRange.prototype = {
			/**
			 * Translate this range to {@link CKEDITOR.dom.range}
			 */
			toDomRange : function()
			{
				var cursors = this._.cursors;
				if ( cursors.length < 1 )
					return null;

				var first = cursors[0],
					last = cursors[ cursors.length - 1 ],
					range = new CKEDITOR.dom.range( editor.document );

				range.setStart( first.textNode, first.offset );
				range.setEnd( last.textNode, last.offset + 1 );
				return range;
			},
			/**
			 * Reflect the latest changes from dom range.
			 */
			updateFromDomRange : function( domRange )
			{
				var cursor,
						walker = new characterWalker( domRange );
				this._.cursors = [];
				do
				{
					cursor = walker.next();
					if ( cursor.character )
						this._.cursors.push( cursor );
				}
				while ( cursor.character );
				this._.rangeLength = this._.cursors.length;
			},

			setMatched : function()
			{
				this._.isMatched = true;
			},

			clearMatched : function()
			{
				this._.isMatched = false;
			},

			isMatched : function()
			{
				return this._.isMatched;
			},

			/**
			 * Hightlight the current matched chunk of text.
			 */
			highlight : function()
			{
				// Do not apply if nothing is found.
				if ( this._.cursors.length < 1 )
					return;

				// Remove the previous highlight if there's one.
				if ( this._.highlightRange )
					this.removeHighlight();

				// Apply the highlight.
				var range = this.toDomRange();
				highlightStyle.applyToRange( range );
				this._.highlightRange = range;

				// Scroll the editor to the highlighted area.
				var element = range.startContainer;
				if ( element.type != CKEDITOR.NODE_ELEMENT )
					element = element.getParent();
				element.scrollIntoView();

				// Update the character cursors.
				this.updateFromDomRange( range );
			},

			/**
			 * Remove highlighted find result.
			 */
			removeHighlight : function()
			{
				if ( !this._.highlightRange )
					return;

				highlightStyle.removeFromRange( this._.highlightRange );
				this.updateFromDomRange( this._.highlightRange );
				this._.highlightRange = null;
			},

			moveBack : function()
			{
				var retval = this._.walker.back(),
					cursors = this._.cursors;

				if ( retval.hitMatchBoundary )
					this._.cursors = cursors = [];

				cursors.unshift( retval );
				if ( cursors.length > this._.rangeLength )
					cursors.pop();

				return retval;
			},

			moveNext : function()
			{
				var retval = this._.walker.next(),
					cursors = this._.cursors;

				// Clear the cursors queue if we've crossed a match boundary.
				if ( retval.hitMatchBoundary )
					this._.cursors = cursors = [];

				cursors.push( retval );
				if ( cursors.length > this._.rangeLength )
					cursors.shift();

				return retval;
			},

			getEndCharacter : function()
			{
				var cursors = this._.cursors;
				if ( cursors.length < 1 )
					return null;

				return cursors[ cursors.length - 1 ].character;
			},

			getNextCharacterRange : function( maxLength )
			{
				var lastCursor,
						cursors = this._.cursors;
				if ( !( lastCursor = cursors[ cursors.length - 1 ] ) )
					return null;
				return new characterRange(
										new characterWalker(
											getRangeAfterCursor( lastCursor ) ),
										maxLength );
			},

			getCursors : function()
			{
				return this._.cursors;
			}
		};


		// The remaining document range after the character cursor.
		function getRangeAfterCursor( cursor , inclusive )
		{
			var range = new CKEDITOR.dom.range();
			range.setStart( cursor.textNode,
						   ( inclusive ? cursor.offset : cursor.offset + 1 ) );
			range.setEndAt( editor.document.getBody(),
							CKEDITOR.POSITION_BEFORE_END );
			return range;
		}

		// The document range before the character cursor.
		function getRangeBeforeCursor( cursor )
		{
			var range = new CKEDITOR.dom.range();
			range.setStartAt( editor.document.getBody(),
							CKEDITOR.POSITION_AFTER_START );
			range.setEnd( cursor.textNode, cursor.offset );
			return range;
		}

		var KMP_NOMATCH = 0,
			KMP_ADVANCED = 1,
			KMP_MATCHED = 2;
		/**
		 * Examination the occurrence of a word which implement KMP algorithm.
		 */
		var kmpMatcher = function( pattern, ignoreCase )
		{
			var overlap = [ -1 ];
			if ( ignoreCase )
				pattern = pattern.toLowerCase();
			for ( var i = 0 ; i < pattern.length ; i++ )
			{
				overlap.push( overlap[i] + 1 );
				while ( overlap[ i + 1 ] > 0
					&& pattern.charAt( i ) != pattern
						.charAt( overlap[ i + 1 ] - 1 ) )
					overlap[ i + 1 ] = overlap[ overlap[ i + 1 ] - 1 ] + 1;
			}

			this._ = {
				overlap : overlap,
				state : 0,
				ignoreCase : !!ignoreCase,
				pattern : pattern
			};
		};

		kmpMatcher.prototype =
		{
			feedCharacter : function( c )
			{
				if ( this._.ignoreCase )
					c = c.toLowerCase();

				while ( true )
				{
					if ( c == this._.pattern.charAt( this._.state ) )
					{
						this._.state++;
						if ( this._.state == this._.pattern.length )
						{
							this._.state = 0;
							return KMP_MATCHED;
						}
						return KMP_ADVANCED;
					}
					else if ( !this._.state )
						return KMP_NOMATCH;
					else
						this._.state = this._.overlap[ this._.state ];
				}

				return null;
			},

			reset : function()
			{
				this._.state = 0;
			}
		};

		var wordSeparatorRegex =
		/[.,"'?!;: \u0085\u00a0\u1680\u280e\u2028\u2029\u202f\u205f\u3000]/;

		var isWordSeparator = function( c )
		{
			if ( !c )
				return true;
			var code = c.charCodeAt( 0 );
			return ( code >= 9 && code <= 0xd )
				|| ( code >= 0x2000 && code <= 0x200a )
				|| wordSeparatorRegex.test( c );
		};

		var finder = {
			searchRange : null,
			matchRange : null,
			find : function( pattern, matchCase, matchWord, matchCyclic, highlightMatched, cyclicRerun )
			{
				if( !this.matchRange )
					this.matchRange =
						new characterRange(
							new characterWalker( this.searchRange ),
							pattern.length );
				else
				{
					this.matchRange.removeHighlight();
					this.matchRange = this.matchRange.getNextCharacterRange( pattern.length );
				}

				var matcher = new kmpMatcher( pattern, !matchCase ),
					matchState = KMP_NOMATCH,
					character = '%';

				while ( character !== null )
				{
					this.matchRange.moveNext();
					while ( ( character = this.matchRange.getEndCharacter() ) )
					{
						matchState = matcher.feedCharacter( character );
						if ( matchState == KMP_MATCHED )
							break;
						if ( this.matchRange.moveNext().hitMatchBoundary )
							matcher.reset();
					}

					if ( matchState == KMP_MATCHED )
					{
						if ( matchWord )
						{
							var cursors = this.matchRange.getCursors(),
								tail = cursors[ cursors.length - 1 ],
								head = cursors[ 0 ];

							var headWalker = new characterWalker( getRangeBeforeCursor( head ), true ),
								tailWalker = new characterWalker( getRangeAfterCursor( tail ), true );

							if ( ! ( isWordSeparator( headWalker.back().character )
										&& isWordSeparator( tailWalker.next().character ) ) )
								continue;
						}
						this.matchRange.setMatched();
						if ( highlightMatched !== false )
							this.matchRange.highlight();
						return true;
					}
				}

				this.matchRange.clearMatched();
				this.matchRange.removeHighlight();
				// Clear current session and restart with the default search
				// range.
				// Re-run the finding once for cyclic.(#3517)
				if ( matchCyclic && !cyclicRerun )
				{
					this.searchRange = getSearchRange( true );
					this.matchRange = null;
					return arguments.callee.apply( this,
						Array.prototype.slice.call( arguments ).concat( [ true ] ) );
				}

				return false;
			},

			/**
			 * Record how much replacement occurred toward one replacing.
			 */
			replaceCounter : 0,

			replace : function( dialog, pattern, newString, matchCase, matchWord,
				matchCyclic , isReplaceAll )
			{
				// Successiveness of current replace/find.
				var result = false;

				// 1. Perform the replace when there's already a match here.
				// 2. Otherwise perform the find but don't replace it immediately.
				if ( this.matchRange && this.matchRange.isMatched()
						&& !this.matchRange._.isReplaced )
				{
					// Turn off highlight for a while when saving snapshots.
					this.matchRange.removeHighlight();
					var domRange = this.matchRange.toDomRange();
					var text = editor.document.createText( newString );
					if ( !isReplaceAll )
					{
						// Save undo snaps before and after the replacement.
						var selection = editor.getSelection();
						selection.selectRanges( [ domRange ] );
						editor.fire( 'saveSnapshot' );
					}
					domRange.deleteContents();
					domRange.insertNode( text );
					if ( !isReplaceAll )
					{
						selection.selectRanges( [ domRange ] );
						editor.fire( 'saveSnapshot' );
					}
					this.matchRange.updateFromDomRange( domRange );
					if ( !isReplaceAll )
						this.matchRange.highlight();
					this.matchRange._.isReplaced = true;
					this.replaceCounter++;
					result = true;
				}
				else
					result = this.find( pattern, matchCase, matchWord, matchCyclic, !isReplaceAll );

				return result;
			}
		};

		/**
		 * The range in which find/replace happened, receive from user
		 * selection prior.
		 */
		function getSearchRange( isDefault )
		{
			var searchRange,
				sel = editor.getSelection(),
				body = editor.document.getBody();
			if ( sel && !isDefault )
			{
				searchRange = sel.getRanges()[ 0 ].clone();
				searchRange.collapse( true );
			}
			else
			{
				searchRange = new CKEDITOR.dom.range();
				searchRange.setStartAt( body, CKEDITOR.POSITION_AFTER_START );
			}
			searchRange.setEndAt( body, CKEDITOR.POSITION_BEFORE_END );
			return searchRange;
		}

		return {
			title : editor.lang.findAndReplace.title,
			resizable : CKEDITOR.DIALOG_RESIZE_NONE,
			minWidth : 350,
			minHeight : 165,
			buttons : [ CKEDITOR.dialog.cancelButton ],		//Cancel button only.
			contents : [
				{
					id : 'find',
					label : editor.lang.findAndReplace.find,
					title : editor.lang.findAndReplace.find,
					accessKey : '',
					elements : [
						{
							type : 'hbox',
							widths : [ '230px', '90px' ],
							children :
							[
								{
									type : 'text',
									id : 'txtFindFind',
									label : editor.lang.findAndReplace.findWhat,
									isChanged : false,
									labelLayout : 'horizontal',
									accessKey : 'F'
								},
								{
									type : 'button',
									align : 'left',
									style : 'width:100%',
									label : editor.lang.findAndReplace.find,
									onClick : function()
									{
										var dialog = this.getDialog();
										if ( !finder.find( dialog.getValueOf( 'find', 'txtFindFind' ),
													dialog.getValueOf( 'find', 'txtFindCaseChk' ),
													dialog.getValueOf( 'find', 'txtFindWordChk' ),
													dialog.getValueOf( 'find', 'txtFindCyclic' ) ) )
											alert( editor.lang.findAndReplace
												.notFoundMsg );
									}
								}
							]
						},
						{
							type : 'vbox',
							padding : 0,
							children :
							[
								{
									type : 'checkbox',
									id : 'txtFindCaseChk',
									isChanged : false,
									style : 'margin-top:28px',
									label : editor.lang.findAndReplace.matchCase
								},
								{
									type : 'checkbox',
									id : 'txtFindWordChk',
									isChanged : false,
									label : editor.lang.findAndReplace.matchWord
								},
								{
									type : 'checkbox',
									id : 'txtFindCyclic',
									isChanged : false,
									'default' : true,
									label : editor.lang.findAndReplace.matchCyclic
								}
							]
						}
					]
				},
				{
					id : 'replace',
					label : editor.lang.findAndReplace.replace,
					accessKey : 'M',
					elements : [
						{
							type : 'hbox',
							widths : [ '230px', '90px' ],
							children :
							[
								{
									type : 'text',
									id : 'txtFindReplace',
									label : editor.lang.findAndReplace.findWhat,
									isChanged : false,
									labelLayout : 'horizontal',
									accessKey : 'F'
								},
								{
									type : 'button',
									align : 'left',
									style : 'width:100%',
									label : editor.lang.findAndReplace.replace,
									onClick : function()
									{
										var dialog = this.getDialog();
										if ( !finder.replace( dialog,
													dialog.getValueOf( 'replace', 'txtFindReplace' ),
													dialog.getValueOf( 'replace', 'txtReplace' ),
													dialog.getValueOf( 'replace', 'txtReplaceCaseChk' ),
													dialog.getValueOf( 'replace', 'txtReplaceWordChk' ),
													dialog.getValueOf( 'replace', 'txtReplaceCyclic' ) ) )
											alert( editor.lang.findAndReplace
												.notFoundMsg );
									}
								}
							]
						},
						{
							type : 'hbox',
							widths : [ '230px', '90px' ],
							children :
							[
								{
									type : 'text',
									id : 'txtReplace',
									label : editor.lang.findAndReplace.replaceWith,
									isChanged : false,
									labelLayout : 'horizontal',
									accessKey : 'R'
								},
								{
									type : 'button',
									align : 'left',
									style : 'width:100%',
									label : editor.lang.findAndReplace.replaceAll,
									isChanged : false,
									onClick : function()
									{
										var dialog = this.getDialog();
										var replaceNums;

										finder.replaceCounter = 0;

										// Scope to full document.
										finder.searchRange = getSearchRange( true );
										if ( finder.matchRange )
										{
											finder.matchRange.removeHighlight();
											finder.matchRange = null;
										}
										editor.fire( 'saveSnapshot' );
										while( finder.replace( dialog,
											dialog.getValueOf( 'replace', 'txtFindReplace' ),
											dialog.getValueOf( 'replace', 'txtReplace' ),
											dialog.getValueOf( 'replace', 'txtReplaceCaseChk' ),
											dialog.getValueOf( 'replace', 'txtReplaceWordChk' ),
											false, true ) )
										{ /*jsl:pass*/ }

										if ( finder.replaceCounter )
										{
											alert( editor.lang.findAndReplace.replaceSuccessMsg.replace( /%1/, finder.replaceCounter ) );
											editor.fire( 'saveSnapshot' );
										}
										else
											alert( editor.lang.findAndReplace.notFoundMsg );
									}
								}
							]
						},
						{
							type : 'vbox',
							padding : 0,
							children :
							[
								{
									type : 'checkbox',
									id : 'txtReplaceCaseChk',
									isChanged : false,
									label : editor.lang.findAndReplace
										.matchCase
								},
								{
									type : 'checkbox',
									id : 'txtReplaceWordChk',
									isChanged : false,
									label : editor.lang.findAndReplace
										.matchWord
								},
								{
									type : 'checkbox',
									id : 'txtReplaceCyclic',
									isChanged : false,
									'default' : true,
									label : editor.lang.findAndReplace
										.matchCyclic
								}
							]
						}
					]
				}
			],
			onLoad : function()
			{
				var dialog = this;

				//keep track of the current pattern field in use.
				var patternField, wholeWordChkField;

				//Ignore initial page select on dialog show
				var isUserSelect = false;
				this.on('hide', function()
						{
							isUserSelect = false;
						} );
				this.on('show', function()
						{
							isUserSelect = true;
						} );

				this.selectPage = CKEDITOR.tools.override( this.selectPage, function( originalFunc )
					{
						return function( pageId )
						{
							originalFunc.call( dialog, pageId );

							var currPage = dialog._.tabs[ pageId ];
							var patternFieldInput, patternFieldId, wholeWordChkFieldId;
							patternFieldId = pageId === 'find' ? 'txtFindFind' : 'txtFindReplace';
							wholeWordChkFieldId = pageId === 'find' ? 'txtFindWordChk' : 'txtReplaceWordChk';

							patternField = dialog.getContentElement( pageId,
								patternFieldId );
							wholeWordChkField = dialog.getContentElement( pageId,
								wholeWordChkFieldId );

							// prepare for check pattern text filed 'keyup' event
							if ( !currPage.initialized )
							{
								patternFieldInput = CKEDITOR.document
									.getById( patternField._.inputId );
								currPage.initialized = true;
							}

							if( isUserSelect )
								// synchronize fields on tab switch.
								syncFieldsBetweenTabs.call( this, pageId );
						};
					} );

			},
			onShow : function()
			{
				// Establish initial searching start position.
				finder.searchRange = getSearchRange();

				if ( startupPage == 'replace' )
					this.getContentElement( 'replace', 'txtFindReplace' ).focus();
				else
					this.getContentElement( 'find', 'txtFindFind' ).focus();
			},
			onHide : function()
			{
				if ( finder.matchRange && finder.matchRange.isMatched() )
				{
					finder.matchRange.removeHighlight();
					editor.focus();
					editor.getSelection().selectRanges(
						[ finder.matchRange.toDomRange() ] );
				}

				// Clear current session before dialog close
				delete finder.matchRange;
			}
		};
	};

	CKEDITOR.dialog.add( 'find', function( editor )
		{
			return findDialog( editor, 'find' );
		});

	CKEDITOR.dialog.add( 'replace', function( editor )
		{
			return findDialog( editor, 'replace' );
		});
})();
