/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	var blurCommand =
		{
			exec : function( editor )
			{
				editor.container.focusNext( true );
			}
		};

	var blurBackCommand =
		{
			exec : function( editor )
			{
				editor.container.focusPrevious( true );
			}
		};

	CKEDITOR.plugins.add( 'tab',
	{
		requires : [ 'keystrokes' ],

		init : function( editor )
		{
			// Register the keystrokes.
			var keystrokes = editor.keystrokeHandler.keystrokes;
			keystrokes[ 9 /* TAB */ ] = 'tab';
			keystrokes[ CKEDITOR.SHIFT + 9 /* TAB */ ] = 'shiftTab';

			var tabSpaces = editor.config.tabSpaces,
				tabText = '';

			while ( tabSpaces-- )
				tabText += '\xa0';

			// Register the "tab" and "shiftTab" commands.
			editor.addCommand( 'tab',
				{
					exec : function( editor )
					{
						// Fire the "tab" event, making it possible to
						// customize the TAB key behavior on specific cases.
						if ( !editor.fire( 'tab' ) )
						{
							if ( tabText.length > 0 )
								editor.insertHtml( tabText );
							else
							{
								// All browsers jump to the next field on TAB,
								// except Safari, so we have to do that manually
								// here.
								/// https://bugs.webkit.org/show_bug.cgi?id=20597
								return editor.execCommand( 'blur' );
							}
						}

						return true;
					}
				});

			editor.addCommand( 'shiftTab',
				{
					exec : function( editor )
					{
						// Fire the "tab" event, making it possible to
						// customize the TAB key behavior on specific cases.
						if ( !editor.fire( 'shiftTab' ) )
							return editor.execCommand( 'blurBack' );

						return true;
					}
				});

			editor.addCommand( 'blur', blurCommand );
			editor.addCommand( 'blurBack', blurBackCommand );
		}
	});
})();

/**
 * Moves the UI focus to the element following this element in the tabindex
 * order.
 * @example
 * var element = CKEDITOR.document.getById( 'example' );
 * element.focusNext();
 */
CKEDITOR.dom.element.prototype.focusNext = function( ignoreChildren )
{
	var $ = this.$,
		curTabIndex = this.getTabIndex(),
		passedCurrent, enteredCurrent,
		elected, electedTabIndex,
		element, elementTabIndex;

	if ( curTabIndex <= 0 )
	{
		// If this element has tabindex <= 0 then we must simply look for any
		// element following it containing tabindex=0.

		element = this.getNextSourceNode( ignoreChildren, CKEDITOR.NODE_ELEMENT );

		while( element )
		{
			if ( element.isVisible() && element.getTabIndex() === 0 )
			{
				elected = element;
				break;
			}

			element = element.getNextSourceNode( false, CKEDITOR.NODE_ELEMENT );
		}
	}
	else
	{
		// If this element has tabindex > 0 then we must look for:
		//		1. An element following this element with the same tabindex.
		//		2. The first element in source other with the lowest tabindex
		//		   that is higher than this element tabindex.
		//		3. The first element with tabindex=0.

		element = this.getDocument().getBody().getFirst();

		while( ( element = element.getNextSourceNode( false, CKEDITOR.NODE_ELEMENT ) ) )
		{
			if ( !passedCurrent )
			{
				if ( !enteredCurrent && element.equals( this ) )
				{
					enteredCurrent = true;

					// Ignore this element, if required.
					if ( ignoreChildren )
					{
						if ( !( element = element.getNextSourceNode( true, CKEDITOR.NODE_ELEMENT ) ) )
							break;
						passedCurrent = 1;
					}
				}
				else if ( enteredCurrent && !this.contains( element ) )
					passedCurrent = 1;
			}

			if ( !element.isVisible() || ( elementTabIndex = element.getTabIndex() ) < 0 )
				continue;

			if ( passedCurrent && elementTabIndex == curTabIndex )
			{
				elected = element;
				break;
			}

			if ( elementTabIndex > curTabIndex && ( !elected || !electedTabIndex || elementTabIndex < electedTabIndex ) )
			{
				elected = element;
				electedTabIndex = elementTabIndex;
			}
			else if ( !elected && elementTabIndex === 0 )
			{
				elected = element;
				electedTabIndex = elementTabIndex;
			}
		}
	}

	if ( elected )
		elected.focus();
};

/**
 * Moves the UI focus to the element before this element in the tabindex order.
 * @example
 * var element = CKEDITOR.document.getById( 'example' );
 * element.focusPrevious();
 */
CKEDITOR.dom.element.prototype.focusPrevious = function( ignoreChildren )
{
	var $ = this.$,
		curTabIndex = this.getTabIndex(),
		passedCurrent, enteredCurrent,
		elected,
		electedTabIndex = 0,
		elementTabIndex;

	var element = this.getDocument().getBody().getLast();

	while( ( element = element.getPreviousSourceNode( false, CKEDITOR.NODE_ELEMENT ) ) )
	{
		if ( !passedCurrent )
		{
			if ( !enteredCurrent && element.equals( this ) )
			{
				enteredCurrent = true;

				// Ignore this element, if required.
				if ( ignoreChildren )
				{
					if ( !( element = element.getPreviousSourceNode( true, CKEDITOR.NODE_ELEMENT ) ) )
						break;
					passedCurrent = 1;
				}
			}
			else if ( enteredCurrent && !this.contains( element ) )
				passedCurrent = 1;
		}

		if ( !element.isVisible() || ( elementTabIndex = element.getTabIndex() ) < 0 )
			continue;

		if ( curTabIndex <= 0 )
		{
			// If this element has tabindex <= 0 then we must look for:
			//		1. An element before this one containing tabindex=0.
			//		2. The last element with the highest tabindex.

			if ( passedCurrent && elementTabIndex === 0 )
			{
				elected = element;
				break;
			}

			if ( elementTabIndex > electedTabIndex )
			{
				elected = element;
				electedTabIndex = elementTabIndex;
			}
		}
		else
		{
			// If this element has tabindex > 0 we must look for:
			//		1. An element preceeding this one, with the same tabindex.
			//		2. The last element in source other with the highest tabindex
			//		   that is lower than this element tabindex.

			if ( passedCurrent && elementTabIndex == curTabIndex )
			{
				elected = element;
				break;
			}

			if ( elementTabIndex < curTabIndex && ( !elected || elementTabIndex > electedTabIndex ) )
			{
				elected = element;
				electedTabIndex = elementTabIndex;
			}
		}
	}

	if ( elected )
		elected.focus();
};

/**
 * Intructs the editor to add a number of spaces (&amp;nbsp;) to the text when
 * hitting the TAB key. If set to zero, the TAB key will be used to move the
 * cursor focus to the next element in the page, out of the editor focus.
 * @type Number
 * @default 0
 * @example
 * config.tabSpaces = 4;
 */
CKEDITOR.config.tabSpaces = 0 ;
