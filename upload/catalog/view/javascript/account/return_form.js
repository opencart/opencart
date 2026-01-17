import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/return');

class AccountReturnForm extends WebComponent {
    async connected() {
        let data = {};


    }

    render(html) {
        this.innerHTML = html;
    }

    addEvent() {
        let form = this.querySelector('#form-customer');

        form.addEventListener('submit', this.onSubmit.bind(this));
    }

    onSubmit(e) {
        e.preventDefault();

    }
}

customElements.define('account-return-form', AccountReturnForm);