import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/product_info');

customElements.define('product-related', class extends WebComponent {
    async render() {

    }
});