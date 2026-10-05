import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/address');

export default class AddressForm extends WebComponent {
    render() {
        //if (!customer.isLogged()) return;

        let data = new Map();

        data.set('addresses', customer.getAddresses());

        return loader.template('account/address', [ data, language, config ]);
    }

    async onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/address.save&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            beforeSend: () => {
                this.submitter.state.add('loading');
            },
            onComplete: () => {
                this.submitter.state.remove('loading');
            },
            onSuccess: this.success.bind(this),
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }

    success(json){
        if (json.has('error')) {
            this.alert.append('<ui-alert type="danger">' + json.get('error') + '</ui-alert>');
        }

        if (json.get('success')) {
            this.alert.append('<ui-alert type="success">' + json.get('success') + '</ui-alert>');
        }
    }
}

customElements.define('address-form', AddressForm);