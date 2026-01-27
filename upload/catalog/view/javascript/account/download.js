import { WebComponent } from '../component.js';
import { loader } from '../index.js';

const language = loader.language('account/download');

class AccountDownload extends WebComponent {
    async render() {
        let data = {};

        data.downloads = {};

        return this.load.template('account/download', { ...data, ...language });
    }
}

customElements.define('account-download', AccountDownload);