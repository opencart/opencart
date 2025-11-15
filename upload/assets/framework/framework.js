import Registry from './library/registry.js';
import Loader from './library/loader.js';
import Storage from './library/storage.js';
import Language from './library/language.js';
import Template from './library/template.js';

// Base
const base = new URL(document.querySelector('base').href);

// lang
let code = document.querySelector('html').lang.toLowerCase();

const registry = new Registry();
const loader = new Loader(registry);

registry.set('load', loader);
registry.set('storage', new Storage('./catalog/view/data/' + base.host + '/' + code + '/'));
registry.set('language', new Language('./catalog/view/language/' + base.host + '/' + code + '/'));
registry.set('template', new Template('./catalog/view/template/' + base.host + '/'));

//loader.library('cart');
//loader.library('tax');
await loader.library('currency');

console.log(registry);

console.log(registry.get('currency').format(1.00, 'USD'));

/*
// Web Components
document.addEventListener('DOMContentLoaded', () => {
    import('./component/alert.js');
    import('./component/autocomplete.js');
    import('./component/ckeditor.js');
    import('./component/country.js');
    import('./component/currency.js');
    import('./component/modal.js');
    import('./component/pagination.js');
    import('./component/switch.js');
    import('./component/upload.js');
    import('./component/zone.js');
});
*/