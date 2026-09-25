import { WebComponent } from '../index.js';
import { loader, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/manufacturer_info');

export default class ManufacturerInfo extends WebComponent {
    async render(){
        let data = new Map();

        // Product Info
        let manufacturer = await loader.storage('manufacturer/manufacturer-' + this.getAttribute('manufacturer_id'));

        if (manufacturer instanceof Map && local.get('language') in manufacturer.description) {
            //description.meta_title
            //description.meta_description
            //description.meta_keyword

            return loader.template('catalog/manufacturer_info', [ manufacturer, manufacturer.description[local.get('language')], language ]);
        }
    }
}

customElements.define('manufacturer-info', ManufacturerInfo);