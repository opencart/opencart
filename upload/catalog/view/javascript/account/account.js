import { WebComponent } from '../index.js';
import { loader, customer } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/account');

export default class AccountAccount extends WebComponent {
    async render() {
        //if (!customer.isLogged()) return;

        return loader.template('account/account', [ language, config ]);
    }
}

customElements.define('account-account', AccountAccount);