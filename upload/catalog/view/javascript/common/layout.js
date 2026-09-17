import { WebComponent } from '../component.js';
import { loader } from '../index.js';
import './header.js';
import './footer.js';

customElements.define('common-layout', class extends WebComponent {
    initialize() {
        this.attachShadow({ mode: 'open' });
    }

    render() {
        return loader.template('common/layout');
    }
});