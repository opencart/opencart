import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/register');

// Library
const ajax = await loader.library('ajax');
const session = await loader.library('session');
const cart = await loader.library('cart');
const customer = await loader.library('customer');

// Storage
const customer_groups = await loader.storage('customer/customer_group');

// Name
export const name = 'account-register';

customElements.define('account-register', class extends WebComponent {
    token = '';

    connect() {
        if (customer.isLogged()) {
            let target = document.getElementById('content');

            target.src = 'account/login';
        }

        this.token = ajax.get('action.php?route=account/register.token');
    }

    async render() {
        let data = {};

        data.customer_groups = customer_groups;

        // Custom Fields
        data.custom_fields = [];

        let customer_group = await loader.storage('customer/customer_group-' + config.config_customer_group_id);

        if (customer_group) {
            data.custom_fields = customer_group.custom_fields;
        }

        data.token = this.token;

        return loader.template('account/register', { ...data, ...language, ...config });
    }

    async onChange(e) {
        let customer_group_info = await this.storage.fetch('customer/customer_group-' + this.value);

        if (customer_group_info) {
            data.custom_fields = customer_group_info.custom_field;
        } else {
            data.custom_fields = [];
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

        console.log('onSubmit');

        let target = e.target;

        let form = new FormData(target);

        ajax.post('action.php?route=account/register', form, {
            beforeSend: () => {
                //ref.get('button-cart').button('loading');
            },
            onComplete: (json) => {
                //ref.get('button-cart').button('reset');
            },
            onSuccess: this.success,
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }

    success(json) {
        console.log('onSuccess', json);

        // Remove past error classes from inputs
        target.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
        target.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

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

                let error = target.querySelector('#error-' + value);

                if (error) {
                    error.classList.add('d-block');
                }
            }
        }

        // Display success message
        if (json['success'] !== undefined) {
            let alert = target.querySelector('#alert');

            if (alert) {
                alert.prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }
        }
    }
});