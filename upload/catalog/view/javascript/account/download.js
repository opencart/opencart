import { WebComponent } from '../component.js';
import { loader } from '../index.js';

const language = loader.language('account/download');

class AccountDownload extends WebComponent {
    async connected() {
        let data = {};

        data.downloads = {};

        this.innerHTML = this.load.template('account/download', { ...data, ...language });
    }
}

customElements.define('account-download', AccountDownload);