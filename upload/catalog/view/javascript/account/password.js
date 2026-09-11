import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Library
const session = loader.library('session');

// Language
const language = await loader.language('account/password');

// Name
export const name = 'account-password';

customElements.define('account-password', class extends WebComponent {
    render() {
        let data = {};

        return loader.template('account/password', { ...data, ...language });
    }

    onSubmit(e) {
        e.preventDefault();

    }
});