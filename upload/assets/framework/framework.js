import { registry } from './library/registry.js';
import { config } from './library/config.js';
import { storage } from './library/storage.js';
import { language } from './library/language.js';
import { template } from './library/template.js';
import { url } from './library/url.js';
import { session } from './library/session.js';
import { local } from './library/local.js';
import { db } from './library/db.js';
import { cart } from './library/cart.js';
import { tax } from './library/tax.js';
import { currency } from './library/currency.js';

export { registry, config, storage, language, template, url, session, local, db, cart, tax, currency };

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