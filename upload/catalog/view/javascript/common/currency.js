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
        let data = new Map();

        // Local storage currency code
        let value = currencies.get(local.get('currency'));

        data.set('symbol_left', value.symbol_left);
        data.set('symbol_right',  value.symbol_right);


        console.log(currencies.entries());
        data.set('currencies', ...currencies);

        return loader.template('common/currency', [ data, language, config ]);
    }

    onClick(e) {
        e.preventDefault();

        let code = e.currentTarget.getAttribute('href');

        if (!currencies.has(code)) {
            this.alert.prepend('<ui-alert type="warning">' + language.get('error_currency') + '</ui-alert>');

            return;
        }

        local.set('currency', code);
    }
});