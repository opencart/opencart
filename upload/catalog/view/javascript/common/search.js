import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('common/search');

// URL Params
const url = URLSearchParams(document.location);

class CommonSearch extends WebComponent {
    async connected() {
        let data = {}

        data.search = query.get('search');

        let response = loader.template('common/search', { ...data, ...language });

        response.then(this.render);
        response.then(this.onSubmit);
        response.then(this.onSubmit);
    }

    render(html) {
        this.innerHTML = html;
    }

    onSubmit() {


        //location = '';
    }
}

customElements.define('common-search', CommonSearch);