import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/password');

export default class AccountPassword extends WebComponent {
    render() {
        let data = {};

        return loader.template('account/password', { ...data, ...language });
    }

    onSubmit(e) {
        e.preventDefault();

    }
}

customElements.define('account-password', AccountPassword);