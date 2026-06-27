import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/menu');

// Storage
const categories = await loader.storage('catalog/category');

export default class extends Controller {
    async render() {
        let data = {};

        data.categories = [];

        for (let category of categories) {
            let children_data = [];

            for (let child of category.children) {
                children_data.push({
                    name: child.description[config.config_language].name,
                    path: child.path,
                    product_total: child.product_total
                });
            }

            data.categories.push({
                name: category.description[config.config_language].name,
                path: category.path,
                children: children_data,
                product_total: category.product_total
            });
        }

        console.log(data);

        return loader.template('common/menu', { ...data,  ...language });
    }
}