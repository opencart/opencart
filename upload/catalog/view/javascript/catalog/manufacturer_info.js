import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/manufacturer_info');

export default class ManufacturerInfo extends WebComponent {
    async render(){
        let data = {};

        // Product Info
        let manufacturer = await loader.storage('manufacturer/manufacturer-' + this.getAttribute('manufacturer_id'));

        if (manufacturer !== undefined && config.config_language in manufacturer.description) {
            let description = manufacturer.description[config.config_language];

            //description.meta_title
            //description.meta_description
            //description.meta_keyword

            return loader.template('catalog/manufacturer_info', { ...manufacturer, ...description, ...language });
        }
    }
}

customElements.define('manufacturer-info', ManufacturerInfo);