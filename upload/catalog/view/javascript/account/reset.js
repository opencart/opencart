import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Language
const language = await loader.language('account/reset');

export default class AccountReset extends WebComponent {
    render() {
        return loader.template('account/reset', [ language ]);
    }

    async onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/reset&language=' + local.get('language') + '&reset_token=' + this.token, form, {
            beforeSend: () => {
                this.submitter.state.add('loading');
            },
            onComplete: () => {
                this.submitter.state.remove('loading');
            },
            onSuccess: this.success.bind(this),
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }

    async onConnect() {
        let json = await ajax.get('action.php?route=account/reset.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.token = json.get('token');
        }
    }

    success(json) {
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
    }
}

customElements.define('account-reset', AccountReset);