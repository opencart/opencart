import { Controller } from '../component.js';
import { loader } from '../index.js';

// Language
const language = loader.language('account/edit');

export default class extends Controller {


    async render() {
        let data = {};

        let article = await loader.storage('cms/article-' + this.getAttribute('article_id'));

        if (article.length) {
            data.name = article.name;
            data.description = article.description;
            data.image = article.image;
            data.author = article.author;
            data.tag = article.tag;
            data.date_added = article.date_added;
        }

        return loader.template('cms/article', { ...data, ...config });
    }
}