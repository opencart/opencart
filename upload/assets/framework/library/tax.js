export class Tax {
    static tax_classes = {};

    constructor(registry) {
        this.storage = registry.get('storage');

        //this.tax_classes = tax_classes;

        this.load(3);
    }

    async load(geo_zone_id) {
        let tax_rates = await this.storage.fetch('localisation/tax_rate-' + geo_zone_id);

        console.log(tax_rates);

        let tax_classes = [];

        for (let i in tax_rates) {
            console.log(i);
            console.log(tax_rates[i]);

            let tax_rule_id = tax_rates[i]['tax_rule_id'];
            let tax_class_id = tax_rates[i]['tax_class_id'];
            let customer_group_id = tax_rates[i]['customer_group_id'];

            if (tax_classes[tax_class_id] == undefined) {
                tax_classes[tax_class_id] = [];
            }

            if (tax_classes[tax_class_id][customer_group_id] == undefined) {

            }


            tax_classes[tax_class_id] = [customer_group_id] + [tax_rule_id];


            if (tax_classes[tax_class_id][customer_group_id] == undefined) {
             //   this.tax_classes[tax_class_id][customer_group_id] = [];
            }

            tax_classes[tax_class_id][customer_group_id][tax_rule_id] = tax_rates[i];
        }


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
}