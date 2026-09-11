import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/return');

// Name
export const name = 'return-info';

customElements.define('return-info', class extends WebComponent {
    async render() {

    }
});