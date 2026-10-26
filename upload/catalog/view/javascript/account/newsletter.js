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

        await ajax.post('action.php?route=account/newsletter.save&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            handleSend: () => {
                this.submitter.state.set('submitting', true);
            },
            handleComplete: () => {
                this.submitter.state.set('submitting', false);
            },
			handleSuccess: (json) => {
				console.log(json);
				
                //this.form.state.clear();

                // Display error messages
                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.state.set('success', json.get('success'));

                    this.update();
                }
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
	}
	
	
    async handleSubmit(e) {
        e.preventDefault();

		//if (customer.isLogged()) return;

        let form = this.form.getData();

        await ajax.post('action.php?route=account/login.login&language=' + local.get('language') + '&login_token=' + this.token, form, {
            handleSend: () => {
                this.form.state.set('submitting', true);
            },
            handleComplete: () => {
                this.form.state.set('submitting', false);
            },
            handleSuccess: (json) => {
                this.form.state.clear();

                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.state.set('success', json.get('success'));

                    customer.login(json.get('customer'));

                    if (json.has('products')) {

                    }

                    this.update();
                }
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
	}
	
	handleSubmitting(value) {
        this.submitter.state.set('submitting', value);
	}
	
	handleError(error) {
        let errors = Object.entries(error);

        if (errors.length === 0) return;

		for (let [key, value] of errors) {
			if (key === 'warning') continue;

			let input = this.root.getElementById('input-' + key);

			if (input) {
				input.state.set('error', value);
			}
        }
    }
}

customElements.define('account-newsletter', AccountNewsletter);