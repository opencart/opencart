import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = loader.language('account/transaction');

class AccountTransaction extends WebComponent {
    render() {
        return loader.template('account/transaction', { ...language });
    }
}

customElements.define('account-transaction', AccountTransaction);