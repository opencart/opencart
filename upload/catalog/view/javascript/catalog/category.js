import { WebComponent } from '../index.js';
import { loader, local } from '../index.js';
import './product_list.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/category');

export default class CatalogCategory extends WebComponent {
    async render() {
        let data = new Map();

        let category_id = 0;

        let path = this.getAttribute('path');

        if (path.indexOf('_') !== -1) {
            category_id = path.split('_').pop();
        } else {
            category_id = path;
        }

        // Product Info
        let category = await loader.storage('category/category-' + category_id);

        if (category instanceof Map && local.get('language') in category.get('description')) {
            let description = category.get('description')[local.get('language')];

            //description.meta_title;
            //description.meta_description;
            //description.meta_keyword;

            this.data.set('categories', []);

            for (let children of category.children) {
                this.data.get('categories').push({
                    name: children.description[local.get('language')].name,
                    path: children.path
                });
            }

            return loader.template('catalog/category', [ category, description, data, language, config ]);
        }
    }
}

customElements.define('catalog-category', CatalogCategory);