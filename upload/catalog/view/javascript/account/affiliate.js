import { WebComponent } from '../index.js';
import { loader, ajax, customer } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/affiliate');

export default class AccountAffiliate extends WebComponent {
    token = '';

    render() {
        //if (!customer.isLogged()) return;

        return loader.template('account/affiliate', language);
    }

    onChange(e) {

    }

    onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=account/affiliate.save&affiliate_token=' + this.token, form, {
            beforeSend: () => {
                this.button.button('loading');
            },
            onComplete: (json) => {
                this.button.button('reset');
            },
            onSuccess: this.success.bind(this),
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('account-affiliate', AccountAffiliate);
/*
$('input[name=\'payment_method\']').on('change', function() {
    $('.payment').hide();

    $('#payment-' + this.value).show();
});

$('input[name=\'payment_method\']:checked').trigger('change');

$('#input-generator').autocomplete({
    'source': function(request, response) {
        return $.ajax({
            url: 'action.php?route=account/tracking.autocomplete&customer_token={{ customer_token }}&search=' + encodeURIComponent(request) + '&tracking=' + encodeURIComponent($('#input-code').val()) + '&language={{ language }}',
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['link']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-link').val(item['value']);
    }
});
 */