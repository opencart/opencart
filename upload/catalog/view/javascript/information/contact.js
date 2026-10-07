import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('information/contact');

// Storage
const locations = await loader.storage('localisation/location');

export default class InformationContact extends WebComponent {
    token = '';

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

    async onConnect() {
        let json = await ajax.get('action.php?route=information/contact.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.token = json.get('token');
        }
    }

    async onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=information/contact.send&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            beforeSend: (request) => {
                this.submitter.state.add('loading');
            },
            onComplete: () => {
                this.submitter.state.remove('loading');
            },
            onSuccess: (json)=> {
                // Remove past error classes from inputs
                this.form.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
                this.form.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

                // Display error messages
                if (json.has('error')) {
                    for (let key in json.get('error')) {
                        this.ref(key).setFormValue();

                        let value = key.replaceAll('_', '-');

                        this.form.querySelector('#input-' + value).classList.add('is-invalid');
                        this.form.querySelector('#input-' + value).querySelectorAll('.form-control, .form-select, .form-check-input, .form-check-label').forEach(element => element.classList.add('is-invalid'));
                        this.form.querySelector('#error-' + value).classList.add('d-block');
                    }
                }

                // Display success message
                if (json.has('success')) {
                    this.alert.prepend('<ui-alert type="success">' + json.get('success') + '</ui-alert>');
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('information-contact', InformationContact);