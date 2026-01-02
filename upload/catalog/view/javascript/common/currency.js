import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// library
const local = await loader.library('local');

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('common/currency');

// Storage
const currencies = await loader.storage('localisation/currency');

class CommonCurrency extends WebComponent {
    async connected() {
        let data = {};

        // Set Default currency
        if (local.has('currency')) {
            data.currency = local.get('currency');
        } else {
            data.currency = config.config_currency;
        }

        data.currencies = currencies;

        console.log(data);

        let response = loader.template('common/currency', { ...data, ...language });

        response.then(this.render.bind(this));
        response.then(this.addEvent.bind(this));
    }

    render(html) {
        this.innerHTML = html;
    }

    addEvent() {
        let form = document.querySelector('#form-currency');

        let elements = form.querySelectorAll('a');

        for (let element of elements) {
            element.addEventListener('click', this.onClick);
        }
    }

    onClick(e) {
        let code = this.getAttribute('href');

        local.set('currency', code);
    }
}

customElements.define('common-currency', CommonCurrency);