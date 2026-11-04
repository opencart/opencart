import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer, local } from '../index.js';

// Language
const language = await loader.language('account/login');

export default class AccountLogin extends WebComponent {
	static formAssociated = true;
	#token = '';

    async render() {
        //if (customer.isLogged()) return;

        return loader.template('account/login', [ language ]);
    }

    async handleConnect() {
        //if (customer.isLogged()) return;

        let json = await ajax.get('action.php?route=account/login.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.#token = json.get('token');
		}
    }

    async handleSubmit(e) {
        e.preventDefault();

		//if (customer.isLogged()) return;

        let form = this.form.getData();

        await ajax.post('action.php?route=account/login.login&language=' + local.get('language') + '&login_token=' + this.#token, form, {
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

                    customer.login(json.get('customer'));

                    if (json.has('products')) {

                    }
                }
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
	}
}

customElements.define('account-login', AccountLogin);