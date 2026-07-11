import { Controller } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('catalog/manufacturer');

export default class extends Controller {
    async connected() {
        let data = {};

        let request = new URL(import.meta.url).searchParams;

        // Product Info
        let manufacturer = await loader.storage('manufacturer/manufacturer-' + request.get('manufacturer_id'));

        if (manufacturer !== undefined && config.config_language in manufacturer.description) {
            data.manufacturer_id = manufacturer.manufacturer_id;

            let description = manufacturer.description[config.config_language];

            //description.meta_title
            //description.meta_description
            //description.meta_keyword

            data.heading_title = description.name;
            data.description = description.description;

            // Images
            data.image = manufacturer.image;

            return loader.template('catalog/manufacturer', { ...data, ...language, ...config });
        }
    }
}