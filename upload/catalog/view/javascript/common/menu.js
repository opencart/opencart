import { WebComponent } from './../../../../assets/framework/library/webcomponent.js';

class XMenu extends WebComponent {
    async connected() {
        await this.load.language('common/menu');

        let categories = await this.load.storage('catalog/category');

        this.innerHtml = this.load.template('common/menu', [...this.language.all(), categories]);
    }
}

customElements.define('x-menu', XMenu);