import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/register');

export default class AccountRegister extends WebComponent {
	static formAssociated = true;
	#token = '';
	
    async render() {
        //if (this.success) {
        //   return `<ui-toast>${this.success}</ui-toast>`;
        //}

		return loader.template('account/register', [ language, config ]);
    }

    async handleConnect() {
        //if (customer.isLogged()) return;

        let json = await ajax.get('action.php?route=account/register.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.#token = json.get('token');
		}
	}

	async handleSubmit(e) {
        //if (customer.isLogged()) return;

		let form = this.form.getData();

		console.log('Form data:', Object.fromEntries(form));
		
        await ajax.post('action.php?route=account/register.save&language=' + local.get('language') + '&register_token=' + this.#token, form, {
			handleSend: () => {
                this.form.toggleAttribute('submitting', true);
            },
            handleComplete: () => {
                this.form.toggleAttribute('submitting', false);
            },
			handleSuccess: (json) => {
				console.log('onSuccess', json);
				
                // Display error messages
				if (json.has('error')) {
                    this.form.setError(json.get('error'));
                }

                // Display success message
				if (json.has('success')) {
					console.log('Success');
                   //this.form.setSuccess(json.get('success'));
                }
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

customElements.define('account-register', AccountRegister);