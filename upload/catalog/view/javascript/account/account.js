import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/account');

// Library
const customer = await loader.library('customer');

// Name
export const name = 'account-account';

customElements.define('account-account', class extends WebComponent {
    connect() {
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
});