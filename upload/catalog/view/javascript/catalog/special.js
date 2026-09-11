import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('catalog/special');

customElements.define('catalog-special', class extends WebComponent {
    render() {
        return loader.template('catalog/special', { ...language });
    }
});