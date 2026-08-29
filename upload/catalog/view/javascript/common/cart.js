import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/cart');

// library
const session = await loader.library('session');
const cart = await loader.library('cart');
const local = await loader.library('local');
const tax = await loader.library('tax');

// Currency
const currency = local.has('currency') ? local.get('currency') : config.config_currency;

customElements.define('common-cart', class extends WebComponent {
    render() {
        let data = {};

        data.quantity = cart.countProducts();
        data.total = cart.getTotal();

        data.currency = currency;

        return loader.template('common/cart', { ...data,  ...language });
    }
});