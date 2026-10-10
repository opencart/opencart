import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Language
const language = await loader.language('account/reset');

export default class AccountReset extends WebComponent {
    render() {
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

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/reset&language=' + local.get('language') + '&reset_token=' + this.token, form, {
            beforeSend: () => {
                this.form.state.set('submitting', true);
            },
            onComplete: () => {
                this.form.state.set('submitting', false);
            },
            onSuccess: (json) => {
                this.form.state.clear();

                // Display error messages
                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.state.set('success', json.get('success'));
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('account-reset', AccountReset);