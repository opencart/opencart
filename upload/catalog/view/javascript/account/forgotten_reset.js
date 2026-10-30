import { WebComponent } from '../index.js';
import { loader, customer, ajax, local } from '../index.js';

const language = await loader.language('account/forgotten_reset');

export default class ForgottenReset extends WebComponent {
    async render() {
        if (customer.isLogged()) return;

        return loader.template('account/forgotten_reset', [ language ]);
    }

    async handleConnect() {
        let json = await ajax.get('action.php?route=account/reset.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.token = json.get('token');
		}
		
		this.form.addListener(this.handleSubmit.bind(this));
	}
	
	async handleSubmit(e) {
        e.preventDefault();

		//if (customer.isLogged()) return;
		
        let form = this.form.getData();

        await ajax.post('action.php?route=account/forgotten.reset&language=' + local.get('language') + '&reset_token=' + this.token, form, {
            handleSend: () => {
                this.form.state.set('submitting', true);
            },
            handleComplete: () => {
                this.form.state.set('submitting', false);
            },
            handleSuccess: (json) => {
                // Display error messages
                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.form.state.set('success', json.get('success'));
                }
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
	}
}

customElements.define('forgotten-reset', ForgottenReset);