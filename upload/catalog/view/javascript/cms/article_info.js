import { WebComponent } from '../index.js';
import { loader, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('cms/article_info');

export default class ArticleInfo extends WebComponent {
    async render(){


        let article = await loader.storage('article/article-' + this.getAttribute('article_id'));

        if (article instanceof Map && local.get('language') in article.get('description')) {
            let description = article.description[config.get('config_language')];

            //description.meta_title
            //description.meta_description
            //description.meta_keyword

            return loader.template('cms/article_info', [ article, description, language, config ]);
        }
    }
}

customElements.define('article-info', ArticleInfo);