import { WebComponent } from '../component.js';
import { loader } from '../index.js';

class CmsArticle extends WebComponent {
    async connected() {

    }
}

customElements.define('cms-article', CmsArticle);