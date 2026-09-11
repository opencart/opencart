import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/newsletter');

// Library
const session = await loader.library('session');

customElements.define('account-newsletter', class extends WebComponent {
    async render() {
        let data = {};

        //let customer = session.get('customer');

        //data.newsletter = customer.get('newsletter');

        return loader.template('account/newsletter', { ...data, ...language });
    }

    onSubmit(e) {
        e.preventDefault();

    }
});