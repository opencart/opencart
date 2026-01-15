import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('common/search');

// URL
const url = new URLSearchParams(document.location.search);

class CommonSearch extends WebComponent {
    connected() {
        let data = {};

        if (url.has('search')) {
            data.search = url.get('search');
        } else {
            data.search = '';
        }

        let response = loader.template('common/search', { ...data, ...language });

        response.then(this.render.bind(this));
        response.then(this.onSubmit.bind(this));
    }

    render(html) {
        this.innerHTML = html;
    }

    onSubmit(e) {


        //location = '';
    }
}

customElements.define('common-search', CommonSearch);