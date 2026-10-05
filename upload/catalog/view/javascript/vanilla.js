/**
 * startup.js
 * ----------
 * The application entry point, loaded once from the page:
 *
 *   <html lang="en-gb">
 *   <head>
 *     <base href="https://example.com/">
 *     <script src="startup.js" type="module"></script>
 *   </head>
 *   <body>
 *     <app-layout></app-layout>
 *   </body>
 *   </html>
 *
 * Order matters in this file:
 *
 *   1. Paths come first. Everything that loads a config/storage/language/
 *      template/stylesheet file resolves it against these, so nothing
 *      should fetch until they're set.
 *   2. Libraries that read config at load time (tax, currency, weight,
 *      length) are pulled in with `loader.library()` *after* the paths,
 *      rather than imported statically at the top, for exactly that reason.
 *   3. Components load last, once everything they depend on is ready.
 */
import { Global, stylesheet } from '../../../assets/framework/engine.js';
import { loader, config, language, local, storage, template } from '../../../assets/framework/library.js';

// Base
const base = new URL(document.querySelector('base').href);
// ─── Paths ─────────────────────────────────────────────────────────────────

// Config Path
//config.addPath('catalog/view/' + base.host + '/config/');
config.addPath('shop/' + base.host + '/config/');

// ─── Settings ──────────────────────────────────────────────────────────────
const setting = await loader.config('default');

// ─── Locale ────────────────────────────────────────────────────────────────
if (!local.has('language')) local.set('language', setting.get('config_language'));
if (!local.has('currency')) local.set('currency', setting.get('config_currency'));

// Storage Path
storage.addPath('shop/' + base.host + '/data/');

// Language Path
//language.addPath('shop/' + base.host + '/language/' + local.get('language') + '/');
language.addPath('catalog/view/language/' + local.get('language') + '/');

// Template Path
//template.addPath('shop/' + base.host + '/template/');
template.addPath('catalog/view/template/');

// Stylesheets
stylesheet.addPath('shop/' + base.host + '/stylesheet/');
stylesheet.addPath('catalog/view/stylesheet/');
stylesheet.addPath('fontawesome/css/', 'assets/fontawesome/css/'); // namespace → alternate directory

// ─── Libraries + template filters ──────────────────────────────────────────
const currency = await loader.library('currency');

// Currency
template.addFilter('currency', currency.format);

// Tax rates depend on the store's geo zone, so wait for them before any
// template can call the `tax` filter.
const tax = await loader.library('tax');

// Geo Zone
await tax.setGeozone(setting.get('config_country_id'), setting.get('config_zone_id'));

template.addFilter('tax', tax.calculate.bind(tax));

// Weight
const weight = await loader.library('weight');

template.addFilter('weight', weight.format.bind(weight));

// Length
const length = await loader.library('length');

template.addFilter('length', length.format.bind(length));

// Register Global Events
Global.registerListener('link', (e) => {
    e.preventDefault();

    let link = e.composedPath().find(element => element.tagName === 'A');

    if (!link) return;

    let href = link.getAttribute('href');

    if (href == null) return;

    Global.get('content').src = href;
});

// Start the root path
let promise = import('./common/layout.js');

promise.then(() => {
    document.getElementById('root').innerHTML = '<common-layout></common-layout>';
});