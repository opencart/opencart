import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// library
const local = await loader.library('local');

// Language
const language = await loader.language('common/currency');

// Storage
const currencies = await loader.storage('localisation/currency');

customElements.define('common-currency', class extends WebComponent {
    async render() {
        // Config stored currency code
        let code = config.config_currency;

        // Local storage currency code
        if (local.has('currency')) {
            code = local.get('currency');
        }

        let data = currencies.find(currency => currency.code === code);

        data.currencies = currencies;

        return loader.template('common/currency', { ...data, ...language });
    }

    onClick(e) {
        e.preventDefault();

        let code = e.currentTarget.getAttribute('href');

        local.set('currency', code);
    }
});