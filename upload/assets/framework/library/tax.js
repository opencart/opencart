import { loader } from './loader.js';

// Config
const config = await loader.config('default');

export default class Tax {
    tax_rates = new Map();

    async setGeozone(country_id, zone_id) {
        let country = await loader.storage('localisation/country-' + country_id);

        if (country == undefined) return;

        let geo_zone = country.geo_zones.find(geo_zone => geo_zone.zone_id == zone_id);

        if (geo_zone == undefined) return;

        let tax_rates = await loader.storage('localisation/tax_rate-' + geo_zone.geo_zone_id);

        if (tax_rates !== undefined) {
            this.tax_rates = tax_rates;
        }
    }

    calculate(value = 0.00, tax_class_id = 0, calculate = true) {
        value = Number(value);

        if (tax_class_id && calculate) {
            let amount = 0;

            let tax_rates = this.getRates(value, tax_class_id);

            for (let tax_rate of tax_rates.values()) {
                amount += tax_rate.amount;
            }

            return value + amount;
        } else {
            return value;
        }
    }

    getTax(value, tax_class_id) {
        value = Number(value);

        let amount = 0;

        let tax_rates = this.getRates(value, tax_class_id);

        for (let tax_rate of tax_rates.values()) {
            amount += tax_rate.amount;
        }

        return amount;
    }

    getRates(value, tax_class_id) {
        value = Number(value);

        let tax_rates = new Map();

        let tax_classes = this.tax_rates.filter(tax_rate => tax_rate.customer_group_id == config.config_customer_group_id && tax_rate.tax_class_id == tax_class_id);

        for (let tax_rate of tax_classes) {
            let amount = 0;

            if (tax_rates.has(tax_rate.tax_rate_id)) {
                amount = tax_rates.get(tax_rate.tax_rate_id).amount;
            }

            if (tax_rate.type == 'F') {
                amount += Number(tax_rate.rate);
            } else if (tax_rate.type == 'P') {
                amount += (value / 100 * Number(tax_rate.rate));
            }

            tax_rates.set(tax_rate.tax_rate_id, {
                tax_rate_id: tax_rate.tax_rate_id,
                name: tax_rate.name,
                rate: tax_rate.rate,
                type: tax_rate.type,
                amount: amount
            });
        }

        return tax_rates;
    }

    clear() {
        this.tax_rates = [];
    }
}