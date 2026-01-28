import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/return');

class AccountReturnForm extends WebComponent {
   render() {
       return loader.template('account/return', { ...language });
    }

    submit(e) {
        e.preventDefault();

    }
}

customElements.define('account-return-form', AccountReturnForm);