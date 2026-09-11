import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/transaction');

// Name
export const name = 'account-transaction';

customElements.define('account-transaction', class extends WebComponent {
    render() {


        return loader.template('account/transaction', { ...language });
    }
});