import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
let config = await loader.config('default');

// library
let local = await loader.library('local');

// Storage
let languages = await loader.storage('localisation/language');

// Language
let language = await loader.language('common/language');

customElements.define('common-language', class extends WebComponent {
    async render() {
        // Config stored language code
        let code = config.config_language;

        // Local storage language code
        if (local.has('language')) {
            code = local.get('language');
        }

        let data = languages.find(language => language.code === code);

        data.languages = languages;

        return loader.template('common/language', { ...data,  ...language });
    }

    onClick(e) {
        e.preventDefault();

        let code = e.currentTarget.getAttribute('href');

        local.set('language', code);
    }
});