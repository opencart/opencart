import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/address_form');

export default class AddressForm extends WebComponent {
    async render() {
        //if (!customer.isLogged()) return;

        let data = new Map();

		if (!this.hasAttribute('address_id')) {
			data.set('firstname', '');
			data.set('lastname', '');
			data.set('company', '');
			data.set('address_1', '');
			data.set('address_2', '');
			data.set('city', '');
			data.set('postcode', '');
			data.set('country_id', parseInt(config.get('config_country_id')));
			data.set('zone_id', '');

			data.set('default', !customer.getAddressId() ? true : false);
		} else { 
			let address_id = parseInt(this.getAttribute('address_id'));

			let address = customer.getAddress(address_id);

			data.set('firstname', address.firstname);
			data.set('lastname', address.lastname);
			data.set('company', address.company);
			data.set('address_1', address.address_1);
			data.set('address_2', address.address_2);
			data.set('city', address.city);
			data.set('postcode', address.postcode);
			data.set('country_id', address.country_id);
			data.set('zone_id', address.zone_id);

			data.set('default', customer.getAddressId() == address_id);
		}

        return loader.template('account/address_form', [ data, language, config ]);
    }

    async handleConnect() {
		//if (customer.isLogged()) return;
		
		// Add event listener for form submit
		this.form.addEventListener('custom-submit', this.handleSubmit.bind(this));

		// Add event listener for country change
		this.form.country.addEventListener('custom-change', this.handleCountry.bind(this));
	}

	async handleCountry(e) {
		console.log(e);

		this.form.postcode.toggleAttribute('required', this.form.country.postcode_required == 1);
		this.form.zone.setAttribute('country_id', e.detail.value_new);
	}

    async handleSubmit(e) {
        e.preventDefault();

        //if (!customer.isLogged()) return;

		await ajax.post({
			url: 'action.php?route=account/address&language=' + local.get('language') + '&customer_token=' + customer.getToken(),
			data: this.form.getData(),
            handleSend: () => {
                this.form.toggleAttribute('submitting', true);
            },
            handleComplete: () => {
                this.form.toggleAttribute('submitting', false);
            },
            handleSuccess: (json) => {
                if (json.has('error')) {
                    this.form.setError(json.get('error'));
                }

                if (json.has('success')) {
                    this.form.setSuccess(json.get('success'));
                }
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
	}
}

customElements.define('address-form', AddressForm);