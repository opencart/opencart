import { WebComponent } from '../component.js';
import { loader } from "../index.js";

// Library
const currency = await loader.library('currency');

// Storage
const currencies = loader.storage('localisation/currency');

class XCurrency extends WebComponent {
    static observed = [
        'code',
        'amount',
        'value'
    ];

    get code() {
        return this.getAttribute('code');
    }

    set code(code) {
        this.setAttribute('code', code);
    }

    get amount() {
        return parseFloat(this.getAttribute('amount'));
    }

    set amount(amount) {
        this.setAttribute('amount', amount);
    }

    get value() {
        if (this.hasAttribute('value')) {
            return parseFloat(this.getAttribute('value')).toFixed(this.decimal_place);
        }

        if (this.code in this.currencies) {
            return this.currencies[this.code]['value'];
        } else {
            return 1.00000;
        }
    }

    set value(value) {
        this.setAttribute('value', value);
    }

    constructor() {
        super();

        this.currency = currency;
        this.currencies = currencies;
    }

    async render() {
        return this.currency.format(this.value, this.code);
    }
}

customElements.define('x-currency', XCurrency);