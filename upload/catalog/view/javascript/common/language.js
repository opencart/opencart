import { WebComponent } from '../index.js';
import { loader, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/language');

// Storage
const languages = await loader.storage('localisation/language');

customElements.define('common-language', class extends WebComponent {
    async render() {


        let data = languages.find(language => language.code === local.get('language'));

        data.languages = languages;

        return loader.template('common/language', [ data, language ]);
    }

    onClick(e) {
        e.preventDefault();

        let code = e.currentTarget.getAttribute('href');

        local.set('language', code);


    }
});