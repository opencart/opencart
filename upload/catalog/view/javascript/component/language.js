import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
let config = await loader.config('default');

// library
let local = await loader.library('local');

// Storage
let languages = await loader.storage('localisation/language');

// Language
let language = await loader.language('component/language');

customElements.define('component-language', class extends WebComponent {
    async render() {
        let data = {};

        // Config stored language code
        data.code = config.config_language;

        // Local storage language code
        if (local.has('language')) {
            data.code = local.get('language');
        }

        data.languages = languages;

        let value = languages.find(language => language.code === data.code);

        if (value !== undefined) {
            data.name = value.name;
            data.image = value.image;
        } else {
            data.name = '';
            data.image = '';
        }

        return loader.template('component/language', { ...data,  ...language });
    }

    onClick(e) {
        e.preventDefault();

        let code = e.target.getAttribute('href');

        local.set('language', code);
    }
});