import { WebComponent } from '../component.js';

class ProductList extends WebComponent {
    async render() {
        let data = {};



        return this.render('catalog/product_info', { ...data, ...language, ...config });
    }
}

customElements.define('product-list', ProductList);