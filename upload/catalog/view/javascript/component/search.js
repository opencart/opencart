import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('component/search');

// URL
const url = new URLSearchParams(document.location.search);

customElements.define('component-search', class extends WebComponent {
    constructor() {
        super();


    }

    render() {
        let data = {};

        if (url.has('search')) {
            data.search = url.get('search');
        } else {
            data.search = '';
        }

        return loader.template('component/search', { ...data, ...language });
    }

    onSubmit(e) {
        e.preventDefault();

    }
});