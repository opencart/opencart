import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/footer');

// Storage
const articles = await loader.storage('cms/article-1');

// Information
const informations = await loader.storage('information/information');

export default class extends Controller {
    async render() {
        let data = {};

        // Articles
        data.articles = Object.values(articles).length;

        // Information Pages
        data.informations = Object.values(informations);

        data.gdpr = config.config_gdpr_id ? true : false;
        data.affiliate = config.config_affiliate_status ? true : false;

        let date = new Date();

        data.year = date.getFullYear();

        return await loader.template('common/footer', { ...data, ...language, ...config });
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.target.getAttribute('href');
    }
}

