import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Language
const language = await loader.language('account/newsletter');

export default class AccountNewsletter extends WebComponent {
    async render() {
        //if (!customer.isLogged()) return;

        let data = new Map();

        data.set('newsletter', customer.getNewsletter());

        return loader.template('account/newsletter', [data, language]);
	}
	
    async handleSubmit(e) {
        e.preventDefault();

        //if (!customer.isLogged()) return;

        let form = this.form.getData();

        await ajax.post('action.php?route=account/newsletter&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
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
                    this.form.setSuccess(json.get('success'));
                }
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
	}
}

customElements.define('account-newsletter', AccountNewsletter);