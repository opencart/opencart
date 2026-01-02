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
        let data = { ...Object.fromEntries(language) };

        // Set the code for the default currency
        let code = config.get('config_currency');

        if (local.has('currency')) {
           code = local.get('currency');
        }

        if (currencies.has(code)) {
            let currency = currencies.get(code);

            data.symbol_left = currency.symbol_left;
            data.symbol_right = currency.symbol_right;
        } else {
            data.symbol_left = '';
            data.symbol_right = '';
        }

        data.code = code;
        data.currencies = currencies.values();

        console.log(data);

        let response = loader.template('common/currency', data);

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