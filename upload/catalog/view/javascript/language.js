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