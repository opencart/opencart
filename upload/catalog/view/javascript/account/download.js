import { WebComponent } from '../component.js';
import { loader } from '../index.js';

const language = await loader.language('account/download');

// Name
export const name = 'account-download';

customElements.define('account-download', class extends WebComponent {
    render() {
        let data = {};

        data.downloads = {};

        return loader.template('account/download', { ...data, ...language });
    }
});