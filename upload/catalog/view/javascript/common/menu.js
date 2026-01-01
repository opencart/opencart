import { WebComponent } from '../component.js';
import { loader } from '../index.js';

const language = await loader.language('common/menu');

const categories = await loader.storage('catalog/category');

class CommonMenu extends WebComponent {
    async connected() {
        let data = {};

        data.categories = categories;

        this.innerHTML = await loader.template('common/menu', { ...data, ...language });
    }
}

customElements.define('common-menu', CommonMenu);