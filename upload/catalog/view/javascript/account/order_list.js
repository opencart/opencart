import { WebComponent } from '../index.js';
import { loader, customer } from '../index.js';

// Language
const language = await loader.language('account/order');

export default class OrderList extends WebComponent {
    render() {
        if (!customer.isLogged()) return;

        return loader.template('account/order', [ language ]);
    }


}

customElements.define('order-list', OrderList);
/*
var product_row = 0;

$('form').on('submit', function(e) {
    e.preventDefault();

    var element = this;

    if (e.originalEvent !== undefined && e.originalEvent.submitter !== undefined) {
        var button = e.originalEvent.submitter;
    } else {
        var button = '';
    }

    $.ajax({
        url: 'action.php?route=checkout/cart.add&language={{ language }}',
        type: 'post',
        data: $(element).serialize(),
        dataType: 'json',
        cache: false,
        processData: false,
        beforeSend: function() {
            $(button).button('loading');
        },
        complete: function() {
            $(button).button('reset');
        },
        success: function(json) {
            console.log(json);

            $('form').find('.invalid-feedback').removeClass('d-block');

            if (json['error']) {
                $('#alert').prepend('<ui-alert type="danger">{{ error_reorder }}</ui-alert>');

                product_row = $(element).attr('id').substr(13);

                if (json['error']['warning']) {
                    $('#alert').prepend('<ui-alert type="danger">' + json['error']['warning'] + '</ui-alert>');

                    delete json['error']['warning'];
                }

                for (key in json['error']) {
                    $('#error-' + product_row + '-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            if (json['success']) {
                $('#alert').prepend('<ui-alert type="success">' + json['success'] + '</ui-alert>');

                $('#cart').load('action.php?route=common/cart.info&language={{ language }}');
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
 */