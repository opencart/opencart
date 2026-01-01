import { loader } from '../../../assets/framework/index.js';

// Base
const base = new URL(document.querySelector('base').href);

// lang
const lang = document.documentElement.lang.toLowerCase();

// Config
const config = await loader.library('config');

config.addPath('catalog/view/data/');
//loader.config('localhost-' + lang);

// Language
const language = await loader.library('language');

language.addPath('catalog/view/language/' + base.host + '/' + lang + '/');

// Storage
const storage = await loader.library('storage');

storage.addPath('catalog/view/data/' + base.host + '/' + lang + '/');

// Template
const template = await loader.library('template');

template.addPath('catalog/view/template/');

// Event
const event = await loader.library('event');

// Inject default language vars into for every language load.
event.register(/language\/.+\/after/g, ({ path, output }) => {
    // Load the default language vars
    //let data = language.fetch('default');

    //for (let key in data) {
    //    if (output[key] == undefined) {
    //        output[key] = data[key];
    //   }
    //}
});

// Inject default language vars into for every language load.
event.register(/template\/.+\/before/g, ({ path, data }) => {
    // Load the default language vars
    //let output = language.fetch('default');

    //for (let key in output) {
    //    if (data[key] == undefined) {
    //        data[key] = output[key];
    //    }
    //}
});

export { loader };

import './component.js';