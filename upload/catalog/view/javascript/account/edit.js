import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/edit');

export default class AccountEdit extends WebComponent {
    states() {
        return {
            submitting: false,
            success: '',
            error: []
        };
	}
	
    async render() {
        //if (!customer.isLogged()) return;

        let data = new Map();

        data.set('firstname', customer.getFirstName());
        data.set('lastname', customer.getLastName());
        data.set('email', customer.getEmail());
        data.set('telephone', customer.getTelephone());

        return loader.template('account/edit', [ data, language, config ]);
    }

    async handleSubmit(e) {
        e.preventDefault();

        //if (!customer.isLogged()) return;

		console.log('handleSubmit called');

        let form = this.form.getData();

        await ajax.post('action.php?route=account/edit.save&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            handleSend: () => {
                this.state.set('submitting', true);
            },
            handleComplete: () => {
                this.state.set('submitting', false);
            },
            handleSuccess: (json) => {
                //this.form.state.clear();

                // Display error messages
                if (json.has('error')) {
                    this.state.set('error', json.get('error'));
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

customElements.define('account-edit', AccountEdit);