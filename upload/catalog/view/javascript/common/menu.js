import { WebComponent } from '../index.js';
import { loader, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/menu');

// Storage
let categories = await loader.storage('category/category');

customElements.define('common-menu', class extends WebComponent {
    async render() {
        let data = new Map();

        data.set('categories', []);

        for (let category of categories) {
            let children = [];

            for (let child of category.children) {
                children.push({
                    name: child.description[local.get('language')].name,
                    path: child.path,
                    product_total: child.product_total
                });
            }

            data.get('categories').push({
                name: category.description[local.get('language')].name,
                path: category.path,
                children: children,
                product_total: category.product_total
            });
        }

        return loader.template('common/menu', [ data, language, config ]);
    }
});