import { WebComponent } from '../component.js';
import { loader } from '../index.js';
import './product_thumb.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/product_list');

customElements.define('product-list', class extends WebComponent {
    async render() {
        let data = {};

        // Product Info
        data.products = await loader.storage('category/category-product-' + this.getAttribute('category_id'));

        console.log(data.products);

        return loader.template('catalog/product_list', { ...data, ...language, ...config });
    }
});