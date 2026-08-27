import { loader } from './index.js';

// Base
const base = new URL(document.querySelector('base').href);

// language
const lang = document.documentElement.lang.toLowerCase();

// Config
const config = await loader.library('config');

config.addPath('shop/' + base.host + '/data/');

// Testing Code
config.cache.set('default', {
    config_path: base + 'catalog/view/javascript/',
    config_name: 'OpenCart Store',
    config_logo: 'catalog/opencart-logo.png',
    config_url: 'http://localhost/opencart-master/upload/',
    config_email: 'test@test.com',
    config_telephone: '01234 567890',
    config_language: 'en-gb',
    config_currency: 'EUR',
    config_country_id: 222,
    config_zone_id: 3563,
    config_customer_group_id: 1,
    config_product_description_length: 100,
    config_product_count: true,
    config_review_status: true,
    config_tax: true,
    config_account_id: 1,
    config_stock_status_id: 4,
    config_file_max_size: 3000
});

// Testing Code
const local = await loader.library('local');

local.set('language', 'en-gb');
local.set('currency', 'EUR');

// Language
const language = await loader.library('language');

//language.addPath('shop/' + base.host + '/language/' + local.get('language') + '/');

// Developer Code
language.addPath('catalog/view/language/' + local.get('language') + '/');

// Storage
const storage = await loader.library('storage');

storage.addPath('shop/' + base.host + '/data/');

// Template
const template = await loader.library('template');

//template.addPath('shop/' + base.host + '/template/');

// Developer Code
template.addPath('catalog/view/template/');

// Currency
const currency = await loader.library('currency');

template.addFilter('currency', (amount, code, value, format = false) => {
    return currency.format(amount, code, value, format);
});

// Tax
const tax = await loader.library('tax');

template.addFilter('tax', (value, tax_class_id = 0, calculate = true) => {
    return tax.calculate(value, tax_class_id, calculate = true);
});

// Weight
const weight = await loader.library('weight');

template.addFilter('weight', (value, weight_class_id, decimal_point = '.', thousand_point = ',') => {
    return weight.format(value, weight_class_id, decimal_point = '.', thousand_point = ',');
});

// Length
const length = await loader.library('length');

template.addFilter('length', (value, length_class_id, decimal_point = '.', thousand_point = ',') => {
    return length.format(value, length_class_id, decimal_point = '.', thousand_point = ',');
});

// Ajax
const ajax = await loader.library('ajax');

// General
import('./common/header.js');
import('./common/footer.js');

/*
import '../../../assets/sass/sass.js';

// Sass Testing Code
const sass = new Sass();
const path = '../sass/stylesheet.scss';

const options = {
    style: compile.Sass.style.expanded,
};

sass.compile(path, function(result) {
    console.log(result);
});
*/