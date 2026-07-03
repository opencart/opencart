import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('component/thmb');

customElements.define('product-list', class extends WebComponent {
    async render() {
        let data = {};

        // Product Info
        let product = await loader.storage('catalog/caterogry.product-' + this.getAttribute('product_id'));

        if (product !== undefined && config.config_language in product.description) {
            data.product_id = product.product_id;

            // Images
            data.thumb = product.thumb;

            let description = product.description[config.config_language];

            data.heading_title = description.name;
            data.description = description.description;

            return this.render('catalog/thumb', { ...data, ...language, ...config });
        }
    }
});