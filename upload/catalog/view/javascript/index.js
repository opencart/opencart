export { ajax, config, language, local, loader, session, storage, template } from './../../../assets/framework/index.js';

const cart = await loader.library('cart');
const currency = await loader.library('currency');
const customer = await loader.library('customer');
const length = await loader.library('length');
const tax = await loader.library('tax');
const weight = await loader.library('weight');

export { cart, currency, customer, length, tax, weight };