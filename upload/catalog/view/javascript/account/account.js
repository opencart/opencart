import { WebComponent } from '../component.js';
import { loader, customer } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/account');

export default class AccountAccount extends WebComponent {
    render() {
        let data = {};

        data.affiliate = customer.isAffiliate();

        return loader.template('account/account', { ...data, ...language, ...config });
    }

    handleConnect() {
        if (!customer.isLogged()) {
            // let target = document.getElementById('content');

            //target.src = 'account/login';
        }
    }

    handleClick(e) {
        e.preventDefault();


    }
}

customElements.define('account-account', AccountAccount);