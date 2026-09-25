import { WebComponent } from '../index.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('catalog/special');

export default class CatalogSpecial extends WebComponent {
    render() {
        return loader.template('catalog/special', { ...language });
    }
}

customElements.define('catalog-special', CatalogSpecial);