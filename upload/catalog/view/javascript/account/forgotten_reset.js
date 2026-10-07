import { WebComponent } from '../index.js';
import { loader, customer } from '../index.js';

export default class ForgottenReset extends WebComponent {
    async render() {
        //if (customer.isLogged()) return;

        return loader.template('account/forgotten_reset', [ language ]);
    }


}

customElements.define('forgotten-reset', ForgottenReset);