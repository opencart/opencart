import { WebComponent } from '../index.js';
import { loader, customer } from '../index.js';

// Language
const language = await loader.language('account/account');

export default class AccountAccount extends WebComponent {
    async render() {
        //if (!customer.isLogged()) return;

        console.log(language);

        return loader.template('account/account', [ language ]);
    }
}

customElements.define('account-account', AccountAccount);