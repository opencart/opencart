$(document).ready(function() {
    /* Search */
    $('.button-search').on('click', function() {
        url = $('base').attr('href') + 'index.php?route=product/search';
                 
        var search = $('input[name=\'search\']').prop('value');
        
        if (search) {
            url += '&search=' + encodeURIComponent(search);
        }
        
        location = url;
    });

    $('#search input[name=\'search\']').keydown(function(e) {
        if (e.keyCode == 13) {
            url = $('base').attr('href') + 'index.php?route=product/search';
             
            var search = $('input[name=\'search\']').prop('value');
            
            if (search) {
                url += '&search=' + encodeURIComponent(search);
            }
            
            location = url;
        }
    });

    // Navigation - Columns
    $('.main-navbar .dropdown-menu').each(function(){

        var menu = $('.main-navbar').offset();
        var dropdown = $(this).parent().offset();

        var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('.main-navbar').outerWidth());

        if (i > 0) {
            $(this).css('margin-left', '-' + (i + 5) + 'px');
        }

    });

    // every 3 product-thumbs gets put into .row div
    $('.layout-row-3').each(function(){

        var divs = $(this).children();
        for(var i = 0; i < divs.length; i+=3) {
          divs.slice(i, i+3).wrapAll("<div class='row'></div>");
        }

    });

    // every 4 product-thumbs gets put into .row div
    $('.layout-row-4').each(function(){

        var divs = $(this).children();
        for(var i = 0; i < divs.length; i+=4) {
          divs.slice(i, i+4).wrapAll("<div class='row'></div>");
        }

    });
    
    // change product-grid to product-list
    $('#list-view').click(function() {
        $('.product-grid').removeClass('product-grid').addClass('product-list');
        $('.product-thumb').addClass('clearfix');

    });
    // change product-list to product-grid
    $('#grid-view').click(function() {
        $('.product-list').removeClass('product-list').addClass('product-grid');
        $('.product-thumb').removeClass('clearfix');

    });

    // tooltips on hover


   $('[data-toggle=\'tooltip\']').tooltip();
});

function getURLVar(key) {
    var value = [];
    
    var query = String(document.location).split('?');
    
    if (query[1]) {
        var part = query[1].split('&');

        for (i = 0; i < part.length; i++) {
            var data = part[i].split('=');
            
            if (data[0] && data[1]) {
                value[data[0]] = data[1];
            }
        }
        
        if (value[key]) {
            return value[key];
        } else {
            return '';
        }
    }
} 

function addToCart(product_id, quantity) {
    quantity = typeof(quantity) != 'undefined' ? quantity : 1;

    $.ajax({
        url: 'index.php?route=checkout/cart/add',
        type: 'post',
        data: 'product_id=' + product_id + '&quantity=' + quantity,
        dataType: 'json',
        success: function(json) {
            $('.success, .warning, .attention, .information, .error').remove();
            
            if (json['redirect']) {
                window.location = json['redirect'];
            }
            
            if (json['success']) {

                $('#notification').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['success'] + '</div>');
                
                $('.success').fadeIn('slow');
                
                $('#cart-total').html(json['total']);
                
                $('html, body').animate({ scrollTop: 0 }, 'slow'); 
            }   
        }
    });
}

function addToWishList(product_id) {
    $.ajax({
        url: 'index.php?route=account/wishlist/add',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        success: function(json) {
            $('.success, .warning, .attention, .information').remove();
                        
            if (json['success']) {
                $('#notification').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['success'] + '</div>');
                
                $('.success').fadeIn('slow');
                
                $('#wishlist-total').html(json['total']);
                
                $('html, body').animate({ scrollTop: 0 }, 'slow');
            }   
        }
    });
}

