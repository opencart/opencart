import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('catalog');

// Library
const session = loader.library('session');

// Language
const language = loader.language('account/password');

class AccountPassword extends WebComponent {
    async connected() {
        let data = {};

        let response = loader.template('account/password', { ...data, ...language, ...config });

        response.then(this.render.bind(this));
        response.then(this.addEvent.bind(this));
    }

    render(html) {
        this.innerHTML = html;
    }
    
    addEvent() {
        let form = this.querySelector('#form-password');

        form.addEventListener('submit', this.onSubmit.bind(this));
    }

    onSubmit(e) {
        e.preventDefault();


    }
}

customElements.define('account-password', AccountPassword);