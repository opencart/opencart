import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('cms/article_list');

customElements.define('article-list', class extends WebComponent {
    async render() {
        let data = {};

        if (this.hasAttribute('search')) {
            data.search = this.getAttribute('search');
        } else {
            data.search = '';
        }

        if (this.hasAttribute('topic_id')) {
            data.topic_id = this.getAttribute('topic_id');
        } else {
            data.topic_id = 0;
        }

        if (this.hasAttribute('sort')) {
            data.sort = this.getAttribute('sort');
        } else {
            data.sort = '';
        }

        if (this.hasAttribute('order')) {
            data.order = this.getAttribute('order');
        } else {
            data.order = '';
        }

        if (this.hasAttribute('limit')) {
            data.limit = this.getAttribute('limit');
        } else {
            data.limit = 10;
        }

        if (this.hasAttribute('page')) {
            data.page = this.getAttribute('page');
        } else {
            data.page = '';
        }

        data.articles = [];

        let article_ids = await loader.storage('topic/topic-article-' + this.getAttribute('topic_id'));

        if (article_ids !== undefined) {
            for (let article_id of article_ids) {
                let article = await loader.storage('article/article-' + article_id);

                if (article !== undefined && config.config_language in article.description) {
                    let description = article.description[config.config_language];

                    data.articles.push({ ...article, ...description });
                }
            }
        }

        return loader.template('cms/article_list', { ...data, ...language, ...config });
    }

    onChange(e) {
        this.setAttribute('sort');
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.currentTarget.getAttribute('href');
    }
});