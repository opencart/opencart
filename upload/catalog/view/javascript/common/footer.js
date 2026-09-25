import { WebComponent } from '../index.js';
import { loader, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/footer');

// Storage
const informations = await loader.storage('information/information');

console.log(informations);

customElements.define('common-footer', class extends WebComponent {
    async render() {
        let data = new Map();

        // Information Pages
        data.set('informations', []);

        if (informations != undefined) {
            for (let information of informations) {
                if (local.get('language') in information.description) {
                    data.get('informations').push({
                        information_id: information.information_id,
                        title: information.description[local.get('language')].title
                    });
                }
            }
        }

        let date = new Date();

        data.set('year', date.getFullYear());

        return await loader.template('common/footer', [ data, language, config ]);
    }
});