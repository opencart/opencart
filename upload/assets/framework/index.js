import { registry } from './library/registry.js';
import { factory } from './library/factory.js';
import { loader } from './library/loader.js';

// Config
await loader.library('config');
const config = registry.get('config');

// DB
await loader.library('db');
const db = registry.get('db');

// Language
await loader.library('language');
const language = registry.get('language');

// Local
await loader.library('local');
const local = registry.get('local');

// Request
await loader.library('request');
const request = registry.get('request');

// Session
await loader.library('session');
const session = registry.get('session');

// Storage
await loader.library('storage');
const storage = registry.get('storage');

// Template
await loader.library('template');
const template = registry.get('template');

// URL
await loader.library('url');
const url = registry.get('url');

export { registry, factory, loader, config, db, language, local, session, storage, template, url };

// Web Components
import('./component/alert.js');
//import('./component/autocomplete.js');
//import('./component/ckeditor.js');
import('./component/datetime.js');
//import './component/modal.js';
import('./component/pagination.js');

// Custom
//import('./component/currency.js');
//import('./component/country.js');
//import('./component/zone.js');

// Input
//import('./input/checkbox.js');
//import('./input/ckeditor.js');
//import('./input/date.js');
//import('./input/datetime.js');
//import('./input/file.js');
//import('./input/radio.js');
//import('./input/select.js');
import('./input/switch.js');
//import('./input/text.js');
//import('./input/textarea.js');
//import('./input/time.js');