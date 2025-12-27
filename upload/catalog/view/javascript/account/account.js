import { WebComponent } from '../component.js';

class AccountAccount extends WebComponent {
    async connected() {
        await this.load.language('account/account');

        this.innerHTML = await this.load.template('account/account', this.language.all());
    }
}

customElements.define('account-account', AccountAccount);