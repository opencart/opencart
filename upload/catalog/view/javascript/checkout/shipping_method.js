import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

export default class extends Controller {
    async connected() {

    }

    async render() {




        return loader.template('checkout/address', { ...data,  ...language });
    }
}