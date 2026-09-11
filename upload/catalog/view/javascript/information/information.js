import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('information/information');

// Name
export const name = 'information-information';

customElements.define('information-information', class extends WebComponent {
    async render(){
        let information = await loader.storage('information/information-' + this.getAttribute('information_id'));

        if (information != undefined && config.config_language in information.description) {
            let description = information.description[config.config_language];

            return await loader.template('information/information', { ...information, ...description, ...language, ...config });
        }
    }
});