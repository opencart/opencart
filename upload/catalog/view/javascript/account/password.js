import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Language
const language = await loader.language('account/password');

export default class AccountPassword extends WebComponent {
    async render() {
        //if (!customer.isLogged()) return;

        return loader.template('account/password', [ language ]);
    }

	handleConnect() {
		this.form.addEventListener('form-submit', this.handleSubmit.bind(this));
	}
	
    async handleSubmit(e) {
        e.preventDefault();

        //if (!customer.isLogged()) return;

        let form = this.form.getData();

        await ajax.post('action.php?route=account/password.save&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
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
                }
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('account-password', AccountPassword);