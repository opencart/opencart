import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/register');

// Storage
const customer_groups = await loader.storage('customer/customer_group');

export default class AccountRegister extends WebComponent {
    token = '';

    async render() {
        let data = new Map();

        data.set('customer_groups', customer_groups);

        // Custom Fields
        data.set('custom_fields', []);

        let customer_group = await loader.storage('customer/customer_group-' + config.get('config_customer_group_id'));

        if (customer_group instanceof Map) {
            data.get('custom_fields').push(customer_group.get('custom_fields'));
        }

        data.set('token', this.token);

        return loader.template('account/register', [ data, language, config ]);
    }

    async onConnect() {
        if (customer.isLogged()) return;

        this.token = ajax.get('action.php?route=account/register.token&language=' + local.get('language'));
    }

    async onChange(e) {
        let custom_fields = [];

        let customer_group = await this.storage.fetch('customer/customer_group-' + this.value);

        if (customer_group_info instanceof Map) {
            data.custom_fields = customer_group_info.custom_field;
        }

        //$('.custom-field').addClass('d-none');
        //$('.custom-field').removeClass('required');

        //for (let i = 0; i < json.length; i++) {
        //    let custom_field = json[i];

        //    $('.custom-field-' + custom_field['custom_field_id']).removeClass('d-none');

        //    if (custom_field['required']) {
        //        $('.custom-field-' + custom_field['custom_field_id']).addClass('required');
        //     }
        //}
    }

    async onSubmit(e) {
        e.preventDefault();

        if (customer.isLogged()) return;

        let form = new FormData(this.form);

        ajax.post('action.php?route=account/register&language=' + local.get('language') + '&register_token=' + this.token, form, {
            beforeSend: () => {
                this.button.button('loading');
            },
            onComplete: (json) => {
                this.button.button('reset');
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
        if ('error' in json) {
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
        if ('success' in json) {
            this.alert.prepend('<ui-alert type="success">' + json['success'] + '</ui-alert>');
        }
    }
}

customElements.define('account-register', AccountRegister);