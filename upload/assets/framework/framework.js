import { registry } from './startup.js';

export { registry };

// Web Components
import XAlert from './component/alert.js';
//import XAutocomplete from './component/autocomplete.js';
//import XCkeditor from './component/ckeditor.js';
import XCountry from './component/country.js';
import XCurrency from './component/currency.js';
//import XModal from './component/modal.js';
import XPagination from './component/pagination.js';
import XSwitch from './component/switch.js';
//import XUpload from './component/upload.js';
import XZone from './component/zone.js';

// Base
const base = new URL(document.querySelector('base').href);

// lang
let code = document.querySelector('html').lang.toLowerCase();
/*
registry.set('load', loader);
registry.set('storage', new Storage('./catalog/view/data/' + base.host + '/' + code + '/'));
registry.set('session', new Session());
registry.set('local', new Local());
registry.set('language', new Language('./catalog/view/language/' + base.host + '/' + code + '/'));
registry.set('template', new Template('./catalog/view/template/' + base.host + '/'));
*/

//loader.library('cart');
//loader.library('tax');
await registry.get('load').library('currency');

/*
// Web Components
customElements.define('x-alert', XAlert);
//customElements.define('x-autocomplete', XAutocomplete);
//customElements.define('x-ckeditor', XCkeditor);
customElements.define('x-country', XCountry);
customElements.define('x-currency', XCurrency);
//customElements.define('x-modal', XModal);
customElements.define('x-pagination', XPagination);
customElements.define('x-switch', XSwitch);
//customElements.define('x-upload', XUpload);
customElements.define('x-zone', XZone);
*/

console.log(registry);

console.log(registry.get('currency').format(1.00, 'USD'));