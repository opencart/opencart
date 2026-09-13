import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// library
const session = await loader.library('session');
const cart = await loader.library('cart');
const local = await loader.library('local');
const tax = await loader.library('tax');

// Language
const language = await loader.language('common/cart');

customElements.define('common-cart', class extends WebComponent {
    async render() {
        let data = {};

        data.products = cart.getProducts();

        data.quantity = cart.countProducts();
        data.total = cart.getTotal();

        data.currency = local.get('currency');

        return loader.template('common/cart', { ...data, ...language, ...config });
    }
});