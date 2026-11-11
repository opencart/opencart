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
		} else { 
			let address = customer.getAddress(parseInt(this.getAttribute('address_id')));

			data.set('firstname', address.firstname);
			data.set('lastname', address.lastname);
			data.set('company', address.company);
			data.set('address_1', address.address_1);
			data.set('address_2', address.address_2);
			data.set('city', address.city);
			data.set('postcode', address.postcode);
			data.set('country_id', address.country_id);
			data.set('zone_id', address.zone_id);
		}

        return loader.template('account/address_form', [ data, language, config ]);
    }

    async handleConnect() {
        //if (customer.isLogged()) return;

		console.dir(this.form.country);

		let inputs = this.form.getInputs();
		
		console.dir(inputs);
		
		//this.form.country.addEventListener('[change]', this.handleCountry.bind(this));
		
		let country = this.form.getInput('country');
		
		country.addEventListener('[change]', this.handleCountry.bind(this));

	}

	handleCountry(e) {
		console.log('Country changed to', this.form.getInput('country').value);

		this.form.getInput('zone').setAttribute('country_id', this.form.getInput('country').value);

		//this.postcode = this.hasAttribute('postcode') ? document.getElementById(this.getAttribute('postcode')) : '';
	}

    async handleSubmit(e) {
        e.preventDefault();

		console.log(e);

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