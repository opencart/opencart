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

        if (this.hasAttribute('path')) {
            data.path = this.getAttribute('path');
        } else {
            data.path = '';
        }

        if (this.hasAttribute('filter')) {
            data.filter = this.getAttribute('filter');
        } else {
            data.filter = '';
        }

        if (this.hasAttribute('manufacturer_id')) {
            data.manufacturer_id = this.getAttribute('manufacturer_id');
        } else {
            data.manufacturer_id = '';
        }

        if (this.hasAttribute('sort')) {
            data.sort = this.getAttribute('sort');
        } else {
            data.sort = '';
        }

        if (this.hasAttribute('order')) {
            data.order = this.getAttribute('order');
        } else {
            data.order = '';
        }

        if (this.hasAttribute('limit')) {
            data.limit = this.getAttribute('limit');
        } else {
            data.limit = 10;
        }

        if (this.hasAttribute('page')) {
            data.page = this.getAttribute('page');
        } else {
            data.page = '';
        }

        // Product Info
        data.products = await loader.storage('category/category-product-' + this.getAttribute('category_id'));

        console.log(data.products);

        return loader.template('catalog/product_list', { ...data, ...language, ...config });
    }

    onChange(e) {
        this.setAttribute('sort');


    }
});