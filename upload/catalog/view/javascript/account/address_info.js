import { WebComponent } from '../component.js';
import { loader } from '../index.js';

const language = loader.language('account/address');


let customer = {};

if (session.has('customer')) {
    customer = session.get('customer');
}

class AccountAddressInfo extends WebComponent {
    async connected() {
        let data = {};



        this.innerHTML = this.load.template('account/address', { ...language });
    }
}

customElements.define('account-address-info', AccountAddressInfo);