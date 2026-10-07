import { WebComponent } from '../index.js';
import { loader } from '../index.js';
import '../component.js';
import './header.js';
import './footer.js';

export default class CommonLayout extends WebComponent {
    async render() {
        return loader.template('common/layout');
    }
}

customElements.define('common-layout', CommonLayout);