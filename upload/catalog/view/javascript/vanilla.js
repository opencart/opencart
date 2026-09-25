import { loader, config, language, local, storage, template } from '../../../assets/framework/index.js';

// Base
const base = new URL(document.querySelector('base').href);

// Add Config Path
config.addPath('shop/' + base.host + '/data/');

// language
const lang = document.documentElement.lang.toLowerCase();

// Testing Code
local.set('language', 'en-gb');
local.set('currency', 'EUR');

// Add Language Path
// language.addPath('shop/' + base.host + '/language/' + local.get('language') + '/');

// Developer Code
language.addPath('catalog/view/language/' + local.get('language') + '/');

// Storage
storage.addPath('shop/' + base.host + '/data/');

// Add Template Path
// template.addPath('shop/' + base.host + '/template/');

// Developer Code
template.addPath('catalog/view/template/');

// Testing Code
local.set('language', 'en-gb');
local.set('currency', 'EUR');

// Add Language Path
// language.addPath('shop/' + base.host + '/language/' + local.get('language') + '/');

// Developer Code
language.addPath('catalog/view/language/' + local.get('language') + '/');

// Storage
storage.addPath('shop/' + base.host + '/data/');

// Add Template Path
// template.addPath('shop/' + base.host + '/template/');

// Developer Code
template.addPath('catalog/view/template/');

// Currency
const currency = await loader.library('currency');

template.addFilter('currency', (amount, code, value, format = false) => {
    return currency.format(amount, code, value, format);
});

// Tax
const tax = await loader.library('tax');

tax.setGeozone(config.cache.get('default').get('config_country_id'), config.cache.get('default').get('config_zone_id'));

template.addFilter('tax', (value, tax_class_id = 0, calculate = true) => {
    return tax.calculate(value, tax_class_id, calculate);
});

// Weight
const weight = await loader.library('weight');

template.addFilter('weight', (value, weight_class_id) => {
    return weight.format(value, weight_class_id);
});

// Length
const length = await loader.library('length');

template.addFilter('length', (value, length_class_id) => {
    return length.format(value, length_class_id);
});

// General
import('./common/layout.js');