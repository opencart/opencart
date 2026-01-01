import { WebComponent } from '../component.js';
import { loader } from '../index.js';

class CommonHome extends WebComponent {
    async connected() {

        this.innerHTML = loader.template('common/home', { ...data, ...language });
    }
}

customElements.define('common-home', CommonHome);