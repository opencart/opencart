import { registry, factory, loader } from './../../../assets/framework/framework.js';

// Base
const base = new URL(document.querySelector('base').href);

// lang
const lang = document.querySelector('html').lang.toLowerCase();

console.log(base.host);
console.log(lang);

// Config
await loader.library('config', () => {
    path: './catalog/view/data/' + base.host + '/config/'
});

// Storage
await loader.library('storage', () => {
    path: './catalog/view/data/' + base.host + '/' + lang + '/'
});

// Language
await loader.library('language', () => {
    path: './catalog/view/language/' + base.host + '/' + lang + '/'
});

// Template
await loader.library('template', () => {
    path: './catalog/view/template/' + base.host + '/template/'
});

// URL
await loader.library('url');

// Session
await loader.library('session');

// Local
await loader.library('local');

// DB
await loader.library('db');

// Cart
await loader.library('cart', registry);

// Tax
const tax_class = async (registry) => {
    let tax_classes = await registry.storage.fetch('localisation/tax_class');
};

await loader.library('tax', tax_class.bind(registry));

// Currency
const currencies = async () => {
    let currencies = await registry.storage.fetch('localisation/currency');
};

await loader.library('currency', currencies.bind(registry));

const config = registry.get('config');
const storage = registry.get('storage');
const language = registry.get('language');
const template = registry.get('template');
const url = registry.get('url');
const session = registry.get('session');
const local = registry.get('local');
const db = registry.get('db');
const cart = registry.get('cart');
const tax = registry.get('tax');
const currency = registry.get('currency');

export { registry, loader, config, storage, language, template, url, session, local, db, cart, tax, currency }


