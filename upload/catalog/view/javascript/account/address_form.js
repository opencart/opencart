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
            this.alert.append('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }

        if (json['success']) {
            this.alert.append('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }
    }
}

customElements.define('address-form', AddressForm);