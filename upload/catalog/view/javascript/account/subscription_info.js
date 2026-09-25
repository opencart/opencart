import { WebComponent } from '../index.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/subscription');

export default class SubscriptionInfo extends WebComponent {
    async render() {
        let data = {};

        return loader.template('account/subscription_info', { ...data, ...language });
    }
}

customElements.define('subscription-info', SubscriptionInfo);