import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/returns');

export default class ReturnForm extends WebComponent {
    constructor() {
        super();

        this.token = '';
    }

    async render() {
        //if (!customer.isLogged()) return;

        return loader.template('account/return_form', [ language ]);
    }

    async onConnect() {
        let json = await ajax.get('action.php?route=information/contact.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.token = json.get('token');
        }
    }

    async onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/return_form&language=' + local.get('language'), form, {
            beforeSend: () => {
                this.submitter.toggleAttribute('loading', true);
            },
            onComplete: (json) => {
                this.submitter.toggleAttribute('loading', false);
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

customElements.define('return-form', ReturnForm);