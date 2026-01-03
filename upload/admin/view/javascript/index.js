import { loader } from '../../../assets/framework/index.js';

// Base
const base = new URL(document.querySelector('base').href);

// lang
const lang = document.documentElement.lang.toLowerCase();

// Config
const config = await loader.library('config');

config.addPath('view/data/');

// Language
const language = await loader.library('language');

language.addPath('view/language/' + base.host + '/' + lang + '/');

// Storage
const storage = await loader.library('storage');

storage.addPath('catalog/view/data/' + base.host + '/' + lang + '/');

// Template
const template = await loader.library('template');

template.addPath('catalog/view/template/');

export { loader }

import './component.js';