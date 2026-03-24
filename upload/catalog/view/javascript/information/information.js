import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = loader.language('account/information');

class InformationInformation extends WebComponent {
    async render() {
        let data = {};

        let information = await loader.storage('information/information-' + this.getAttribute('information_id'));

        if (information.length) {
            data.title = information.title;
            data.description = information.description;
        }

        return loader.template('information/information', { ...data, ...language, ...config });
    }
}

customElements.define('information-information', InformationInformation);