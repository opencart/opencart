import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Library
const session = loader.library('session');

// Language
const language = await loader.language('account/payment_method');

// Name
export const name = 'account-payment';

customElements.define('account-payment', class extends WebComponent {
    render() {
        let data = {};

        return loader.template('account/payment_method', { ...data, ...language });
    }

    onSubmit(e) {
        e.preventDefault();

    }
});