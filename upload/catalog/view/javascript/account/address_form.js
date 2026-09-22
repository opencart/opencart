import { WebComponent } from '../component.js';
import { loader, ajax, customer } from '../index.js';

// Language
const language = await loader.language('account/address');

export default class AddressForm extends WebComponent {
    render() {
        let data = {};

        data.addresses = customer.getAddresses();

        return loader.template('account/address', { ...data, ...language });
    }

    onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=account/address.save&token=' + this.token, form, {
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
        let alert = document.getElementById('alert');

        if (json['error']) {
            alert.append('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }

        if (json['success']) {
            alert.append('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }
    }
}

customElements.define('address-form', AddressForm);