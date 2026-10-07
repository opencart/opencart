import { WebComponent } from '../index.js';
import { loader, ajax, customer, local, session } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/payment_address');

customElements.define('payment-address', class extends WebComponent {
    async render() {
        let data = new Map();

        data.set('firstname', '');
        data.set('lastname', '');
        data.set('address_1', '');
        data.set('address_2', '');
        data.set('city', '');
        data.set('postcode', '');
        data.set('country_id', config.get('config_country_id'));
        data.set('zone_id', '');
        data.set('addresses', []);

        if (customer.isLogged()) {
            data.set('logged', true);
            data.set('firstname', customer.getFirstName());
            data.set('lastname', customer.getLastName());
            data.set('addresses', customer.getAddresses());
        }

        if (session.has('payment_address')) {
            let address = session.get('payment_address');

            data.set('firstname', address.firstname);
            data.set('lastname', address.lastname);
            data.set('address_1', address.address_1);
            data.set('address_2', address.address_2);
            data.set('city', address.city);
            data.set('postcode', address.postcode);
            data.set('country_id', address.country_id);
            data.set('zone_id', address.zone_id);
        }

        return loader.template('checkout/payment_address', [ data, language ]);
    }

    setAddress(e) {
        e.preventDefault();

        var element = this;

        ajax.post('action.php?route=checkout/payment_address.address&language=' + local.get('language') + '&customer_token' + customer.getToken() + '&address_id=' + this.address.value, {
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

                if (json.has('success')) {
                    $('#alert').prepend('<ui-alert type="success">' + json.get('success') + '</ui-alert>');

                    $('#input-shipping-method').val('');
                    $('#input-payment-method').val('');

                    $('#checkout-confirm').load('action.php?route=checkout/confirm.confirm&language={{ language }}');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    // New Payment Address
    onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=checkout/payment_address.save&language=', form, {
            beforeSend: function() {
                this.submitter.state.add('loading');
            },
            complete: function() {
                this.submitter.state.remove('loading');
            },
            success: this.success.bind(this),
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    success(json) {
        $('#form-payment-address').find('.is-invalid').removeClass('is-invalid');
        $('#form-payment-address').find('.invalid-feedback').removeClass('d-block');

        if (json.has('redirect')) {
            location = json.get('redirect');
        }

        if (json.has('error')) {
            if (json['error']['warning']) {
                $('#alert').prepend('<ui-alert type="danger">' + json['error']['warning'] + '</ui-alert>');
            }

            for (let i in json['error']) {
                for (let key in json['error']) {
                    $('#input-payment-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-payment-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }
        }

        if (json.has('success')) {
            $('#alert').prepend('<ui-alert type="success">' + json.get('success') + '</ui-alert>');

            $('#form-payment-address')[0].reset();

            var html = '<option value="">{{ text_select }}</option>';

            if (json.has('addresses')) {
                for (let i in json.get('addresses')) {
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

            $('#checkout-confirm').load('action.php?route=checkout/confirm.confirm&language={{ language }}');
        }
    }
});