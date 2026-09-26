import { WebComponent } from '../index.js';
import { loader, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('information/information');

export default class InformationInformation extends WebComponent {
    async render(){
        let data = new Map();

        let information = await loader.storage('information/information-' + this.getAttribute('information_id'));

        if (information instanceof Map && local.get('language') in information.get('description')) {
            let description = information.description[local.get('language')];

            //description.meta_title
            //description.meta_description
            //description.meta_keyword

            return await loader.template('information/information', [ information, description, language, config ]);
        }
    }
}

customElements.define('information-information', InformationInformation);