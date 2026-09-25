import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/cart');

customElements.define('shipping-address', class extends WebComponent {
    async render()  {
        let data = {};

        data.firstname = customer.getFirstName();
        data.lastname = customer.getLastName();

        data.addresses = customer.getAddresses();

        return loader.template('checkout/shipping_address', { ...data,  ...language,  ...config });
    }

    async onConnect() {

    }

    onExisting(e) {

        if ($(this).val() == 1) {
            $('#shipping-existing').show();
            $('#shipping-new').hide();
        } else {
            $('#shipping-existing').hide();
            $('#shipping-new').show();
        }
    }

    onSubmit(e) {
        e.preventDefault();

        ajax.post('index.php?route=checkout/shipping_address.save&language={{ language }}', form, {
            beforeSend: function() {
                $('#button-shipping-address').button('loading');
            },
            complete: function() {
                $('#button-shipping-address').button('reset');
            },
            success: function(json) {
                console.log(json);

                $('#form-shipping-address').find('.is-invalid').removeClass('is-invalid');
                $('#form-shipping-address').find('.invalid-feedback').removeClass('d-block');

                if (json['redirect']) {
                    location = json['redirect'];
                }

                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#alert').prepend('<ui-alert type="danger">' + json['error']['warning'] + '</ui-alert>');
                    }

                    for (i in json['error']) {
                        for (key in json['error']) {
                            $('#input-shipping-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                            $('#error-shipping-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                        }
                    }
                }

                if (json['success']) {
                    $('#alert').prepend('<ui-alert type="success">' + json['success'] + '</ui-alert>');

                    $('#form-shipping-address')[0].reset();

                    var html = '<option value="">{{ text_select|escape('js') }}</option>';

                    if (json['addresses']) {
                        for (let i in json['addresses']) {
                            html += '<option value="' + json['addresses'][i]['address_id'] + '">' + json['addresses'][i]['firstname'] + ' ' + json['addresses'][i]['lastname'] + ', ' + (json['addresses'][i]['company'] ? json['addresses'][i]['company'] + ', ' : '') + json['addresses'][i]['address_1'] + ', ' + json['addresses'][i]['city'] + ', ' + json['addresses'][i]['zone'] + ', ' + json['addresses'][i]['country'] + '</option>';
                        }
                    }

                    // Shipping Address
                    $('#input-shipping-address').html(html);

                    $('#input-shipping-address').val(json['address_id']);

                    $('#shipping-addresses').css({display: 'block'});

                    $('#input-shipping-existing').trigger('click');

                    // Payment Address
                    var payment_address_id = $('#input-payment-address').val();

                    $('#input-payment-address').html(html);

                    if (payment_address_id) {
                        $('#input-payment-address').val(payment_address_id);
                    }

                    $('#payment-addresses').css({display: 'block'});

                    $('#input-payment-existing').trigger('click');

                    $('#input-shipping-method').val('');
                    $('#input-payment-method').val('');

                    $('#checkout-confirm').load('index.php?route=checkout/confirm.confirm&language={{ language }}');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

    }

    onChange(e) {
        var element = this;

        ajax.get('index.php?route=checkout/shipping_address.address&language={{ language }}&address_id=' + $(element).val(), {
            dataType: 'json',
            beforeSend: function() {
                $(element).prop('disabled', true);
            },
            complete: function() {
                $(element).prop('disabled', false);
            },
            success: function(json) {
                console.log(json);

                $('#input-shipping-address').removeClass('is-invalid');
                $('#error-shipping-address').removeClass('d-block');

                if (json['redirect']) {
                    location = json['redirect'];
                }

                if (json['error']) {
                    $('#input-shipping-address').addClass('is-invalid');
                    $('#error-shipping-address').html(json['error']).addClass('d-block');
                }

                if (json['success']) {
                    $('#alert').prepend('<ui-alert type="success">' + json['success'] + '</ui-alert>');

                    $('#input-shipping-method').val('');
                    $('#input-payment-method').val('');

                    $('#checkout-confirm').load('index.php?route=checkout/confirm.confirm&language={{ language }}');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});