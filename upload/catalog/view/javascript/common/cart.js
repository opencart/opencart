import { WebComponent } from '../index.js';
import { loader, cart, local, tax } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/cart');

customElements.define('common-cart', class extends WebComponent {
    async render() {
        let data = new Map();

        data.set('products', cart.getProducts());

        data.set('quantity', cart.countProducts());
        data.set('total', cart.getTotal());

        data.set('currency', local.get('currency'));

        return loader.template('common/cart', { ...language, ...config });
    }
});