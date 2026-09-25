import { WebComponent } from '../index.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/subscription');

customElements.define('subscription-list', class extends WebComponent {
    render() {
        let data = new Map();

        return loader.template('account/subscription_list', [ data, language ]);
    }
});

/*
$('#button-cancel').on('click', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: 'action.php?route=account/subscription.cancel&language={{ language }}&customer_token={{ customer_token }}&subscription_id={{ subscription_id }}',
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            console.log(json);

            if (json['error']) {
                $('#alert').prepend('<ui-alert type="danger">' + json['error'] + '</ui-alert>');
            }

            if (json['success']) {
                $('#alert').prepend('<ui-alert type="success">' + json['success'] + '</ui-alert>');

                $('#history').load('action.php?route=account/subscription.history&language={{ language }}&customer_token={{ customer_token }}&subscription_id={{ subscription_id }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#history').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#history').load(this.href);
});

$('#order').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#order').load(this.href);
});
*/