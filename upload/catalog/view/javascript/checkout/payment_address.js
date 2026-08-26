import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

export default class extends Controller {
    async connected() {

    }


    async render() {
        let data = {};



        return loader.template('checkout/payment_address', { ...data,  ...language });
    }
}


$(document).on('change', '#input-address-match', function() {
    if ($(this).prop('checked')) {
        $('#shipping-address').hide();
    } else {
        $('#shipping-address').show();
    }
});

$('input[name=\'payment_existing\']').on('change', function() {
    if ($(this).val() == 1) {
        $('#payment-existing').show();
        $('#payment-new').hide();
    } else {
        $('#payment-existing').hide();
        $('#payment-new').show();
    }
});

// Existing Payment Address
$('#input-payment-address').on('change', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=checkout/payment_address.address&language={{ language }}&address_id=' + $(element).val(),
        dataType: 'json',
        beforeSend: function() {
            $(element).prop('disabled', true);
        },
        complete: function() {
            $(element).prop('disabled', false);
        },
        success: function(json) {
            console.log(json);

            $('#input-payment-address').removeClass('is-invalid');
            $('#error-payment-address').removeClass('d-block');

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['error']) {
                $('#input-payment-address').addClass('is-invalid');
                $('#error-payment-address').html(json['error']).addClass('d-block');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#input-shipping-method').val('');
                $('#input-payment-method').val('');

                $('#checkout-confirm').load('index.php?route=checkout/confirm.confirm&language={{ language }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// New Payment Address
$('#form-payment-address').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=checkout/payment_address.save&language={{ language }}',
        type: 'post',
        data: $('#form-payment-address').serialize(),
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded',
        beforeSend: function() {
            $('#button-payment-address').button('loading');
        },
        complete: function() {
            $('#button-payment-address').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('#form-payment-address').find('.is-invalid').removeClass('is-invalid');
            $('#form-payment-address').find('.invalid-feedback').removeClass('d-block');

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['error']) {
                if (json['error']['warning']) {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (i in json['error']) {
                    for (key in json['error']) {
                        $('#input-payment-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                        $('#error-payment-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                    }
                }
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#form-payment-address')[0].reset();

                var html = '<option value="">{{ text_select|escape('js') }}</option>';

                if (json['addresses']) {
                    for (i in json['addresses']) {
                        html += '<option value="' + json['addresses'][i]['address_id'] + '">' + json['addresses'][i]['firstname'] + ' ' + json['addresses'][i]['lastname'] + ', ' + (json['addresses'][i]['company'] ? json['addresses'][i]['company'] + ', ' : '') + json['addresses'][i]['address_1'] + ', ' + json['addresses'][i]['city'] + ', ' + json['addresses'][i]['zone'] + ', ' + json['addresses'][i]['country'] + '</option>';
                    }
                }

                // Payment Address
                $('#input-payment-address').html(html);

                $('#input-payment-address').val(json['address_id']);

                $('#payment-addresses').css({display: 'block'});

                $('#input-payment-existing').trigger('click');

                // Shipping Address
                var shipping_address_id = $('#input-shipping-address').val();

                $('#input-shipping-address').html(html);

                if (shipping_address_id) {
                    $('#input-shipping-address').val(shipping_address_id);
                }

                $('#shipping-address').css({display: 'block'});
                $('#shipping-addresses').css({display: 'block'});

                $('#input-shipping-existing').trigger('click');

                $('#input-shipping-method').val('');
                $('#input-payment-method').val('');

                $('#checkout-confirm').load('index.php?route=checkout/confirm.confirm&language={{ language }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});