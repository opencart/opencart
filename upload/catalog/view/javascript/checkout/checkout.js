import { WebComponent } from '../component.js';
import { loader } from '../index.js';
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

// library
const cart = await loader.library('cart');
const customer = await loader.library('customer');

// Name
export const name = 'checkout-checkout';

customElements.define('checkout-checkout', class extends WebComponent {
    async render() {
        let data = {};

        //let download = cart.hasDownload();
        //let minimum = cart.hasMinimum();

        data.logged = customer.isLogged();
        data.shipping = cart.hasShipping();

        return loader.template('checkout/checkout', { ...data, ...language, ...config });
    }
});