import { WebComponent } from '../index.js';
import { loader, customer, local } from '../index.js';

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

    add(e) {
        e.preventDefault();

    }

    async remove(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        await ajax.post('index.php?route=account/wishlist.list&language=' + local.get('language') + '&customer_token={{ customer_token }}', form, {
            beforeSend: function() {
                this.submitter.button('loading');
            },
            complete: function() {
                this.submitter.button('reset');
            },
            success: function(json) {

            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    success(json) {
        console.log(json);

        if (json.has('error')) {
            $('#alert').prepend('<ui-alert type="danger">' + json.get('error') + '</ui-alert>');
        }

        if (json.has('success')) {
            $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json.get('success') + '</ui-alert>');
        }
    }
}

customElements.define('account-wishlist', AccountWishlist);