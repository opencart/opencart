import { loader } from './loader.js';

const weight_classes = await loader.storage('localisation/weight_class');

export default class Weight {
    convert(value, from, to) {
        let weight_class_from = weight_classes.find(weight_class => weight_class.weight_class_id === from);
        let weight_class_to = weight_classes.find(weight_class => weight_class.weight_class_id === to);

        if (!weight_class_from || !weight_class_to) return value;

        console.log(weight_classes);

        return value * (weight_class_to.value / weight_class_from.value);
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
    format(value, weight_class_id) {
        let weight_class = weight_classes.find(weight_class => weight_class.weight_class_id === weight_class_id);

        if (!weight_class) return value;

        let string = Intl.NumberFormat(document.querySelector('html').lang).format(parseFloat(value ? value : weight_class.value));

        if (weight_class.unit) {
            string += weight_class.unit;
        }

        return string;
    }

    getUnit(weight_class_id) {
        let weight_class = weight_classes.find(weight_class => weight_class.weight_class_id === weight_class_id);

        if (weight_class) {
            return weight_class.unit;
        } else {
            return '';
        }
    }
}