// Engine
export { WebComponent, ajax, config, language, global, local, session, storage, template } from '../../../assets/framework/index.js';

// Library
import { loader } from '../../../assets/framework/index.js';

// Load up the Application classes
const cart = await loader.library('cart');
const currency = await loader.library('currency');
const customer = await loader.library('customer');
const length = await loader.library('length');
const tax = await loader.library('tax');
const weight = await loader.library('weight');

export { loader, cart, currency, customer, length, tax, weight };