import { loader, config, language, local, storage, template, stylesheet } from '../../../assets/framework/index.js';

// Base
const base = new URL(document.querySelector('base').href);

// Config Path
config.addPath('shop/' + base.host + '/config/');

// Storage Path
storage.addPath('shop/' + base.host + '/data/');

// language
const lang = document.documentElement.lang.toLowerCase();

// Developer Code
local.set('language', 'en-gb');

// Language Path
//language.addPath('shop/' + base.host + '/language/' + local.get('language') + '/');
language.addPath('catalog/view/language/' + local.get('language') + '/');

// Template Path
//template.addPath('shop/' + base.host + '/template/');
template.addPath('catalog/view/template/');

// Storage
storage.addPath('shop/' + base.host + '/data/');

// Stylesheets
//stylesheet.addPath('shop/' + base.host + '/stylesheet/');
stylesheet.addPath('catalog/view/stylesheet/');
stylesheet.addPath('fontawesome/css/', 'assets/fontawesome/css/');

// Currency
local.set('currency', 'EUR');

const currency = await loader.library('currency');

template.addFilter('currency', (amount, code, value, format = false) => currency.format(amount, code, value, format));

// Tax
const tax = await loader.library('tax');

tax.setGeozone(config.cache.get('default').get('config_country_id'), config.cache.get('default').get('config_zone_id'));

template.addFilter('tax', (value, tax_class_id = 0, calculate = true) => {
    return tax.calculate(value, tax_class_id, calculate)
});

// Weight
const weight = await loader.library('weight');

template.addFilter('weight', (value, weight_class_id) => {
    weight.format(value, weight_class_id)
});

// Length
const length = await loader.library('length');

template.addFilter('length', (value, length_class_id) => {
    length.format(value, length_class_id)
});

// General
import('./common/layout.js');