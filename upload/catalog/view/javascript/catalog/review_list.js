import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/review');

// Library
const ajax = await loader.library('ajax');

customElements.define('review-list', class extends WebComponent {
    async render(){
        let data = {};

        return loader.template('catalog/review_list', { ...data, ...language, ...config });
    }
});
