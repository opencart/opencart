import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('cms/article_info');

export default class extends Controller {
    async render() {
        let request = new URL(import.meta.url).searchParams;

        // Article Info
        let article = await loader.storage('article/article-' + request.get('article_id'));

        if (article !== undefined && config.config_language in article.description) {
            let description = article.description[config.config_language];

            //description.meta_title
            //description.meta_description
            //description.meta_keyword

            return loader.template('cms/article_info', { ...article, ...description, ...config, ...language });
        }
    }
}