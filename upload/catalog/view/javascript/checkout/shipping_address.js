import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer, local, session } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/shipping_address');

export default class ShippingAddress extends WebComponent {
    async render() {
        if (!cart.hasShipping()) return;

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

		if (session.has('shipping_address')) {
			let address = session.get('shipping_address');

			data.set('firstname', address.firstname);
			data.set('lastname', address.lastname);
			data.set('address_1', address.address_1);
			data.set('address_2', address.address_2);
			data.set('city', address.city);
			data.set('postcode', address.postcode);
			data.set('country_id', parseInt(address.country_id));
			data.set('zone_id', parseInt(address.zone_id));
		}
		
        console.log(data);

        return loader.template('checkout/shipping_address', [ data, language,  config ]);
	}
	
    handleChange(e) {
        //e.target

        if ($(e.target).val() == 1) {
            $('#shipping-existing').show();
            $('#shipping-new').hide();
        } else {
            $('#shipping-existing').hide();
            $('#shipping-new').show();
        }
    }

    async setAddress(e) {
        e.preventDefault();

		let shipping_address = session.get('shipping_address');

		
    }

    async handleSubmit(e) {
        e.preventDefault();

		await ajax.post({
			url: 'action.php?route=checkout/shipping_address&language=' + local.get('language') + '&customer_token=' + customer.getToken(),
			data: this.form.getData(),
            handleSend: () => {
                this.form.set('submitting', true);
            },
            handleComplete: () => {
                this.form.set('submitting', false);
            },
            handleSuccess: (json) => {
                if (json.has('error')) {
                    this.form.set('error', json.get('error'));
                }

                if (json.has('success')) {
                    this.form.set('success', json.get('success'));

                    $('#form-shipping-address')[0].reset();

                    var html = '<option value="">{{ text_select | escape: \'js\' }}</option>';

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

                    $('#checkout-confirm').load('action.php?route=checkout/confirm.confirm&language={{ language }}');
                }
            },
            handleError: (xhr, ajaxOptions, thrownError) => {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
}

customElements.define('shipping-address', ShippingAddress);