import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/edit');

export default class AccountEdit extends WebComponent {
    token = '';

    async render() {
        if (!customer.isLogged()) return;

        let data = new Map();

        data.set('firstname', customer.getFirstName());
        data.set('lastname', customer.getLastName());
        data.set('email', customer.getEmail());
        data.set('telephone', customer.getTelephone());

        data.set('token', customer.getToken());

        return loader.template('account/edit', [ data, language, config ]);
    }

    async onConnect() {
        if (!customer.isLogged()) return;

        this.token = await ajax.get('action.php?route=account/edit.token&language=' + local.get('language') + '&customer_token=' + customer.getToken());
    }

    async onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/edit.save&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            beforeSend: () => {
                this.submitter.button('loading');
            },
            onComplete: (json) => {
                this.submitter.button('reset');
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
        if (json.get('error')) {
            for (let key in json.get('error')) {
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

customElements.define('account-edit', AccountEdit);