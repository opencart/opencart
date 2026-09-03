import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/menu');

customElements.define('common-menu', class extends WebComponent {
    async render() {
        let data = {};

        data.categories = [];

        // Storage
        let categories = await loader.storage('category/category');

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

        return loader.template('common/menu', { ...data, ...language, ...config });
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.currentTarget.getAttribute('href');
    }
});