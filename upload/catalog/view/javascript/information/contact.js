import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('information/contact');

// Storage
const locations = await loader.storage('localisation/location');

export default class InformationContact extends WebComponent {
    constructor() {
        super();

        this.token = '';
    }

    async render() {
        let data = new Map();

        data.set('name', '');
        data.set('email', '');

        if (customer.isLogged()) {
            data.set('name', customer.getFirstName() + ' ' + customer.getLastName());
            data.set('email', customer.getEmail());
        }

        data.set('locations', locations);

        return loader.template('information/contact', [ data, language, config ]);
    }

    async handleConnect() {
        let json = await ajax.get('action.php?route=information/contact.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.token = json.get('token');
        }
    }

    async handleSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        await ajax.post('action.php?route=information/contact.send&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            beforeSend: () => {
                this.form.state.set('submitting', true);
            },
            onComplete: () => {
                this.form.state.set('submitting', false);
            },
            onSuccess: (json)=> {
                this.form.state.clear();

                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.state.set('success', json.get('success'));

                    this.update();
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('information-contact', InformationContact);