import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('information/contact');

// Storage
const locations = await loader.storage('localisation/location');

export default class InformationContact extends WebComponent {
	static formAssociated = true;
	#token = '';
	
    async render() {
        let data = new Map();

		if (customer.isLogged()) {
			data.set('name', customer.getFirstName() + ' ' + customer.getLastName());
			data.set('email', customer.getEmail());
		} else { 
			data.set('name', '');
			data.set('email', '');
		}

        data.set('locations', locations);

        return loader.template('information/contact', [ data, language, config ]);
    }

    async handleConnect() {
        let json = await ajax.get('action.php?route=information/contact.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.#token = json.get('token');
		}
    }

    async handleSubmit(e) {
        e.preventDefault();

		await ajax.post({
			url: 'action.php?route=information/contact.send&language=' + local.get('language') + '&contact_token=' + this.#token,
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

customElements.define('information-contact', InformationContact);