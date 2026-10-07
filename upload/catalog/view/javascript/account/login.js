import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer, local } from '../index.js';

// Language
const language = await loader.language('account/login');

export default class AccountLogin extends WebComponent {
    token = '';

    async render() {
        //if (customer.isLogged()) return;

        let data = new Map();

        data.set('redirect', '');
        data.set('token', this.token);

        return loader.template('account/login', [ data, language ]);
    }

    async onConnect() {
        //if (customer.isLogged()) return;

        let json = await ajax.get('action.php?route=account/login.token&language=' + local.get('language'));

        console.log(json);

        if (json.has('token')) {
            this.token = json.get('token');
        }
    }

    async onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/login.login&language=' + local.get('language') + '&login_token=' + this.token, form, {
            beforeSend: () => {
                this.submitter.setAttribute('loading');
            },
            onComplete: () => {
                this.submitter.removeAttribute('loading');
            },
            onSuccess: (json) => {
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
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('account-login', AccountLogin);