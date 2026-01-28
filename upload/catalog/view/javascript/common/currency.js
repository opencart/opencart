import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// library
const local = await loader.library('local');

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('common/currency');

// Storage
let currencies = await loader.storage('localisation/currency');

class CommonCurrency extends WebComponent {
    render() {
        let data = {};

        // Config stored currency code
        data.code = config.config_currency;

        // Local storage currency code
        if (local.has('currency')) {
            data.code = local.get('currency');
        }

        if (data.code in currencies) {
            data.symbol_left = currencies[data.code].symbol_left;
            data.symbol_right = currencies[data.code].symbol_right;
        } else {
            data.symbol_left = '';
            data.symbol_right = '';
        }

        data.currencies = Object.values(currencies);

        return loader.template('common/currency', { ...data, ...language });
    }

    change(e) {
        local.set('currency', e.target.getAttribute('href'));
    }
}

customElements.define('common-currency', CommonCurrency);