import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/return');

// Name
customElements.define('return-history', class extends WebComponent {
    render() {
        return loader.template('account/return_history', { ...language });
    }
});