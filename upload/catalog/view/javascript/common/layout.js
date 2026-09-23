import { WebComponent } from '../component.js';
import { AutoComplete } from '../component.js';
import { Checkbox } from '../component.js';
import { Country } from '../component.js';
import { Form } from '../component.js';
import { Include } from '../component.js';
import { Link } from '../component.js';
import { Markdown } from '../component.js';
import { Pagination } from '../component.js';
import { Switch } from '../component.js';
import { Upload } from '../component.js';
import { Zone } from '../component.js';
import { loader } from '../index.js';

// Load local components
import './header.js';
import './footer.js';

customElements.define('common-layout', class extends WebComponent {
    render() {
        return loader.template('common/layout');
    }
});

customElements.define('input-autocomplete', AutoComplete);
customElements.define('input-checkbox', Checkbox);
customElements.define('input-country', Country);
customElements.define('form-ajax', Form);
customElements.define('x-include', Include);
customElements.define('x-link', Link);
customElements.define('input-markdown', Markdown);
customElements.define('x-pagination', Pagination);
customElements.define('input-switch', Switch);
customElements.define('input-upload', Upload);
customElements.define('input-zone', Zone);
