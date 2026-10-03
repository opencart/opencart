import { WebComponent } from '../index.js';
import { loader } from '../index.js';
import './header.js';
import './footer.js';

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