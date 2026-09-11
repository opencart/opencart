import { WebComponent } from '../component.js';
import { loader } from '../index.js';
import './header.js';
import './footer.js';

customElements.define('common-layout', class extends WebComponent {
    content = '';

    render() {
        return loader.template('common/layout');
    }
});