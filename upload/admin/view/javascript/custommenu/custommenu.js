$( document ).ready(function() {
	$('.accordion>.accordion-content').css('display', 'none');
	  
	$('.accordion>h4,.accordion>.accordion-heading').click(function() {
		if (!$(this).hasClass('active')) {
		  $(this).addClass('active').siblings('h4,.accordion-heading').removeClass('active');
		  var statsTemplate = $(this).next('.accordion-content');
		  $(statsTemplate).slideDown(350).siblings('.accordion-content').slideUp(350);
		}
	});
	  
	$('.accordion h4:first-child,.accordion .accordion-heading:first-child').each(function(dataAndEvents, deepDataAndEvents) {
		$(this).click();
	});

	$('#layout-add-iframe').on('load', function(event) {
		event.preventDefault();
		var iframe = $('#layout-add-iframe');
		var current_url = document.getElementById("layout-add-iframe").contentWindow.location.href;

		iframe.contents().find('[href]').on('click', function(event) {
			$('#layout-add-loading').addClass('loading_iframe');
		});

		iframe.contents().find('form').on('submit', function(event) {
			$('#layout-add-loading').addClass('loading_iframe');
		});
		if (current_url.indexOf('design/custommenu/') < 0) {
			$('#layout-add').modal('hide');
			window.location.reload();
		} else if (current_url.indexOf('design/custommenu') > -1) {
			iframe.contents().find('html,body').css({
				height: 'auto'
			});
			iframe.contents().find('#header,#content .page-header .breadcrumb,#column-left,#footer,#module').hide();
			iframe.contents().find('#content').css({marginLeft: '0px'});
			iframe.contents().find('#content').css({padding: '10px 0 0 0'});
			$('#layout-add-loading').removeClass('loading_iframe');
		}
	});
	
	$('input[name=\'filter_category_name\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=design/custommenu/autocomplete&token=' + token + '&filter_category_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['index'],
							name: item['name'],
							value: item['category_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'filter_category_name\']').val(item['name']);
			$('#category_id').val(item['value']);
		}
	});

	$('input[name=\'filter_product_name\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=design/custommenu/autocomplete&token=' + token + '&filter_product_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['index'],
							name: item['name'],
							value: item['product_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'filter_product_name\']').val(item['name']);
			$('#product_id').val(item['value']);
		}
	});

	$('input[name=\'filter_manufacturer_name\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=design/custommenu/autocomplete&token=' + token + '&filter_manufacturer_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['index'],
							name: item['name'],
							value: item['manufacturer_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'filter_manufacturer_name\']').val(item['name']);
			$('#manufacturer_id').val(item['value']);
		}
	});

	$('input[name=\'filter_information_name\']').autocomplete({
		'source': function(request, response) {
			$.ajax({
				url: 'index.php?route=design/custommenu/autocomplete&token=' + token + '&filter_information_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['index'],
							name: item['name'],
							value: item['information_id']
						}
					}));
				}
			});
		},
		'select': function(item) {
			$('input[name=\'filter_information_name\']').val(item['name']);
			$('#information_id').val(item['value']);
		}
	});


	$(document).on('click', '.opencustommenuItem', function() {	
		var id = $(this).attr('id');

		$(this).html('<i class="fa fa-caret-up"></i>');
		$("#custommenu-item-settings-" + id).slideToggle();
	});
});
function addcustommenu(type) {
    var link = '';

	if (type == 'category') {
		var id = $('#category_id').val();
		var name = '';	
		var loading = $('#addCategory');
	} else if(type == 'product') {
		var id = $('#product_id').val();
		var name = '';
		var loading = $('#addProduct');
	} else if(type == 'manufacturer') {
		var id = $('#manufacturer_id').val();
		var name = '';
		var loading = $('#addManufacturer');		
	} else if(type == 'information') {
		var id = $('#information_id').val();
		var name = '';
		var loading = $('#addInformation');		
	} else if(type == 'custom') {
        var id = '99999';
		var name = $('#input-custom-name').val();
		var link = $('#input-custom-link').val();
		var loading = $('#addCustom');
	}
	
	$.ajax({
		url: addcustommenuHref,
		type: 'post',
		data: {type:type, id:id, name:name, link:link},
		dataType: 'html',
		beforeSend: function() {
            loading.after('<span id="loading-bar"><i class="fa fa-spinner fa-spin"></i></span>');
        },
        complete: function() {
           $('#loading-bar').remove();
        },
		success: function(html) {
			$('#custommenu-to-edit').append(html);
			 $('html, body').scrollTop( $(document).height() - $(window).height() );
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

function savecustommenu(wrap_id, custommenu_show_id) {
	var listing = custommenu_show_id.split('-');
	var loading = $('#' + wrap_id + ' .pull-right .btn-loading');
	
	var heigth = $('#' + wrap_id).height() + 20;
    var _heigth = heigth/ 11;
	$.ajax({
		url: savecustommenuHref + '&type=' + listing[1],
		type: 'post',
		data: $('#' + wrap_id + ' input').serialize(),
		beforeSend: function() {
            $('#' + wrap_id).append('<span id="loading-bar" style="font-size:15px"><span class="save-loading" style="left:35%; top :'+ _heigth +'%;"><i class="fa fa-spinner fa-spin"></i></span></span>');
        },
		success: function() {
			setTimeout(function(){
				$('#loading-bar').remove();
			},800);
			
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

function statuscustommenu(type, custommenu_id, custommenu_show_id, id) {
	var listing = custommenu_show_id.split('-');
		
	if(listing[1] == 'child') {
		if(type == 'enable'){
			var data_href = statuscustommenuChildEnable;
		} else {
			var data_href = statuscustommenuChildDisable;
		}
	} else {
		if(type == 'enable'){
			var data_href = statuscustommenuEnable;
		} else {
			var data_href = statuscustommenuDisable;
		}
	}
	
	$.ajax({
		url: data_href,
		type: 'post',
		data: {custommenu_id:custommenu_id, id:id},
		dataType: 'html',
		success: function(html) {
			$('#' + id).after(html);
			$('#' + id).remove();
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	
}

function deletecustommenu(custommenu_id, custommenu_show_id) {
	var checkcustommenuItem = 0;
	
	$('#custommenu-to-edit li').each(function(index, custommenuItem){
	  var custommenuItemId = custommenuItem.id;
	  if( checkcustommenuItem && custommenuItemId.indexOf( 'custommenu-item' ) != -1 ) {
		checkcustommenuItem = 0
	  }
	  if(custommenu_show_id == custommenuItemId) {
		checkcustommenuItem = 1;
	  }
	  if( checkcustommenuItem && custommenuItemId.indexOf( 'custommenu-child-item' ) != -1 ) {
		$('#' + custommenuItemId).remove();
	  }
	  	  
    });
	
	$('#' + custommenu_show_id).remove();
	var listing = custommenu_show_id.split('-');
	
	if(listing[1] == 'child') {
		var link = deletecustommenuChildHref;
	} else {
		var link = deletecustommenuHref;
	}
	
	$.ajax({
		url: link,
		type: 'post',
		data: {custommenu_id:custommenu_id},
		dataType: 'json',
		success: function(json) {},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

/**
 * WordPress Administration Navigation custommenu
 * Interface JS functions
 *
 * @version 2.0.0
 *
 * @package WordPress
 * @subpackage Administration
 */

/* global custommenus, postboxes, columns, isRtl, navcustommenuL10n, ajaxurl */

var wpNavcustommenu;

(function($) {

	var api;

	api = wpNavcustommenu = {

		options : {
			custommenuItemDepthPerLevel : 30, // Do not use directly. Use depthToPx and pxToDepth instead.
			globalMaxDepth : 1
		},

		custommenuList : undefined,	// Set in init.
		targetList : undefined, // Set in init.
		custommenusChanged : false,
		isRTL: !! ( 'undefined' != typeof isRtl && isRtl ),
		negateIfRTL: ( 'undefined' != typeof isRtl && isRtl ) ? -1 : 1,

		// Functions that run on init.
		init : function() {
			api.custommenuList = $('#custommenu-to-edit');
			api.targetList = api.custommenuList;

			this.jQueryExtensions();

			this.attachcustommenuEditListeners();

			this.setupInputWithDefaultTitle();
			this.attachQuickSearchListeners();
			this.attachThemeLocationsListeners();

			this.attachTabsPanelListeners();

			this.attachUnsavedChangesListener();

			if ( api.custommenuList.length )
			this.initSortables();

            this.initManageLocations();

			this.initAccessibility();

			this.initToggles();

			this.initPreviewing();
		},

		jQueryExtensions : function() {
			// jQuery extensions
			$.fn.extend({
				custommenuItemDepth : function() {
					var margin = api.isRTL ? this.eq(0).css('margin-right') : this.eq(0).css('margin-left');
					return api.pxToDepth( margin && -1 != margin.indexOf('px') ? margin.slice(0, -2) : 0 );
				},
				updateDepthClass : function(current, prev) {
					return this.each(function(){
						var t = $(this);
						
						prev = prev || t.custommenuItemDepth();
						if(current<3){
							$(this).removeClass('custommenu-item-depth-'+ prev )
								.addClass('custommenu-item-depth-'+ current );
						} else {
							$(this).removeClass('custommenu-item-depth-'+ prev )
								.addClass('custommenu-item-depth-1');
						}
					});
				},
				shiftDepthClass : function(change) {
					return this.each(function(){
						var t = $(this),
							depth = t.custommenuItemDepth();
						if(depth + change < 3) {
							$(this).removeClass('custommenu-item-depth-'+ depth )
								.addClass('custommenu-item-depth-'+ (depth + change) );
						} else {
							$(this).removeClass('custommenu-item-depth-'+ depth )
								.addClass('custommenu-item-depth-1');
						}
					});
				},
				childcustommenuItems : function() {
					var result = $();
					this.each(function(){
						var t = $(this), depth = t.custommenuItemDepth(), next = t.next();
						while( next.length && next.custommenuItemDepth() > depth ) {
							result = result.add( next );
							next = next.next();
						}
					});
					return result;
				},
				shiftHorizontally : function( dir ) {
					return this.each(function(){
						var t = $(this),
							depth = t.custommenuItemDepth(),
							newDepth = depth + dir;

						// Change .custommenu-item-depth-n class
						t.moveHorizontally( newDepth, depth );
					});
				},
				moveHorizontally : function( newDepth, depth ) {
					return this.each(function(){
						var t = $(this),
							children = t.childcustommenuItems(),
							diff = newDepth - depth,
							subItemText = t.find('.is-subcustommenu');

						// Change .custommenu-item-depth-n class
						t.updateDepthClass( newDepth, depth ).updateParentcustommenuItemDBId();

						// If it has children, move those too
						if ( children ) {
							children.each(function() {
								var t = $(this),
									thisDepth = t.custommenuItemDepth(),
									newDepth = thisDepth + diff;
								t.updateDepthClass(newDepth, thisDepth).updateParentcustommenuItemDBId();
							});
						}

						// Show "Sub item" helper text
						if (0 === newDepth)
							subItemText.hide();
						else
							subItemText.show();
					});
				},
				updateParentcustommenuItemDBId : function() {
					return this.each(function(){
						var item = $(this),
							input = item.find( '.custommenu-item-data-parent-id' ),
							depth = parseInt( item.custommenuItemDepth(), 10 ),
							parentDepth = depth - 1,
							parent = item.prevAll( '.custommenu-item-depth-' + parentDepth ).first();
						var custommenuType = ( parent.find( '.custommenu-item-data-typecustommenu' ).val() );

							
						if ( 0 === depth ) { // Item is on the top level, has no parent
							input.val(0);
//							custommenuType.val('Maincustommenu');
						} else { // Find the parent item, and retrieve its object id.
							if( custommenuType == 'Childcustommenu') {
								input.val( parent.find( '.custommenu-item-data-db-id' ).val() + '_1');
							} else {
								input.val( parent.find( '.custommenu-item-data-db-id' ).val() );
							}
						}
					});
				},
				hideAdvancedcustommenuItemFields : function() {
					return this.each(function(){
						var that = $(this);
						$('.hide-column-tog').not(':checked').each(function(){
							that.find('.field-' + $(this).val() ).addClass('hidden-field');
						});
					});
				},
				/**
				 * Adds selected custommenu items to the custommenu.
				 *
				 * @param jQuery metabox The metabox jQuery object.
				 */
				addSelectedTocustommenu : function(processMethod) {
					if ( 0 === $('#custommenu-to-edit').length ) {
						return false;
					}

					return this.each(function() {
						var t = $(this), custommenuItems = {},
							checkboxes = ( custommenus.oneThemeLocationNocustommenus && 0 === t.find( '.tabs-panel-active .categorychecklist li input:checked' ).length ) ? t.find( '#page-all li input[type="checkbox"]' ) : t.find( '.tabs-panel-active .categorychecklist li input:checked' ),
							re = /custommenu-item\[([^\]]*)/;

						processMethod = processMethod || api.addcustommenuItemToBottom;

						// If no items are checked, bail.
						if ( !checkboxes.length )
							return false;

						// Show the ajax spinner
						t.find('.spinner').show();

						// Retrieve custommenu item data
						$(checkboxes).each(function(){
							var t = $(this),
								listItemDBIDMatch = re.exec( t.attr('name') ),
								listItemDBID = 'undefined' == typeof listItemDBIDMatch[1] ? 0 : parseInt(listItemDBIDMatch[1], 10);

							if ( this.className && -1 != this.className.indexOf('add-to-top') )
								processMethod = api.addcustommenuItemToTop;
							custommenuItems[listItemDBID] = t.closest('li').getItemData( 'add-custommenu-item', listItemDBID );
						});

						// Add the items
						api.addItemTocustommenu(custommenuItems, processMethod, function(){
							// Deselect the items and hide the ajax spinner
							checkboxes.removeAttr('checked');
							t.find('.spinner').hide();
						});
					});
				},
				getItemData : function( itemType, id ) {
					itemType = itemType || 'custommenu-item';

					var itemData = {}, i,
					fields = [
						'custommenu-item-db-id',
						'custommenu-item-object-id',
						'custommenu-item-object',
						'custommenu-item-parent-id',
						'custommenu-item-position',
						'custommenu-item-type',
						'custommenu-item-title',
						'custommenu-item-url',
						'custommenu-item-description',
						'custommenu-item-attr-title',
						'custommenu-item-target',
						'custommenu-item-classes',
						'custommenu-item-xfn'
					];

					if( !id && itemType == 'custommenu-item' ) {
						id = this.find('.custommenu-item-data-db-id').val();
					}

					if( !id ) return itemData;

					this.find('input').each(function() {
						var field;
						i = fields.length;
						while ( i-- ) {
							if( itemType == 'custommenu-item' )
								field = fields[i] + '[' + id + ']';
							else if( itemType == 'add-custommenu-item' )
								field = 'custommenu-item[' + id + '][' + fields[i] + ']';

							if (
								this.name &&
								field == this.name
							) {
								itemData[fields[i]] = this.value;
							}
						}
					});

					return itemData;
				},
				setItemData : function( itemData, itemType, id ) { // Can take a type, such as 'custommenu-item', or an id.
					itemType = itemType || 'custommenu-item';

					if( !id && itemType == 'custommenu-item' ) {
						id = $('.custommenu-item-data-db-id', this).val();
					}

					if( !id ) return this;

					this.find('input').each(function() {
						var t = $(this), field;
						$.each( itemData, function( attr, val ) {
							if( itemType == 'custommenu-item' )
								field = attr + '[' + id + ']';
							else if( itemType == 'add-custommenu-item' )
								field = 'custommenu-item[' + id + '][' + attr + ']';

							if ( field == t.attr('name') ) {
								t.val( val );
							}
						});
					});
					return this;
				}
			});
		},

		countcustommenuItems : function( depth ) {
			return $( '.custommenu-item-depth-' + depth ).length;
		},

		movecustommenuItem : function( $this, dir ) {

			var items, newItemPosition, newDepth,
				custommenuItems = $( '#custommenu-to-edit li' ),
				custommenuItemsCount = custommenuItems.length,
				thisItem = $this.parents( 'li.custommenu-item' ),
				thisItemChildren = thisItem.childcustommenuItems(),
				thisItemData = thisItem.getItemData(),
				thisItemDepth = parseInt( thisItem.custommenuItemDepth(), 10 ),
				thisItemPosition = parseInt( thisItem.index(), 10 ),
				nextItem = thisItem.next(),
				nextItemChildren = nextItem.childcustommenuItems(),
				nextItemDepth = parseInt( nextItem.custommenuItemDepth(), 10 ) + 1,
				prevItem = thisItem.prev(),
				prevItemDepth = parseInt( prevItem.custommenuItemDepth(), 10 ),
				prevItemId = prevItem.getItemData()['custommenu-item-db-id'];

			switch ( dir ) {
			case 'up':
				newItemPosition = thisItemPosition - 1;

				// Already at top
				if ( 0 === thisItemPosition )
					break;

				// If a sub item is moved to top, shift it to 0 depth
				if ( 0 === newItemPosition && 0 !== thisItemDepth )
					thisItem.moveHorizontally( 0, thisItemDepth );

				// If prev item is sub item, shift to match depth
				if ( 0 !== prevItemDepth )
					thisItem.moveHorizontally( prevItemDepth, thisItemDepth );

				// Does this item have sub items?
				if ( thisItemChildren ) {
					items = thisItem.add( thisItemChildren );
					// Move the entire block
					items.detach().insertBefore( custommenuItems.eq( newItemPosition ) ).updateParentcustommenuItemDBId();
				} else {
					thisItem.detach().insertBefore( custommenuItems.eq( newItemPosition ) ).updateParentcustommenuItemDBId();
				}
				break;
			case 'down':
				// Does this item have sub items?
				if ( thisItemChildren ) {
					items = thisItem.add( thisItemChildren ),
						nextItem = custommenuItems.eq( items.length + thisItemPosition ),
						nextItemChildren = 0 !== nextItem.childcustommenuItems().length;

					if ( nextItemChildren ) {
						newDepth = parseInt( nextItem.custommenuItemDepth(), 10 ) + 1;
						thisItem.moveHorizontally( newDepth, thisItemDepth );
					}

					// Have we reached the bottom?
					if ( custommenuItemsCount === thisItemPosition + items.length )
						break;

					items.detach().insertAfter( custommenuItems.eq( thisItemPosition + items.length ) ).updateParentcustommenuItemDBId();
				} else {
					// If next item has sub items, shift depth
					if ( 0 !== nextItemChildren.length )
						thisItem.moveHorizontally( nextItemDepth, thisItemDepth );

					// Have we reached the bottom
					if ( custommenuItemsCount === thisItemPosition + 1 )
						break;
					thisItem.detach().insertAfter( custommenuItems.eq( thisItemPosition + 1 ) ).updateParentcustommenuItemDBId();
				}
				break;
			case 'top':
				// Already at top
				if ( 0 === thisItemPosition )
					break;
				// Does this item have sub items?
				if ( thisItemChildren ) {
					items = thisItem.add( thisItemChildren );
					// Move the entire block
					items.detach().insertBefore( custommenuItems.eq( 0 ) ).updateParentcustommenuItemDBId();
				} else {
					thisItem.detach().insertBefore( custommenuItems.eq( 0 ) ).updateParentcustommenuItemDBId();
				}
				break;
			case 'left':
				// As far left as possible
				if ( 0 === thisItemDepth )
					break;
				thisItem.shiftHorizontally( -1 );
				break;
			case 'right':
				// Can't be sub item at top
				if ( 0 === thisItemPosition )
					break;
				// Already sub item of prevItem
				if ( thisItemData['custommenu-item-parent-id'] === prevItemId )
					break;
				thisItem.shiftHorizontally( 1 );
				break;
			}
			$this.focus();
			api.registerChange();
			api.refreshKeyboardAccessibility();
			api.refreshAdvancedAccessibility();
		},

		initAccessibility : function() {
			var custommenu = $( '#custommenu-to-edit' );

			api.refreshKeyboardAccessibility();
			api.refreshAdvancedAccessibility();
		},

		refreshAdvancedAccessibility : function() {

			// Hide all links by default
			$( '.custommenu-item-settings .field-move a' ).css( 'display', 'none' );

			$( '.item-edit' ).each( function() {
				var thisLink, thisLinkText, primaryItems, itemPosition, title,
					parentItem, parentItemId, parentItemName, subItems,
					$this = $(this),
					custommenuItem = $this.closest( 'li.custommenu-item' ).first(),
					depth = custommenuItem.custommenuItemDepth(),
					isPrimarycustommenuItem = ( 0 === depth ),
					itemName = $this.closest( '.custommenu-item-handle' ).find( '.custommenu-item-title' ).text(),
					position = parseInt( custommenuItem.index(), 10 ),
					prevItemDepth = ( isPrimarycustommenuItem ) ? depth : parseInt( depth - 1, 10 ),
					prevItemNameLeft = custommenuItem.prevAll('.custommenu-item-depth-' + prevItemDepth).first().find( '.custommenu-item-title' ).text(),
					prevItemNameRight = custommenuItem.prevAll('.custommenu-item-depth-' + depth).first().find( '.custommenu-item-title' ).text(),
					totalcustommenuItems = $('#custommenu-to-edit li').length,
					hasSameDepthSibling = custommenuItem.nextAll( '.custommenu-item-depth-' + depth ).length;

				// Where can they move this custommenu item?
                $this.prop('title', title).html( title );
			});
		},

		refreshKeyboardAccessibility : function() {
			$( '.item-edit' ).off( 'focus' ).on( 'focus', function(){
				$(this).off( 'keydown' ).on( 'keydown', function(e){

					var arrows,
						$this = $( this ),
						thisItem = $this.parents( 'li.custommenu-item' ),
						thisItemData = thisItem.getItemData();

					// Bail if it's not an arrow key
					if ( 37 != e.which && 38 != e.which && 39 != e.which && 40 != e.which )
						return;

					// Avoid multiple keydown events
					$this.off('keydown');

					// Bail if there is only one custommenu item
					if ( 1 === $('#custommenu-to-edit li').length )
						return;

					// If RTL, swap left/right arrows
					arrows = { '38': 'up', '40': 'down', '37': 'left', '39': 'right' };
					if ( $('body').hasClass('rtl') )
						arrows = { '38' : 'up', '40' : 'down', '39' : 'left', '37' : 'right' };

					switch ( arrows[e.which] ) {
					case 'up':
						api.movecustommenuItem( $this, 'up' );
						break;
					case 'down':
						api.movecustommenuItem( $this, 'down' );
						break;
					case 'left':
						api.movecustommenuItem( $this, 'left' );
						break;
					case 'right':
						api.movecustommenuItem( $this, 'right' );
						break;
					}
					// Put focus back on same custommenu item
					$( '#edit-' + thisItemData['custommenu-item-db-id'] ).focus();
					return false;
				});
			});
		},

		initPreviewing : function() {
			// Update the item handle title when the navigation label is changed.
			$( '#custommenu-to-edit' ).on( 'change input', '.edit-custommenu-item-title', function(e) {
				var input = $( e.currentTarget ), title, titleEl;
				title = input.val();
				titleEl = input.closest( '.custommenu-item' ).find( '.custommenu-item-title' );
				// Don't update to empty title.
				if ( title ) {
					titleEl.text( title ).removeClass( 'no-title' );
				} else {
					titleEl.text( navcustommenuL10n.untitled ).addClass( 'no-title' );
				}
			} );
		},

		initToggles : function() {


			// hide fields
			api.custommenuList.hideAdvancedcustommenuItemFields();

			$('.hide-postbox-tog').click(function () {
				var hidden = $( '.accordion-container li.accordion-section' ).filter(':hidden').map(function() { return this.id; }).get().join(',');
				$.post(ajaxurl, {
					action: 'closed-postboxes',
					hidden: hidden,
					closedpostboxesnonce: jQuery('#closedpostboxesnonce').val(),
					page: 'nav-custommenus'
				});
			});
		},

		initSortables : function() {
			var currentDepth = 0, originalDepth, minDepth, maxDepth,
				prev, next, prevBottom, nextThreshold, helperHeight, transport,
				custommenuEdge = api.custommenuList.offset().left,
				body = $('body'), maxChildDepth,
				custommenuMaxDepth = initialcustommenuMaxDepth();

			if( 0 !== $( '#custommenu-to-edit li' ).length )
				$( '.drag-instructions' ).show();

			// Use the right edge if RTL.
			custommenuEdge += api.isRTL ? api.custommenuList.width() : 0;

			api.custommenuList.sortable({
				handle: '.custommenu-item-handle',
				placeholder: 'sortable-placeholder',
				start: function(e, ui) {
					var height, width, parent, children, tempHolder;

					// handle placement for rtl orientation
					if ( api.isRTL )
						ui.item[0].style.right = 'auto';

					transport = ui.item.children('.custommenu-item-transport');

					// Set depths. currentDepth must be set before children are located.
					originalDepth = ui.item.custommenuItemDepth();
					updateCurrentDepth(ui, originalDepth);

					// Attach child elements to parent
					// Skip the placeholder
					parent = ( ui.item.next()[0] == ui.placeholder[0] ) ? ui.item.next() : ui.item;
					children = parent.childcustommenuItems();
					transport.append( children );

					// Update the height of the placeholder to match the moving item.
					height = transport.outerHeight();
					// If there are children, account for distance between top of children and parent
					height += ( height > 0 ) ? (ui.placeholder.css('margin-top').slice(0, -2) * 1) : 0;
					height += ui.helper.outerHeight();
					helperHeight = height;
					height -= 2; // Subtract 2 for borders
					ui.placeholder.height(height);

					// Update the width of the placeholder to match the moving item.
					maxChildDepth = originalDepth;
					children.each(function(){
						var depth = $(this).custommenuItemDepth();
						maxChildDepth = (depth > maxChildDepth) ? depth : maxChildDepth;
					});
					width = ui.helper.find('.custommenu-item-handle').outerWidth(); // Get original width
					width += api.depthToPx(maxChildDepth - originalDepth); // Account for children
					width -= 2; // Subtract 2 for borders
					ui.placeholder.width(width);

					// Update the list of custommenu items.
					tempHolder = ui.placeholder.next();
					tempHolder.css( 'margin-top', helperHeight + 'px' ); // Set the margin to absorb the placeholder
					ui.placeholder.detach(); // detach or jQuery UI will think the placeholder is a custommenu item
					$(this).sortable( 'refresh' ); // The children aren't sortable. We should let jQ UI know.
					ui.item.after( ui.placeholder ); // reattach the placeholder.
					tempHolder.css('margin-top', 0); // reset the margin

					// Now that the element is complete, we can update...
					updateSharedVars(ui);
				},
				stop: function(e, ui) {
					var children, subcustommenuTitle,
						depthChange = currentDepth - originalDepth;

					// Return child elements to the list
					children = transport.children().insertAfter(ui.item);

					// Add "sub custommenu" description
					subcustommenuTitle = ui.item.find( '.item-title .is-subcustommenu' );
					if ( 0 < currentDepth )
						subcustommenuTitle.show();
					else
						subcustommenuTitle.hide();

					// Update depth classes
					if ( 0 !== depthChange ) {
						ui.item.updateDepthClass( currentDepth );
						children.shiftDepthClass( depthChange );
						updatecustommenuMaxDepth( depthChange );
					}
					// Register a change
					api.registerChange();
					// Update the item data.
					ui.item.updateParentcustommenuItemDBId();

					// address sortable's incorrectly-calculated top in opera
					ui.item[0].style.top = 0;

					// handle drop placement for rtl orientation
					if ( api.isRTL ) {
						ui.item[0].style.left = 'auto';
						ui.item[0].style.right = 0;
					}
					
					$.ajax({
						url: changecustommenuPosition,
						type: 'post',
						data: jQuery('#form-custommenu').serialize(),
						error: function(xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
										
					api.refreshKeyboardAccessibility();
					api.refreshAdvancedAccessibility();
					
				},
				change: function(e, ui) {
					// Make sure the placeholder is inside the custommenu.
					// Otherwise fix it, or we're in trouble.
					if( ! ui.placeholder.parent().hasClass('custommenu') )
						(prev.length) ? prev.after( ui.placeholder ) : api.custommenuList.prepend( ui.placeholder );

					updateSharedVars(ui);
				},
				sort: function(e, ui) {
					var offset = ui.helper.offset(),
						edge = api.isRTL ? offset.left + ui.helper.width() : offset.left,
						depth = api.negateIfRTL * api.pxToDepth( edge - custommenuEdge );
					// Check and correct if depth is not within range.
					// Also, if the dragged element is dragged upwards over
					// an item, shift the placeholder to a child position.
					if ( depth > maxDepth || offset.top < prevBottom ) depth = maxDepth;
					else if ( depth < minDepth ) depth = minDepth;

					if( depth != currentDepth )
						updateCurrentDepth(ui, depth);

					// If we overlap the next element, manually shift downwards
					if( nextThreshold && offset.top + helperHeight > nextThreshold ) {
						next.after( ui.placeholder );
						updateSharedVars( ui );
						$( this ).sortable( 'refreshPositions' );
					}
				}
			});

			function updateSharedVars(ui) {
				var depth;

				prev = ui.placeholder.prev();
				next = ui.placeholder.next();

				// Make sure we don't select the moving item.
				if( prev[0] == ui.item[0] ) prev = prev.prev();
				if( next[0] == ui.item[0] ) next = next.next();

				prevBottom = (prev.length) ? prev.offset().top + prev.height() : 0;
				nextThreshold = (next.length) ? next.offset().top + next.height() / 3 : 0;
				minDepth = (next.length) ? next.custommenuItemDepth() : 0;

				if( prev.length )
					maxDepth = ( (depth = prev.custommenuItemDepth() + 1) > api.options.globalMaxDepth ) ? api.options.globalMaxDepth : depth;
				else
					maxDepth = 0;
			}

			function updateCurrentDepth(ui, depth) {
				ui.placeholder.updateDepthClass( depth, currentDepth );
				currentDepth = depth;
			}

			function initialcustommenuMaxDepth() {
				if( ! body[0].className ) return 0;
				var match = body[0].className.match(/custommenu-max-depth-(\d+)/);
				return match && match[1] ? parseInt( match[1], 10 ) : 0;
			}

			function updatecustommenuMaxDepth( depthChange ) {
				var depth, newDepth = custommenuMaxDepth;
				if ( depthChange === 0 ) {
					return;
				} else if ( depthChange > 0 ) {
					depth = maxChildDepth + depthChange;
					if( depth > custommenuMaxDepth )
						newDepth = depth;
				} else if ( depthChange < 0 && maxChildDepth == custommenuMaxDepth ) {
					while( ! $('.custommenu-item-depth-' + newDepth, api.custommenuList).length && newDepth > 0 )
						newDepth--;
				}
				// Update the depth class.
				body.removeClass( 'custommenu-max-depth-' + custommenuMaxDepth ).addClass( 'custommenu-max-depth-' + newDepth );
				custommenuMaxDepth = newDepth;
			}
		},

		initManageLocations : function () {
			$('#custommenu-locations-wrap form').submit(function(){
				window.onbeforeunload = null;
			});
			$('.custommenu-location-custommenus select').on('change', function () {
				var editLink = $(this).closest('tr').find('.locations-edit-custommenu-link');
				if ($(this).find('option:selected').data('orig'))
					editLink.show();
				else
					editLink.hide();
			});
		},

		attachcustommenuEditListeners : function() {
			var that = this;
			$('#update-nav-custommenu').bind('click', function(e) {
				if ( e.target && e.target.className ) {
					if ( -1 != e.target.className.indexOf('item-edit') ) {
						return that.eventOnClickEditLink(e.target);
					} else if ( -1 != e.target.className.indexOf('custommenu-save') ) {
						return that.eventOnClickcustommenuSave(e.target);
					} else if ( -1 != e.target.className.indexOf('custommenu-delete') ) {
						return that.eventOnClickcustommenuDelete(e.target);
					} else if ( -1 != e.target.className.indexOf('item-delete') ) {
						return that.eventOnClickcustommenuItemDelete(e.target);
					} else if ( -1 != e.target.className.indexOf('item-cancel') ) {
						return that.eventOnClickCancelLink(e.target);
					}
				}
			});
			$('#add-custom-links input[type="text"]').keypress(function(e){
				if ( e.keyCode === 13 ) {
					e.preventDefault();
					$( '#submit-customlinkdiv' ).click();
				}
			});
		},

		/**
		 * An interface for managing default values for input elements
		 * that is both JS and accessibility-friendly.
		 *
		 * Input elements that add the class 'input-with-default-title'
		 * will have their values set to the provided HTML title when empty.
		 */
		setupInputWithDefaultTitle : function() {
			var name = 'input-with-default-title';

			$('.' + name).each( function(){
				var $t = $(this), title = $t.attr('title'), val = $t.val();
				$t.data( name, title );

				if( '' === val ) $t.val( title );
				else if ( title == val ) return;
				else $t.removeClass( name );
			}).focus( function(){
				var $t = $(this);
				if( $t.val() == $t.data(name) )
					$t.val('').removeClass( name );
			}).blur( function(){
				var $t = $(this);
				if( '' === $t.val() )
					$t.addClass( name ).val( $t.data(name) );
			});

			$( '.blank-slate .input-with-default-title' ).focus();
		},

		attachThemeLocationsListeners : function() {
			var loc = $('#nav-custommenu-theme-locations'), params = {};
			params.action = 'custommenu-locations-save';
			params['custommenu-settings-column-nonce'] = $('#custommenu-settings-column-nonce').val();
			loc.find('input[type="submit"]').click(function() {
				loc.find('select').each(function() {
					params[this.name] = $(this).val();
				});
				loc.find('.spinner').show();
				$.post( ajaxurl, params, function() {
					loc.find('.spinner').hide();
				});
				return false;
			});
		},

		attachQuickSearchListeners : function() {
			var searchTimer;

			$('.quick-search').keypress(function(e){
				var t = $(this);

				if( 13 == e.which ) {
					api.updateQuickSearchResults( t );
					return false;
				}

				if( searchTimer ) clearTimeout(searchTimer);

				searchTimer = setTimeout(function(){
					api.updateQuickSearchResults( t );
				}, 400);
			}).attr('autocomplete','off');
		},

		updateQuickSearchResults : function(input) {
			var panel, params,
			minSearchLength = 2,
			q = input.val();

			if( q.length < minSearchLength ) return;

			panel = input.parents('.tabs-panel');
			params = {
				'action': 'custommenu-quick-search',
				'response-format': 'markup',
				'custommenu': $('#custommenu').val(),
				'custommenu-settings-column-nonce': $('#custommenu-settings-column-nonce').val(),
				'q': q,
				'type': input.attr('name')
			};

			$('.spinner', panel).show();

			$.post( ajaxurl, params, function(custommenuMarkup) {
				api.processQuickSearchQueryResponse(custommenuMarkup, params, panel);
			});
		},

		addCustomLink : function( processMethod ) {
			var url = $('#custom-custommenu-item-url').val(),
				label = $('#custom-custommenu-item-name').val();

			processMethod = processMethod || api.addcustommenuItemToBottom;

			if ( '' === url || 'http://' == url )
				return false;

			// Show the ajax spinner
			$('.customlinkdiv .spinner').show();
			this.addLinkTocustommenu( url, label, processMethod, function() {
				// Remove the ajax spinner
				$('.customlinkdiv .spinner').hide();
				// Set custom link form back to defaults
				$('#custom-custommenu-item-name').val('').blur();
				$('#custom-custommenu-item-url').val('http://');
			});
		},

		addLinkTocustommenu : function(url, label, processMethod, callback) {
			processMethod = processMethod || api.addcustommenuItemToBottom;
			callback = callback || function(){};

			api.addItemTocustommenu({
				'-1': {
					'custommenu-item-type': 'custom',
					'custommenu-item-url': url,
					'custommenu-item-title': label
				}
			}, processMethod, callback);
		},

		addItemTocustommenu : function(custommenuItem, processMethod, callback) {
			var custommenu = $('#custommenu').val(),
				nonce = $('#custommenu-settings-column-nonce').val(),
				params;

			processMethod = processMethod || function(){};
			callback = callback || function(){};

			params = {
				'action': 'add-custommenu-item',
				'custommenu': custommenu,
				'custommenu-settings-column-nonce': nonce,
				'custommenu-item': custommenuItem
			};

			$.post( ajaxurl, params, function(custommenuMarkup) {
				var ins = $('#custommenu-instructions');

				custommenuMarkup = $.trim( custommenuMarkup ); // Trim leading whitespaces
				processMethod(custommenuMarkup, params);

				// Make it stand out a bit more visually, by adding a fadeIn
				$( 'li.pending' ).hide().fadeIn('slow');
				$( '.drag-instructions' ).show();
				if( ! ins.hasClass( 'custommenu-instructions-inactive' ) && ins.siblings().length )
					ins.addClass( 'custommenu-instructions-inactive' );

				callback();
			});
		},

		/**
		 * Process the add custommenu item request response into custommenu list item.
		 *
		 * @param string custommenuMarkup The text server response of custommenu item markup.
		 * @param object req The request arguments.
		 */
		addcustommenuItemToBottom : function( custommenuMarkup ) {
			$(custommenuMarkup).hideAdvancedcustommenuItemFields().appendTo( api.targetList );
			api.refreshKeyboardAccessibility();
			api.refreshAdvancedAccessibility();
		},

		addcustommenuItemToTop : function( custommenuMarkup ) {
			$(custommenuMarkup).hideAdvancedcustommenuItemFields().prependTo( api.targetList );
			api.refreshKeyboardAccessibility();
			api.refreshAdvancedAccessibility();
		},

		attachUnsavedChangesListener : function() {
			$('#custommenu-management input, #custommenu-management select, #custommenu-management, #custommenu-management textarea, .custommenu-location-custommenus select').change(function(){
				api.registerChange();
			});

			if ( 0 !== $('#custommenu-to-edit').length || 0 !== $('.custommenu-location-custommenus select').length ) {
				window.onbeforeunload = function(){
					if ( api.custommenusChanged )
						return navcustommenuL10n.saveAlert;
				};
			} else {
				// Make the post boxes read-only, as they can't be used yet
				$( '#custommenu-settings-column' ).find( 'input,select' ).end().find( 'a' ).attr( 'href', '#' ).unbind( 'click' );
			}
		},

		registerChange : function() {
			api.custommenusChanged = true;
		},

		attachTabsPanelListeners : function() {
			$('#custommenu-settings-column').bind('click', function(e) {
				var selectAreaMatch, panelId, wrapper, items,
					target = $(e.target);

				if ( target.hasClass('nav-tab-link') ) {

					panelId = target.data( 'type' );

					wrapper = target.parents('.accordion-section-content').first();

					// upon changing tabs, we want to uncheck all checkboxes
					$('input', wrapper).removeAttr('checked');

					$('.tabs-panel-active', wrapper).removeClass('tabs-panel-active').addClass('tabs-panel-inactive');
					$('#' + panelId, wrapper).removeClass('tabs-panel-inactive').addClass('tabs-panel-active');

					$('.tabs', wrapper).removeClass('tabs');
					target.parent().addClass('tabs');

					// select the search bar
					$('.quick-search', wrapper).focus();

					e.preventDefault();
				} else if ( target.hasClass('select-all') ) {
					selectAreaMatch = /#(.*)$/.exec(e.target.href);
					if ( selectAreaMatch && selectAreaMatch[1] ) {
						items = $('#' + selectAreaMatch[1] + ' .tabs-panel-active .custommenu-item-title input');
						if( items.length === items.filter(':checked').length )
							items.removeAttr('checked');
						else
							items.prop('checked', true);
						return false;
					}
				} else if ( target.hasClass('submit-add-to-custommenu') ) {
					api.registerChange();

					if ( e.target.id && 'submit-customlinkdiv' == e.target.id )
						api.addCustomLink( api.addcustommenuItemToBottom );
					else if ( e.target.id && -1 != e.target.id.indexOf('submit-') )
						$('#' + e.target.id.replace(/submit-/, '')).addSelectedTocustommenu( api.addcustommenuItemToBottom );
					return false;
				} else if ( target.hasClass('page-numbers') ) {
					$.post( ajaxurl, e.target.href.replace(/.*\?/, '').replace(/action=([^&]*)/, '') + '&action=custommenu-get-metabox',
						function( resp ) {
							if ( -1 == resp.indexOf('replace-id') )
								return;

							var metaBoxData = $.parseJSON(resp),
							toReplace = document.getElementById(metaBoxData['replace-id']),
							placeholder = document.createElement('div'),
							wrap = document.createElement('div');

							if ( ! metaBoxData.markup || ! toReplace )
								return;

							wrap.innerHTML = metaBoxData.markup ? metaBoxData.markup : '';

							toReplace.parentNode.insertBefore( placeholder, toReplace );
							placeholder.parentNode.removeChild( toReplace );

							placeholder.parentNode.insertBefore( wrap, placeholder );

							placeholder.parentNode.removeChild( placeholder );

						}
					);

					return false;
				}
			});
		},

		eventOnClickEditLink : function(clickedEl) {
			var settings, item,
			matchedSection = /#(.*)$/.exec(clickedEl.href);
			if ( matchedSection && matchedSection[1] ) {
				settings = $('#'+matchedSection[1]);
				item = settings.parent();
				if( 0 !== item.length ) {
					if( item.hasClass('custommenu-item-edit-inactive') ) {
						if( ! settings.data('custommenu-item-data') ) {
							settings.data( 'custommenu-item-data', settings.getItemData() );
						}
						settings.slideDown('fast');
						item.removeClass('custommenu-item-edit-inactive')
							.addClass('custommenu-item-edit-active');
					} else {
						settings.slideUp('fast');
						item.removeClass('custommenu-item-edit-active')
							.addClass('custommenu-item-edit-inactive');
					}
					return false;
				}
			}
		},

		eventOnClickCancelLink : function(clickedEl) {
			var settings = $( clickedEl ).closest( '.custommenu-item-settings' ),
				thiscustommenuItem = $( clickedEl ).closest( '.custommenu-item' );
			thiscustommenuItem.removeClass('custommenu-item-edit-active').addClass('custommenu-item-edit-inactive');
			settings.setItemData( settings.data('custommenu-item-data') ).hide();
			return false;
		},

		eventOnClickcustommenuSave : function() {
			var locs = '',
			custommenuName = $('#custommenu-name'),
			custommenuNameVal = custommenuName.val();
			// Cancel and warn if invalid custommenu name
			if( !custommenuNameVal || custommenuNameVal == custommenuName.attr('title') || !custommenuNameVal.replace(/\s+/, '') ) {
				custommenuName.parent().addClass('form-invalid');
				return false;
			}
			// Copy custommenu theme locations
			$('#nav-custommenu-theme-locations select').each(function() {
				locs += '<input type="hidden" name="' + this.name + '" value="' + $(this).val() + '" />';
			});
			$('#update-nav-custommenu').append( locs );
			// Update custommenu item position data
			api.custommenuList.find('.custommenu-item-data-position').val( function(index) { return index + 1; } );
			window.onbeforeunload = null;

			return true;
		},

		eventOnClickcustommenuDelete : function() {
			// Delete warning AYS
			if ( window.confirm( navcustommenuL10n.warnDeletecustommenu ) ) {
				window.onbeforeunload = null;
				return true;
			}
			return false;
		},

		eventOnClickcustommenuItemDelete : function(clickedEl) {
			var itemID = parseInt(clickedEl.id.replace('delete-', ''), 10);
			api.removecustommenuItem( $('#custommenu-item-' + itemID) );
			api.registerChange();
			return false;
		},

		/**
		 * Process the quick search response into a search result
		 *
		 * @param string resp The server response to the query.
		 * @param object req The request arguments.
		 * @param jQuery panel The tabs panel we're searching in.
		 */
		processQuickSearchQueryResponse : function(resp, req, panel) {
			var matched, newID,
			takenIDs = {},
			form = document.getElementById('nav-custommenu-meta'),
			pattern = /custommenu-item[(\[^]\]*/,
			$items = $('<div>').html(resp).find('li'),
			$item;

			if( ! $items.length ) {
				$('.categorychecklist', panel).html( '<li><p>' + navcustommenuL10n.noResultsFound + '</p></li>' );
				$('.spinner', panel).hide();
				return;
			}

			$items.each(function(){
				$item = $(this);

				// make a unique DB ID number
				matched = pattern.exec($item.html());

				if ( matched && matched[1] ) {
					newID = matched[1];
					while( form.elements['custommenu-item[' + newID + '][custommenu-item-type]'] || takenIDs[ newID ] ) {
						newID--;
					}

					takenIDs[newID] = true;
					if ( newID != matched[1] ) {
						$item.html( $item.html().replace(new RegExp(
							'custommenu-item\\[' + matched[1] + '\\]', 'g'),
							'custommenu-item[' + newID + ']'
						) );
					}
				}
			});

			$('.categorychecklist', panel).html( $items );
			$('.spinner', panel).hide();
		},

		removecustommenuItem : function(el) {
			var children = el.childcustommenuItems();

			el.addClass('deleting').animate({
					opacity : 0,
					height: 0
				}, 350, function() {
					var ins = $('#custommenu-instructions');
					el.remove();
					children.shiftDepthClass( -1 ).updateParentcustommenuItemDBId();
					if ( 0 === $( '#custommenu-to-edit li' ).length ) {
						$( '.drag-instructions' ).hide();
						ins.removeClass( 'custommenu-instructions-inactive' );
					}
				});
		},

		depthToPx : function(depth) {
			return depth * api.options.custommenuItemDepthPerLevel;
		},

		pxToDepth : function(px) {
			return Math.floor(px / api.options.custommenuItemDepthPerLevel);
		}

	};

	$(document).ready(function(){ wpNavcustommenu.init(); });

})(jQuery);