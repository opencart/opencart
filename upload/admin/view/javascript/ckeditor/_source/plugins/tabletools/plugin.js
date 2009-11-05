/*
Copyright (c) 2003-2009, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	function removeRawAttribute( $node, attr )
	{
		if ( CKEDITOR.env.ie )
			$node.removeAttribute( attr );
		else
			delete $node[ attr ];
	}

	var cellNodeRegex = /^(?:td|th)$/;

	function getSelectedCells( selection )
	{
		// Walker will try to split text nodes, which will make the current selection
		// invalid. So save bookmarks before doing anything.
		var bookmarks = selection.createBookmarks();

		var ranges = selection.getRanges();
		var retval = [];
		var database = {};

		function moveOutOfCellGuard( node )
		{
			// Apply to the first cell only.
			if ( retval.length > 0 )
				return;

			// If we are exiting from the first </td>, then the td should definitely be
			// included.
			if ( node.type == CKEDITOR.NODE_ELEMENT && cellNodeRegex.test( node.getName() )
					&& !node.getCustomData( 'selected_cell' ) )
			{
				CKEDITOR.dom.element.setMarker( database, node, 'selected_cell', true );
				retval.push( node );
			}
		}

		for ( var i = 0 ; i < ranges.length ; i++ )
		{
			var range = ranges[ i ];

			if ( range.collapsed )
			{
				// Walker does not handle collapsed ranges yet - fall back to old API.
				var startNode = range.getCommonAncestor();
				var nearestCell = startNode.getAscendant( 'td', true ) || startNode.getAscendant( 'th', true );
				if ( nearestCell )
					retval.push( nearestCell );
			}
			else
			{
				var walker = new CKEDITOR.dom.walker( range );
				var node;
				walker.guard = moveOutOfCellGuard;

				while ( ( node = walker.next() ) )
				{
					// If may be possible for us to have a range like this:
					// <td>^1</td><td>^2</td>
					// The 2nd td shouldn't be included.
					//
					// So we have to take care to include a td we've entered only when we've
					// walked into its children.

					var parent = node.getParent();
					if ( parent && cellNodeRegex.test( parent.getName() ) && !parent.getCustomData( 'selected_cell' ) )
					{
						CKEDITOR.dom.element.setMarker( database, parent, 'selected_cell', true );
						retval.push( parent );
					}
				}
			}
		}

		CKEDITOR.dom.element.clearAllMarkers( database );

		// Restore selection position.
		selection.selectBookmarks( bookmarks );

		return retval;
	}

	function createTableMap( $refCell )
	{
		var refCell = new CKEDITOR.dom.element( $refCell );
		var $table = ( refCell.getName() == 'table' ? $refCell : refCell.getAscendant( 'table' ) ).$;
		var $rows = $table.rows;

		// Row and column counters.
		var r = -1;
		var map = [];
		for ( var i = 0 ; i < $rows.length ; i++ )
		{
			r++;
			if ( !map[ r ] )
				map[ r ] = [];

			var c = -1;

			for ( var j = 0 ; j < $rows[ i ].cells.length ; j++ )
			{
				var $cell = $rows[ i ].cells[ j ];

				c++;
				while ( map[ r ][ c ] )
					c++;

				var colSpan = isNaN( $cell.colSpan ) ? 1 : $cell.colSpan;
				var rowSpan = isNaN( $cell.rowSpan ) ? 1 : $cell.rowSpan;

				for ( var rs = 0 ; rs < rowSpan ; rs++ )
				{
					if ( !map[ r + rs ] )
						map[ r + rs ] = [];

					for ( var cs = 0 ; cs < colSpan ; cs++ )
						map [ r + rs ][ c + cs ] = $rows[ i ].cells[ j ];
				}

				c += colSpan - 1;
			}
		}

		return map;
	}

	function installTableMap( tableMap, $table )
	{
		/*
		 * IE BUG: rowSpan is always 1 in IE if the cell isn't attached to a row. So
		 * store is separately in another attribute. (#1917)
		 */
		var rowSpanAttr = CKEDITOR.env.ie ? '_cke_rowspan' : 'rowSpan';

		/*
		 * Disconnect all the cells in tableMap from their parents, set all colSpan
		 * and rowSpan attributes to 1.
		 */
		for ( var i = 0 ; i < tableMap.length ; i++ )
		{
			for ( var j = 0 ; j < tableMap[ i ].length ; j++ )
			{
				var $cell = tableMap[ i ][ j ];
				if ( $cell.parentNode )
					$cell.parentNode.removeChild( $cell );
				$cell.colSpan = $cell[ rowSpanAttr ] = 1;
			}
		}

		// Scan by rows and set colSpan.
		var maxCol = 0;
		for ( i = 0 ; i < tableMap.length ; i++ )
		{
			for ( j = 0 ; j < tableMap[ i ].length ; j++ )
			{
				$cell = tableMap[ i ][ j ];
				if ( !$cell )
					continue;
				if ( j > maxCol )
					maxCol = j;
				if ( $cell[ '_cke_colScanned' ] )
					continue;
				if ( tableMap[ i ][ j - 1 ] == $cell )
					$cell.colSpan++;
				if ( tableMap[ i ][ j + 1 ] != $cell )
					$cell[ '_cke_colScanned' ] = 1;
			}
		}

		// Scan by columns and set rowSpan.
		for ( i = 0 ; i <= maxCol ; i++ )
		{
			for ( j = 0 ; j < tableMap.length ; j++ )
			{
				if ( !tableMap[ j ] )
					continue;
				$cell = tableMap[ j ][ i ];
				if ( !$cell || $cell[ '_cke_rowScanned' ] )
					continue;
				if ( tableMap[ j - 1 ] && tableMap[ j - 1 ][ i ] == $cell )
					$cell[ rowSpanAttr ]++;
				if ( !tableMap[ j + 1 ] || tableMap[ j + 1 ][ i ] != $cell  )
					$cell[ '_cke_rowScanned' ] = 1;
			}
		}

		// Clear all temporary flags.
		for ( i = 0 ; i < tableMap.length ; i++ )
		{
			for ( j = 0 ; j < tableMap[ i ].length ; j++ )
			{
				$cell = tableMap[ i ][ j ];
				removeRawAttribute( $cell, '_cke_colScanned' );
				removeRawAttribute( $cell, '_cke_rowScanned' );
			}
		}

		// Insert physical rows and columns to table.
		for ( i = 0 ; i < tableMap.length ; i++ )
		{
			var $row = $table.ownerDocument.createElement( 'tr' );
			for ( j = 0 ; j < tableMap[ i ].length ; )
			{
				$cell = tableMap[ i ][ j ];
				if ( tableMap[ i - 1 ] && tableMap[ i - 1 ][ j ] == $cell )
				{
					j += $cell.colSpan;
					continue;
				}
				$row.appendChild( $cell );
				if ( rowSpanAttr != 'rowSpan' )
				{
					$cell.rowSpan = $cell[ rowSpanAttr ];
					$cell.removeAttribute( rowSpanAttr );
				}
				j += $cell.colSpan;
				if ( $cell.colSpan == 1 )
					$cell.removeAttribute( 'colSpan' );
				if ( $cell.rowSpan == 1 )
					$cell.removeAttribute( 'rowSpan' );
			}

			if ( CKEDITOR.env.ie )
				$table.rows[ i ].replaceNode( $row );
			else
			{
				var dest = new CKEDITOR.dom.element( $table.rows[ i ] );
				var src = new CKEDITOR.dom.element( $row );
				dest.setHtml( '' );
				src.moveChildren( dest );
			}
		}
	}

	function clearRow( $tr )
	{
		// Get the array of row's cells.
		var $cells = $tr.cells;

		// Empty all cells.
		for ( var i = 0 ; i < $cells.length ; i++ )
		{
			$cells[ i ].innerHTML = '';

			if ( !CKEDITOR.env.ie )
				( new CKEDITOR.dom.element( $cells[ i ] ) ).appendBogus();
		}
	}

	function insertRow( selection, insertBefore )
	{
		// Get the row where the selection is placed in.
		var row = selection.getStartElement().getAscendant( 'tr' );
		if ( !row )
			return;

		// Create a clone of the row.
		var newRow = row.clone( true );

		// Insert the new row before of it.
		newRow.insertBefore( row );

		// Clean one of the rows to produce the illusion of inserting an empty row
		// before or after.
		clearRow( insertBefore ? newRow.$ : row.$ );
	}

	function deleteRows( selectionOrRow )
	{
		if ( selectionOrRow instanceof CKEDITOR.dom.selection )
		{
			var cells = getSelectedCells( selectionOrRow );
			var rowsToDelete = [];

			// Queue up the rows - it's possible and likely that we have duplicates.
			for ( var i = 0 ; i < cells.length ; i++ )
			{
				var row = cells[ i ].getParent();
				rowsToDelete[ row.$.rowIndex ] = row;
			}

			for ( i = rowsToDelete.length ; i >= 0 ; i-- )
			{
				if ( rowsToDelete[ i ] )
					deleteRows( rowsToDelete[ i ] );
			}
		}
		else if ( selectionOrRow instanceof CKEDITOR.dom.element )
		{
			var table = selectionOrRow.getAscendant( 'table' );

			if ( table.$.rows.length == 1 )
				table.remove();
			else
				selectionOrRow.remove();
		}
	}

	function insertColumn( selection, insertBefore )
	{
		// Get the cell where the selection is placed in.
		var startElement = selection.getStartElement();
		var cell = startElement.getAscendant( 'td', true ) || startElement.getAscendant( 'th', true );

		if ( !cell )
			return;

		// Get the cell's table.
		var table = cell.getAscendant( 'table' );
		var cellIndex = cell.$.cellIndex;

		// Loop through all rows available in the table.
		for ( var i = 0 ; i < table.$.rows.length ; i++ )
		{
			var $row = table.$.rows[ i ];

			// If the row doesn't have enough cells, ignore it.
			if ( $row.cells.length < ( cellIndex + 1 ) )
				continue;

			cell = new CKEDITOR.dom.element( $row.cells[ cellIndex ].cloneNode( false ) );

			if ( !CKEDITOR.env.ie )
				cell.appendBogus();

			// Get back the currently selected cell.
			var baseCell = new CKEDITOR.dom.element( $row.cells[ cellIndex ] );
			if ( insertBefore )
				cell.insertBefore( baseCell );
			else
				cell.insertAfter( baseCell );
		}
	}

	function deleteColumns( selectionOrCell )
	{
		if ( selectionOrCell instanceof CKEDITOR.dom.selection )
		{
			var colsToDelete = getSelectedCells( selectionOrCell );
			for ( var i = colsToDelete.length ; i >= 0 ; i-- )
			{
				if ( colsToDelete[ i ] )
					deleteColumns( colsToDelete[ i ] );
			}
		}
		else if ( selectionOrCell instanceof CKEDITOR.dom.element )
		{
			// Get the cell's table.
			var table = selectionOrCell.getAscendant( 'table' );

			// Get the cell index.
			var cellIndex = selectionOrCell.$.cellIndex;

			/*
			 * Loop through all rows from down to up, coz it's possible that some rows
			 * will be deleted.
			 */
			for ( i = table.$.rows.length - 1 ; i >= 0 ; i-- )
			{
				// Get the row.
				var row = new CKEDITOR.dom.element( table.$.rows[ i ] );

				// If the cell to be removed is the first one and the row has just one cell.
				if ( !cellIndex && row.$.cells.length == 1 )
				{
					deleteRows( row );
					continue;
				}

				// Else, just delete the cell.
				if ( row.$.cells[ cellIndex ] )
					row.$.removeChild( row.$.cells[ cellIndex ] );
			}
		}
	}

	function insertCell( selection, insertBefore )
	{
		var startElement = selection.getStartElement();
		var cell = startElement.getAscendant( 'td', true ) || startElement.getAscendant( 'th', true );

		if ( !cell )
			return;

		// Create the new cell element to be added.
		var newCell = cell.clone();
		if ( !CKEDITOR.env.ie )
			newCell.appendBogus();

		if ( insertBefore )
			newCell.insertBefore( cell );
		else
			newCell.insertAfter( cell );
	}

	function deleteCells( selectionOrCell )
	{
		if ( selectionOrCell instanceof CKEDITOR.dom.selection )
		{
			var cellsToDelete = getSelectedCells( selectionOrCell );
			for ( var i = cellsToDelete.length - 1 ; i >= 0 ; i-- )
				deleteCells( cellsToDelete[ i ] );
		}
		else if ( selectionOrCell instanceof CKEDITOR.dom.element )
		{
			if ( selectionOrCell.getParent().getChildCount() == 1 )
				selectionOrCell.getParent().remove();
			else
				selectionOrCell.remove();
		}
	}

	// Context menu on table caption incorrect (#3834)
	var contextMenuTags = { thead : 1, tbody : 1, tfoot : 1, td : 1, tr : 1, th : 1 };

	CKEDITOR.plugins.tabletools =
	{
		init : function( editor )
		{
			var lang = editor.lang.table;

			editor.addCommand( 'cellProperties', new CKEDITOR.dialogCommand( 'cellProperties' ) );
			CKEDITOR.dialog.add( 'cellProperties', this.path + 'dialogs/tableCell.js' );

			editor.addCommand( 'tableDelete',
				{
					exec : function( editor )
					{
						var selection = editor.getSelection();
						var startElement = selection && selection.getStartElement();
						var table = startElement && startElement.getAscendant( 'table', true );

						if ( !table )
							return;

						// Maintain the selection point at where the table was deleted.
						selection.selectElement( table );
						var range = selection.getRanges()[0];
						range.collapse();
						selection.selectRanges( [ range ] );

						// If the table's parent has only one child, remove it as well.
						if ( table.getParent().getChildCount() == 1 )
							table.getParent().remove();
						else
							table.remove();
					}
				} );

			editor.addCommand( 'rowDelete',
				{
					exec : function( editor )
					{
						var selection = editor.getSelection();
						deleteRows( selection );
					}
				} );

			editor.addCommand( 'rowInsertBefore',
				{
					exec : function( editor )
					{
						var selection = editor.getSelection();
						insertRow( selection, true );
					}
				} );

			editor.addCommand( 'rowInsertAfter',
				{
					exec : function( editor )
					{
						var selection = editor.getSelection();
						insertRow( selection );
					}
				} );

			editor.addCommand( 'columnDelete',
				{
					exec : function( editor )
					{
						var selection = editor.getSelection();
						deleteColumns( selection );
					}
				} );

			editor.addCommand( 'columnInsertBefore',
				{
					exec : function( editor )
					{
						var selection = editor.getSelection();
						insertColumn( selection, true );
					}
				} );

			editor.addCommand( 'columnInsertAfter',
				{
					exec : function( editor )
					{
						var selection = editor.getSelection();
						insertColumn( selection );
					}
				} );

			editor.addCommand( 'cellDelete',
				{
					exec : function( editor )
					{
						var selection = editor.getSelection();
						deleteCells( selection );
					}
				} );

			editor.addCommand( 'cellInsertBefore',
				{
					exec : function( editor )
					{
						var selection = editor.getSelection();
						insertCell( selection, true );
					}
				} );

			editor.addCommand( 'cellInsertAfter',
				{
					exec : function( editor )
					{
						var selection = editor.getSelection();
						insertCell( selection );
					}
				} );

			// If the "menu" plugin is loaded, register the menu items.
			if ( editor.addMenuItems )
			{
				editor.addMenuItems(
					{
						tablecell :
						{
							label : lang.cell.menu,
							group : 'tablecell',
							order : 1,
							getItems : function()
							{
								var cells = getSelectedCells( editor.getSelection() );
								return {
									tablecell_insertBefore : CKEDITOR.TRISTATE_OFF,
									tablecell_insertAfter : CKEDITOR.TRISTATE_OFF,
									tablecell_delete : CKEDITOR.TRISTATE_OFF,
									tablecell_properties : cells.length > 0 ? CKEDITOR.TRISTATE_OFF : CKEDITOR.TRISTATE_DISABLED
								};
							}
						},

						tablecell_insertBefore :
						{
							label : lang.cell.insertBefore,
							group : 'tablecell',
							command : 'cellInsertBefore',
							order : 5
						},

						tablecell_insertAfter :
						{
							label : lang.cell.insertAfter,
							group : 'tablecell',
							command : 'cellInsertAfter',
							order : 10
						},

						tablecell_delete :
						{
							label : lang.cell.deleteCell,
							group : 'tablecell',
							command : 'cellDelete',
							order : 15
						},

						tablecell_properties :
						{
							label : lang.cell.title,
							group : 'tablecellproperties',
							command : 'cellProperties',
							order : 20
						},

						tablerow :
						{
							label : lang.row.menu,
							group : 'tablerow',
							order : 1,
							getItems : function()
							{
								return {
									tablerow_insertBefore : CKEDITOR.TRISTATE_OFF,
									tablerow_insertAfter : CKEDITOR.TRISTATE_OFF,
									tablerow_delete : CKEDITOR.TRISTATE_OFF
								};
							}
						},

						tablerow_insertBefore :
						{
							label : lang.row.insertBefore,
							group : 'tablerow',
							command : 'rowInsertBefore',
							order : 5
						},

						tablerow_insertAfter :
						{
							label : lang.row.insertAfter,
							group : 'tablerow',
							command : 'rowInsertAfter',
							order : 10
						},

						tablerow_delete :
						{
							label : lang.row.deleteRow,
							group : 'tablerow',
							command : 'rowDelete',
							order : 15
						},

						tablecolumn :
						{
							label : lang.column.menu,
							group : 'tablecolumn',
							order : 1,
							getItems : function()
							{
								return {
									tablecolumn_insertBefore : CKEDITOR.TRISTATE_OFF,
									tablecolumn_insertAfter : CKEDITOR.TRISTATE_OFF,
									tablecolumn_delete : CKEDITOR.TRISTATE_OFF
								};
							}
						},

						tablecolumn_insertBefore :
						{
							label : lang.column.insertBefore,
							group : 'tablecolumn',
							command : 'columnInsertBefore',
							order : 5
						},

						tablecolumn_insertAfter :
						{
							label : lang.column.insertAfter,
							group : 'tablecolumn',
							command : 'columnInsertAfter',
							order : 10
						},

						tablecolumn_delete :
						{
							label : lang.column.deleteColumn,
							group : 'tablecolumn',
							command : 'columnDelete',
							order : 15
						}
					});
			}

			// If the "contextmenu" plugin is laoded, register the listeners.
			if ( editor.contextMenu )
			{
				editor.contextMenu.addListener( function( element, selection )
					{
						if ( !element )
							return null;

						while ( element )
						{
							if ( element.getName() in contextMenuTags )
							{
								return {
									tablecell : CKEDITOR.TRISTATE_OFF,
									tablerow : CKEDITOR.TRISTATE_OFF,
									tablecolumn : CKEDITOR.TRISTATE_OFF
								};
							}
							element = element.getParent();
						}

						return null;
					} );
			}
		},

		getSelectedCells : getSelectedCells

	};
	CKEDITOR.plugins.add( 'tabletools', CKEDITOR.plugins.tabletools );
})();
