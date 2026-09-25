import { WebComponent} from '../index.js';
import { loader, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('information/information');

export default class InformationInformation extends WebComponent {
    async render(){
        let information = await loader.storage('information/information-' + this.getAttribute('information_id'));

        if (information != undefined && local.get('language') in information.description) {
            let description = information.description[local.get('language')];

            return await loader.template('information/information', [ information, description, language, config ]);
        }
    }
}

customElements.define('information-information', InformationInformation);