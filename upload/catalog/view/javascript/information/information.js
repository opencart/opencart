import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = loader.language('information/information');

export default class extends Controller {
    async render() {
        let data = {};

        let request = new URL(import.meta.url).searchParams;

        let information = await loader.storage('catalog/information-' + request.get('information_id'));

        if (information != undefined && config.config_language in information.description) {
            data.information_id = information.information_id;

            let description = information.description[config.config_language];

            data.heading_title = description.title;
            data.description = description.description;
        }

        return loader.template('information/information', { ...data, ...language, ...config });
    }
}