import { WebComponent} from '../index.js';
import { loader, ajax, customer } from '../index.js';

// Language
const language = await loader.language('account/newsletter');

export default class AccountNewsletter extends WebComponent {
    async render() {
        //if (!customer.isLogged()) return;

        let data = new Map();

        data.set('newsletter', customer.getNewsletter());

        return loader.template('account/newsletter', [ data, language ]);
    }

    async handleSubmit(e) {
        e.preventDefault();

        //if (!customer.isLogged()) return;

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/newsletter.save&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
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

customElements.define('account-newsletter', AccountNewsletter);