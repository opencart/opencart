import { WebComponent } from '../component.js';
import { loader, customer } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/account');

export default class AccountAccount extends WebComponent {
    connected() {
        if (!customer.isLogged()) {
            let target = document.getElementById('content');

            target.src = 'account/login';
        }
    }

    render() {
        let data = {};

        data.affiliate = customer.isAffiliate();

        return loader.template('account/account', { ...data, ...language, ...config });
    }
}

customElements.define('account-account', AccountAccount);