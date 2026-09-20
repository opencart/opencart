import { WebComponent } from '../component.js';
import { loader, customer } from '../index.js';

// Language
const language = await loader.language('account/newsletter');

export default class AccountNewsletter extends WebComponent {
    async render() {
        let data = {};

        data.newsletter = customer.getNewsletter();

        return loader.template('account/newsletter', { ...data, ...language });
    }

    onSubmit(e) {
        e.preventDefault();



    }
}

customElements.define('account-newsletter', AccountNewsletter);