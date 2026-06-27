import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/footer');

// Storage
const articles = await loader.storage('cms/article-1');

// Information
const informations = await loader.storage('catalog/information');

export default class extends Controller {
    async render() {
        let data = {};

        // Articles
        data.articles = Object.values(articles).length;

        // Information Pages
        data.informations = [];

        if (informations.length && config.config_language in informations.description) {

            for (let information of informations) {
                information.description[config.config_language];

                console.log(information);

                data.informations.push(information);
            }

            data.gdpr = config.config_gdpr_id ? true : false;
            data.affiliate = config.config_affiliate_status ? true : false;

            let date = new Date();

            data.year = date.getFullYear();

            return await loader.template('common/footer', {...data, ...language, ...config});
        }
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.target.getAttribute('href');
    }
}

