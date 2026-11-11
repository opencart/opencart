import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Language
const language = await loader.language('account/wishlist');

export default class AccountWishlist extends WebComponent {
    render() {
        let data = new Map();

		if (customer.isLogged()) {
			data.set('wishlist', []);
		} else { 
			data.set('wishlist', customer.getWishlist());
		}

       return loader.template('account/wishlist', [ data, language ]);
    }

	onConnect() {
		this.form.addEventListener('form-submit', this.add.bind(this));
	}

    add(e) {
        e.preventDefault();


    }

    async remove(e) {
        e.preventDefault();

		await ajax.post({
			url: 'action.php?route=account/wishlist.list&language=' + local.get('language') + '&customer_token=' + customer.getToken(),
			data: this.form.getData(),
            handleSend: () => {
                this.form.toggleAttribute('submitting', true);
            },
            handleComplete: () => {
                this.form.toggleAttribute('submitting', false);
            },
            handleSuccess: (json) => {
                console.log(json);

                if (json.has('error')) {
                    $('#alert').prepend('<ui-alert type="danger">' + json.get('error') + '</ui-alert>');
                }

                if (json.has('success')) {
                    $('#alert').prepend('<ui-alert type="alert-success">' + json.get('success') + '</ui-alert>');
                }
            },
            handleError: (xhr, ajaxOptions, thrownError) => {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
}

customElements.define('account-wishlist', AccountWishlist);