import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer } from '../index.js';

// Language
const language = await loader.language('account/login');

export default class AccountLogin extends WebComponent {
    token = '';

    async render() {
        if (customer.isLogged()) return;

        let data = new Map();

        data.set('token', this.token);

        return loader.template('account/login', [ data, language ]);
    }

    async onConnect() {
        if (customer.isLogged()) return;

        this.token = await ajax.get('action.php?route=account/login.token');
    }

    async onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=account/login.login&login_token=' + this.token, form, {
            beforeSend: () => {
                this.button.button('loading');
            },
            onComplete: (json) => {
                this.button.button('reset');
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
        if (json.has('error')) {
            for (let [ key, value ] of json.get('error')) {
                key = key.replaceAll('_', '-');

                let input = this.form.querySelector('#input-' + key);

                if (input) {
                    input.classList.add('is-invalid');

                    // If the element has inputs inside.
                    input.querySelectorAll('.form-control, .form-select, .form-check-input, .form-check-label').forEach(element => element.classList.add('is-invalid'));
                }

                let error = this.form.querySelector('#error-' + key);

                if (error) {
                    error.classList.add('d-block');
                }
            }
        }

        // Display success message
        if (json.has('success')) {
            this.alert.prepend('<ui-alert type="success">' + json.get('success') + '</ui-alert>');
        }
    }
}

customElements.define('account-login', AccountLogin);