import { Controller } from '../component.js';
import { loader } from '../index.js';

// Language
const language = loader.language('catalog/category');

export default class extends Controller {
    async render() {
        let data = {};

        // Product Info
        let category = await loader.storage('catalog/category-42');

        if (category.length && config.config_language in category.description) {
            data.category_id = category.category_id;

            // Images
            data.thumb = category.thumb;

            let description = category.description[config.config_language];

            //description.meta_title
            //description.meta_description
            //description.meta_keyword

            data.heading_title = description.name;
            data.description = description.description;

            return loader.template('catalog/category', { ...data, ...language, ...config });
        }
    }
}