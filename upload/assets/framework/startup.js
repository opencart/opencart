// Library
import { registry } from './library/registry.js';
import { loader } from './library/loader.js';
import { storage } from './library/storage.js';
import { session } from './library/session.js';
import { local } from './library/local.js';
import { language } from './library/language.js';
import { template } from './library/template.js';

registry.set('load', loader);
registry.set('storage', storage);
registry.set('session', session);
registry.set('local', local);
registry.set('language', language);
registry.set('template', template);

export { registry };