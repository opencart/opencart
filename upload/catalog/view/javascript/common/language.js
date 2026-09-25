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
        let data = new Map();

        // Local storage language code
        let value = languages.get(local.get('language'));

        data.set('name', value.name);
        data.set('code', value.code);

        console.log(languages);


        data.set('languages', languages);

        return loader.template('common/language', [ data, language ]);
    }

    onClick(e) {
        e.preventDefault();

        let code = e.currentTarget.getAttribute('href');

        if (!languages.has(code)) {
            this.alert.prepend('<ui-alert type="warning">' + language.get('error_language') + '</ui-alert>');

            return;
        }

        local.set('language', code);
    }
});