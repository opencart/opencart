import { WebComponent } from '../index.js';
import { loader, ajax, customer } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/register');

// Storage
const customer_groups = await loader.storage('customer/customer_group');

customElements.define('checkout-register', class extends WebComponent {
    constructor() {
        super();

        this.token = '';
    }

    async render(){
        let data = new Map();

        data.set('token', this.token);

        return loader.template('checkout/register', [ data,  language, config ]);
    }

    async onConnect() {
        //if (customer.isLogged()) return;

        let json = await ajax.get('action.php?route=checkout/register.token');

        if (json.has('token')) {
            this.token = json.get('token');
        }
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

    async onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        await ajax.post('action.php?route=checkout/register.save&language=' + local.get('language') + '&register_token=' + this.token, form, {
            beforeSend: () => {
                this.submitter.toggleAttribute('loading', true);
            },
            onComplete: () => {
                this.submitter.toggleAttribute('loading', false);
            },
            onSuccess: this.success.bind(this),
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }

    success(json) {
        this.form.state.clear();

        if (json.has('redirect')) {
            location = json.get('redirect');
        }

        if (json.has('error')) {
            this.form.state.set('error', json.get('error'));
        }

        // Display success message
        if (json.has('success')) {
            this.form.state.set('success', json.get('success'));
            this.form.state.set('disabled', true);

            if ($('#input-register').prop('checked')) {
                $('input[name=\'account\']').prop('disabled', true);
                $('#input-customer-group').prop('disabled', true);
                $('#input-password').prop('disabled', true);
                $('#input-captcha').prop('disabled', true);
                $('#input-register-agree').prop('disabled', true);
            }

            $('#input-shipping-method').val('');
            $('#input-payment-method').val('');

            //$('#checkout-confirm').load('action.php?route=checkout/confirm.confirm&language={{ language }}');
        }
    }
});