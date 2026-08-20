import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('information/sitemap');

// Storage
const categories = await loader.storage('category/category');
const informations = await loader.storage('information/information');

console.log(categories);
console.log(informations);

export default class extends Controller {
    async render() {
        let data = {};

        data.categories = [];

        for (let category of categories) {
            let children = [];

            for (let child of category.children) {
                children.push({
                    name: child.description[config.config_language].name,
                    path: child.path,
                    product_total: child.product_total
                });
            }

            data.categories.push({
                name: category.description[config.config_language].name,
                path: category.path,
                children: children,
                product_total: category.product_total
            });
        }

        data.informations = [];

        for (let information of informations) {
            data.informations.push({
                information_id: information.information_id,
                title: information.description[config.config_language].title
            });
        }

        data.config_product_count = config.config_product_count;

        return loader.template('information/sitemap', { ...data, ...language });
    }
}