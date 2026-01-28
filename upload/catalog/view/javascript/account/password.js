import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Library
const session = loader.library('session');

// Language
const language = loader.language('account/password');

class AccountPassword extends WebComponent {
    render() {
        return loader.template('account/password', { ...language });
    }

    submit(e) {
        e.preventDefault();
    }
}

customElements.define('account-password', AccountPassword);