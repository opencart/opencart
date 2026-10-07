import { WebComponent } from '../index.js';
import { loader, customer } from '../index.js';

const language = await loader.language('account/order');

 export default class AccountOrderHistory extends WebComponent {
    render(){
        let data = new Map();

        data.set('orders', []);

        return loader.template('account/order', [ data, language ]);
    }
}

customElements.define('order-history', AccountOrderHistory);