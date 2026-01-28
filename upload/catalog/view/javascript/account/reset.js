import { WebComponent } from '../component.js';
import { loader } from '../index.js';

const language = await loader.language('account/reset');

class AccountReset extends WebComponent {
    render() {
        return loader.template('account/reset', { ...language });
    }
}

customElements.define('account-reset', AccountReset);