/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

/**
 * @file DOM iterator, which iterates over list items, lines and paragraphs.
 */

CKEDITOR.plugins.add( 'domiterator' );

(function()
{

	var iterator = function( range )
	{
		if ( arguments.length < 1 )
			return;

		this.range = range;
		this.forceBrBreak = false;

		// Whether include <br>s into the enlarged range.(#3730).
		this.enlargeBr = true;
		this.enforceRealBlocks = false;

		this._ || ( this._ = {} );
	},
		beginWhitespaceRegex = /^[\r\n\t ]+$/;


	iterator.prototype = {
		getNextParagraph : function( blockTag )
		{
			// The block element to be returned.
			var block;

			// The range object used to identify the paragraph contents.
			var range;

			// Indicats that the current element in the loop is the last one.
			var isLast;

			// Instructs to cleanup remaining BRs.
			var removePreviousBr, removeLastBr;

			// This is the first iteration. Let's initialize it.
			if ( !this._.lastNode )
			{
				range = this.range.clone();
				range.enlarge( this.forceBrBreak || !this.enlargeBr ?
							   CKEDITOR.ENLARGE_LIST_ITEM_CONTENTS : CKEDITOR.ENLARGE_BLOCK_CONTENTS );

				var walker = new CKEDITOR.dom.walker( range ),
					ignoreBookmarkTextEvaluator = CKEDITOR.dom.walker.bookmark( true, true );
				// Avoid anchor inside bookmark inner text.
				walker.evaluator = ignoreBookmarkTextEvaluator;
				this._.nextNode = walker.next();
				// TODO: It's better to have walker.reset() used here.
				walker = new CKEDITOR.dom.walker( range );
				walker.evaluator = ignoreBookmarkTextEvaluator;
				var lastNode = walker.previous();
				this._.lastNode = lastNode.getNextSourceNode( true );

				// We may have an empty text node at the end of block due to [3770].
				// If that node is the lastNode, it would cause our logic to leak to the
				// next block.(#3887)
				if ( this._.lastNode &&
						this._.lastNode.type == CKEDITOR.NODE_TEXT &&
						!CKEDITOR.tools.trim( this._.lastNode.getText( ) ) &&
						this._.lastNode.getParent().isBlockBoundary() )
				{
					var testRange = new CKEDITOR.dom.range( range.document );
					testRange.moveToPosition( this._.lastNode, CKEDITOR.POSITION_AFTER_END );
					if ( testRange.checkEndOfBlock() )
					{
						var path = new CKEDITOR.dom.elementPath( testRange.endContainer );
						var lastBlock = path.block || path.blockLimit;
						this._.lastNode = lastBlock.getNextSourceNode( true );
					}
				}

				// Probably the document end is reached, we need a marker node.
				if ( !this._.lastNode )
				{
					this._.lastNode = this._.docEndMarker = range.document.createText( '' );
					this._.lastNode.insertAfter( lastNode );
				}

				// Let's reuse this variable.
				range = null;
			}

			var currentNode = this._.nextNode;
			lastNode = this._.lastNode;

			this._.nextNode = null;
			while ( currentNode )
			{
				// closeRange indicates that a paragraph boundary has been found,
				// so the range can be closed.
				var closeRange = false;

				// includeNode indicates that the current node is good to be part
				// of the range. By default, any non-element node is ok for it.
				var includeNode = ( currentNode.type != CKEDITOR.NODE_ELEMENT ),
					continueFromSibling = false;

				// If it is an element node, let's check if it can be part of the
				// range.
				if ( !includeNode )
				{
					var nodeName = currentNode.getName();

					if ( currentNode.isBlockBoundary( this.forceBrBreak && { br : 1 } ) )
					{
						// <br> boundaries must be part of the range. It will
						// happen only if ForceBrBreak.
						if ( nodeName == 'br' )
							includeNode = true;
						else if ( !range && !currentNode.getChildCount() && nodeName != 'hr' )
						{
							// If we have found an empty block, and haven't started
							// the range yet, it means we must return this block.
							block = currentNode;
							isLast = currentNode.equals( lastNode );
							break;
						}

						// The range must finish right before the boundary,
						// including possibly skipped empty spaces. (#1603)
						if ( range )
						{
							range.setEndAt( currentNode, CKEDITOR.POSITION_BEFORE_START );

							// The found boundary must be set as the next one at this
							// point. (#1717)
							if ( nodeName != 'br' )
								this._.nextNode = currentNode;
						}

						closeRange = true;
					}
					else
					{
						// If we have child nodes, let's check them.
						if ( currentNode.getFirst() )
						{
							// If we don't have a range yet, let's start it.
							if ( !range )
							{
								range = new CKEDITOR.dom.range( this.range.document );
								range.setStartAt( currentNode, CKEDITOR.POSITION_BEFORE_START );
							}

							currentNode = currentNode.getFirst();
							continue;
						}
						includeNode = true;
					}
				}
				else if ( currentNode.type == CKEDITOR.NODE_TEXT )
				{
					// Ignore normal whitespaces (i.e. not including &nbsp; or
					// other unicode whitespaces) before/after a block node.
					if ( beginWhitespaceRegex.test( currentNode.getText() ) )
						includeNode = false;
				}

				// The current node is good to be part of the range and we are
				// starting a new range, initialize it first.
				if ( includeNode && !range )
				{
					range = new CKEDITOR.dom.range( this.range.document );
					range.setStartAt( currentNode, CKEDITOR.POSITION_BEFORE_START );
				}

				// The last node has been found.
				isLast = ( ( !closeRange || includeNode ) && currentNode.equals( lastNode ) );

				// If we are in an element boundary, let's check if it is time
				// to close the range, otherwise we include the parent within it.
				if ( range && !closeRange )
				{
					while ( !currentNode.getNext() && !isLast )
					{
						var parentNode = currentNode.getParent();

						if ( parentNode.isBlockBoundary( this.forceBrBreak && { br : 1 } ) )
						{
							closeRange = true;
							isLast = isLast || ( parentNode.equals( lastNode) );
							break;
						}

						currentNode = parentNode;
						includeNode = true;
						isLast = ( currentNode.equals( lastNode ) );
						continueFromSibling = true;
					}
				}

				// Now finally include the node.
				if ( includeNode )
					range.setEndAt( currentNode, CKEDITOR.POSITION_AFTER_END );

				currentNode = currentNode.getNextSourceNode( continueFromSibling, null, lastNode );
				isLast = !currentNode;

				// We have found a block boundary. Let's close the range and move out of the
				// loop.
				if ( ( closeRange || isLast ) && range )
				{
					var boundaryNodes = range.getBoundaryNodes(),
						startPath = new CKEDITOR.dom.elementPath( range.startContainer ),
						endPath = new CKEDITOR.dom.elementPath( range.endContainer );

					// Drop the range if it only contains bookmark nodes.(#4087)
					if ( boundaryNodes.startNode.equals( boundaryNodes.endNode )
						&& boundaryNodes.startNode.getParent().equals( startPath.blockLimit )
						&& boundaryNodes.startNode.type == CKEDITOR.NODE_ELEMENT
						&& boundaryNodes.startNode.getAttribute( '_fck_bookmark' ) )
					{
						range = null;
						this._.nextNode = null;
					}
					else
						break;
				}

				if ( isLast )
					break;

			}

			// Now, based on the processed range, look for (or create) the block to be returned.
			if ( !block )
			{
				// If no range has been found, this is the end.
				if ( !range )
				{
					this._.docEndMarker && this._.docEndMarker.remove();
					this._.nextNode = null;
					return null;
				}

				startPath = new CKEDITOR.dom.elementPath( range.startContainer );
				var startBlockLimit = startPath.blockLimit,
					checkLimits = { div : 1, th : 1, td : 1 };
				block = startPath.block;

				if ( !block
						&& !this.enforceRealBlocks
						&& checkLimits[ startBlockLimit.getName() ]
						&& range.checkStartOfBlock()
						&& range.checkEndOfBlock() )
					block = startBlockLimit;
				else if ( !block || ( this.enforceRealBlocks && block.getName() == 'li' ) )
				{
					// Create the fixed block.
					block = this.range.document.createElement( blockTag || 'p' );

					// Move the contents of the temporary range to the fixed block.
					range.extractContents().appendTo( block );
					block.trim();

					// Insert the fixed block into the DOM.
					range.insertNode( block );

					removePreviousBr = removeLastBr = true;
				}
				else if ( block.getName() != 'li' )
				{
					// If the range doesn't includes the entire contents of the
					// block, we must split it, isolating the range in a dedicated
					// block.
					if ( !range.checkStartOfBlock() || !range.checkEndOfBlock() )
					{
						// The resulting block will be a clone of the current one.
						block = block.clone( false );

						// Extract the range contents, moving it to the new block.
						range.extractContents().appendTo( block );
						block.trim();

						// Split the block. At this point, the range will be in the
						// right position for our intents.
						var splitInfo = range.splitBlock();

						removePreviousBr = !splitInfo.wasStartOfBlock;
						removeLastBr = !splitInfo.wasEndOfBlock;

						// Insert the new block into the DOM.
						range.insertNode( block );
					}
				}
				else if ( !isLast )
				{
					// LIs are returned as is, with all their children (due to the
					// nested lists). But, the next node is the node right after
					// the current range, which could be an <li> child (nested
					// lists) or the next sibling <li>.

					this._.nextNode = ( block.equals( lastNode ) ? null :
						range.getBoundaryNodes().endNode.getNextSourceNode( true, null, lastNode ) );
				}
			}

			if ( removePreviousBr )
			{
				var previousSibling = block.getPrevious();
				if ( previousSibling && previousSibling.type == CKEDITOR.NODE_ELEMENT )
				{
					if ( previousSibling.getName() == 'br' )
						previousSibling.remove();
					else if ( previousSibling.getLast() && previousSibling.getLast().$.nodeName.toLowerCase() == 'br' )
						previousSibling.getLast().remove();
				}
			}

			if ( removeLastBr )
			{
				// Ignore bookmark nodes.(#3783)
				var bookmarkGuard = CKEDITOR.dom.walker.bookmark( false, true );

				var lastChild = block.getLast();
				if ( lastChild && lastChild.type == CKEDITOR.NODE_ELEMENT && lastChild.getName() == 'br' )
				{
					// Take care not to remove the block expanding <br> in non-IE browsers.
					if ( CKEDITOR.env.ie
						 || lastChild.getPrevious( bookmarkGuard )
						 || lastChild.getNext( bookmarkGuard ) )
						lastChild.remove();
				}
			}

			// Get a reference for the next element. This is important because the
			// above block can be removed or changed, so we can rely on it for the
			// next interation.
			if ( !this._.nextNode )
			{
				this._.nextNode = ( isLast || block.equals( lastNode ) ) ? null :
					block.getNextSourceNode( true, null, lastNode );
			}

			return block;
		}
	};

	CKEDITOR.dom.range.prototype.createIterator = function()
	{
		return new iterator( this );
	};
})();
