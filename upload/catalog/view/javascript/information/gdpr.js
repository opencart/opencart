import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('information/gdpr');

// Name
export const name = 'information-gdpr';

customElements.define('information-gdpr', class extends WebComponent {
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
});