import { loader, config, language, local, storage, template } from './../../../assets/framework/index.js';

// Base
const base = new URL(document.querySelector('base').href);

// Add Config Path
config.addPath('shop/' + base.host + '/data/');

// language
const lang = document.documentElement.lang.toLowerCase();

// Testing Code
config.cache.set('default', {
    config_path: base + 'catalog/view/javascript/',
    config_logo: 'catalog/opencart-logo.png',
    config_url: 'http://localhost/opencart-master/upload/',

    config_name: 'OpenCart Store',
    config_owner: '',
    config_address: '44 Abc Road,' + "\n" + 'TX',
    config_email: 'test@test.com',
    config_telephone: '01234 567890',

    config_image: '',
    config_open: '',
    config_comment: '',
    config_location_list: [],

    config_country_id: 222,
    config_zone_id: 3563,
    config_timezone: 'UTC',
    config_language: 'en-gb',
    config_currency: 'EUR',

    config_length_class_id: 1,
    config_weight_class_id: 1,

    config_product_description_length: 100,

    config_customer_group_id: 1,

    config_product_count: true,
    config_review_status: true,
    config_tax: true,
    config_account_id: 1,
    config_checkout_guest: true,
    config_checkout_payment_address: true,
    config_gdpr_id: 0,
    config_stock_status_id: 4,
    config_affiliate_status: 1,
    config_file_max_size: 3000
});

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

tax.setGeozone(config.cache.get('default').config_country_id, config.cache.get('default').config_zone_id);

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