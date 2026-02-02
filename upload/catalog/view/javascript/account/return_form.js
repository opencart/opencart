import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/return');

class AccountReturnForm extends WebComponent {
   render() {
       return loader.template('account/return', { ...language });
    }

    onSubmit(e) {
        e.preventDefault();

    }
}

customElements.define('account-return-form', AccountReturnForm);