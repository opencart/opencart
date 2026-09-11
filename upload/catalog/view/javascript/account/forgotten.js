import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/edit');

customElements.define('account-forgotten', class extends WebComponent {
    connect() {
        if (!customer.isLogged()) {
            let target = document.getElementById('content');

            target.src = 'account/login';
        }
    }

    render() {
        return loader.template('account/forgotten', { ...language });
    }

    confirm(e) {
        e.preventDefault();

    }
});