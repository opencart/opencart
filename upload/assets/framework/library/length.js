import { loader } from './loader.js';

const length_classes = await loader.storage('localisation/length_class');

export default class Length {
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
    format(value, length_class_id) {
        let length_class = length_classes.find(length_class => length_class.length_class_id === length_class_id);

        if (!length_class) return value;

        let string = Intl.NumberFormat(document.querySelector('html').lang).format(parseFloat(value ? value : length_class.value));

        if (length_class.unit) {
            string += length_class.unit;
        }

        return string;
    }

    getUnit(length_class_id) {
        let length_class = length_classes.find(length_class => length_class.length_class_id === length_class_id);

        if (length_class) {
             return length_class.unit;
        } else {
             return '';
        }
    }
}