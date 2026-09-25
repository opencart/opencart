import {ajax, WebComponent} from '../index.js';
import { loader, customer } from '../index.js';

// Language
const language = await loader.language('account/newsletter');

export default class AccountNewsletter extends WebComponent {
    async render() {
        if (!customer.isLogged()) return;

        let data = new Map();

        data.newsletter = customer.getNewsletter();

        return loader.template('account/newsletter', [ data, language ]);
    }

    onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=account/newsletter.confirm&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            beforeSend: () => {
                this.submitter.button('loading');
            },
            onComplete: (json) => {
                this.submitter.button('reset');
            },
            onSuccess: this.success.bind(this),
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }

    success(json) {

    }
}

customElements.define('account-newsletter', AccountNewsletter);