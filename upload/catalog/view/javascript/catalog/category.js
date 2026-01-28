import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = loader.language('catalog/category');

class CatalogCategory extends WebComponent {
    render() {
        let data = {};

        return loader.template('catalog/category', { ...data, ...language });
    }
}

customElements.define('catalog-category', CatalogCategory);