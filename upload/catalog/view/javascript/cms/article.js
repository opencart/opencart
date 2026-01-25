import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = loader.language('account/edit');

class CmsArticle extends WebComponent {
    async render() {
        let data = {};

        let article = await loader.storage('cms/article-' + this.getAttribute('article_id'));

        if (article.length) {
            data.image = article.image;

            data.name = article.name;
            data.description = article.description;
            data.author = article.author;
            data.tag = article.tag;
            data.date_added = article.date_added;
        }

        return loader.template('cms/article', { ...data, ...config });
    }
}

customElements.define('cms-article', CmsArticle);