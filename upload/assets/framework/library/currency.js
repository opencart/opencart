import { loader } from './loader.js';

let currencies = loader.storage('localisation/currency');

export default class Currency {
    convert(value, from, to) {
        let currency_from = currencies.find(currency => currency.code === from);
        let currency_to = currencies.find(currency => currency.code === to);

        if (!currency_from || !currency_to) return value;

        return value * (currency_to.value / currency_from.value);
    }

    /**
     * This function can prefix/suffix your string.
     *
     * @example
     * el.format('foo', { prefix: '...' });
     *
     * @param {string} number String to format
     * @param {string} code Mandatory and will be added before the string
     * @param {string} value Optional and will be added after the string
     * @param {string} format Optional and will be added after the string
     */
    format(number, code, value = 0, format = true) {
        let currency = currencies.find(currency => currency.code === code);

        if (!currency) return number;

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
}