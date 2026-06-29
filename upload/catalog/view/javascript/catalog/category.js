import { Controller } from '../component.js';
import { loader } from '../index.js';

// Language
const language = loader.language('catalog/category');

// Config
const config = await loader.config('default');

export default class extends Controller {
    async render() {
        let data = {};

        // Product Info
        let category = await loader.storage('catalog/category-42');

        console.log(category);


        if (category !== undefined && config.config_language in category.description) {
            data.category_id = category.category_id;

            console.log('[hi]');

            // Images
            data.thumb = category.thumb;

            let description = category.description[config.config_language];

            //description.meta_title
            //description.meta_description
            //description.meta_keyword

            data.heading_title = description.name;
            data.description = description.description;

            data.children = [];

            for (let children of category.children) {
                data.children = data.children.push({
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