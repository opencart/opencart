import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('common/menu');

// Storage
const categories = await loader.storage('catalog/category');

class CommonMenu extends WebComponent {
    render() {
        let data = {};

        data.categories = categories;

        return loader.template('common/menu', { ...data,  ...language });
    }
}

customElements.define('common-menu', CommonMenu);