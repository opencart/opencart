import Registry from './library/registry.js';
import Storage from './library/storage.js';
import Language from './library/language.js';
import Template from './library/template.js';
import WebComponent from './library/webcomponent.js';
import Cart from './library/cart.js';
import Currency from './library/cart.js';
import Tax from './library/tax.js';

const registry = new Registry();

const base = new URL(document.querySelector('base').href);

let language = document.querySelector('html').lang.toLowerCase();

registry.set('storage', new Storage('./catalog/view/data/' + base.host + '/' + language + '/'));
registry.set('language', new Language('./catalog/view/language/' + base.host + '/' + language + '/'));
registry.set('template', new Template('./catalog/view/template/' + base.host + '/'));

// Currency
registry.set('currency', new Currency(registry));


// Web Components
document.addEventListener('DOMContentLoaded', () => {
    import('./component/currency.js');
    import('./component/country.js');
    import('./component/pagination.js');
    import('./component/switch.js');
    import('./component/zone.js');
});
