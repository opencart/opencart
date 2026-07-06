import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// library
const local = await loader.library('local');

// Config
const config = await loader.config('default');

// Storage
const currencies = await loader.storage('localisation/currency');

// Language
const language = await loader.language('component/currency');

customElements.define('component-currency', class extends WebComponent {
    async render() {
        let data = {};

        // Config stored currency code
        data.code = config.config_currency;

        // Local storage currency code
        if (local.has('currency')) {
            data.code = local.get('currency');
        }

        data.currencies = currencies;

        let value = currencies.find(currency => currency.code === data.code);

        if (value !== undefined) {
            data.symbol_left = value.symbol_left;
            data.symbol_right = value.symbol_right;
        } else {
            data.symbol_left = '';
            data.symbol_right = '';
        }

        return loader.template('component/currency', { ...data, ...language });
    }

    onClick(e) {
        e.preventDefault();

        let code = e.target.getAttribute('href');

        local.set('currency', code);

        let elements = document.querySelectorAll('x-currency');

        for (let element of elements) {

        }
    }
});