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

        data.products = cart.getProducts();

        console.log(data.products);

        let shipping = await cart.hasShipping();
        let download = await cart.hasDownload();
        let minimum = await cart.hasMinimum();

        data.currency = currency;

        return await loader.template('checkout/cart', { ...data,  ...language });
    }

    async add(e) {
        e.preventDefault();

        console.log('addToCart');

        //this.$button_cart.state = 'loading';

        let target = e.target;

        let form = new FormData(target);

        let response = await fetch('index.php?route=checkout/cart.add', {
            method: 'POST',
            body: form
        });

        if (!response.ok) {
            console.log(response);

            //throw new Error(response.thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }

        let json = await response.json();

        // Remove past error classes from inputs
        target.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
        target.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

        // Display error messages
        if (json['error'] !== undefined) {
            for (let key in json['error']) {
                let value = key.replaceAll('_', '-');

                let input = target.querySelector('#input-' + value);

                if (input) {
                    input.classList.add('is-invalid');

                    // If the element has inputs inside.
                    input.querySelectorAll('.form-control, .form-select, .form-check-input, .form-check-label').forEach(element => element.classList.add('is-invalid'));
                }

                let error = target.querySelector('#error-' + value);

                if (error) {
                    error.classList.add('d-block');
                }
            }
        }

        // Display success message
        if (json['success'] !== undefined) {
            let alert = target.querySelector('#alert');

            if (alert) {
                alert.prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            let output = [];

            let regex = /^([^\[]+)\[(\w+)\]$/g;

            for (let [ key, value] of form.entries()) {
                // Get Options. JavaScript is terrible!
                let matches = key.match(/^(.*?)\[(.*?)\]/);

                console.log('form.entries');
                console.log(key);
                console.log(value);
                console.log(matches);

                if (matches) {
                    let [ test, match] = matches;

                    console.log('match');
                    console.log(test);
                    console.log(match);

                    output[match] = value;
                }
            }

            let { product_id, quantity, option, subscription_plan_id } = Object.fromEntries(form);

            console.log(Object.fromEntries(form));

            cart.add(1, product_id, quantity, option, subscription_plan_id);

            let button = document.querySelector('#cart > button');

            button.click();
        }

        //this.$button_cart.state = '';
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