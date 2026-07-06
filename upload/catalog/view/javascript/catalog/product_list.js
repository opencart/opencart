import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('component/product');

customElements.define('component-product', class extends WebComponent {
    async render() {
        let data = {};

        // Product Info
        data.products = await loader.storage('catalog/category-product-' + this.getAttribute('category_id'));

        console.log(data.products);

        return loader.template('component/product_list', { ...data, ...language, ...config });
    }
});