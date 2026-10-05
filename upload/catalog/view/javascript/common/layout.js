console.log('common-layout');

import { WebComponent } from '../index.js';
import { loader, currency, tax, template, weight, length } from '../index.js';
import './header.js';
import './footer.js';

const config = await loader.config('default');

// Currency
template.addFilter('currency', currency.format);

// Tax
await tax.setGeozone(config.get('config_country_id'), config.get('config_zone_id'));

template.addFilter('tax', tax.calculate);

// Weight
template.addFilter('weight', weight.format);

// Length
template.addFilter('length', length.format);

export default class CommonLayout extends WebComponent {
    async render() {
        return loader.template('common/layout');
    }
}