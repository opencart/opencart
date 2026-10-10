import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/edit');

export default class AccountEdit extends WebComponent {
    async render() {
        //if (!customer.isLogged()) return;

        let data = new Map();

        data.set('firstname', customer.getFirstName());
        data.set('lastname', customer.getLastName());
        data.set('email', customer.getEmail());
        data.set('telephone', customer.getTelephone());

        data.set('token', customer.getToken());

        return loader.template('account/edit', [ data, language, config ]);
    }

    async handleSubmit(e) {
        e.preventDefault();

        //if (!customer.isLogged()) return;

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/edit.save&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
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

                    this.update();
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('account-edit', AccountEdit);