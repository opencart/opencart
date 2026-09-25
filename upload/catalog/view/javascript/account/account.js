import { WebComponent } from '../index.js';
import { loader, customer } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/account');

export default class AccountAccount extends WebComponent {
    async render() {
        if (customer.isLogged()) return;

        let data = new Map();

        data.set('affiliate', customer.isAffiliate());

        return loader.template('account/account', [ data, language, config ]);
    }
}

customElements.define('account-account', AccountAccount);