import { registry, factory, loader } from './../../../assets/framework/framework.js';

// lang
const lang = document.documentElement.lang.toLowerCase();

// Config
loader.library('config', { path: './view/data/config/' });

// Storage
loader.library('storage', { path: './view/data/' + lang + '/' });

// Language
loader.library('language', { path: './view/language/' + lang + '/' });

// Template
loader.library('template', { path: './view/template/template/' });

// URL
loader.library('url');

// Session
loader.library('session');

// Local
loader.library('local');

// DB
loader.library('db');

// Cart
await loader.library('cart');

// Tax
loader.library('tax');

// Currency
await loader.library('currency');

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