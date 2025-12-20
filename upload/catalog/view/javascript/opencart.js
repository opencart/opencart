import { registry, factory, loader, config, storage, language, template, url, session, local, db } from './index.js';

// Base
const base = new URL(document.querySelector('base').href);

// lang
const lang = document.documentElement.lang.toLowerCase();

// Config
config.addPath('catalog/view/data/');

//config.load('localhost-' + lang);

config.set('store_url', base);
config.set('language', lang);

// Storage
storage.addPath('catalog/view/data/' + base.host + '/' + lang + '/');

// Language
language.addPath('catalog/view/language/' + base.host + '/' + lang + '/');

// Template
template.addPath('catalog/view/template/');

// Autoload
loader.storage('localisation/language');
loader.storage('localisation/country');
loader.storage('localisation/tax');
loader.storage('localisation/currency');

// Cart
await loader.library('cart');

// Tax
await loader.library('tax');

// Currency
await loader.library('currency');

const cart = registry.get('cart');
const currency = registry.get('currency');
const tax = registry.get('tax');

export { registry, factory, loader, config, db, language, local, session, storage, template, url, cart, tax, currency };

document.addEventListener('DOMContentLoaded', () => {

});

// Common
import('./common/header.js');
//import('./common/language.js');
//import('./common/currency.js');
//import('./common/search.js');
import('./common/menu.js');
import('./common/footer.js');
//import('./common/cart.js');
//import('./common/cookie.js');

// Home Page
import('./common/home.js');
//import('./common/maintenance.js');

// Error
import('./error/not_found.js');

// Account
//import('./common/account.js');
//import('./common/address.js');
//import('./common/address_info.js');

// Catalog
//import('./catalog/brand.js');
//import('./catalog/category.js');
//import('./catalog/compare.js');
//import('./catalog/product.js');
//import('./catalog/product_list.js');
//import('./catalog/product_info.js');
//import('./catalog/product_thumb.js');
//import('./catalog/related.js');
//import('./catalog/review_list.js');
//import('./catalog/review_form.js');
//import('./catalog/search.js');
//import('./catalog/special.js');

// Checkout
//import('./checkout/cart.js');
//import('./checkout/checkout.js');

// CMS
//import('./cms/article.js');
//import('./cms/topic.js');

// Information
//import('./information/contact.js');
//import('./information/gdpr.js');
//import('./information/information.js');
//import('./information/sitemap.js');