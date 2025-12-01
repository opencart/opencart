import { registry, factory, loader } from './../../../assets/framework/framework.js';

// Base
const base = new URL(document.querySelector('base').href);

// lang
const lang = document.documentElement.lang.toLowerCase();

// Config
loader.library('config', { path: './catalog/view/data/' + base.host + '/config/' });

// Storage
loader.library('storage', { path: './catalog/view/data/' + base.host + '/' + lang + '/' });

// Language
loader.library('language', { path: './catalog/view/language/' + base.host + '/' + lang + '/' });

// Template
loader.library('template', { path: './catalog/view/template/' + base.host + '/template/' });

// Request
loader.library('request');

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

const api = registry.get('api');
const cart = registry.get('cart');
const config = registry.get('config');
const currency = registry.get('currency');
const db = registry.get('db');
const language = registry.get('language');
const local = registry.get('local');
const session = registry.get('session');\
const storage = registry.get('storage');
const tax = registry.get('tax');
const template = registry.get('template');
const url = registry.get('url');

export { registry, loader, config, storage, language, template, url, session, local, db, cart, tax, currency }