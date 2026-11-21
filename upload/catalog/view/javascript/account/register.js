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
		let data = new Map();

		data.set('success', customer.isLogged());

		return loader.template('account/register', [ language, config, data ]);
    }

    async handleConnect() {
        //if (customer.isLogged()) return;

        let json = await ajax.get('action.php?route=account/register.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.#token = json.get('token');
		}
	}

	async handleSubmit(e) {
		e.preventDefault();
	
        //if (customer.isLogged()) return;

		await ajax.post({
			url: 'action.php?route=account/register&language=' + local.get('language') + '&register_token=' + this.#token,
			data: this.form.getData(),
			handleSend: () => {
                this.form.submitting = true;
            },
			handleComplete: () => {
                this.form.submitting = false;
            },
			handleSuccess: (json) => {
				console.log('onSuccess', json);
				
                // Display error messages
				if (json.has('error')) {
                    this.form.setError(json.get('error'));
                }

                // Display success message
				if (json.has('success')) {
					customer.login(json.get('customer'));
					
					this.update()
					
					this.global.get('header').update();
					this.global.get('content').update();
					this.global.get('footer').update();
                }
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
    }

    handleAgree(e) {
        this.form.disabled = !e.target.hasAttribute('checked');
    }
}

customElements.define('account-register', AccountRegister);