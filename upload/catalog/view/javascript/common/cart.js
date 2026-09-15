import { WebComponent } from '../component.js';
import { loader, cart, local, tax } from '../index.js';

// Config
const config = await loader.config('default');

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