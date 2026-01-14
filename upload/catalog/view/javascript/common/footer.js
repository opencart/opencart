import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('common/footer');

// Storage
const articles = await loader.storage('cms/article-1');

// Information
const informations = await loader.storage('information/information');

class CommonFooter extends WebComponent {
    async connected() {
        let data = { ...language, ...config };

        // Articles
        data.articles = articles;

        // Information Pages
        data.informations = informations;

        this.innerHTML = await loader.template('common/footer', data);
    }
}

customElements.define('common-footer', CommonFooter);