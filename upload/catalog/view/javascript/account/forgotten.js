import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/forgotten');

export default class AccountForgotten extends WebComponent {
    render() {
        if (customer.isLogged()) return;

        return loader.template('account/forgotten', [ language ]);
    }

    async onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/forgotten.confirm&language=' + local.get('language'), form, {
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

customElements.define('account-forgotten', AccountForgotten);