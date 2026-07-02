import { WebComponent } from '../component.js';
import { loader } from '../index.js';

customElements.define('product-list', class extends WebComponent {



    async render() {
        let data = {};

        // Product Info
        let product = await loader.storage('catalog/product-' + request.get('product_id'));

        if (product !== undefined && config.config_language in product.description) {
            data.product_id = product.product_id;

            // Images
            data.thumb = manufacturer.thumb;

            let description = manufacturer.description[config.config_language];

            data.heading_title = description.name;
            data.description = description.description;

            //description.meta_title
            //description.meta_description
            //description.meta_keyword

            return loader.template('catalog/manufacturer', { ...data, ...language, ...config });
        }

        return this.render('catalog/thumb', { ...data, ...language, ...config });
    }
});