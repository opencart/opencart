import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/returns');

export default class ReturnForm extends WebComponent {
	static formAssociated = true;
	#token = '';
	
    async render() {
        //if (!customer.isLogged()) return;

        return loader.template('account/return_form', [ language ]);
    }

    async handleConnect() {
        let json = await ajax.get('action.php?route=account/return_form.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.#token = json.get('token');
		}
    }

    async handleSubmit(e) {
        e.preventDefault();

        let form = this.form.getData();

        await ajax.post('action.php?route=account/return_form&language=' + local.get('language') + '&token=' + this.#token, form, {
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

customElements.define('return-form', ReturnForm);