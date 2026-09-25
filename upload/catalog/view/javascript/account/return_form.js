import { WebComponent } from '../index.js';
import { loader, ajax, local, customer } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/returns');

export default class ReturnForm extends WebComponent {
    render() {
       return loader.template('account/return_form', { ...language });
    }

    onConnect() {
        if (!customer.isLogged()) {
            let target = document.getElementById('content');

            target.src = 'account/account';
        }
    }

    async onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=account/return_form&language=' + local.get('language'), form, {
            beforeSend: () => {
                this.submitter.button('loading');
            },
            onComplete: (json) => {
                this.submitter.button('reset');
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
        if (json['error'] !== undefined) {
            for (let key in json['error']) {
                let value = key.replaceAll('_', '-');

                let input = target.querySelector('#input-' + value);

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
        if (json['success'] !== undefined) {
            this.alert.prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

            let output = [];

            console.log(json['products']);

            //console.log(Object.fromEntries(form));
            for (let product of json['products']) {
                cart.add(product);
            }
        }
    }
}

customElements.define('return-form', ReturnForm);