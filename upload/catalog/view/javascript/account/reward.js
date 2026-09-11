import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/reward');

// Name
export const name = 'account-reward';

customElements.define('account-reward', class extends WebComponent {
    async render() {

    }
});