import { Controller } from '../component.js';
import { loader } from '../index.js';

export default class extends Controller {
    async connected() {

    }

    async render() {
        let data = {};



        return loader.template('checkout/register', { ...data,  ...language });
    }
}