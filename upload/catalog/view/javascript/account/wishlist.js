import { WebComponent } from '../index.js';
import { loader, customer } from '../index.js';

// Language
const language = await loader.language('account/wishlist');

export default class AccountWishlist extends WebComponent {
    render() {
        let data = new Map();

        data.set('wishlist', []);

        if (customer.isLogged()) {
            data.set('wishlist', customer.getWishlist());
        }

       return loader.template('account/wishlist', [ data, language ]);
    }

    remove() {

    }
}

customElements.define('account-wishlist', AccountWishlist);

/*
$('#wishlist').on('click', '.btn-danger', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).attr('href'),
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
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#wishlist').load('index.php?route=account/wishlist.list&language={{ language }}&customer_token={{ customer_token }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});
*/