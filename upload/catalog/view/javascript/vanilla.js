import { Global, stylesheet } from '../../../assets/framework/engine.js';
import { loader, config, language, local, storage, template } from '../../../assets/framework/library.js';

console.log('Vanilla');

// Base
const base = new URL(document.querySelector('base').href);

export const test = {
    path: {
        config: 'catalog/view/' + base.host + '/config/',
        storage: '',
        language: '',
        template: '',
        stylesheet: ''
    },
    library: [
        'ajax',
        'cart',
        'config',
        'currency',
        'customer',
        'language',
        'length',
        'config',
        'config'
    ],
    component: [
        '',
        '',
        '',
        '',
        '',
        '',
        ''
    ],
    start: 'commmon/layout',
    stylesheet: [

    ]
};

// Config Path
//config.addPath('catalog/view/' + base.host + '/config/');
config.addPath('shop/' + base.host + '/config/');

// Storage Path
storage.addPath('shop/' + base.host + '/data/');

// language
local.set('language', document.documentElement.lang.toLowerCase());

// Language Path
//language.addPath('shop/' + base.host + '/language/' + local.get('language') + '/');
language.addPath('catalog/view/language/' + local.get('language') + '/');

// Currency
local.set('currency', 'EUR');

// Template Path
//template.addPath('shop/' + base.host + '/template/');
template.addPath('catalog/view/template/');

// Stylesheets
stylesheet.addPath('shop/' + base.host + '/stylesheet/');
stylesheet.addPath('catalog/view/stylesheet/');
stylesheet.addPath('fontawesome/css/', 'assets/fontawesome/css/');

// Register Global Events
Global.registerListener('link', (e) => {
    e.preventDefault();

    let link = e.composedPath().find(element => element.tagName === 'A');

    if (!link) return;

    let href = link.getAttribute('href');

    if (href == null) return;

    Global.get('content').src = href;
});

document.addEventListener('DOMContentLoaded', async () => {
    // Currency
    template.addFilter('currency', currency.format);

    // Geo Zone
    await tax.setGeozone(config.get('config_country_id'), config.get('config_zone_id'));

    // Tax
    template.addFilter('tax', tax.calculate.bind(tax));

    // Weight
    template.addFilter('weight', weight.format);

    // Length
    template.addFilter('length', length.format);

    let component = await import(config.cache.get('default').get('config_path') + 'common/layout.js');

    customElements.define('common-layout', component.default);

    // Start the root path
    const root = document.getElementById('root');

    root.innerHTML = '<common-layout></common-layout>';
});