import { WebComponent } from '../component.js';
import { loader } from '../loader.js';

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('catalog/product');

// Library
const tax = await loader.library('tax');
const weight = await loader.library('weight');
const length = await loader.library('length');

class ProductInfo extends WebComponent {
    async render() {
        let data = {};

        // customer groups
        let product = loader.storage('product/product-' + this.getAttribute('product_id'));

        if (product.length) {
            data.thumb = product.thumb;
            data.popup = product.popup;
            data.images = product.image;

            data.name = product.name;
            data.description = product.description;
            data.model = product.model;

            data.codes = product.code;

            data.manufacturer_id = product.manufacturer_id;
            data.manufacturer = product.manufacturer;

            data.price = tax.calculate(product.price);
            data.special = tax.calculate(product.special);
            data.tax = '';

            if (config.config_tax) {
                data.tax = product.special ? product.special : product.price;
            }

            data.quantity = product.quantity;
            data.minimum = product.minimum;
            data.stock_status = product.stock_status;

            data.points = product.points;
            data.rewards = product.rewards;

            data.sales = product.sales;
            data.rating = product.rating;

            data.weight = product.weight;
            data.weight_class_id = product.weight_class_id;

            data.length = product.length;
            data.width = product.width;
            data.height = product.height;
            data.length_class_id = product.length_class_id;

            data.attributes = product.attribute;

            data.discounts = [];

            for (let discount of product.discount) {
                data.discounts = [];
            }

            data.options = product.option;

            data.subscriptions = product.subscription;
        }

        return loader.template('catalog/product_info', { ...data, ...language, ...config });
    }

    onChange() {


    }

    submit() {


    }


}

customElements.define('product-info', ProductInfo);


$('#input-subscription').on('change', function(e) {
    var element = this;

    $('.subscription').addClass('d-none');

    $('#subscription-description-' + $(element).val()).removeClass('d-none');
});

$('#form-product').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=checkout/cart.add&language={{ language }}',
        type: 'post',
        data: $('#form-product').serialize(),
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded',
        cache: false,
        processData: false,
        beforeSend: function() {
            $('#button-cart').button('loading');
        },
        complete: function() {
            $('#button-cart').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('#form-product').find('.is-invalid').removeClass('is-invalid');
            $('#form-product').find('.invalid-feedback').removeClass('d-block');

            if (json['error']) {
                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#cart').load('index.php?route=common/cart.info&language={{ language }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$(document).ready(function() {
    $('.magnific-popup').magnificPopup({
        type: 'image',
        delegate: 'a',
        gallery: {
            enabled: true
        }
    });
});