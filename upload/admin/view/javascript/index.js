import { loader } from '../../../assets/framework/index.js';

// Base
const base = new URL(document.querySelector('base').href);

// language
const lang = document.documentElement.lang.toLowerCase();

// Config
const config = await loader.library('config');

config.addPath('view/data/');

// Testing Code
config.cache.set('default', {
    config_path: base + 'view/javascript/',
    config_name: 'OpenCart Store',
    config_logo: 'catalog/opencart-logo.png',
    config_url: 'http://localhost/opencart-master/upload/admin/',
    config_telephone: '01234 567890',
    config_language: 'en-gb',
    config_currency: 'EUR',
    config_country_id: 222,
    config_zone_id: 3563,
    config_customer_group_id: 1,
    config_account_id: 1,
    config_product_count: true
});

// Testing Code
const local = await loader.library('local');

local.set('language', 'en-gb');
local.set('currency', 'EUR');

// Language
const language = await loader.library('language');

language.addPath('view/language/' + local.get('language') + '/');

// Storage
const storage = await loader.library('storage');

storage.addPath('view/data/');

storage.cache.set('cms/article-1', { articles: [] });
storage.cache.set('catalog/information', { information: [] });

// Template
const template = await loader.library('template');

template.addPath('view/template/');

import './component.js';

export { loader }