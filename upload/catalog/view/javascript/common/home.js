import { WebComponent } from '../index.js';
import { loader } from '../index.js';

export default class CommonHome extends WebComponent {
    render() {
        return loader.template('common/home');
    }
}

customElements.define('common-home', CommonHome);