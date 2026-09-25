import { WebComponent } from '../index.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('common/search');

// URL
const url = new URLSearchParams(document.location.search);

customElements.define('common-search', class extends WebComponent {
    async render() {
        let data = new Map();

        data.set('search', '');

        if (url.has('search')) data.set('search', url.get('search'));

        return loader.template('common/search', [ data, language ]);
    }

    onSubmit(e) {
        e.preventDefault();



    }

    onInput(e) {


    }
});