import { WebComponent } from '../component.js';
import { loader } from '../index.js';

customElements.define('component-language', class extends WebComponent {
    async connected() {
        // library
        this.local = await loader.library('local');

        // Config
        this.config = await loader.config('default');

        // Language
        this.language = await loader.language('component/language');

        // Storage
        this.languages = await loader.storage('localisation/language');
    }

    async render() {
        let data = {};

        // Config stored language code
        data.code = config.config_language;

        // Local storage language code
        if (local.has('language')) {
            data.code = local.get('language');
        }

        if (languages.has(data.code)) {
            data.name = languages.get(data.code).name;
            data.image = languages.get(data.code).image;
        } else {
            data.name = '';
            data.image = '';
        }

        data.languages = languages;

        return loader.template('component/language', { ...data,  ...language });
    }

    onClick(e) {
        e.preventDefault();

        let code = e.target.getAttribute('href');

        local.set('language', code);

        this.update();
    }
});