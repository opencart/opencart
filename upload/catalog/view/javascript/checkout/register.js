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

		data.set('firstname', '');
		data.set('lastname', '');
		data.set('company', '');
	    data.set('email', '');
        data.set('telephone', '');

		data.set('account', this.hasAttribute('account') && this.getAttribute('account') == 'register');
		
        return loader.template('checkout/register', [ language, config, data ]);
    }

    async handleConnect() {
        //if (customer.isLogged()) return;

        let json = await ajax.get('action.php?route=checkout/register.token&language=' + local.get('language'));

		console.log(json);

        if (json.has('token')) {
            this.#token = json.get('token');
		}
    }

    async handleSubmit(e) {
		e.preventDefault();
		
		//if (customer.isLogged()) return;
		
		console.log(e);

		await ajax.post({
			url: 'action.php?route=checkout/register&language=' + local.get('language') + '&register_token=' + this.#token,
			data: this.form.getData(),
			handleSend: () => {
                this.form.submitting = true;
            },
			handleComplete: () => {
                this.form.submitting = false;
            },
			handleSuccess: (json) => {
				console.log('onSuccess', json);

                if (json.has('error')) {
                    this.form.setError(json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
					this.form.setSuccess(json.get('success'));
					
                    this.form.toggleAttribute('disabled', true);

                    //if ($('#input-register').prop('checked')) {
                        //$('input[name=\'account\']').prop('disabled', true);
                        //$('#input-customer-group').prop('disabled', true);
                        //$('#input-password').prop('disabled', true);
                        //$('#input-captcha').prop('disabled', true);
                        //$('#input-register-agree').prop('disabled', true);
                    //}

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