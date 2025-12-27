import { WebComponent } from '../component.js';
import { loader } from '../index.js';

const language = await loader.language('common/menu');

//const categories = await loader.storage('catalog/category');

const categories = [];

class CommonMenu extends WebComponent {
    async connected() {
        //let categories = await this.load.storage('catalog/category');

        this.innerHTML = await this.load.template('common/menu', this.language.all());
    }
}

customElements.define('common-menu', CommonMenu);