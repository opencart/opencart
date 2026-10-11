import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer, session } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/payment_method');

customElements.define('payment-method', class extends WebComponent {
    constructor() {
        super();

        this.data = new Map();
    }

    async render(){
        let data = new Map();

        session.get('payment');

        let html = '<ui-modal>';

        html += '<h5 slot="title" class="modal-title"><i class="fa fa-credit-card"></i> {{ text_payment_method | escape }}</h5>';
        html += '<form slot="body" id="form-payment-method">';
        html += '  <p>{{ text_payment | escape }}</p>';

        var first = true;

        for (let [ key_1, value ] of json.get('payment_methods')) {
            html += '<p><strong>' + value['name'] + '</strong></p>';

            if (!value['error']) {
                for (let [ key_2, option ] of value.option) {
                    html += '<div class="form-check">';

                    var code = key + '-' + j.replaceAll('_', '-');

                    html += '<input type="radio" name="payment_method" value="' + option['code'] + '" id="input-payment-method-' + code + '"';

                    var method = $('#input-payment-code').val();

                    if ((json['payment_methods'][i]['option'][j]['code'] == method) || (!method && first)) {
                        html += ' checked';

                        first = false;
                    }

                    html += '/>';
                    html += '  <label for="input-payment-method-' + code + '">' + json['payment_methods'][i]['option'][j]['name'] + '</label>';
                    html += '</div>';
                }
            } else {
                html += '<div class="alert alert-danger">' + value['error'] + '</div>';
            }
        }

        html += '  <div class="text-end">';
        html += '    <button type="submit" @ref="submitter" class="btn btn-primary">{{ button_continue }}</button>';
        html += '  </div>';
        html += '</form>';

        return loader.template(html, [ data, language ]);
    }

    handleConnect() {

    }

    handleDisconnect() {

    }

    handleSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=checkout/payment_method&language=' + local.get('language'), form, {
            handleSend: () => {
                this.form.state.set('submitting', true);
            },
            handleComplete: () => {
                this.form.state.set('submitting', false);
            },
            handleSuccess: (json) => {
                this.form.state.clear();

                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.state.set('success', json.get('success'));


                    $('#modal-payment').modal('hide');

                    $('#input-payment-method').val($('input[name=\'payment_method\']:checked').parent().find('label').text());
                    $('#input-payment-code').val($('input[name=\'payment_method\']:checked').val());

                    $('#checkout-confirm').load('action.php?route=checkout/confirm.confirm&language={{ language }}');
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }

    onAgree() {

    }
});

/*
$(document).on('change', '#input-checkout-agree', function() {
    var element = this;

    $.ajax({
        url: 'action.php?route=checkout/payment_method.agree&language={{ language }}',
        type: 'post',
        data: $('#input-checkout-agree').serialize(),
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded',
        beforeSend: function() {
            $('#button-confirm').button('loading');
        },
        complete: function() {
            $('#button-confirm').button('reset');
        },
        success: function(json) {
            $('#checkout-confirm').load('action.php?route=checkout/confirm.confirm&language={{ language }}');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});
*/