export class Currency {
    static currencies = [];

    constructor(currencies) {
        this.currencies = currencies;
    }

    format(number, code, value = 0, format = true) {
        if (this.currencies[code] == undefined) {
            return number;
        }

        value = parseFloat(value ? value : this.currencies[code].value);

        let symbol_left = this.currencies[code].symbol_left;
        let symbol_right = this.currencies[code].symbol_right;
        let decimal_place = this.currencies[code].decimal_place;

        let amount = parseFloat(number).toFixed(decimal_place);

        let option = {
            style: 'currency',
            currency: code,
            currencyDisplay: 'symbol',
            currencySign: 'standard',
            minimumIntegerDigits: 1,
            minimumFractionDigits: decimal_place
        };

        let string = '';

        if (symbol_left) {
            string += symbol_left;
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

        if (symbol_right) {
            string += symbol_right;
        }

        return string;
    }

    convert(value, from, to) {
        let from_value = 1;
        let to_value = 1;

        if (this.currencies[from] !== undefined) {
            from_value = this.currencies[from].value;
        }

        if (this.currencies[to] !== undefined) {
            to = this.currencies[to].value;
        }

        return value * (to_value / from_value);
    }
}