import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Library
const template = await loader.library('template');

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('common/language');

// Storage
const languages = await loader.storage('localisation/language');

// URL
const url = new URLSearchParams(document.location.search);

class CommonLanguage extends WebComponent {
    connected() {
        let data = {};

        // lang
        data.code = document.documentElement.lang.toLowerCase();

        data.languages = Object.values(languages);

        let response = loader.template('common/language', { ...data,  ...language});

        response.then(this.render.bind(this));
        response.then(this.addEvent.bind(this));
    }

    render(html) {
        this.innerHTML = html;
    }

    addEvent() {
        let form = document.querySelector('#form-language');

        let elements = form.querySelectorAll('a');

        for (let element of elements) {
            element.addEventListener('click', this.onClick);
        }
    }

    async onClick(e) {
        let code = this.getAttribute('href');


    }
}

customElements.define('common-language', CommonLanguage);