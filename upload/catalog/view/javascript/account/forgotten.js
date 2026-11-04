import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Language
const language = await loader.language('account/forgotten');

export default class AccountForgotten extends WebComponent {
	static formAssociated = true;
	#token = '';

    render() {
        if (customer.isLogged()) return;

        return loader.template('account/forgotten', [ language ]);
    }

    async handleConnect() {
        let json = await ajax.get('action.php?route=account/forgotten.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.#token = json.get('token');
		}
    }

    async handleSubmit(e) {
        e.preventDefault();

        //if (customer.isLogged()) return;

        let form = this.form.getData();

        await ajax.post('action.php?route=account/forgotten.confirm&language=' + local.get('language') + '&token=' + this.#token, form, {
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

customElements.define('account-forgotten', AccountForgotten);