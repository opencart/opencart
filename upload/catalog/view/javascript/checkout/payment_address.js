import { WebComponent } from '../index.js';
import { loader, ajax, customer, local, session } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/payment_address');

export default class PaymentAddress extends WebComponent {
    async render() {
        let data = new Map();

		data.set('logged', customer.isLogged());

		data.set('addresses', []);

        data.set('firstname', '');
        data.set('lastname', '');
        data.set('address_1', '');
        data.set('address_2', '');
        data.set('city', '');
        data.set('postcode', '');
        data.set('country_id', parseInt(config.get('config_country_id')));
        data.set('zone_id', 0);

		if (customer.isLogged()) {
			data.set('addresses', customer.getAddresses());

            data.set('firstname', customer.getFirstName());
            data.set('lastname', customer.getLastName());
        }

        if (session.has('payment_address')) {
            let address = session.get('payment_address');

            data.set('firstname', address.firstname);
            data.set('lastname', address.lastname);
            data.set('address_1', address.address_1);
            data.set('address_2', address.address_2);
            data.set('city', address.city);
            data.set('postcode', address.postcode);
            data.set('country_id', parseInt(address.country_id));
            data.set('zone_id', parseInt(address.zone_id));
        }

        return loader.template('checkout/payment_address', [ data, language ]);
    }

    handleChange(e) {
        //e.target

        if ($(this).val() == 1) {
            $('#shipping-existing').show();
            $('#shipping-new').hide();
        } else {
            $('#shipping-existing').hide();
            $('#shipping-new').show();
        }
    }

    async setAddress(e) {
        e.preventDefault();

    }

    handleSubmit(e) {
        e.preventDefault();

		ajax.post({
			url: 'action.php?route=checkout/payment_address&language=' + local.get('language') + '&customer_token=' + customer.getToken(),
			data: this.form.getData(),
            handleSend: () => {
                this.form.toggleAttribute('submitting', true);
            },
            handleComplete: () => {
                this.form.toggleAttribute('submitting', false);
            },
            handleSuccess: (json) => {
                if (json.has('redirect')) {
                    location = json.get('redirect');
                }

                if (json.has('error')) {
                    this.form.setError(json.get('error'));
                }

                if (json.has('success')) {
                    this.form.setSuccess(json.get('success'));

                    this.update();
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
            },
            handleError: (xhr, ajaxOptions, thrownError) => {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
}

customElements.define('payment-address', PaymentAddress);