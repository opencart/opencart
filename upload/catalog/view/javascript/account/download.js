import { WebComponent } from '../index.js';
import { loader, customer } from '../index.js';

const language = await loader.language('account/download');

export default class AccountDownload extends WebComponent {
    render() {
        let data = {};

        data.downloads = {};

        return loader.template('account/download', [ data, language ]);
    }
}

customElements.define('account-download', AccountDownload);