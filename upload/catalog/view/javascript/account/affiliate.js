import { Controller } from '../component.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('cms/article_info');

export default class extends Controller {
    data = [];

    async connected() {
        this.load.language('account/affiliate');

        return loader.template('account/address', this.language.all());
    }
};




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