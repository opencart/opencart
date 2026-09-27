import { WebComponent } from '../index.js';
import { loader } from '../index.js';

// Load local components
import './header.js';
import './footer.js';
import '../component/autocomplete.js';
import '../component/checkbox.js';
import '../component/country.js';
import '../component/form.js';
import '../component/include.js';
import '../component/markdown.js';
import '../component/pagination.js';
import '../component/switch.js';
import '../component/upload.js';
import '../component/zone.js';

customElements.define('common-layout', class extends WebComponent {
    render() {
        return loader.template('common/layout');
    }
});