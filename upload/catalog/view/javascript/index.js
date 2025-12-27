import { registry, factory, loader } from '../../../assets/framework/index.js';

// Base
const base = new URL(document.querySelector('base').href);

// lang
const lang = document.documentElement.lang.toLowerCase();

// Config
const config = await loader.library('config');

config.addPath('catalog/view/data/');
//loader.config('localhost-' + lang);

// Storage
const storage = await loader.library('storage');

storage.addPath('catalog/view/data/' + base.host + '/' + lang + '/');

// Language
const language = await loader.library('language');

language.addPath('catalog/view/language/' + base.host + '/' + lang + '/');
language.load('default');

// Template
const template = await loader.library('template');

template.addPath('catalog/view/template/');

export { registry, factory, loader };

import './component.js';