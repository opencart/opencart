import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('cms/article_info');

let request = new URL(import.meta.url).searchParams;

// Name
export const name = 'article-info';

customElements.define('article-info', class extends WebComponent {
    async render(){

        console.log('article-info', this.getAttribute('article_id'));

        // Article Info
        let article = await loader.storage('article/article-' + this.getAttribute('article_id'));

        if (article !== undefined && config.config_language in article.description) {
            let description = article.description[config.config_language];

            //description.meta_title
            //description.meta_description
            //description.meta_keyword

            return loader.template('cms/article_info', { ...article, ...description, ...config, ...language });
        }
    }
});