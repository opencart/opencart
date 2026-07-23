import { Controller } from '../component.js';
import { loader } from '../index.js';

// Language
const language = loader.language('account/edit');

export default class extends Controller {
    async render() {
        let data = {};

        let request = new URL(import.meta.url).searchParams;

        // Article Info
        let article = await loader.storage('cms/article-' + request.get('article_id'));

        if (article !== undefined && config.config_language in article.description) {
            data.article_id = article.article_id;

            let description = article.description[config.config_language];

            data.image = description.image;

            data.name = description.name;
            data.description = description.description;

            data.author = article.author;
            data.tags = article.tag.split(',');
            data.date_added = article.date_added;

            data.comment_total = article.comment_total;

            return loader.template('cms/article', { ...data, ...config });
        }
    }
}