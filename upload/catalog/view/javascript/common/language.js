import { WebComponent } from './../../../../assets/framework/library/webcomponent.js';

class XLanguage extends WebComponent {
    currency = '';

    async connected() {
        this.load.language('common/language');

        let html = '';

        html += '<form id="form-language">';
        html += '  <div class="dropdown">';
        html += '    <a href="#" data-bs-toggle="dropdown" class="dropdown-toggle"><img src="{{ image }}" alt="{{ name }}" title="{{ name }}"> <span class="d-none d-md-inline">' + this.language.get('text_language') + '</span> <i class="fa-solid fa-caret-down"></i></a>';
        html += '    <ul class="dropdown-menu"></ul>';
        html += '  </div>';
        html += '</form>';

        this.innerHtml = html;

        let form = this.querySelector('#form-language');


        let response = this.load.storage('localisation/language');

        response.then(this.render);
    }

    render(languages) {
        let html = '';

        html += '<form id="form-language">';
        html += '  <div class="dropdown">';
        html += '    <a href="#" data-bs-toggle="dropdown" class="dropdown-toggle"><img src="{{ image }}" alt="{{ name }}" title="{{ name }}"> <span class="d-none d-md-inline">{{ text_language }}</span> <i class="fa-solid fa-caret-down"></i></a>';
        html += '    <ul class="dropdown-menu">';

        for (let language of languages) {
            html += '<li><a href="' + language.code + '" class="dropdown-item">' + language.name + '</a></li>';
        }

        html += '     </ul>';
        html += '  </div>';
        html += '</form>';

        this.innerHtml = html;
    }
}

customElements.define('x-language', XLanguage);



import { registry } from './library/registry.js';

// Language
let form = document.getElementById('form-language');

const language = form.querySelectorAll('a');

document.addEventListener('DOMContentLoaded', async (e) => {
    let element = this;

    registry.local.set('language', code);

    language.addEventListener('click', async (e) => {
        let element = this;

        let code = $(element).attr('href');

        registry.local.set('currency', code);
    });

});