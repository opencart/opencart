import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/edit');

export default class AccountEdit extends WebComponent {
    token = '';

    async render() {
        let data = new Map();

        data.set('firstname', customer.getFirstName());
        data.set('lastname', customer.getLastName());
        data.set('email', customer.getEmail());
        data.set('telephone', customer.getTelephone());

        // Custom Fields
        data.set('custom_fields', []);

        let customer_group = await loader.storage('customer/customer_group-' + customer.getGroupId());

        if (customer_group) {
            data.get('custom_fields').push(customer_group.custom_fields);
        }

        data.token = customer.getToken();

        return loader.template('account/edit', [ data, language, config ]);
    }

    async onConnect() {
        this.token = await ajax.get('action.php?route=account/edit.token&language=' + local.get('language') + '&customer_token=' + customer.getToken());
    }

    async onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=account/edit.save&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            beforeSend: () => {
                this.button.button('loading');
            },
            onComplete: (json) => {
                this.button.button('reset');
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
        if (json['error'] !== undefined) {
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
        if (json['success'] !== undefined) {
            this.alert.prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }
    }
}

customElements.define('account-edit', AccountEdit);