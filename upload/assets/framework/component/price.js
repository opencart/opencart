import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Library
const currency = await loader.library('currency');
const tax = await loader.library('tax');

// Storage
const currencies = loader.storage('localisation/currency');
const tax_rates = loader.storage('localisation/tax_rate-');

class XPrice extends WebComponent {
    static observed = [
        'code',
        'amount',
        'value'
    ];

    constructor() {
        super();

        this.currencies = currencies;
    }

    get currency() {
        return this.getAttribute('currency');
    }

    set currency(currency) {
        this.setAttribute('currency', currency);
    }

    get tax_class_id() {
        return this.getAttribute('tax_class_id');
    }

    set tax_class_id(tax_class_id) {
        this.setAttribute('tax_class_id', tax_class_id);
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
            return this.currencies[this.code].value;
        } else {
            return 1.00000;
        }
    }

    set value(value) {
        this.setAttribute('value', value);
    }

    async render() {
        console.log(this.code);

        return this.currency.format(this.value, this.code);
    }
}

customElements.define('x-price', XPrice);