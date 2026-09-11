import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Name
export const name = 'common-home';

customElements.define('common-home', class extends WebComponent {
    render() {
        return loader.template('common/home');
    }
});