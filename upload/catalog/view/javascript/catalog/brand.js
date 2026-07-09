import { Controller } from '../component.js';
import { loader } from '../index.js';

// Language
const language = loader.language('manufacturer/manufacturer');

export default class extends Controller {
    async connected() {
        let data = {};

        // Product Info
        let manufacturer = await loader.storage('manufacturer/manufacturer-42');

        if (manufacturer.length && config.config_language in manufacturer.description) {
            data.manufacturer_id = manufacturer.manufacturer_id;

            // Images
            data.thumb = manufacturer.thumb;

            let description = manufacturer.description[config.config_language];

            //description.meta_title
            //description.meta_description
            //description.meta_keyword

            data.heading_title = description.name;
            data.description = description.description;

            return loader.template('catalog/manufacturer', { ...data, ...language, ...config });
        }
    }
}