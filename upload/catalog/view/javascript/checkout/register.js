import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/register');

export default class CheckoutRegister extends WebComponent {
	static formAssociated = true;
	#token = '';

    async render() {
        let data = new Map();

		


        return loader.template('checkout/register', [ data, language, config ]);
    }

    async handleConnect() {
        //if (customer.isLogged()) return;

        let json = await ajax.get('action.php?route=checkout/register.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.#token = json.get('token');
        }
    }

    handleChange() {
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

    async handleSubmit(e) {
        e.preventDefault();

		await ajax.post({
			url: 'action.php?route=checkout/register&language=' + local.get('language') + '&register_token=' + this.#token,
			data: this.form.getData(),
            handleSend: () => {
                this.form.toggleAttribute('submitting', true);
            },
            handleComplete: () => {
                this.form.toggleAttribute('submitting', false);
            },
            handleSuccess: (json) => {
                if (json.has('error')) {
                    this.form.setError(json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
					this.form.setSuccess(json.get('success'));
					
                    this.form.toggleAttribute('disabled', true);

                    if ($('#input-register').prop('checked')) {
                        //$('input[name=\'account\']').prop('disabled', true);
                        //$('#input-customer-group').prop('disabled', true);
                        //$('#input-password').prop('disabled', true);
                        //$('#input-captcha').prop('disabled', true);
                        //$('#input-register-agree').prop('disabled', true);
                    }

                    //$('#input-shipping-method').val('');
                    //$('#input-payment-method').val('');
				}
				
                //$('#checkout-confirm').load('action.php?route=checkout/confirm.confirm&language={{ language }}');
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
	}
	
	handleAgree(e) {
    	this.form.setAttribute('disabled', !e.target.hasAttribute('checked'));
    }
}

customElements.define('checkout-register', CheckoutRegister);