import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// library
const local = await loader.library('local');

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('common/language');

// Storage
const languages = await loader.storage('localisation/language');

class CommonLanguage extends WebComponent {
    render() {
        let data = {};

        // Config stored language code
        data.code = config.config_language;

        // Local storage language code
        if (local.has('language')) {
            data.code = local.get('language');
        }

        if (data.code in languages) {
            data.name = languages[data.code].name;
            data.image = languages[data.code].image;
        } else {
            data.name = '';
            data.image = '';
        }

        data.languages = Object.values(languages);

        return loader.template('common/language', { ...data,  ...language });
    }

    change(e) {
        local.set('language', e.target.getAttribute('href'));

        this.initialize();
    }
}

customElements.define('common-language', CommonLanguage);