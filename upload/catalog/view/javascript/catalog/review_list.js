import { WebComponent } from '../component.js';
import { loader, ajax } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/review');

customElements.define('review-list', export default class extends WebComponent {
    async render(){
        let data = {};

        return loader.template('catalog/review_list', { ...data, ...language, ...config });
    }
});
