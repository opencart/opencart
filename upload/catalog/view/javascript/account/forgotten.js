import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('account/edit');

class AccountForgotten extends WebComponent {
    render() {

        return loader.template('account/forgotten', { ...language });
    }

    confirm(e) {
        e.preventDefault();

    }
}

customElements.define('account-forgotten', AccountForgotten);