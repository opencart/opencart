/**
 * startup.js
 * ----------
 * The application entry point, loaded once from the page:
 *
 *   <html lang="en-gb">
 *   <head>
 *     <base href="https://www.example.com/">
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
import { loader, config, global, language, local, storage, stylesheet, template } from '../../../assets/framework/library.js';

// Base
const base = new URL(document.querySelector('base').href);
// ─── Paths ─────────────────────────────────────────────────────────────────

// Config Path
//config.setDirectory('catalog/view/' + base.host + '/config/');
//config.addPath('shop/' + base.host + '/config/');

// ─── Settings ──────────────────────────────────────────────────────────────
const setting = await loader.config('default');

// ─── Locale ────────────────────────────────────────────────────────────────
if (!local.has('language')) local.set('language', setting.get('config_language'));
if (!local.has('currency')) local.set('currency', setting.get('config_currency'));

// Storage Path
storage.setDirectory('shop/' + base.host + '/data/');

// Language Path
language.setDirectory('catalog/view/language/' + local.get('language') + '/');
//language.addPath('shop/', 'shop/' + base.host + '/language/' + local.get('language') + '/');

// Template Path
template.addPath('catalog/view/template/');
//template.addPath('shop/', 'shop/' + base.host + '/template/');

// Stylesheets
stylesheet.setDirectory('catalog/view/stylesheet/');
stylesheet.addPath('catalog/', 'catalog/view/stylesheet/');
stylesheet.addPath('shop/', 'shop/' + base.host + '/stylesheet/');
stylesheet.addPath('fontawesome/css/', base + 'assets/fontawesome/css/'); // namespace → alternate directory

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
global.addListener('link', (e) => {
    e.preventDefault();

    let link = e.composedPath().find(element => element.tagName === 'A');

    // If element clicked has no A tag in the DOM path.
    if (!link) return;

    let href = link.getAttribute('href');

    if (href == null) return;

    global.get('content').setAttribute('src', href);
});

// Opens a link's page in a modal instead of the content area:
// <a href="account/register.js" :click="modal">Register</a>
global.addListener('dialog', async (e) => {
    e.preventDefault();

    let link = e.composedPath().find(element => element.tagName === 'A');
  
    if (!link) return;

    let href = link.getAttribute('href');

    if (href == null) return;

    let dialog = global.get('dialog');
	
	let element = dialog.querySelector('nav-dialog');

	element.setAttribute('src', href);
		
	dialog.show();
});

// Start the root path
let promise = import('./common/layout.js');

promise.then(() => document.getElementById('root').innerHTML = '<common-layout></common-layout>');