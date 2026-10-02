import { WebComponent } from '../index.js';
import { loader, ajax, customer } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/register');

// Storage
const customer_groups = await loader.storage('customer/customer_group');

customElements.define('checkout-register', class extends WebComponent {
    token = '';

    async render(){
        let data = new Map();

        // Custom Fields
        data.set('custom_fields', []);

        let customer_group = await loader.storage('customer/customer_group-' + config.get('config_customer_group_id'));

        if (customer_group instanceof Map) {
            data.set('custom_fields', customer_group.custom_fields);
        }

        data.set('token', this.token);

        return loader.template('checkout/register', [ data,  language, config ]);
    }

    async onConnect() {
        this.token = await ajax.get('action.php?route=checkout/register.token');
    }

    onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=checkout/register.save&language=' + local.get('language') + '&register_token=' + this.token, form, {
            beforeSend: (request) => {
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

    onChange() {
        $('input[name=\'account\']').on('click', function() {
            if ($(this).val() == 1) {
                $('#password').removeClass('d-none');
            } else {
                // If guest hide password field
                $('#password').addClass('d-none');
            }

            if ($(this).val() == 1) {
                $('#register-agree').removeClass('d-none');
            } else {
                // If guest hide register agree field
                $('#register-agree').addClass('d-none');
            }
        });

        $('input[name=\'account\']:checked').trigger('click');
    }

    success(json) {
        // Remove past error classes from inputs
        this.form.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
        this.form.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

        if (json.has('redirect')) {
            location = json.get('redirect');
        }

        // Display error messages
        if (json.has('error')) {
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

            if ($('#input-register').prop('checked')) {
                $('input[name=\'account\']').prop('disabled', true);
                $('#input-customer-group').prop('disabled', true);
                $('#input-password').prop('disabled', true);
                $('#input-captcha').prop('disabled', true);
                $('#input-register-agree').prop('disabled', true);
            }

            $('#input-shipping-method').val('');
            $('#input-payment-method').val('');

            $('#checkout-confirm').load('action.php?route=checkout/confirm.confirm&language={{ language }}');
        }
    }
});