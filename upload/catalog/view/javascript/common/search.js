import { WebComponent } from '../component.js';
import { loader } from '../index.js';

class CommonSearch extends WebComponent {
    async connected() {
        this.language.load('common/search');

        this.render('common/search');
    }

    onClick() {

        location = '';
    }
}

customElements.define('common-search', CommonSearch);