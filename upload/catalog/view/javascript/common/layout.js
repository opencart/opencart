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
    global = {
        link: () => {

        }
    };

    render() {
        return loader.template('common/layout');
    }

    link(e) {
        e.preventDefault();
        e.stopPropagation();

        console.log('e', e);
        console.log('e.target', e.target);
        console.log('this', this);
        console.log('global', global);
        console.log('getRef', global.get('content'));

        const elements = e.composedPath();

        console.log(elements);

        console.log(elements.find(element => element.tagName == 'a'));

        global.get('content').src = e.target.getAttribute('href');
    }
});