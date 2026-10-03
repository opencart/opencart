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

        this.token = await ajax.get('action.php?route=account/register.token&language=' + local.get('language'));
    }

    async onSubmit(e) {
        e.preventDefault();

        if (customer.isLogged()) return;

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/register&language=' + local.get('language') + '&register_token=' + this.token, form, {
            beforeSend: () => {
                this.submitter.state.add('loading');
            },
            onComplete: (json) => {
                this.submitter.state.delete('loading');
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
            for (let key in json.get('error')) {
                let value = key.replaceAll('_', '-');

                // If the element has inputs inside.
                this.form.querySelector('#input-' + value).classList.add('is-invalid');
                this.form.querySelectorAll('.form-control, .form-select, .form-check-input, .form-check-label').forEach(element => element.classList.add('is-invalid'));
                this.form.querySelector('#error-' + value).classList.add('d-block');
            }
        }

        // Display success message
        if (json.has('success')) {
            this.alert.prepend('<ui-alert type="success">' + json.get('success') + '</ui-alert>');
        }
    }

    onAgree(e) {
        console.log(e);

        //this.ref('agree');
    }
}

customElements.define('account-register', AccountRegister);