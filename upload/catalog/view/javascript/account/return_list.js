import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/return');

customElements.define('return-list', export default class extends WebComponent {
    async connect() {

    }
});