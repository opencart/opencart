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

        data.set('firstname', '');
        data.set('lastname', '');
        data.set('address_1', '');
        data.set('address_2', '');
        data.set('city', '');
        data.set('postcode', '');
        data.set('country_id', config.get('config_country_id'));
        data.set('zone_id', '');

        if (this.hasAttribute('address_id')) {
            let address = customer.getAddress(this.getAttribute('address_id'));

            data.set('firstname', address.firstname);
            data.set('lastname', address.lastname);
            data.set('address_1', address.address_1);
            data.set('address_2', address.address_2);
            data.set('city', address.city);
            data.set('postcode', address.postcode);
            data.set('country_id', address.country_id);
            data.set('zone_id', address.zone_id);
        }

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
            onSuccess: (json) => {
                if (json.has('error')) {
                    this.alert.append('<ui-alert type="danger">' + json.get('error') + '</ui-alert>');
                }

                if (json.has('success')) {
                    this.alert.append('<ui-alert type="success">' + json.get('success') + '</ui-alert>');
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('address-form', AddressForm);