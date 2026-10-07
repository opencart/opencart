import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Language
const language = await loader.language('account/password');

export default class AccountPassword extends WebComponent {
    render() {
        //if (!customer.isLogged()) return;

        return loader.template('account/password', [ language ]);
    }

    async onSubmit(e) {
        e.preventDefault();

        //if (!customer.isLogged()) return;

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/password.save&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            beforeSend: () => {
                this.submitter.state.add('loading');
            },
            onComplete: () => {
                this.submitter.state.remove('loading');
            },
            onSuccess: (json) => {
                // Remove past error classes from inputs
                this.form.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
                this.form.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

                // Display error messages
                if (json.has('error')) {
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
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('account-password', AccountPassword);