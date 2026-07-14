import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/manufacturer_list');

export default class extends Controller {
    async render() {
        let data = {};

        let request = new URL(import.meta.url).searchParams;

        data.heading_title = language.heading_title;

        data.categories = [];

        // Product Info
        let manufacturers = await loader.storage('manufacturer/manufacturer');

        for (let manufacturer of manufacturers) {
            if (config.config_language in manufacturer.description) {
                let name = manufacturer.description[config.config_language].name;

                let key = name.substr(0, 1);

                if (typeof key === 'number') {
                    key = '0 - 9';
                } else {
                    key = key.toUpperCase();
                }

                //if (!key in data.categories) {
                    data.categories[key] = {
                        name: key,
                        manufacturer: []
                    };
                //}
                /*
                data.categories[key].manufacturer.push({
                    manufacturer_id: manufacturer.manufacturer_id,
                    name: name,
                    image: manufacturer.image
                });
                */
            }
        }

        console.log(data);

        return loader.template('catalog/manufacturer_list', { ...data, ...language });
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.target.getAttribute('href');
    }
}