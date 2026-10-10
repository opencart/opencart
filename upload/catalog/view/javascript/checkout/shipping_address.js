import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/shipping_address');

customElements.define('shipping-address', class extends WebComponent {
    async render()  {
        if (!cart.hasShipping()) return;

        let data = new Map();

        data.set('firstname', '');
        data.set('lastname', '');
        data.set('address_1', '');
        data.set('address_2', '');
        data.set('city', '');
        data.set('postcode', '');
        data.set('country_id', parseInt(config.get('config_country_id')));
        data.set('zone_id', 0);
        data.set('addresses', []);

        if (customer.isLogged()) {
            data.set('logged', true);
            data.set('firstname', customer.getFirstName());
            data.set('lastname', customer.getLastName());
            data.set('addresses', customer.getAddresses());
        } else {
            data.set('logged', false);
            data.set('firstname', '');
            data.set('lastname', '');
        }

        data.set('address_1', '');
        data.set('address_2', '');
        data.set('city', '');
        data.set('postcode', '');
        data.set('country_id', parseInt(config.get('config_country_id')));
        data.set('zone_id', 0);

        return loader.template('checkout/shipping_address', [ data,  language,  config ]);
    }

    onChange(e) {
        e.target

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

        await ajax.post('action.php?route=checkout/shipping_address.address&language=' + local.get('language') + '&address_id=' + element.value, {
            beforeSend: () => {
                this.form.state.set('submitting', true);
            },
            onComplete: () => {
                this.form.state.set('submitting', false);
            },
            success: (json)=> {
                this.form.state.clear();

                if (json.has('redirect')) {
                    location = json.get('redirect');
                }

                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                if (json.has('success')) {
                    this.form.set('success', json.get('success'));

                    //$('#input-shipping-method').val('');
                    //$('#input-payment-method').val('');

                    //$('#checkout-confirm').load('action.php?route=checkout/confirm.confirm&language={{ language }}');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    async onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        await ajax.post('action.php?route=checkout/shipping_address.save&language=' + local.get('language'), form, {
            beforeSend: () => {
                this.form.state.set('submitting', true);
            },
            onComplete: () => {
                this.form.state.set('submitting', false);
            },
            onsuccess: (json) => {
                this.form.state.clear();

                if (json.has('redirect')) {
                    location = json.get('redirect');
                }

                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
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
            error: (xhr, ajaxOptions, thrownError) => {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});