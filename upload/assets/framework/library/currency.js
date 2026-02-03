import { loader } from './loader.js';

const currencies = await loader.storage('localisation/currency');

export default class Currency {
    format(number, code, value = 0, format = true) {
        if (!code in currencies) return number;

        let currency = currencies[code];

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
        if (!from in currencies || !to in currencies) return value;

        return value * (currencies[to].value / currencies[from].value);
    }
}