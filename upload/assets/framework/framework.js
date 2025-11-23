import { registry } from './library/registry.js';

// Library
import './library/config.js';

// Base
registry.config.set('base', new URL(document.querySelector('base').href));

// lang
registry.config.set('language', document.querySelector('html').lang.toLowerCase());

// Library
import './library/storage.js';
import './library/language.js';
import './library/template.js';
import './library/url.js';
import './library/session.js';
import './library/local.js';
import './library/cart.js';
import './library/tax.js';
import './library/currency.js';

// Web Components
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

export { registry };