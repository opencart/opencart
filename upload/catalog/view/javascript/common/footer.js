import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('common/footer');

// Storage
const articles = await loader.storage('cms/article-1');

const informations = await loader.storage('information/information');

const date = new Date();

class CommonFooter extends WebComponent {
    async connected() {
        let data = {};

        // Articles
        data.articles = articles;

        // Information Pages
        data.informations = informations;

        data.config_name = config.config_name;

        data.year = date.getFullYear();

        this.innerHTML = await loader.template('common/footer', { ...data, ...language });
    }
}

customElements.define('common-footer', CommonFooter);