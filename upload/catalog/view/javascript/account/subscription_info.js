import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/subscription');

// Name
export const name = 'subscription-info';

customElements.define('subscription-info', class extends WebComponent {
    async render() {
        let data = {};

        return loader.template('account/subscription_info', { ...data, ...language });
    }
});