function addToCompare(product_id) { 
    $.ajax({
        url: 'index.php?route=product/compare/add',
        type: 'post',
        data: 'product_id=' + product_id,
        dataType: 'json',
        success: function(json) {
            $('.success, .warning, .attention, .information').remove();
                        
            if (json['success']) {
                $('#notification').html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['success'] + '</div>');
                
                $('.success').fadeIn('slow');
                
                $('#compare-total').html(json['total']);
                
                $('html, body').animate({ scrollTop: 0 }, 'slow'); 
            }   
        }
    });
}

/* Agree to terms */
$(document).delegate('.agree', 'click', function(e) {
	//e.De
	
	$('#agree').remove(); 
	
	$(this).attr('href'); 
	
	html = '<div class="modal fade" id="agree">';
    html += '<div class="modal-dialog">';
    html += '  <div class="modal-content">';
    html += '    <div class="modal-header">';
    html += '     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
    html += '      <h4 class="modal-title">Modal title</h4>';
    html += '    </div>';
    html += '    <div class="modal-body">';
    
    html += '    </div>';
    html += '    <div class="modal-footer">';
    html += '      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>';
    html += '      <button type="button" class="btn btn-primary">Save changes</button>';
    html += '    </div>;'
    html += '  </div';
    html += '</div>';
	html += '</div>';

});

/* Autocomplete */
(function($) {
	function Autocomplete(element, options) {
		this.element = element;
		this.options = options;
		this.timer = null;
		this.items = new Array();

		$(element).attr('autocomplete', 'off');
		$(element).on('focus', $.proxy(this.focus, this));
		$(element).on('blur', $.proxy(this.blur, this));
		$(element).on('keydown', $.proxy(this.keydown, this));
		
		$(element).after('<ul class="dropdown-menu"></ul>');
		$(element).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));		
	}
	
	Autocomplete.prototype = {
		focus: function() {
			this.request();
		},
		blur: function() {
			setTimeout(function(object) {
				object.hide();
			}, 200, this);
		},
		click: function(event) {
			event.preventDefault();
			
			value = $(event.target).parent().attr('data-value');
			
			if (value && this.items[value]) {
				this.options.select(this.items[value]);
			}
		},	
		keydown: function(event) {
			switch(event.keyCode) {
				case 27: // escape
					this.hide();
					break;
				default:
					this.request();
					break;
			}
		},
		show: function() {
			var pos = $(this.element).position();
			
			$(this.element).siblings('ul.dropdown-menu').css({
				top: pos.top + $(this.element).outerHeight(),
				left: pos.left
			});
						
			$(this.element).siblings('ul.dropdown-menu').show();			
		},
		hide: function() {
			$(this.element).siblings('ul.dropdown-menu').hide();
		},
		request: function() {
			clearTimeout(this.timer);
			
			this.timer = setTimeout(function(object) {
				object.options.source($(object.element).val(), $.proxy(object.response, object));
			}, 200, this);
		},		
		response: function(json) {
			html = '';
			
			if (json.length) {
				for (i = 0; i < json.length; i++) {
					this.items[json[i]['value']] = json[i];				
				}
				
				for (i = 0; i < json.length; i++) {
					if (!json[i]['category']) {
						html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
					}
				}	
				
				// Get all the ones with a categories
				var category = new Array();
				
				for (i = 0; i < json.length; i++) {
					if (json[i]['category']) { 
						if (!category[json[i]['category']]) {
							category[json[i]['category']] = new Array();
							category[json[i]['category']]['name'] = json[i]['category'];
							category[json[i]['category']]['item'] = new Array();
						}
						
						category[json[i]['category']]['item'].push(json[i]);
					}
				}
				
				for (i in category) {
					html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';
					
					for (j = 0; j < category[i]['item'].length; j++) {
						html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
					}
				}
			}
			
			if (html) {
				this.show();
			} else {
				this.hide();
			}
			
			$(this.element).siblings('ul.dropdown-menu').html(html);

		}
	};

	$.fn.autocomplete = function(option) {
		return this.each(function() {
			var data = $(this).data('autocomplete');
			
			if (!data) {
				data = new Autocomplete(this, option);
				
				$(this).data('autocomplete', data);
			}
		});	
	}
})(window.jQuery);