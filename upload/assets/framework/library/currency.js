import { loader } from './loader.js';

class Currency {
    static instance = null;
    static currencies = new Map();

    constructor(currencies) {
        this.currencies = currencies;
    }

    format(number, code, value = 0, format = true) {
        if (!this.currencies.has(code)) return number;

        let currency = this.currencies.get(code);

        value = parseFloat(value ? value : currency.value);

        let amount = parseFloat(number).toFixed(currency.decimal_place);

        let option = {
            style: 'currency',
            currency: code,
            currencyDisplay: 'symbol',
            currencySign: 'standard',
            minimumIntegerDigits: 1,
            minimumFractionDigits: currency.decimal_place
        };

        let string = '';

        if (currency.symbol_left) {
            string += currency.symbol_left;
        }

        let formater = new Intl.NumberFormat(document.querySelector('html').lang, option);

        let part = formater.formatToParts(amount * value);

        let allowed = [
            'minusSign',
            'integer',
            'group',
            'decimal',
            'fraction',
            'literal'
        ];

        for (let i = 0; i < part.length; i++) {
            if (allowed.includes(part[i].type)) {
                string += part[i].value;
            }
        }

        if (currency.symbol_right) {
            string += currency.symbol_right;
        }

        return string;
    }

    convert(value, from, to) {
        if (!this.currencies.has(from) || !this.currencies.has(to)) return value;

        return value * (this.currencies.get(to).value / this.currencies.get(from).value);
    }

    static getInstance(loader) {
        if (!this.instance) {
            this.instance = new Currency(loader);
        }

        return this.instance;
    }
}

const currency = Currency.getInstance(loader);

export default currency;