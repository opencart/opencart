import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/cart');

// library
const session = await loader.library('session');
const cart = await loader.library('cart');
const local = await loader.library('local');
const tax = await loader.library('tax');

// Currency
const currency = local.has('currency') ? local.get('currency') : config.config_currency;

export default class extends Controller {
    connected() {

    }

    async render() {
        let data = {};

        data.products = [];

        let products = cart.getProducts();



        data.currency = currency;

        return await loader.template('checkout/cart', { ...data,  ...language });
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.target.getAttribute('href');
    }

    editProduct(e) {
        e.preventDefault();

    }

    deleteProduct(e) {
        e.preventDefault();

    }

    open() {

    }

    close(e) {
        console.log('hi');

        let modal = document.getElementById('dialog');

        modal.close();
    }
};

/*
$('#shopping-cart').on('submit', '#output-cart form', function(e) {
    e.preventDefault();

    var element = this;

    if (e.originalEvent !== undefined && e.originalEvent.submitter !== undefined) {
        var button = e.originalEvent.submitter;
    } else {
        var button = '';
    }

    $.ajax({
        url: $(button).attr('formaction'),
        type: 'post',
        data: $(element).serialize(),
        dataType: 'json',
        beforeSend: function() {
            $(button).button('loading');
        },
        complete: function() {
            $(button).button('reset');
        },
        success: function(json) {
            console.log(json);

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#shopping-cart').load('index.php?route=checkout/cart.list&language={{ language }}', {}, function() {
                    $('#cart').load('index.php?route=common/cart.info&language={{ language }}');
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#shopping-cart').on('click', '.btn-danger', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).attr('href'),
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            console.log(json);

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#shopping-cart').load('index.php?route=checkout/cart.list&language={{ language }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#shopping-cart').observe(function(e) {
    $('#cart').load('index.php?route=common/cart.info&language={{ language }}');
});

$('#cart').on('submit', 'form', function(e) {
    window.setTimeout(function() {
        $('#shopping-cart').load('index.php?route=checkout/cart.list&language={{ language }}');
    }, 3000);
});
*/