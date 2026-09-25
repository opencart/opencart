import { WebComponent} from '../index.js';
import { ajax, customer, loader, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('information/contact');

// Storage
const locations = await loader.storage('localisation/location');

export default class InformationContact extends WebComponent {
    async render() {
        let data = {};

        if (customer.isLogged()) {
            data.name = customer.getFirstName() + ' ' + customer.getLastName();
            data.email = customer.getEmail();
        } else {
            data.name = '';
            data.email = '';
        }

        data.locations = locations;

        return loader.template('information/contact', { ...data, ...language, ...config });
    }

    onConnect() {
        this.token = ajax.get('action.php?route=information/contact.send&language=' + local.get('language'));
    }

    onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=information/contact.send&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            beforeSend: (request) => {
                this.button.state('loading');
            },
            onComplete: () => {
                this.button.state('reset');
            },
            onSuccess: this.success.bind(this),
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }

    success(json) {
        // Remove past error classes from inputs
        this.form.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
        this.form.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

        // Display error messages
        if (json['error'] !== undefined) {
            for (let key in json['error']) {
                let value = key.replaceAll('_', '-');

                let input = this.form.querySelector('#input-' + value);

                if (input) {
                    input.classList.add('is-invalid');

                    // If the element has inputs inside.
                    input.querySelectorAll('.form-control, .form-select, .form-check-input, .form-check-label').forEach(element => element.classList.add('is-invalid'));
                }

                let error = this.form.querySelector('#error-' + value);

                if (error) {
                    error.classList.add('d-block');
                }
            }
        }

        // Display success message
        if (json['success'] !== undefined) {
            this.alert.prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

            //console.log(Object.fromEntries(form));
            for (let product of json['products']) {
                cart.add(product);
            }
        }
    }
}

customElements.define('information-contact', InformationContact);