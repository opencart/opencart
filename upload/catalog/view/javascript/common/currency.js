import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// library
const local = await loader.library('local');

// Testing code
local.set('currency', 'EUR');

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('common/currency');

// Storage
let currencies = await loader.storage('localisation/currency');

class CommonCurrency extends WebComponent {
    connected() {
        let data = {};

        // Set the code for the default currency
        let code = config.config_currency;

        if (local.has('currency')) {
           code = local.get('currency');
        }

        if (code in currencies) {
            data.symbol_left = currencies[code].symbol_left;
            data.symbol_right = currencies[code].symbol_right;
        } else {
            data.symbol_left = '';
            data.symbol_right = '';
        }

        data.code = code;
        data.currencies = Object.values(currencies);

        let response = loader.template('common/currency', { ...data,  ...language, ...config });

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
        local.set('currency', this.getAttribute('href'));
    }
}

customElements.define('common-currency', CommonCurrency);