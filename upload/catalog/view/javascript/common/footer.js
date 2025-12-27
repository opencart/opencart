import { WebComponent } from '../component.js';
import { loader } from '../index.js';

const language = await loader.language('common/footer');
const config = await loader.config('catalog');
const date = new Date();

//const articles = await loader.storage('cms/article-1');

const articles = [];

//const informations = await loader.storage('information/information');

const informations = [];

class CommonFooter extends WebComponent {
    data = [];

    async connected() {
        language

        // Blog
        if (articles.length > 0) {
            this.data.blog = true;
        }

        // Information Pages
        this.data.informations = [];

        let i = 0;

        for (let information of informations) {
            data.informations[i++] = information + [href => information.information_id];
        }

        this.data.powered = language.text_powered.replace('%s', config.config_name).replace('%s', date.getFullYear());

        this.render('common/footer');
    }
}

customElements.define('common-footer', CommonFooter);