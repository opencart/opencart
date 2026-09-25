import { WebComponent } from '../index.js';
import { loader, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('common/currency');

// Storage
const currencies = await loader.storage('localisation/currency');

customElements.define('common-currency', class extends WebComponent {
    async render() {
        // Local storage currency code
        let data = new Map();

        currencies.find(currency => currency.code === local.get('currency'));

        data.set('currencies', currencies);

        return loader.template('common/currency', [ data, language, config ]);
    }

    onClick(e) {
        e.preventDefault();

        let code = e.currentTarget.getAttribute('href');

        local.set('currency', code);
    }
});