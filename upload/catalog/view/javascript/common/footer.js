import { Controller } from '../component.js';
import { loader } from '../index.js';
import '../component/currency.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/footer');

// Information
const informations = await loader.storage('catalog/information');

export default class extends Controller {
    async render() {
        let data = {};

        // Information Pages
        data.informations = [];

        for (let information of informations) {
            if (config.config_language in information.description) {
                data.informations.push({
                    information_id: information.information_id,
                    title: information.description[config.config_language].title
                });
            }
        }

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
};

