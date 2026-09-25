import { WebComponent } from '../index.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/reward');

export default class AccountReward extends WebComponent {
    async render() {

    }
}

customElements.define('account-reward', AccountReward);