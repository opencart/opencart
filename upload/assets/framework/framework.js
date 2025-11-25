// Library
import { Registry } from './library/registry.js';

let registry = new Registry();

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

