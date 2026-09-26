import { WebComponent } from '../index.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/review');

customElements.define('review-list', class extends WebComponent {
    async render(){
        let data = new Map();

        return loader.template('catalog/review_list', [ data, language, config ]);
    }
});
