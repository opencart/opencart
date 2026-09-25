import { WebComponent } from '../index.js';
import { loader, ajax, customer } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/address');

export default class AddressForm extends WebComponent {
    render() {
        let data = new Map();

        data.set('addresses', customer.getAddresses());

        return loader.template('account/address', [ data, language, config ]);
    }

    onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=account/address.save&language=' + + '&customer_token=' + customer.getToken(), form, {
            beforeSend: () => {
                this.submitter.button('loading');
            },
            onComplete: (json) => {
                this.submitter.button('reset');
            },
            onSuccess: this.success.bind(this),
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }

    success(json){
        if (json['error']) {
            this.alert.append('<ui-alert type="danger">' + json['error'] + '</ui-alert>');
        }

        if (json['success']) {
            this.alert.append('<ui-alert type="success">' + json['success'] + '</ui-alert>');
        }
    }
}

customElements.define('address-form', AddressForm);