import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/edit');

// Library
const session = loader.library('session');

class AccountNewsletter extends WebComponent {
    async render() {
        let data = {};

        let customer = session.get('customer');

        data.newsletter = customer.get('newsletter');

        return loader.template('account/newsletter', { ...data, ...language, ...config });
    }

    onSubmit(e) {
        e.preventDefault();

    }
}

customElements.define('account-newsletter', AccountNewsletter);