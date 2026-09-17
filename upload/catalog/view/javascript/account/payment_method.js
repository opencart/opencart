import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/payment_method');

export default class AccountPayment extends WebComponent {
    render() {
        let data = {};

        return loader.template('account/payment_method', { ...data, ...language });
    }

    onSubmit(e) {
        e.preventDefault();

    }
}

customElements.define('account-payment', AccountPayment);