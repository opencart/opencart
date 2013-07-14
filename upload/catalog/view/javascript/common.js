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

    // Cart Dropdown
    $(document).on('click', '#cart > .dropdown-toggle', function() {
        $('#cart').load('/index.php?route=module/cart #cart > *');
    });
    
    // Notifications.
    $('.success img, .warning img, .attention img, .information img').click(function() {
        $(this).parent().fadeOut('slow', function() {
            $(this).remove();
        });
    });

    // Language Dropdown
    $('#language-menu li a').on('click', function(){

        var languageVal = $(this).children('img').html();
        $('#language-choice').html(languageVal);

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