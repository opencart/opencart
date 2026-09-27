import { WebComponent } from '../index.js';
import { loader, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/manufacturer_list');

// Storage
let manufacturers = await loader.storage('manufacturer/manufacturer');

export default class ManufacturerList extends WebComponent {
    async render() {
        let data = new Map();

        data.set('manufacturers', []);

        for (let manufacturer of manufacturers) {
            if (local.get('language') in manufacturer.description) {


                /*
                data.categories.manufacturer.push({
                    manufacturer_id: manufacturer.manufacturer_id,
                    name: name,
                    image: manufacturer.image
                });
                */
            }
        }

        return loader.template('catalog/manufacturer_list', [ data, language ]);
    }
}

customElements.define('manufacturer-list', ManufacturerList);