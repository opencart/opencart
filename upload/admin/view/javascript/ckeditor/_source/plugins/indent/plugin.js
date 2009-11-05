/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file Increse and decrease indent commands.
 */

(function()
{
	var listNodeNames = { ol : 1, ul : 1 };

	function setState( editor, state )
	{
		editor.getCommand( this.name ).setState( state );
	}

	function onSelectionChange( evt )
	{
		var elements = evt.data.path.elements,
			listNode, listItem,
			editor = evt.editor;

		for ( var i = 0 ; i < elements.length ; i++ )
		{
			if ( elements[i].getName() == 'li' )
			{
				listItem = elements[i];
				continue;
			}
			if ( listNodeNames[ elements[i].getName() ] )
			{
				listNode = elements[i];
				break;
			}
		}

		if ( listNode )
		{
			if ( this.name == 'outdent' )
				return setState.call( this, editor, CKEDITOR.TRISTATE_OFF );
			else
			{
				while ( listItem && ( listItem = listItem.getPrevious( CKEDITOR.dom.walker.whitespaces( true ) ) ) )
				{
					if ( listItem.getName && listItem.getName() == 'li' )
						return setState.call( this, editor, CKEDITOR.TRISTATE_OFF );
				}
				return setState.call( this, editor, CKEDITOR.TRISTATE_DISABLED );
			}
		}

		if ( !this.useIndentClasses && this.name == 'indent' )
			return setState.call( this, editor, CKEDITOR.TRISTATE_OFF );

		var path = evt.data.path,
			firstBlock = path.block || path.blockLimit;
		if ( !firstBlock )
			return setState.call( this, editor, CKEDITOR.TRISTATE_DISABLED );

		if ( this.useIndentClasses )
		{
			var indentClass = firstBlock.$.className.match( this.classNameRegex ),
				indentStep = 0;
			if ( indentClass )
			{
				indentClass = indentClass[1];
				indentStep = this.indentClassMap[ indentClass ];
			}
			if ( ( this.name == 'outdent' && !indentStep ) ||
					( this.name == 'indent' && indentStep == editor.config.indentClasses.length ) )
				return setState.call( this, editor, CKEDITOR.TRISTATE_DISABLED );
			return setState.call( this, editor, CKEDITOR.TRISTATE_OFF );
		}
		else
		{
			var indent = parseInt( firstBlock.getStyle( this.indentCssProperty ), 10 );
			if ( isNaN( indent ) )
				indent = 0;
			if ( indent <= 0 )
				return setState.call( this, editor, CKEDITOR.TRISTATE_DISABLED );
			return setState.call( this, editor, CKEDITOR.TRISTATE_OFF );
		}
	}

	function indentList( editor, range, listNode )
	{
		// Our starting and ending points of the range might be inside some blocks under a list item...
		// So before playing with the iterator, we need to expand the block to include the list items.
		var startContainer = range.startContainer,
			endContainer = range.endContainer;
		while ( startContainer && !startContainer.getParent().equals( listNode ) )
			startContainer = startContainer.getParent();
		while ( endContainer && !endContainer.getParent().equals( listNode ) )
			endContainer = endContainer.getParent();

		if ( !startContainer || !endContainer )
			return;

		// Now we can iterate over the individual items on the same tree depth.
		var block = startContainer,
			itemsToMove = [],
			stopFlag = false;
		while ( !stopFlag )
		{
			if ( block.equals( endContainer ) )
				stopFlag = true;
			itemsToMove.push( block );
			block = block.getNext();
		}
		if ( itemsToMove.length < 1 )
			return;

		// Do indent or outdent operations on the array model of the list, not the
		// list's DOM tree itself. The array model demands that it knows as much as
		// possible about the surrounding lists, we need to feed it the further
		// ancestor node that is still a list.
		var listParents = listNode.getParents( true );
		for ( var i = 0 ; i < listParents.length ; i++ )
		{
			if ( listParents[i].getName && listNodeNames[ listParents[i].getName() ] )
			{
				listNode = listParents[i];
				break;
			}
		}
		var indentOffset = this.name == 'indent' ? 1 : -1,
			startItem = itemsToMove[0],
			lastItem = itemsToMove[ itemsToMove.length - 1 ],
			database = {};

		// Convert the list DOM tree into a one dimensional array.
		var listArray = CKEDITOR.plugins.list.listToArray( listNode, database );

		// Apply indenting or outdenting on the array.
		var baseIndent = listArray[ lastItem.getCustomData( 'listarray_index' ) ].indent;
		for ( i = startItem.getCustomData( 'listarray_index' ) ; i <= lastItem.getCustomData( 'listarray_index' ) ; i++ )
			listArray[i].indent += indentOffset;
		for ( i = lastItem.getCustomData( 'listarray_index' ) + 1 ;
				i < listArray.length && listArray[i].indent > baseIndent ; i++ )
			listArray[i].indent += indentOffset;

		// Convert the array back to a DOM forest (yes we might have a few subtrees now).
		// And replace the old list with the new forest.
		var newList = CKEDITOR.plugins.list.arrayToList( listArray, database, null, editor.config.enterMode, 0 );

		// Avoid nested <li> after outdent even they're visually same,
		// recording them for later refactoring.(#3982)
		if ( this.name == 'outdent' )
		{
			var parentLiElement;
			if ( ( parentLiElement = listNode.getParent() ) && parentLiElement.is( 'li' ) )
			{
				var children = newList.listNode.getChildren(),
					pendingLis = [],
					count = children.count(),
					child;

				for ( i = count - 1 ; i >= 0 ; i-- )
				{
					if( ( child = children.getItem( i ) ) && child.is && child.is( 'li' )  )
						pendingLis.push( child );
				}
			}
		}

		if ( newList )
			newList.listNode.replace( listNode );

		// Move the nested <li> to be appeared after the parent.
		if ( pendingLis && pendingLis.length )
		{
			for (  i = 0; i < pendingLis.length ; i++ )
			{
				var li = pendingLis[ i ],
					followingList = li;

				// Nest preceding <ul>/<ol> inside current <li> if any.
				while( ( followingList = followingList.getNext() ) &&
					   followingList.is &&
					   followingList.getName() in listNodeNames )
				{
					li.append( followingList );
				}

				li.insertAfter( parentLiElement );
			}
		}

		// Clean up the markers.
		CKEDITOR.dom.element.clearAllMarkers( database );
	}

	function indentBlock( editor, range )
	{
		var iterator = range.createIterator(),
			enterMode = editor.config.enterMode;
		iterator.enforceRealBlocks = true;
		iterator.enlargeBr = enterMode != CKEDITOR.ENTER_BR;
		var block;
		while ( ( block = iterator.getNextParagraph() ) )
		{

			if ( this.useIndentClasses )
			{
				// Transform current class name to indent step index.
				var indentClass = block.$.className.match( this.classNameRegex ),
					indentStep = 0;
				if ( indentClass )
				{
					indentClass = indentClass[1];
					indentStep = this.indentClassMap[ indentClass ];
				}

				// Operate on indent step index, transform indent step index back to class
				// name.
				if ( this.name == 'outdent' )
					indentStep--;
				else
					indentStep++;
				indentStep = Math.min( indentStep, editor.config.indentClasses.length );
				indentStep = Math.max( indentStep, 0 );
				var className = CKEDITOR.tools.ltrim( block.$.className.replace( this.classNameRegex, '' ) );
				if ( indentStep < 1 )
					block.$.className = className;
				else
					block.addClass( editor.config.indentClasses[ indentStep - 1 ] );
			}
			else
			{
				var currentOffset = parseInt( block.getStyle( this.indentCssProperty ), 10 );
				if ( isNaN( currentOffset ) )
					currentOffset = 0;
				currentOffset += ( this.name == 'indent' ? 1 : -1 ) * editor.config.indentOffset;
				currentOffset = Math.max( currentOffset, 0 );
				currentOffset = Math.ceil( currentOffset / editor.config.indentOffset ) * editor.config.indentOffset;
				block.setStyle( this.indentCssProperty, currentOffset ? currentOffset + editor.config.indentUnit : '' );
				if ( block.getAttribute( 'style' ) === '' )
					block.removeAttribute( 'style' );
			}
		}
	}

	function indentCommand( editor, name )
	{
		this.name = name;
		this.useIndentClasses = editor.config.indentClasses && editor.config.indentClasses.length > 0;
		if ( this.useIndentClasses )
		{
			this.classNameRegex = new RegExp( '(?:^|\\s+)(' + editor.config.indentClasses.join( '|' ) + ')(?=$|\\s)' );
			this.indentClassMap = {};
			for ( var i = 0 ; i < editor.config.indentClasses.length ; i++ )
				this.indentClassMap[ editor.config.indentClasses[i] ] = i + 1;
		}
		else
			this.indentCssProperty = editor.config.contentsLangDirection == 'ltr' ? 'margin-left' : 'margin-right';
	}

	indentCommand.prototype = {
		exec : function( editor )
		{
			var selection = editor.getSelection(),
				range = selection && selection.getRanges()[0];

			if ( !selection || !range )
				return;

			var bookmarks = selection.createBookmarks( true ),
				nearestListBlock = range.getCommonAncestor();

			while ( nearestListBlock && !( nearestListBlock.type == CKEDITOR.NODE_ELEMENT &&
				listNodeNames[ nearestListBlock.getName() ] ) )
				nearestListBlock = nearestListBlock.getParent();

			if ( nearestListBlock )
				indentList.call( this, editor, range, nearestListBlock );
			else
				indentBlock.call( this, editor, range );

			editor.focus();
			editor.forceNextSelectionCheck();
			selection.selectBookmarks( bookmarks );
		}
	};

	CKEDITOR.plugins.add( 'indent',
	{
		init : function( editor )
		{
			// Register commands.
			var indent = new indentCommand( editor, 'indent' ),
				outdent = new indentCommand( editor, 'outdent' );
			editor.addCommand( 'indent', indent );
			editor.addCommand( 'outdent', outdent );

			// Register the toolbar buttons.
			editor.ui.addButton( 'Indent',
				{
					label : editor.lang.indent,
					command : 'indent'
				});
			editor.ui.addButton( 'Outdent',
				{
					label : editor.lang.outdent,
					command : 'outdent'
				});

			// Register the state changing handlers.
			editor.on( 'selectionChange', CKEDITOR.tools.bind( onSelectionChange, indent ) );
			editor.on( 'selectionChange', CKEDITOR.tools.bind( onSelectionChange, outdent ) );
		},

		requires : [ 'domiterator', 'list' ]
	} );
})();

CKEDITOR.tools.extend( CKEDITOR.config,
	{
		indentOffset : 40,
		indentUnit : 'px',
		indentClasses : null
	});
