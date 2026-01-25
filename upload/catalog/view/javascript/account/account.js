import { WebComponent } from '../component.js';
import { loader } from '../index.js';

const language = await loader.language('account/account');

class AccountAccount extends WebComponent {
    connected() {

    }

    async render() {
        return await loader.template('account/account', { ...language });
    }
}

customElements.define('account-account', AccountAccount);