import { registry, factory, loader } from './../../../assets/framework/framework.js';

// Base
const base = new URL(document.querySelector('base').href);

// lang
const lang = document.querySelector('html').lang.toLowerCase();

// Config
loader.library('config', { path: './catalog/view/data/' + base.host + '/config/' });

// Storage
loader.library('storage', { path: './catalog/view/data/' + base.host + '/' + lang + '/' });

// Language
loader.library('language', { path: './catalog/view/language/' + base.host + '/' + lang + '/' });

// Template
loader.library('template', { path: './catalog/view/template/' + base.host + '/template/' });

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