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
    connected() {
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

        let response = loader.template('common/language', { ...data,  ...language });

        response.then(this.render.bind(this));
        response.then(this.addEvent.bind(this));
    }

    render(html) {
        this.innerHTML = html;
    }

    addEvent() {
        let form = document.getElementById('form-language');

        let elements = form.querySelectorAll('a');

        for (let element of elements) {
            element.addEventListener('click', this.onClick);
        }
    }

    async onClick(e) {
        local.set('language', e.target.getAttribute('href'));
    }
}

customElements.define('common-language', CommonLanguage);