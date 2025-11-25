import { registry } from './registry.js';

class Tax {
    static tax_classes = [];

    constructor(tax_classes) {
        this.tax_classes = tax_classes;
    }

    calculate(value = 0.00, tax_class_id = 0, calculate = true) {
        if (tax_class_id && calculate) {
            let amount = 0;

            let tax_rates = this.getRates(value, tax_class_id);

            for (let i in tax_rates) {
                amount += tax_rates[i].amount;
            }

            return value + amount;
        } else {
            return value;
        }
    }

    getTax(value, tax_class_id) {
        let amount = 0;

        let tax_rates = this.getRates(value, tax_class_id);

        for (let i in tax_rates) {
            amount += tax_rates[i].amount;
        }

        return amount;
    }

    getRates(value, tax_class_id) {
        let tax_rate_data = [];

        if (this.tax_classes[tax_class_id] == undefined) {
            return [];
        }

        for (let [i, tax_rate] in this.tax_classes[tax_class_id].entries()) {
            let amount = 0;

            if (tax_rate_data[tax_rate.tax_rate_id]) {
                amount = tax_rate_data[tax_rate['tax_rate_id']].amount;
            }

            if (tax_rate.type == 'F') {
                amount += tax_rate.rate;
            } else if (tax_rate.type == 'P') {
                amount += (value / 100 * tax_rate.rate);
            }

            tax_rate_data[tax_rate.tax_rate_id] = {
                'tax_rate_id': tax_rate.tax_rate_id,
                'name':        tax_rate.name,
                'rate':        tax_rate.rate,
                'type':        tax_rate.type,
                'amount':      amount
            };
        }

        return tax_rate_data;
    }

    clear() {
        this.data = [];
    }

    static async getInstance(registry) {
        if (!this.instance) {
            let tax_classes = await registry.get('storage').fetch('localisation/tax_class');

            this.instance = new Tax();
        }

        return this.instance;
    }
}

const tax = await Tax.getInstance(registry);

export { tax };