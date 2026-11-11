import { WebComponent } from '../index.js';
import { loader, customer, ajax, local } from '../index.js';

const language = await loader.language('account/forgotten_reset');

export default class ForgottenReset extends WebComponent {
	static formAssociated = true;
	#token = '';
	
    async render() {
        if (customer.isLogged()) return;

        return loader.template('account/forgotten_reset', [ language ]);
    }

    async handleConnect() {
        let json = await ajax.get('action.php?route=account/reset.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.#token = json.get('token'); 
		}
	}
	
	async handleSubmit(e) {
        e.preventDefault();

		//if (customer.isLogged()) return;
		
		await ajax.post({
			url: 'action.php?route=account/forgotten.reset&language=' + local.get('language') + '&reset_token=' + this.#token,
			data: this.form.getData(),
            handleSend: () => {
                this.form.toggleAttribute('submitting', true);
            },
            handleComplete: () => {
                this.form.toggleAttribute('submitting', false);
            },
            handleSuccess: (json) => {
                // Display error messages
                if (json.has('error')) {
                    this.form.setError(json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.form.setSuccess(json.get('success'));
                }
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
	}
}

customElements.define('forgotten-reset', ForgottenReset);