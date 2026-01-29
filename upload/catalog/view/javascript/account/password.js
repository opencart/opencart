import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Library
const session = loader.library('session');

// Language
const language = loader.language('account/password');

class AccountPassword extends WebComponent {
    render() {
        let data = {};

        return loader.template('account/password', { ...data, ...language });
    }

    submit(e) {
        e.preventDefault();




    }
}

customElements.define('account-password', AccountPassword);