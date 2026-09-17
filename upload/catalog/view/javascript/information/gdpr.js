import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('information/gdpr');

export default class InformationGdpr extends WebComponent {
    async render() {


        return loader.template('information/gdpr', { ...language });
    }

    onChange() {
        if (this.value == 'remove') {
            $('#collapse-remove').slideDown();
        } else {
            $('#collapse-remove').slideUp();
        }
    }
}

customElements.define('information-gdpr', InformationGdpr);