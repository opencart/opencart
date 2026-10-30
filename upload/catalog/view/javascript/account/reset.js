import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Language
const language = await loader.language('account/reset');

export default class AccountReset extends WebComponent {
    token = '';

    async render() {
        return loader.template('account/reset', [ language ]);
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

        let form = this.form.getData();

        await ajax.post('action.php?route=account/reset&language=' + local.get('language') + '&reset_token=' + this.token, form, {
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

customElements.define('account-reset', AccountReset);