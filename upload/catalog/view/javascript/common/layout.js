import { WebComponent } from '../index.js';
import { loader, global } from '../index.js';

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

    onConnect() {
        global.registerListener('link', this.handleContent.bind(this));
    }

    handleContent(e) {
        e.preventDefault();
        //e.stopPropagation();

        console.log('e', e);
        console.log('e.target', e.target);
        console.log('this', this);
        console.log('global', global);
        console.log('getRef', global.getRef('content'));

        //const path = e.composedPath();

        //console.log(path);
        //e.target.update();

        //this.update();

        global.getRef('content').src = e.target.getAttribute('href');
    }
});