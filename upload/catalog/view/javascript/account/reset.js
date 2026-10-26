import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Language
const language = await loader.language('account/reset');

export default class AccountReset extends WebComponent {
    token = '';

    states() {
        return {
            submitting: false,
            success: '',
            error: []
        };
    }

    async render() {
        return loader.template('account/reset', [ language ]);
    }

    async handleConnect() {
        let json = await ajax.get('action.php?route=account/reset.token&language=' + local.get('language'));

        if (json.has('token')) {
            this.token = json.get('token');
        }
    }

    async handleSubmit(e) {
        e.preventDefault();

        let form = this.form.getData();

        await ajax.post('action.php?route=account/reset&language=' + local.get('language') + '&reset_token=' + this.token, form, {
            handleSend: () => {
                this.form.state.set('submitting', true);
            },
            handleComplete: () => {
                this.form.state.set('submitting', false);
            },
            handleSuccess: (json) => {
                //this.form.state.clear();

                // Display error messages
                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.state.set('success', json.get('success'));
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

customElements.define('account-reset', AccountReset);