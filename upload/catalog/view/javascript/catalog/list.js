import { Controller } from '../component.js';
import { loader } from '../index.js';

export default class extends Controller {
    async render() {
        let data = {};

        // Product Info
        let manufacturer = await loader.storage('catalog/product-42');

        if (manufacturer.length && config.config_language in manufacturer.description) {
            data.product_id = category.product_id;

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

        return this.render('catalog/product_info', { ...data, ...language, ...config });
    }
}