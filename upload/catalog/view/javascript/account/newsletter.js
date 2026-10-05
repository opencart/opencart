import { WebComponent} from '../index.js';
import { loader, ajax, customer } from '../index.js';

// Language
const language = await loader.language('account/newsletter');

export default class AccountNewsletter extends WebComponent {
    async render() {
        if (!customer.isLogged()) return;

        let data = new Map();

        data.set('newsletter', customer.getNewsletter());

        return loader.template('account/newsletter', [ data, language ]);
    }

    async onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        await ajax.post('action.php?route=account/newsletter.save&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            beforeSend: () => {
                this.submitter.state.add('loading');
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