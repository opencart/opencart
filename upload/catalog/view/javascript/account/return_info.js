import { WebComponent } from '../index.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/return');

export default class ReturnInfo extends WebComponent {
    async render() {

    }
}

customElements.define('return-info', ReturnInfo);