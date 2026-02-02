import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('common/search');

// URL
const url = new URLSearchParams(document.location.search);

class CommonSearch extends WebComponent {
    render() {
        let data = {};

        if (url.has('search')) {
            data.search = url.get('search');
        } else {
            data.search = '';
        }

        return loader.template('common/search', { ...data, ...language });
    }

    onSubmit(e) {
        e.preventDefault();

    }
}

customElements.define('common-search', CommonSearch);