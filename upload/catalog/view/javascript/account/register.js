import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/register');

export default class AccountRegister extends WebComponent {
    token = '';

    async render() {
        let data = new Map();

        data.set('token', this.token);

        return loader.template('account/register', [ data, language, config ]);
    }

    async onConnect() {
        if (customer.isLogged()) return;

        this.token = ajax.get('action.php?route=account/register.token&language=' + local.get('language'));
    }

    async onSubmit(e) {
        e.preventDefault();

        if (customer.isLogged()) return;

        let form = new FormData(this.form);

        ajax.post('action.php?route=account/register&language=' + local.get('language') + '&register_token=' + this.token, form, {
            beforeSend: () => {
                this.button.button('loading');
            },
            onComplete: (json) => {
                this.button.button('reset');
            },
            onSuccess: this.success,
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
        if (json.get('error')) {
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
        if (json.has('success')) {
            this.alert.prepend('<ui-alert type="success">' + json.get('success') + '</ui-alert>');
        }
    }
}

customElements.define('account-register', AccountRegister);