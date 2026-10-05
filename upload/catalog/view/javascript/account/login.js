import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer } from '../index.js';

// Language
const language = await loader.language('account/login');

export default class AccountLogin extends WebComponent {
    token = '';

    async render() {
        //if (customer.isLogged()) return;

        let data = new Map();

        data.set('token', this.token);

        return loader.template('account/login', [ data, language ]);
    }

    async onConnect() {
        //if (customer.isLogged()) return;

        //this.token = await ajax.get('action.php?route=account/login.token');
    }

    async onSubmit(e) {
        e.preventDefault();

        this.form.state.submitting = true;

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/login.login&login_token=' + this.token, form, {
            beforeSend: () => {
                this.submitter.state.add('loading');
            },
            onComplete: (json) => {
                this.submitter.state.remove('loading');
            },
            onSuccess: this.success.bind(this),
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }

    success(json) {
        this.form.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
        this.form.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

        if (json.has('error')) {
            for (let [ key, value ] of json.get('error')) {
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
    }
}

customElements.define('account-login', AccountLogin);