import { Global, loader, config, language, local, storage, template, stylesheet } from '../../../assets/framework/index.js';

// Base
const base = new URL(document.querySelector('base').href);

// Config Path
config.addPath('shop/' + base.host + '/config/');

// Storage Path
storage.addPath('shop/' + base.host + '/data/');

// language
local.set('language', document.documentElement.lang.toLowerCase());

// Language Path
//language.addPath('shop/' + base.host + '/language/' + local.get('language') + '/');
language.addPath('catalog/view/language/' + local.get('language') + '/');

// Template Path
//template.addPath('shop/' + base.host + '/template/');
template.addPath('catalog/view/template/');

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

await tax.setGeozone(config.cache.get('default').get('config_country_id'), config.cache.get('default').get('config_zone_id'));

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

Global.registerListener('link', (e) => {
    e.preventDefault();

    let link = e.composedPath().find(element => element.tagName === 'A');

    if (!link) return;

    let href = link.getAttribute('href');

    if (href == null) return;

    Global.get('content').src = href;
});

import './component.js';
import('./common/layout.js');