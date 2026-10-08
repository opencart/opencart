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

    async onSubmit(e) {
        e.preventDefault();

        //if (!customer.isLogged()) return;

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/newsletter.save&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            beforeSend: () => {
                this.submitter.toggleAttribute('loading', true);
            },
            onComplete: (json) => {
                this.submitter.toggleAttribute('loading', false);
            },
            onSuccess: (json) => {
                // Display error messages
                if (json.has('error')) {
                    this.alert.prepend('<ui-alert type="warning">' + json.get('error') + '</ui-alert>');
                }

                // Display success message
                if (json.has('success')) {
                    this.alert.prepend('<ui-alert type="success">' + json.get('success') + '</ui-alert>');
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('account-newsletter', AccountNewsletter);