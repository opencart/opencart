import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/footer');

// Storage
const informations = await loader.storage('information/information');

customElements.define('common-footer', class extends WebComponent {
    async render() {
        let data = {};

        // Information Pages
        data.informations = [];

        if (informations != undefined) {
            for (let information of informations) {
                if (config.config_language in information.description) {
                    data.informations.push({
                        information_id: information.information_id,
                        title: information.description[config.config_language].title
                    });
                }
            }
        }

        let date = new Date();

        data.year = date.getFullYear();

        return await loader.template('common/footer', { ...data, ...language, ...config });
    }
});