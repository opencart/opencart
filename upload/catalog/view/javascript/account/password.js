import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Language
const language = await loader.language('account/password');

export default class AccountPassword extends WebComponent {
    render() {
        //if (!customer.isLogged()) return;

        return loader.template('account/password', [ language ]);
    }

    async handleSubmit(e) {
        e.preventDefault();

        //if (!customer.isLogged()) return;

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/password.save&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            beforeSend: () => {
                this.form.state.set('submitting', true);
            },
            onComplete: () => {
                this.form.state.set('submitting', false);
            },
            onSuccess: (json) => {
                this.form.state.clear();

                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.state.set('success', json.get('success'));

                    this.update();
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('account-password', AccountPassword);