import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = loader.language('common/menu');

// Storage
const categories = loader.storage('catalog/category');

class CommonMenu extends WebComponent {
    async connected() {
        let data = {};

        data.categories = categories;

        this.innerHTML = await loader.template('common/menu', { ...data,  ...language });
    }
}

customElements.define('common-menu', CommonMenu);