import { WebComponent } from '../index.js';
import { loader, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('information/sitemap');

// Storage
const categories = await loader.storage('category/category');
const informations = await loader.storage('information/information');

export default class InformationSitemap extends WebComponent {
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

            data.categories.push({
                name: category.description[local.get('language')].name,
                path: category.path,
                children: children,
                product_total: category.product_total
            });
        }

        data.informations = [];

        for (let information of informations) {
            data.informations.push({
                information_id: information.information_id,
                title: information.description[local.get('language')].title
            });
        }

        return loader.template('information/sitemap', [ data, language, config ]);
    }
}

customElements.define('information-sitemap', InformationSitemap);