import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('common/language');

// Storage
const languages = await loader.storage('localisation/language');

// URL
const url = new URLSearchParams(document.location.search);

class CommonLanguage extends WebComponent {
    language = languages;

    connected() {
        let data = { ...Object.fromEntries(language) };

        // lang
        data.code = document.documentElement.lang.toLowerCase();

        data.languages = this.language.values();

        let response = loader.template('common/language', data);

        response.then(this.render.bind(this));
        response.then(this.addEvent.bind(this));
    }

    render(html) {
        this.innerHTML = html;

        `<form id="form-language">
  <div class="dropdown"><a href="#" data-bs-toggle="dropdown" class="dropdown-toggle"><img src="{{ image }}" alt="{{ name }}" title="{{ name }}"> <span class="d-none d-md-inline">{{ text_language }}</span> <i class="fa-solid fa-caret-down"></i></a>
    <ul class="dropdown-menu">
      {% for language in languages %}
        <li><a href="{{ language.code }}" class="dropdown-item"><img src="{{ language.image }}" alt="{{ language.name }}" title="{{ language.name }}"/> {{ language.name }}</a></li>
      {% endfor %}
    </ul>
  </div>
</form>`

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