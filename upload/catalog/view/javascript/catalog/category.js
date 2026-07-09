import { Controller } from '../component.js';
import { loader } from '../index.js';
import '../catalog/product_list.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/category');

export default class extends Controller {
    async render() {
        let data = {};

        let request = new URL(import.meta.url).searchParams;

        let category_id = 0

        let path = request.get('path');

        if (path.indexOf('_') !== -1) {
            category_id = path.split('_').pop();
        } else {
            category_id = path;
        }

        // Product Info
        let category = await loader.storage('category/category-' + category_id);

        if (category !== undefined && config.config_language in category.description) {
            data.category_id = category.category_id;

            // Images
            data.image = category.image;

            let description = category.description[config.config_language];

            //description.meta_title;
            //description.meta_description;
            //description.meta_keyword;

            data.heading_title = description.name;
            data.description = description.description;

            data.categories = [];

            for (let children of category.children) {
                data.categories.push({
                    name: children.description[config.config_language].name,
                    path: children.path
                });
            }

            return loader.template('catalog/category', { ...data, ...language, ...config });
        }
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.target.getAttribute('href');
    }
}