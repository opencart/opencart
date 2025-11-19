// Library
import { registry, loader } from './library/loader.js';

// Base
const base = new URL(document.querySelector('base').href);

// lang
let code = document.querySelector('html').lang.toLowerCase();

await loader.library('config');

registry.config.set('storage_path', './catalog/view/data/' + base.host + '/' + code + '/');
registry.config.set('language_path', './catalog/view/language/' + base.host + '/' + code + '/');
registry.config.set('template_path', './catalog/view/template/' + base.host + '/');

await loader.library('storage');
await loader.library('language');
await loader.library('template');
await loader.library('session');
await loader.library('local');
await loader.library('cart');
await loader.library('tax');
await loader.library('currency');

// Web Components
import('./component/currency.js');
import('./component/alert.js');
//import('./component/autocomplete.js');
//import('./component/ckeditor.js');
import('./component/country.js');
import('./component/currency.js');
//import './component/modal.js';
import('./component/pagination.js');
import('./component/switch.js');
//import './component/upload.js';
import('./component/zone.js');

console.log(registry);

console.log(registry.currency.format(1.00, 'USD'));

export { registry };