import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/article_list');

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

        // Product Info
        data.articles = await loader.storage('topic/topic-article-' + this.getAttribute('topic_id'));

        console.log(data.articles);

        return loader.template('catalog/article_list', { ...data, ...language, ...config });
    }

    onChange(e) {
        this.setAttribute('sort');
    }
});