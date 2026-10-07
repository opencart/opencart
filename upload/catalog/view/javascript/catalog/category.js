import { WebComponent } from '../index.js';
import { loader, local } from '../index.js';
import './product_list.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/category');

// Validate Category Path
let regex = new RegExp(/^(\d+(_\d+)*)$/);

export default class CatalogCategory extends WebComponent {
    async render() {
        let data = new Map();

        let match = this.getAttribute('path').match(regex);

        if (!match) return;

        let [ path ] = match;

        let category = await loader.storage('category/category-' + path);

        if (category instanceof Map && local.get('language') in category.get('description')) {
            let description = category.get('description')[local.get('language')];

            data.set('path', path);

            data.set('categories', []);

            for (let children of category.get('children')) {
                data.get('categories').push({
                    name: children.description[local.get('language')].name,
                    path: children.path
                });
            }

            return loader.template('catalog/category', [ data, category, description, language, config ]);
        }
    }
}

customElements.define('catalog-category', CatalogCategory);