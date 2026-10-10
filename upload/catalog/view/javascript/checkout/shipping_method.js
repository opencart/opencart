import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/shipping_method');

customElements.define('shipping-method', class extends WebComponent {
    constructor() {
        super();

        this.data = new Map();
    }

    async render() {
        let data = new Map();

        if (this.hasAttribute('disabled')) {

        }

        return loader.template('checkout/shipping_method', [ data, language ]);
    }

    async getMethods(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        await ajax.post('action.php?route=checkout/shipping_method.quote&language=' + local.get('language'), form, {
            beforeSend: () => {
                this.form.state.set('submitting', true);
            },
            onComplete: () => {
                this.form.state.set('submitting', false);
            },
            onSuccess: (json) => {
                this.form.state.clear();

                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                if (json['shipping_methods']) {
                    $('#modal-shipping').remove();

                    let html = '<ui-modal>';

                    html += '<modal-title><i class="fa fa-truck"></i> {{ text_shipping_method | escape('js') }}</modal-title>';
                    html += '<modal-body>';
                    html += '  <form id="form-shipping-method">';
                    html += '    <p>{{ text_shipping | escape: 'js' }}</p>';

                    var first = true;

                    for (let i in json['shipping_methods']) {
                        html += '<p><strong>' + json['shipping_methods'][i]['name'] + '</strong></p>';

                        if (!json['shipping_methods'][i]['error']) {
                            for (let j in json['shipping_methods'][i]['quote']) {
                                let html;
                                html += '<div class="form-check">';

                                var code = i + '-' + j.replaceAll('_', '-');

                                html += '<input type="radio" name="shipping_method" value="' + json['shipping_methods'][i]['quote'][j]['code'] + '" id="input-shipping-method-' + code + '"';

                                var method = $('#input-shipping-code').val();

                                if ((json['shipping_methods'][i]['quote'][j]['code'] == method) || (!method && first)) {
                                    html += ' checked';

                                    first = false;
                                }

                                html += '/>';
                                html += '  <label for="input-shipping-method-' + code + '">' + json['shipping_methods'][i]['quote'][j]['name'] + ' - <x-currency code="{{ currency }}" amount="' + json['shipping_methods'][i]['quote'][j]['cost'] + '"></x-currency></label>';
                                html += '</div>';
                            }
                        } else {
                            html += '<div class="alert alert-danger">' + json['shipping_methods'][i]['error'] + '</div>';
                        }
                    }

                    html += '          <div class="text-end">';
                    html += '            <button type="submit" id="button-shipping-method" class="btn btn-primary">{{ button_continue|escape('js') }}</button>';
                    html += '          </div>';
                    html += '    </form>';
                    html += '  </modal-body>';
                    html += '</ui-modal>';

                    $('body').append(html);

                    $('#modal-shipping').modal('show');
                }
            },
            onError: (xhr, ajaxOptions, thrownError)=> {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    async handleSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        await ajax.post('action.php?route=checkout/shipping_method.save&language=' + local.get('language'), form, {
            beforeSend: () => {
                this.form.state.set('submitting', true);
            },
            onComplete: () => {
                this.form.state.set('submitting', false);
            },
            onSuccess: async (json) => {
                console.log(json);

                if (json['redirect']) {
                    location = json['redirect'];
                }

                if (json.has('error')) {
                    $('#alert').prepend('<ui-alert type="danger">' + json['error'] + '</ui-alert>');
                }

                if (json.has('success')) {
                    $('#alert').prepend('<ui-alert type="success">' + json.get('success') + '</ui-alert>');

                    //$('#modal-shipping').modal('hide');

                    //$('#input-shipping-method').val($('input[name=\'shipping_method\']:checked').parent().find('label').text());
                    //$('#input-shipping-code').val($('input[name=\'shipping_method\']:checked').val());

                    //$('#input-payment-method').val('');

                    //$('#cart').load('index.php?route=common/cart.info&language={{ language }}');
                    //$('#checkout-confirm').load('index.php?route=checkout/confirm.confirm&language={{ language }}');
                }
            },
            onError: (xhr, ajaxOptions, thrownError) => {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});