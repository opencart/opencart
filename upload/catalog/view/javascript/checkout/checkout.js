import { WebComponent } from '../index.js';
import { loader, cart, customer } from '../index.js';
import './register.js';
import './payment_address.js';
import './shipping_address.js';
import './shipping_method.js';
import './payment_method.js';
import './confirm.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/checkout');

export default class CheckoutCheckout extends WebComponent {
    async render() {
        let data = {};

        data.logged = customer.isLogged();
        data.minimum = cart.hasMinimum();
        data.shipping = cart.hasShipping();
        data.download = cart.hasDownload();

        return loader.template('checkout/checkout', { ...data, ...language, ...config });
    }
}

customElements.define('checkout-checkout', CheckoutCheckout);