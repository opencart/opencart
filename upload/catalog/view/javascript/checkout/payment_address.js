import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

export default class extends Controller {
    async connected() {

    }


    async render() {
        let data = {};



        return loader.template('checkout/payment_address', { ...data,  ...language });
    }
}