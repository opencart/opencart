import { loader } from './loader.js';

let length_classes = await loader.storage('localisation/length');

export default class Weight {
    convert(value, from, to) {
        let length_class_from = length_classes.find(length_class => length_class.length_class_id === from);
        let length_class_to = length_classes.find(length_class => length_class.length_class_id === to);

        if (!length_class_from || !length_class_to) return value;

        return value * (length_class_to.value / length_class_from.value);
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
    format(value, weight_class_id, decimal_point = '.', thousand_point = ',') {
        let weight_class = weight_classes.find(weight_class => weight_class.weight_class_id === weight_class_id);

        if (!weight_class) return number;

        value = parseFloat(value ? value : weight_class.value);

        let string = '';

        if (currency.symbol_left) {
            string += currency.symbol_left;
        }

        let part = formater.formatToParts(amount * value);

        return string;
    }

    getUnit(weight_class_id) {
        //this.weights[$weight_class_id]

        // if () {
        //     return $this->weights[$weight_class_id]['unit'];
        //} else {
        //     return '';
        //}
    }
}