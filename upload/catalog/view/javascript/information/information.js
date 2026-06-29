import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = loader.language('information/information');

export default class extends Controller {
    async render() {
        let data = {};

        console.log(document.currentScript);

        //let information = await loader.storage('catalog/information-' + this.getAttribute('information_id'));

        //if (information.length) {
         //   data.title = information.title;
        //    data.description = information.description;
        //}

        return loader.template('information/information', { ...data, ...language, ...config });
    }
}