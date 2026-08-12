import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/manufacturer_info');

export default class extends Controller {
    async render() {
        let data = {};

        let request = new URL(import.meta.url).searchParams;

        // Product Info
        let manufacturer = await loader.storage('manufacturer/manufacturer-' + request.get('manufacturer_id'));

        if (manufacturer !== undefined && config.config_language in manufacturer.description) {
            let description = manufacturer.description[config.config_language];

            //description.meta_title
            //description.meta_description
            //description.meta_keyword

            return loader.template('catalog/manufacturer_info', { ...manufacturer, ...description, ...language });
        }
    }
}